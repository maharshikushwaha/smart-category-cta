jQuery(document).ready(function($) {
    const deactivateLink = $(`tr[data-slug="smart-category-cta"] .deactivate a`);
    if (deactivateLink.length) {
        deactivateLink.on('click', function(e) {
            e.preventDefault();
            const url = this.href;
            if (confirm('Do you want to delete Smart Category CTA data during deactivation? Click OK to delete data, or Cancel to keep it.')) {
                $.ajax({
                    url: sccAdmin.ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'scc_set_delete_data',
                        nonce: sccAdmin.nonce,
                        delete_data: '1'
                    },
                    success: function() {
                        window.location = url;
                    },
                    error: function() {
                        alert('Error processing request. Data will be kept.');
                        window.location = url;
                    }
                });
            } else {
                $.ajax({
                    url: sccAdmin.ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'scc_set_delete_data',
                        nonce: sccAdmin.nonce,
                        delete_data: '0'
                    },
                    success: function() {
                        window.location = url;
                    },
                    error: function() {
                        alert('Error processing request. Data will be kept.');
                        window.location = url;
                    }
                });
            }
        });
    }
});
