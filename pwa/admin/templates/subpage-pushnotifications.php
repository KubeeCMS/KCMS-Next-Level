<?php

if (!defined('ABSPATH')) exit;

?>
<div class="daftplugAdminPage_subpage -pushnotifications -flex12" data-subpage="pushnotifications">
    <div class="daftplugAdminPage_content -flex9">
    <?php
    if (!daftplugInstantifyPwa::isOnesignalActive()) {
        if (!version_compare(PHP_VERSION, '7.1', '<') && extension_loaded('gmp') && extension_loaded('mbstring') && extension_loaded('openssl')) {
            ?>
            <div class="daftplugAdminSettings -flexAuto">
                <form name="daftplugAdminSettings_form" class="daftplugAdminSettings_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>" spellcheck="false" autocomplete="off">
                    <fieldset class="daftplugAdminFieldset">
                        <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Subscription List', $this->textDomain); ?></h4>
                        <p class="daftplugAdminFieldset_description"><?php esc_html_e('Below is a list of your users with their device information who subscribed your website for push notifications.', $this->textDomain); ?></p>
                        <?php
                        if (!empty($this->daftplugInstantifyPwaAdminPushnotifications->subscribedDevices)) {
                        ?>
                        <div class="daftplugAdminFieldset_button">
                            <span class="daftplugAdminButton -sendNotification" data-subscription="all" data-open-popup="pushModal"><?php esc_html_e('Send Push Notification', $this->textDomain); ?></span>
                        </div>
                        <?php 
                        }
                        ?>
                        <div class="daftplugAdminTableWrap">
                            <table class="daftplugAdminTable">
                                <thead>
                                    <tr class="daftplugAdminTable_row">
                                        <th class="daftplugAdminTable_header -deviceInfo"><?php esc_html_e('Device Information', $this->textDomain); ?></th>
                                        <th class="daftplugAdminTable_header -regDate"><?php esc_html_e('Registration Date', $this->textDomain); ?></th>
                                        <th class="daftplugAdminTable_header -country"><?php esc_html_e('Country', $this->textDomain); ?></th>
                                        <th class="daftplugAdminTable_header -actions"><?php esc_html_e('Actions', $this->textDomain); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (empty($this->daftplugInstantifyPwaAdminPushnotifications->subscribedDevices)) {
                                ?>
                                    <tr class="daftplugAdminTable_row">
                                        <td class="daftplugAdminTable_data" colspan="4">
                                            <h4 class="daftplugAdminTable_nodata"><?php esc_html_e('No subscribed devices', $this->textDomain); ?></h4>
                                        </td>
                                    </tr>
                                <?php 
                                } else {
                                    foreach($this->daftplugInstantifyPwaAdminPushnotifications->subscribedDevices as $subscribedDevice) {
                                    ?>
                                    <tr class="daftplugAdminTable_row">
                                        <td class="daftplugAdminTable_data -deviceInfo"><?php echo $subscribedDevice['deviceInfo']; ?></td>
                                        <td class="daftplugAdminTable_data -regDate"><?php echo $subscribedDevice['date']; ?></td>
                                        <td class="daftplugAdminTable_data -country"><?php echo (array_key_exists('country', $subscribedDevice) ? $subscribedDevice['country'] : __('Unknown', $this->textDomain)); ?></td>
                                        <td class="daftplugAdminTable_data -actions">
                                            <span class="daftplugAdminTable_action -send" data-subscription="<?php echo $subscribedDevice['endpoint']; ?>" data-tooltip="<?php esc_html_e('Notify', $this->textDomain); ?>" data-open-popup="pushModal">
                                                <svg class="daftplugAdminTable_icon -iconBell">
                                                    <use href="#iconBell"></use>
                                                </svg>
                                            </span>
                                            <span class="daftplugAdminTable_action -remove" data-subscription="<?php echo $subscribedDevice['endpoint']; ?>" data-tooltip="<?php esc_html_e('Remove', $this->textDomain); ?>">
                                                <svg class="daftplugAdminTable_icon -iconRemove">
                                                    <use href="#iconRemove"></use>
                                                </svg>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                    <fieldset class="daftplugAdminFieldset">
                        <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Push Notifications Settings', $this->textDomain); ?></h4>
                        <p class="daftplugAdminFieldset_description"><?php esc_html_e('Web push notifications allow users to opt-in to timely updates from sites they love and allow you to effectively re-engage them with customized, relevant content.', $this->textDomain); ?></p>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable to prompt your users to subscribe for notifications as soon as your website loads on their screens.', $this->textDomain); ?></p>
                            <label for="pwaPromptOnLoad" class="daftplugAdminField_label -flex4"><?php esc_html_e('Prompt On Load', $this->textDomain); ?></label>
                            <label class="daftplugAdminInputCheckbox -flexAuto">
                                <input type="checkbox" name="pwaPromptOnLoad" id="pwaPromptOnLoad" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaPromptOnLoad'), 'on'); ?>>
                            </label>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('This image will represent the notification when there is not enough space to display the notification itself, such as, for example, the Android Notification Bar. It will be automatically masked. For the best result use a single-color graphic with a transparent background. Has to be at least 92x92 px.', $this->textDomain); ?></p>
                            <label for="pwaNotificationBarIcon" class="daftplugAdminField_label -flex4"><?php esc_html_e('Notification Bar Icon', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputUpload -flexAuto">
                                <input type="text" name="pwaNotificationBarIcon" id="pwaNotificationBarIcon" class="daftplugAdminInputUpload_field" value="<?php echo daftplugInstantify::getSetting('pwaNotificationBarIcon'); ?>" data-mimes="png" data-min-width="92" data-max-width="" data-min-height="92" data-max-height="" data-attach-url="<?php echo wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaNotificationBarIcon'), 'full')[0]; ?>">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="daftplugAdminFieldset">
                        <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Push Notifications Automation', $this->textDomain); ?></h4>
                        <p class="daftplugAdminFieldset_description"><?php esc_html_e('From this section you can enable sending automatic predefined push notifications on certain events to re-engage your users and increase conversion. You will also be allowed to exclude particular post events from sending automatic push notification via meta boxes.', $this->textDomain); ?></p>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable automatic notification on publishing new post. Notification will include post title, content and featured image.', $this->textDomain); ?></p>
                            <label for="pwaPushNewPost" class="daftplugAdminField_label -flex5"><?php esc_html_e('New Post Publish', $this->textDomain); ?></label>
                            <label class="daftplugAdminInputCheckbox -flexAuto">
                                <input type="checkbox" name="pwaPushNewPost" id="pwaPushNewPost" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaPushNewPost'), 'on'); ?>>
                            </label>
                        </div>
                        <div class="daftplugAdminField -pwaPushNewPostDependentHideD">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select allowed post types for new post publish notification.', $this->textDomain); ?></p>
                            <label for="pwaPushNewPostPostTypes" class="daftplugAdminField_label -flex5"><?php esc_html_e('New Post Publish Post Types', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputSelect -flexAuto">
                                <select multiple name="pwaPushNewPostPostTypes" id="pwaPushNewPostPostTypes" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('New Post Publish Post Types', $this->textDomain); ?>" autocomplete="off" required>
                                    <?php foreach (array_map('get_post_type_object', $this->daftplugInstantifyPwaAdminPushnotifications->getPostTypes()) as $postType) { ?>
                                        <option value="<?php echo $postType->name; ?>" <?php selected(true, in_array($postType->name, (array)daftplugInstantify::getSetting('pwaPushNewPostPostTypes'))); ?>><?php echo $postType->label; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <?php if (daftplugInstantify::isWooCommerceActive()) { ?>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable automatic notification on adding new product. Notification will include product title, content and featured image.', $this->textDomain); ?></p>
                            <label for="pwaPushNewProduct" class="daftplugAdminField_label -flex5"><?php esc_html_e('WooCommerce New Product', $this->textDomain); ?></label>
                            <label class="daftplugAdminInputCheckbox -flexAuto">
                                <input type="checkbox" name="pwaPushNewProduct" id="pwaPushNewProduct" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaPushNewProduct'), 'on'); ?>>
                            </label>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable automatic notification on product price drop. Notification will include product title and featured image.', $this->textDomain); ?></p>
                            <label for="pwaPushPriceDrop" class="daftplugAdminField_label -flex5"><?php esc_html_e('WooCommerce Price Drop', $this->textDomain); ?></label>
                            <label class="daftplugAdminInputCheckbox -flexAuto">
                                <input type="checkbox" name="pwaPushPriceDrop" id="pwaPushPriceDrop" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaPushPriceDrop'), 'on'); ?>>
                            </label>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable automatic notification when sale price is added to the product. Notification will include product title and featured image.', $this->textDomain); ?></p>
                            <label for="pwaPushSalePrice" class="daftplugAdminField_label -flex5"><?php esc_html_e('WooCommerce Sale Price', $this->textDomain); ?></label>
                            <label class="daftplugAdminInputCheckbox -flexAuto">
                                <input type="checkbox" name="pwaPushSalePrice" id="pwaPushSalePrice" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaPushSalePrice'), 'on'); ?>>
                            </label>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable automatic notification when product is back in stock. Notification will include product title and featured image.', $this->textDomain); ?></p>
                            <label for="pwaPushBackInStock" class="daftplugAdminField_label -flex5"><?php esc_html_e('WooCommerce Back In Stock', $this->textDomain); ?></label>
                            <label class="daftplugAdminInputCheckbox -flexAuto">
                                <input type="checkbox" name="pwaPushBackInStock" id="pwaPushBackInStock" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaPushBackInStock'), 'on'); ?>>
                            </label>
                        </div>
                        <?php } ?>
                    </fieldset>
                    <fieldset class="daftplugAdminFieldset">
                        <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Push Notifications Button', $this->textDomain); ?></h4>
                        <p class="daftplugAdminFieldset_description"><?php esc_html_e('The push notifications button is a custom subscription button on your website to increase opt-in rate and allow your users to fully control when they want to subscribe and unsubscribe for your push notifications.', $this->textDomain); ?></p>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable subscribe push notifications button on your website.', $this->textDomain); ?></p>
                            <label for="pwaSubscribeButton" class="daftplugAdminField_label -flex4"><?php esc_html_e('Subscribe Button', $this->textDomain); ?></label>
                            <label class="daftplugAdminInputCheckbox -flexAuto">
                                <input type="checkbox" name="pwaSubscribeButton" id="pwaSubscribeButton" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaSubscribeButton'), 'on'); ?>>
                            </label>
                        </div>
                        <div class="daftplugAdminField -pwaSubscribeButtonDependentDisableD">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select the bell icon color on your subscribe notifications button.', $this->textDomain); ?></p>
                            <label for="pwaBellIconColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Bell Icon Color', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputColor -flexAuto">
                                <input type="text" name="pwaBellIconColor" id="pwaBellIconColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaBellIconColor'); ?>" data-placeholder="<?php esc_html_e('Bell Icon Color', $this->textDomain); ?>" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField -pwaSubscribeButtonDependentDisableD">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select the background color of your subscribe notifications button.', $this->textDomain); ?></p>
                            <label for="pwaButtonBackgroundColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Button Background Color', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputColor -flexAuto">
                                <input type="text" name="pwaButtonBackgroundColor" id="pwaButtonBackgroundColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaButtonBackgroundColor'); ?>" data-placeholder="<?php esc_html_e('Button Background Color', $this->textDomain); ?>" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField -pwaSubscribeButtonDependentDisableD">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select position of your subscribe notifications button on your website.', $this->textDomain); ?></p>
                            <label for="pwaButtonPosition" class="daftplugAdminField_label -flex4"><?php esc_html_e('Button Position', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputSelect -flexAuto">
                                <select name="pwaButtonPosition" id="pwaButtonPosition" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Button Position', $this->textDomain); ?>" autocomplete="off" required>
                                    <option value="bottom-left" <?php selected(daftplugInstantify::getSetting('pwaButtonPosition'), 'bottom-left') ?>><?php esc_html_e('Bottom Left', $this->textDomain); ?></option>
                                    <option value="top-left" <?php selected(daftplugInstantify::getSetting('pwaButtonPosition'), 'top-left') ?>><?php esc_html_e('Top Left', $this->textDomain); ?></option>
                                    <option value="bottom-right" <?php selected(daftplugInstantify::getSetting('pwaButtonPosition'), 'bottom-right') ?>><?php esc_html_e('Bottom Right', $this->textDomain); ?></option>
                                    <option value="top-right" <?php selected(daftplugInstantify::getSetting('pwaButtonPosition'), 'top-right') ?>><?php esc_html_e('Top Right', $this->textDomain); ?></option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <div class="daftplugAdminSettings_submit">
                        <button type="submit" class="daftplugAdminButton -submit" data-submit="<?php esc_html_e('Save Settings', $this->textDomain); ?>" data-waiting="<?php esc_html_e('Waiting', $this->textDomain); ?>" data-submitted="<?php esc_html_e('Settings Saved', $this->textDomain); ?>" data-failed="<?php esc_html_e('Saving Failed', $this->textDomain); ?>"></button>
                    </div>
                </form>
            </div>
            <?php
        } else {
            ?>
            <fieldset class="daftplugAdminFieldset">
                <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Unsupported PHP version or missing extensions', $this->textDomain); ?></h4>
                <p class="daftplugAdminFieldset_description"><?php _e('Push Notification features need PHP version 7.1 or higher and <i style="font-style: italic;"
                >gmp</i>, <i style="font-style: italic;">mbstring</i> and <i style="font-style: italic;">openssl</i> extensions to function properly. If you\'re having trouble to update your PHP version or enable extensions, please contact your hosting provider to do this in your stead.', $this->textDomain); ?></p>
            </fieldset>
            <?php
        }
    } else {
    ?>
        <fieldset class="daftplugAdminFieldset">
            <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('OneSignal Detected', $this->textDomain); ?></h4>
            <p class="daftplugAdminFieldset_description"><?php _e('You are using the <a class="daftplugAdminLink" href="https://wordpress.org/plugins/onesignal-free-web-push-notifications/" target="_blank">OneSignal Push Notification</a> plugin as a push notification service, so this section is disabled. If you want to use built-in Firebase push notifications, please disable OneSignal plugin and visit this section again.', $this->textDomain); ?></p>
        </fieldset>
    <?php
    }
    ?>
        <div class="daftplugAdminPopup" data-popup="pushModal">
            <div class="daftplugAdminPopup_container">
                <form name="daftplugAdminSendPush_form" class="daftplugAdminSendPush_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_send_notification_nonce"); ?>" spellcheck="false" autocomplete="off">
                    <fieldset class="daftplugAdminFieldset">
                        <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Send Push Notification', $this->textDomain); ?></h4>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select what segment of your subscribers should receive this notification.', $this->textDomain); ?></p>
                            <div class="daftplugAdminInputSelect -flexAuto">
                                <select name="pushSegment" id="pushSegment" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Segment', $this->textDomain); ?>" autocomplete="off" required>
                                    <option value="all"><?php esc_html_e('All Users', $this->textDomain); ?></option>
                                    <option value="mobile"><?php esc_html_e('Mobile Users', $this->textDomain); ?></option>
                                    <option value="desktop"><?php esc_html_e('Desktop Users', $this->textDomain); ?></option>
                                    <?php foreach($this->daftplugInstantifyPwaAdminPushnotifications->subscribedDevices as $subscribedDevice) { ?>
                                        <option value="<?php echo $subscribedDevice['endpoint']; ?>"><?php echo $subscribedDevice['deviceInfo'].' / '.$subscribedDevice['date'].' / '.(array_key_exists('country', $subscribedDevice) ? $subscribedDevice['country'] : __('Unknown', $this->textDomain)); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enter the title of your notification.', $this->textDomain); ?></p>
                            <div class="daftplugAdminInputText -flexAuto">
                                <input type="text" name="pushTitle" id="pushTitle" class="daftplugAdminInputText_field" value="" data-placeholder="<?php esc_html_e('Title', $this->textDomain); ?>" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enter the body of your notification (text description).', $this->textDomain); ?></p>
                            <div class="daftplugAdminInputTextarea -flexAuto">
                                <textarea name="pushBody" id="pushBody" class="daftplugAdminInputTextarea_field" data-placeholder="<?php esc_html_e('Body', $this->textDomain); ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select the image of notification. it will be displayed as large image on notification.', $this->textDomain); ?></p>
                            <div class="daftplugAdminInputUpload -flexAuto">
                                <input type="text" name="pushImage" id="pushImage" class="daftplugAdminInputUpload_field" value="" data-mimes="png,jpg,jpeg" data-min-width="50" data-max-width="" data-min-height="50" data-max-height="" data-attach-url="">
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enter the redirect URL of your notification. Your users will be redirected here after they click on your notification.', $this->textDomain); ?></p>
                            <div class="daftplugAdminInputText -flexAuto">
                                <input type="url" name="pushUrl" id="pushUrl" class="daftplugAdminInputText_field" value="<?php echo trailingslashit(home_url('/', 'https')); ?>" data-placeholder="<?php esc_html_e('URL', $this->textDomain); ?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select the icon of notification. We recommend to use your website logo or site icon.', $this->textDomain); ?></p>
                            <div class="daftplugAdminInputUpload -flexAuto">
                                <input type="text" name="pushIcon" id="pushIcon" class="daftplugAdminInputUpload_field" value="" data-mimes="png,jpg,jpeg" data-min-width="50" data-max-width="" data-min-height="50" data-max-height="" data-attach-url="">
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('You can add up to two action buttons to the notification. Currently action buttons are only supported by Chrome browsers.', $this->textDomain); ?></p>
                        </div>
                        <div class="daftplugAdminFieldset -miniFieldset -pushActionbutton1">
                        	<h5 class="daftplugAdminFieldset_title"><?php esc_html_e('Action Button 1', $this->textDomain); ?></h5>
                            <label class="daftplugAdminInputCheckbox -flexAuto -hidden">
                                <input type="checkbox" name="pushActionbutton1" id="pushActionbutton1" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pushActionbutton1'), 'on'); ?>>
                            </label>
	                        <div class="daftplugAdminField">
	                            <div class="daftplugAdminInputText -flexAuto">
	                                <input type="text" name="pushActionbutton1Text" id="pushActionbutton1Text" class="daftplugAdminInputText_field" value="" data-placeholder="<?php esc_html_e('Text', $this->textDomain); ?>" autocomplete="off" required>
	                            </div>
	                        </div>
	                        <div class="daftplugAdminField">
	                            <div class="daftplugAdminInputText -flexAuto">
	                                <input type="url" name="pushActionbutton1Url" id="pushActionbutton1Url" class="daftplugAdminInputText_field" value="" data-placeholder="<?php esc_html_e('URL', $this->textDomain); ?>" autocomplete="off" required>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="daftplugAdminFieldset -miniFieldset -pushActionbutton2">
	                    	<h5 class="daftplugAdminFieldset_title"><?php esc_html_e('Action Button 2', $this->textDomain); ?></h5>
                            <label class="daftplugAdminInputCheckbox -flexAuto -hidden">
                                <input type="checkbox" name="pushActionbutton2" id="pushActionbutton2" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pushActionbutton2'), 'on'); ?>>
                            </label>
	                        <div class="daftplugAdminField">
	                            <div class="daftplugAdminInputText -flexAuto">
	                                <input type="text" name="pushActionbutton2Text" id="pushActionbutton2Text" class="daftplugAdminInputText_field" value="" data-placeholder="<?php esc_html_e('Text', $this->textDomain); ?>" autocomplete="off" required>
	                            </div>
	                        </div>
	                        <div class="daftplugAdminField">
	                            <div class="daftplugAdminInputText -flexAuto">
	                                <input type="url" name="pushActionbutton2Url" id="pushActionbutton2Url" class="daftplugAdminInputText_field" value="" data-placeholder="<?php esc_html_e('URL', $this->textDomain); ?>" autocomplete="off" required>
	                            </div>
	                        </div>
	                    </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputAddField -flexAuto">
                                <span class="daftplugAdminButton -addField" data-add="pushActionbutton" data-max="2"><?php esc_html_e('+ Add Action Button', $this->textDomain); ?></span>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminField_response -flex7"></div>
                            <div class="daftplugAdminField_submit -flex5">
                                <button type="submit" class="daftplugAdminButton -submit" data-submit="<?php esc_html_e('Send Notification', $this->textDomain); ?>" data-waiting="<?php esc_html_e('Sending', $this->textDomain); ?>" data-submitted="<?php esc_html_e('Notification Sent', $this->textDomain); ?>" data-failed="<?php esc_html_e('Sending Failed', $this->textDomain); ?>"></button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>       
        </div>
    </div>
</div>