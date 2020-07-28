(function($) {
'use strict';
    jQuery(function() {
        var daftplugPublic = jQuery('.daftplugPublic');
    	var optionName = daftplugPublic.data('daftplug-plugin');
    	var objectName = window[optionName+'_public_js_vars'];

        // Handle tooltips
        daftplugPublic.on('mouseenter mouseleave', '[data-tooltip]', function(e) {
            var self = jQuery(this);
            var tooltip = self.attr('data-tooltip');

            if (e.type === 'mouseenter') {
                self.append(`<span class="daftplugPublicTooltip">`+tooltip+`</span>`);
            }

            if (e.type === 'mouseleave') {
                self.find('.daftplugPublicTooltip').remove();
            }
        });
    });
})(jQuery);