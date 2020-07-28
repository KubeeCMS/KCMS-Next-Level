(function($) {
'use strict';
    jQuery(function() {
        var daftplugAdmin = jQuery('.daftplugAdmin');
        var optionName = daftplugAdmin.attr('data-daftplug-plugin');
        var objectName = window[optionName + '_admin_js_vars'];

        // Generate launch screens
        daftplugAdmin.find('.daftplugAdminSettings_form').on('submit', function(e) {
            e.preventDefault();
            var action = optionName + '_generate_launch_screens';
            var canvas = document.createElement('canvas');
            canvas.width = 2048;
            canvas.height = 2732;
            var image = new Image();
            image.src = jQuery('#pwaIcon').attr('data-attach-url');
            image.onload = function() {
                var ctx = canvas.getContext('2d');
                ctx.fillStyle = jQuery('#pwaBackgroundColor').val();
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(image,
                  canvas.width / 2 - image.width / 2,
                  canvas.height / 2 - image.height / 2
                );

                var launchScreen = canvas.toDataURL('image/png');

                jQuery.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: action,
                        launchScreen: launchScreen,
                    },
                    beforeSend: function() {
                        
                    },
                    success: function(response, textStatus, jqXhr) {
                        
                    },
                    complete: function() {

                    },
                    error: function(jqXhr, textStatus, errorThrown) {
                        
                    }
                });
            };
        });

        // Handle populating segment select
        daftplugAdmin.on('click', '.daftplugAdminTable_action.-send, .daftplugAdminButton.-sendNotification', function(e) {
            var self = jQuery(this);
            var openPopup = self.attr('data-open-popup');
            var popup = daftplugAdmin.find('[data-popup="'+openPopup+'"]');
            var subscription = self.attr('data-subscription');
            var form = popup.find('.daftplugAdminSendPush_form');
            var pushSegmentSelect = form.find('#pushSegment');
            var pushSegmentDropdown = form.find('.daftplugAdminInputSelect_dropdown[data-name="pushSegment"]');
            var pushSegmentList = form.find('.daftplugAdminInputSelect_list[data-name="pushSegment"]');

            pushSegmentSelect.val(subscription);
            pushSegmentList.find('.daftplugAdminInputSelect_option.-selected').removeClass('-selected');
            pushSegmentList.find('.daftplugAdminInputSelect_option[data-value="'+subscription+'"]').addClass('-selected');
            pushSegmentDropdown.attr('data-value', subscription).text(pushSegmentList.find('.daftplugAdminInputSelect_option.-selected').find('.daftplugAdminInputSelect_text').text());
        });

        // Handle table data remove
        daftplugAdmin.on('click', '.daftplugAdminTable_action.-remove', function(e) {
            var self = jQuery(this);
            var row = self.closest('.daftplugAdminTable_row');
            var action = optionName + '_handle_subscription';
            var method = 'remove';
            var endpoint = self.attr('data-subscription');

            jQuery.ajax({
                url: ajaxurl,
                dataType: 'text',
                type: 'POST',
                data: {
                    action: action,
                    method: method,
                    endpoint: endpoint,
                },
                beforeSend: function() {
                    row.addClass('-disabled');
                },
                success: function(response, textStatus, jqXhr) {
                    row.remove();
                    jQuery('.daftplugAdminButton.-sendAll').remove();
                },
                complete: function() {

                },
                error: function(jqXhr, textStatus, errorThrown) {
                    row.remove();
                    jQuery('.daftplugAdminButton.-sendAll').remove();
                }
            });
        });

        // Send push notification
        daftplugAdmin.find('.daftplugAdminSendPush_form').submit(function(e) {
            e.preventDefault();
            var self = jQuery(this);
            var button = self.find('.daftplugAdminButton.-submit');
            var responseText = self.find('.daftplugAdminField_response');
            var action = optionName + '_send_notification';
            var nonce = self.attr('data-nonce');
            var pushSegment = self.find('#pushSegment');
            var pushTitle = self.find('#pushTitle');
            var pushBody = self.find('#pushBody');
            var pushImage = self.find('#pushImage');
            var pushUrl = self.find('#pushUrl');
            var pushIcon = self.find('#pushIcon');
            var pushActionbutton1Text = self.find('#pushActionbutton1Text');
            var pushActionbutton1Url = self.find('#pushActionbutton1Url');
            var pushActionbutton2Text = self.find('#pushActionbutton2Text');
            var pushActionbutton2Url = self.find('#pushActionbutton2Url');
            
            jQuery.ajax({
                url: ajaxurl,
                dataType: 'text',
                type: 'POST',
                data: {
                    action: action,
                    nonce: nonce,
                    pushSegment: pushSegment.val(),
                    pushTitle: pushTitle.val(),
                    pushBody: pushBody.val(),
                    pushImage: pushImage.val(),
                    pushUrl: pushUrl.val(),
                    pushIcon: pushIcon.val(),
                    pushActionbutton1Text: pushActionbutton1Text.val(),
                    pushActionbutton1Url: pushActionbutton1Url.val(),
                    pushActionbutton2Text: pushActionbutton2Text.val(),
                    pushActionbutton2Url: pushActionbutton2Url.val(),
                },
                beforeSend: function() {
                    button.addClass('-loading');
                },
                success: function(response, textStatus, jqXhr) {
                    if (response == 1) {
                        button.addClass('-success');
                        setTimeout(function() {
                            button.removeClass('-loading -success');
                        }, 1500);
                        responseText.css('color', '#4073FF').html('Notification sent successfully.').fadeIn('fast').delay(3000).fadeOut('fast', function() {
                            responseText.empty().show();
                        });
                        self.trigger('reset');
                        pushImage.val('').removeClass('-hasFile').removeAttr('data-attach-url');
                        pushIcon.val('').removeClass('-hasFile').removeAttr('data-attach-url');
                        self.find('.daftplugAdminMinifielset_close').trigger('click');
                    } else {
                        button.addClass('-fail');
                        setTimeout(function() {
                            button.removeClass('-loading -fail');
                        }, 1500);
                        responseText.css('color', '#FF3A3A').html('Sending push notification failed.').fadeIn('fast');
                    }

                    console.log(response);
                },
                complete: function() {

                },
                error: function(jqXhr, textStatus, errorThrown) {
                    button.addClass('-fail');
                    setTimeout(function() {
                        button.removeClass('-loading -fail');
                    }, 1500);
                    responseText.css('color', '#FF3A3A').html('Sending push notification failed.').fadeIn('fast');

                    console.log(jqXhr);
                }
            });
        });

        // Clear cache
        daftplugAdmin.on('click', '.daftplugAdminCacheInfo_button', function(e) {
            var self = jQuery(this);
            var cacheMeta = jQuery('.daftplugAdminCacheInfo_meta');
            var action = optionName + '_clear_cache';

            jQuery.ajax({
                url: ajaxurl,
                dataType: 'json',
                type: 'POST',
                data: {
                    action: action
                },
                beforeSend: function() {
                    self.css({
                        'cursor':'default',
                        'pointer-events':'none',
                    }).text('Clearing...');
                },
                success: function(response, textStatus, jqXhr) {
                    self.addClass('-disabled').text('Clear Cache');
                    cacheMeta.text('0 Files, 0 B');
                },
                complete: function() {

                },
                error: function(jqXhr, textStatus, errorThrown) {
                    self.addClass('-disabled').text('Clear Cache');
                    cacheMeta.text('0 Files, 0 B');
                }
            });
        });
    });
})(jQuery);