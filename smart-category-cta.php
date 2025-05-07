<?php
/*
Plugin Name: Smart Category CTA
Description: Ultra-lightweight plugin to add customizable CTA links to pages at the end of posts in selected categories.
Version: 1.5
Author: Maharshi Kushwaha
Author URI: https://github.com/maharshikushwaha
Contributors: maharshikush
*/

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'scc_add_admin_menu');
function scc_add_admin_menu() {
    add_menu_page(
        'Smart Category CTA',
        'Smart CTA',
        'manage_options',
        'smart-category-cta',
        'scc_settings_page',
        'dashicons-admin-links',
        30
    );
}

add_action('admin_init', 'scc_settings_init');
function scc_settings_init() {
    register_setting('scc_settings_group', 'scc_mappings', [
        'sanitize_callback' => 'scc_sanitize_mappings',
        'default' => []
    ]);
    register_setting('scc_settings_group', 'scc_cta_text', [
        'sanitize_callback' => 'sanitize_text_field',
        'default' => 'Learn more:'
    ]);
    register_setting('scc_settings_group', 'scc_delete_data', [
        'sanitize_callback' => 'scc_sanitize_delete_data',
        'default' => '0'
    ]);
}

function scc_sanitize_mappings($input) {
    $sanitized = [];
    if (is_array($input)) {
        foreach ($input as $cat_id => $page_ids) {
            if (is_numeric($cat_id) && is_array($page_ids)) {
                $valid_page_ids = array_filter(array_map('absint', $page_ids), function($id) {
                    return $id > 0;
                });
                if (!empty($valid_page_ids)) {
                    $sanitized[absint($cat_id)] = array_unique($valid_page_ids);
                }
            }
        }
    }
    return $sanitized;
}

function scc_sanitize_delete_data($input) {
    return $input === '1' ? '1' : '0';
}

function scc_settings_page() {
    $mappings = get_option('scc_mappings', []);
    $cta_text = get_option('scc_cta_text', 'Learn more:');
    $delete_data = get_option('scc_delete_data', '0');
    $categories = get_categories(['hide_empty' => false]);
    $pages = get_pages();
    ?>
    <div class="wrap">
        <h1>Smart Category CTA</h1>
        <form method="post" action="options.php">
            <?php settings_fields('scc_settings_group'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="scc_cta_text">CTA Text</label></th>
                    <td>
                        <input type="text" id="scc_cta_text" name="scc_cta_text" value="<?php echo esc_attr($cta_text); ?>" class="regular-text">
                        <p class="description">Text to display before the page link (e.g., "Learn more:").</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="scc_delete_data">Delete Data on Deactivation</label></th>
                    <td>
                        <input type="checkbox" id="scc_delete_data" name="scc_delete_data" value="1" <?php checked($delete_data, '1'); ?>>
                        <p class="description">Check to set default to delete plugin data when deactivating. You will be prompted to confirm during deactivation.</p>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr>
                    <th>Category</th>
                    <th>Linked Pages</th>
                </tr>
                <?php foreach ($categories as $category) : ?>
                    <tr>
                        <td><?php echo esc_html($category->name); ?></td>
                        <td>
                            <select multiple name="scc_mappings[<?php echo esc_attr($category->term_id); ?>][]">
                                <?php foreach ($pages as $page) : ?>
                                    <option value="<?php echo esc_attr($page->ID); ?>" <?php echo isset($mappings[$category->term_id]) && in_array($page->ID, $mappings[$category->term_id]) ? 'selected' : ''; ?>>
                                        <?php echo esc_html($page->post_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <p class="description">Hold Ctrl (Windows) or Cmd (Mac) to select multiple pages.</p>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}

add_filter('the_content', 'scc_append_cta_link', 20);
function scc_append_cta_link($content) {
    if (is_single() && in_the_loop() && is_main_query()) {
        $mappings = get_option('scc_mappings', []);
        $cta_text = get_option('scc_cta_text', 'Learn more:');
        $post_categories = wp_get_post_categories(get_the_ID(), ['fields' => 'ids']);
        foreach ($post_categories as $cat_id) {
            if (isset($mappings[$cat_id]) && !empty($mappings[$cat_id])) {
                foreach ($mappings[$cat_id] as $page_id) {
                    $content .= '<p>' . esc_html($cta_text) . ' <a href="' . esc_url(get_permalink($page_id)) . '">' . esc_html(get_the_title($page_id)) . '</a></p>';
                }
                break;
            }
        }
    }
    return $content;
}

add_action('admin_enqueue_scripts', 'scc_enqueue_admin_scripts');
function scc_enqueue_admin_scripts($hook) {
    if ($hook === 'plugins.php') {
        wp_enqueue_script('scc-admin', plugin_dir_url(__FILE__) . 'scc-admin.js', ['jquery'], '1.5', true);
        wp_localize_script('scc-admin', 'sccAdmin', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('scc_deactivate_nonce'),
            'pluginSlug' => 'smart-category-cta/smart-category-cta.php'
        ]);
    }
}

add_action('wp_ajax_scc_set_delete_data', 'scc_set_delete_data');
function scc_set_delete_data() {
    check_ajax_referer('scc_deactivate_nonce', 'nonce');
    if (!current_user_can('activate_plugins')) {
        wp_send_json_error('Insufficient permissions');
    }
    $delete_data = isset($_POST['delete_data']) && $_POST['delete_data'] === '1' ? '1' : '0';
    update_option('scc_delete_data', $delete_data);
    wp_send_json_success();
}

register_deactivation_hook(__FILE__, 'scc_deactivate');
function scc_deactivate() {
    if (get_option('scc_delete_data', '0') === '1') {
        delete_option('scc_mappings');
        delete_option('scc_cta_text');
        delete_option('scc_delete_data');
    }
}
?>
