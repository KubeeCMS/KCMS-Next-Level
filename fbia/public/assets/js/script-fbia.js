(function($) {
'use strict';
    jQuery(function() {
    	var daftplugPublic = jQuery('.daftplugPublic');
        var optionName = daftplugPublic.attr('data-daftplug-plugin');
        var objectName = window[optionName + '_public_js_vars'];

    });
})(jQuery);