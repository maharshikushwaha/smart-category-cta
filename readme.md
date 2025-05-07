# Smart Category CTA – WordPress Plugin

Smart Category CTA WordPress Plugin is a lightweight and secure plugin designed to enhance your WordPress site by appending customizable Call-to-Action (CTA) links to specific pages at the end of posts in selected categories.

Developed for JobCrate, a career-focused WordPress site, this plugin is ideal for job boards, career blogs, or content-rich websites. With no external CSS and minimal JavaScript, it offers powerful functionality with minimal performance impact.

## Repository Information

- Repository Name: smart-category-cta
- Purpose: Hosts all files required for the plugin, including core functionality, documentation, and licensing. Designed to be clean and lightweight for easy maintenance and high performance.

## Key Features

- Assign multiple pages to a single WordPress category via a dedicated WordPress dashboard menu.
- Customizable CTA text (e.g., "Learn more:") for links appended to WordPress posts.
- Automatically adds CTA links (e.g., "[CTA Text] [Page Title]") to posts in specified categories.
- Lightweight, with minimal JavaScript (only for deactivation prompt) and no external CSS.
- Secure, using WordPress core sanitization functions and best practices.
- User-friendly deactivation prompt in the WordPress admin to choose whether to delete or keep data.
- Data cleanup option during deactivation, configurable via WordPress settings.

## Use Case

Imagine you manage a WordPress blog about digital marketing with a category called "SEO Executive" for SEO-related posts. You want to guide readers to:

- “How to Prepare for SEO Jobs”
- “SEO Certification Guide”

With Smart Category CTA WordPress Plugin, you can:

1. Assign both pages to the "SEO Executive" category in the plugin settings.
2. Set your CTA text to: Explore more:
3. Readers will see links like:

Explore more: How to Prepare for SEO Jobs  
Explore more: SEO Certification Guide

This increases engagement, internal traffic, and SEO effectiveness—perfect for career-focused sites like JobCrate.

## Installation

### Easy Method (via WordPress Dashboard)

1. Download the plugin zip.
2. Navigate to Plugins > Add New > Upload Plugin.
3. Upload the zip and click Install Now.
4. Click Activate once installed.

### Manual Method

1. Unzip the plugin.
2. Upload the `smart-category-cta` folder to `/wp-content/plugins/`.
3. Activate it via the WordPress admin Plugins menu.

## Usage

1. After activation, go to Smart CTA in the WordPress admin dashboard.
2. Configure:
   - CTA Text: (e.g., "Learn more:")
   - Delete Data on Deactivation: Checkbox to predefine cleanup behavior.
   - Category to Pages Mapping: Select one or more pages for each category using multi-select.
3. Click Save Settings.

Posts in mapped categories will automatically display CTA links at the end of content.

## Deactivation Process

1. Go to Plugins > Installed Plugins.
2. Click Deactivate on Smart Category CTA.
3. A JavaScript prompt will appear:

Do you want to delete Smart Category CTA data during deactivation?  
Click OK to delete data, or Cancel to keep it.

4. Choose accordingly. Data is removed or retained based on your choice.

## Frequently Asked Questions

**Can I link multiple pages to a single WordPress category?**  
Yes. You can assign multiple pages to each category using the multi-select dropdown.

**Is the plugin secure?**  
Yes. It uses WordPress core sanitization functions (absint, esc_url, esc_html, sanitize_text_field) and restricts access to users with manage_options or activate_plugins capabilities.

**Will it slow down my site?**  
No. The plugin is optimized for minimal performance impact—no external CSS and only a small JS file for the deactivation prompt.

**Can I customize the styling of the CTA links?**  
Yes. The links are wrapped in `<p>` tags. You can style them via your theme’s CSS or modify the plugin code to add classes.

**What happens to my data when I deactivate the plugin?**  
A prompt asks if you want to delete or keep plugin data (category mappings, CTA text). You decide.

## Changelog

### 1.5
- Initial release.
- Includes: top-level admin menu, customizable CTA text, multi-page mapping per category, and data deletion prompt.

## Credits

Author: Maharshi Kushwaha  
Author URI: https://github.com/maharshikushwaha  
Contributors: maharshikush

## License

This plugin is licensed under the GNU General Public License v2 or later.
