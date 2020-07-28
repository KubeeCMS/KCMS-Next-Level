<?php

if (!defined('ABSPATH')) exit;

?>
<div class="daftplugAdminPage_subpage -addtohomescreen -flex12" data-subpage="addtohomescreen">
	<div class="daftplugAdminPage_content -flex8">
        <div class="daftplugAdminSettings -flexAuto">
            <form name="daftplugAdminSettings_form" class="daftplugAdminSettings_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>" spellcheck="false" autocomplete="off">
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Web App Manifest', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('The web app manifest is a simple JSON file that tells the browser about your web application and how it should behave when "installed" on the user\'s mobile device or desktop. Having a manifest is required by browsers to show the Add to Home Screen prompt.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Dynamic Manifest option gives you ability to make each of your pages and posts installable individually. That means that your app name, short name, description and URL will be automatically fetched from the current singular page that the user is accessing. This will apply only on singular pages, so on the homepage app name, short name, description and URL will be taken from the settings below.', $this->textDomain); ?></p>
                        <label for="pwaDynamicManifest" class="daftplugAdminField_label -flex4"><?php esc_html_e('Dynamic Manifest', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaDynamicManifest" id="pwaDynamicManifest" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaDynamicManifest'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enter the name of your web app.', $this->textDomain); ?></p>
                        <label for="pwaName" class="daftplugAdminField_label -flex4"><?php esc_html_e('Name', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputText -flexAuto">
                            <input type="text" name="pwaName" id="pwaName" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaName'); ?>" data-placeholder="<?php esc_html_e('Name', $this->textDomain); ?>" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enter the shortened name of your web app. Maximum 12 characters.', $this->textDomain); ?></p>
                        <label for="pwaShortName" class="daftplugAdminField_label -flex4"><?php esc_html_e('Short Name', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputText -flexAuto">
                            <input type="text" name="pwaShortName" id="pwaShortName" class="daftplugAdminInputText_field" maxlength="12" value="<?php echo daftplugInstantify::getSetting('pwaShortName'); ?>" data-placeholder="<?php esc_html_e('Short Name', $this->textDomain); ?>" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the start page of your web application.', $this->textDomain); ?></p>
                        <label for="pwaStartPage" class="daftplugAdminField_label -flex4"><?php esc_html_e('Start Page', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaStartPage" id="pwaStartPage" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Start Page', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="<?php echo trailingslashit(home_url('/', 'https')); ?>" <?php selected(daftplugInstantify::getSetting('pwaStartPage'), trailingslashit(home_url('/', 'https'))) ?>><?php esc_html_e('Home Page', $this->textDomain); ?></option>
                            	<?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(daftplugInstantify::getSetting('pwaStartPage'), get_page_link($page->ID)) ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Describe your web application. The description should contain your web app\'s purpose and goals.', $this->textDomain); ?></p>
                        <label for="pwaDescription" class="daftplugAdminField_label -flex4"><?php esc_html_e('Description', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputTextarea -flexAuto">
                            <textarea name="pwaDescription" id="pwaDescription" class="daftplugAdminInputTextarea_field" data-placeholder="<?php esc_html_e('Description', $this->textDomain); ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" rows="4" required><?php echo daftplugInstantify::getSetting('pwaDescription'); ?></textarea>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php printf(__('Your web app icon and WordPress Site Icon are the same, so we will use your site icon as a PWA icon if you have set the site icon in the <a class="daftplugAdminLink" href="%s">WordPress customizer</a>. Alternatively, if you\'re not using WP Site Icon feature, you can set PWA icon manually.', $this->textDomain), add_query_arg('autofocus[control]', 'site_icon', admin_url('customize.php'))); ?></p>
                        <label for="pwaIcon" class="daftplugAdminField_label -flex4"><?php esc_html_e('Icon', $this->textDomain); ?></label>
						<?php 
                        if (has_site_icon()) {
                        ?>
                        <div class="daftplugAdminInputIcon -flexAuto">
							<img id="pwaIcon" width="30px" height="30px" data-attach-url="<?php echo get_site_icon_url(512); ?>" src="<?php echo get_site_icon_url(512); ?>"/>
						</div>
						<?php
                        } else {
                        ?>
                        <div class="daftplugAdminInputUpload -flexAuto">
							<input type="text" name="pwaIcon" id="pwaIcon" class="daftplugAdminInputUpload_field" value="<?php echo daftplugInstantify::getSetting('pwaIcon'); ?>" data-mimes="png" data-min-width="512" data-max-width="" data-min-height="512" data-max-height="" data-attach-url="<?php echo wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaIcon'), array(512, 512))[0]; ?>" required>
                        </div>
                        <?php	
                        }
                        ?>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Maskable icons is a new icon format that ensures that your PWA icon looks great on all Android devices. On newer Android devices, PWA icons that don\'t follow the maskable icon format are a given a white background. When you use a maskable icon, it ensures that the icon takes up all of the space that Android provides for it.', $this->textDomain); ?></p>
                        <label for="pwaIconMaskable" class="daftplugAdminField_label -flex4"><?php esc_html_e('Maskable Icon', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaIconMaskable" id="pwaIconMaskable" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaIconMaskable'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the display mode of your web application. We recommend to choose Standalone, as it provides a native app feeling.', $this->textDomain); ?></p>
                        <label for="pwaDisplayMode" class="daftplugAdminField_label -flex4"><?php esc_html_e('Display Mode', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaDisplayMode" id="pwaDisplayMode" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Display Mode', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="fullscreen" <?php selected(daftplugInstantify::getSetting('pwaDisplayMode'), 'fullscreen') ?>><?php esc_html_e('Fullscreen - App takes whole display', $this->textDomain); ?></option>
                                <option value="standalone" <?php selected(daftplugInstantify::getSetting('pwaDisplayMode'), 'standalone') ?>><?php esc_html_e('Standalone - Native app feeling', $this->textDomain); ?></option>
                                <option value="minimal-ui" <?php selected(daftplugInstantify::getSetting('pwaDisplayMode'), 'minimal-ui') ?>><?php esc_html_e('Minimal UI - App with browser controls', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select orientation of your web application. We recommend to choose Portrait, as it provides a more native app feeling.', $this->textDomain); ?></p>
                        <label for="pwaOrientation" class="daftplugAdminField_label -flex4"><?php esc_html_e('Orientation', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaOrientation" id="pwaOrientation" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Orientation', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="any" <?php selected(daftplugInstantify::getSetting('pwaOrientation'), 'any') ?>><?php esc_html_e('Both', $this->textDomain); ?></option>
                                <option value="portrait" <?php selected(daftplugInstantify::getSetting('pwaOrientation'), 'portrait') ?>><?php esc_html_e('Portrait', $this->textDomain); ?></option>
                                <option value="landscape" <?php selected(daftplugInstantify::getSetting('pwaOrientation'), 'landscape') ?>><?php esc_html_e('Landscape', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the iOS status bar style for your web application when accessed from Apple devices.', $this->textDomain); ?></p>
                        <label for="pwaIosStatusBarStyle" class="daftplugAdminField_label -flex4"><?php esc_html_e('iOS Status Bar Style', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaIosStatusBarStyle" id="pwaIosStatusBarStyle" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('iOS Status Bar Style', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="default" <?php selected(daftplugInstantify::getSetting('pwaIosStatusBarStyle'), 'default') ?>><?php esc_html_e('White bar with black text', $this->textDomain); ?></option>
                                <option value="black" <?php selected(daftplugInstantify::getSetting('pwaIosStatusBarStyle'), 'black') ?>><?php esc_html_e('Black bar with white text', $this->textDomain); ?></option>
                                <option value="black-translucent" <?php selected(daftplugInstantify::getSetting('pwaIosStatusBarStyle'), 'black-translucent') ?>><?php esc_html_e('Transparent bar with white text', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the theme color of your web application. It should be same as the main color palette of your website.', $this->textDomain); ?></p>
                        <label for="pwaThemeColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Theme Color', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputColor -flexAuto">
                            <input type="text" name="pwaThemeColor" id="pwaThemeColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaThemeColor'); ?>" data-placeholder="<?php esc_html_e('Theme Color', $this->textDomain); ?>" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the background color of your web application. It should be same as the background color of your website.', $this->textDomain); ?></p>
                        <label for="pwaBackgroundColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Background Color', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputColor -flexAuto">
                            <input type="text" name="pwaBackgroundColor" id="pwaBackgroundColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaBackgroundColor'); ?>" data-placeholder="<?php esc_html_e('Background Color', $this->textDomain); ?>" required>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('App Shortcuts', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('App shortcuts help users quickly start common or recommended tasks within your web app. Easy access to those tasks from anywhere the app icon is displayed will enhance users productivity as well as increase their engagement with the web app. The app shortcuts menu is invoked by right-clicking the app icon in the taskbar (Windows) or dock (macOS) on the user\'s desktop, or long pressing the app\'s launcher icon on Android.', $this->textDomain); ?></p>
                    <div class="daftplugAdminFieldset -miniFieldset -pwaAppShortcut1">
                        <h5 class="daftplugAdminFieldset_title"><?php esc_html_e('App Shortcut 1', $this->textDomain); ?></h5>
	                    <label class="daftplugAdminInputCheckbox -flexAuto -hidden">
							<input type="checkbox" name="pwaAppShortcut1" id="pwaAppShortcut1" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaAppShortcut1'), 'on'); ?>>
						</label>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputText -flexAuto">
                                <input type="text" name="pwaAppShortcut1Name" id="pwaAppShortcut1Name" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaAppShortcut1Name'); ?>" data-placeholder="<?php esc_html_e('Name', $this->textDomain); ?>" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputText -flexAuto">
                                <input type="url" name="pwaAppShortcut1Url" id="pwaAppShortcut1Url" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaAppShortcut1Url'); ?>" data-placeholder="<?php esc_html_e('URL', $this->textDomain); ?>" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputUpload -flexAuto">
                                <input type="text" name="pwaAppShortcut1Icon" id="pwaAppShortcut1Icon" class="daftplugAdminInputUpload_field" value="<?php echo daftplugInstantify::getSetting('pwaAppShortcut1Icon'); ?>" data-mimes="png" data-min-width="192" data-max-width="" data-min-height="192" data-max-height="" data-attach-url="<?php echo wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaAppShortcut1Icon'), array(192, 192))[0]; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="daftplugAdminFieldset -miniFieldset -pwaAppShortcut2">
                        <h5 class="daftplugAdminFieldset_title"><?php esc_html_e('App Shortcut 2', $this->textDomain); ?></h5>
                    	<label class="daftplugAdminInputCheckbox -flexAuto -hidden">
                    		<input type="checkbox" name="pwaAppShortcut2" id="pwaAppShortcut2" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaAppShortcut2'), 'on'); ?>>
                    	</label>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputText -flexAuto">
                                <input type="text" name="pwaAppShortcut2Name" id="pwaAppShortcut2Name" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaAppShortcut2Name'); ?>" data-placeholder="<?php esc_html_e('Name', $this->textDomain); ?>" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputText -flexAuto">
                                <input type="url" name="pwaAppShortcut2Url" id="pwaAppShortcut2Url" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaAppShortcut2Url'); ?>" data-placeholder="<?php esc_html_e('URL', $this->textDomain); ?>" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputUpload -flexAuto">
                                <input type="text" name="pwaAppShortcut2Icon" id="pwaAppShortcut2Icon" class="daftplugAdminInputUpload_field" value="<?php echo daftplugInstantify::getSetting('pwaAppShortcut2Icon'); ?>" data-mimes="png" data-min-width="192" data-max-width="" data-min-height="192" data-max-height="" data-attach-url="<?php echo wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaAppShortcut2Icon'), array(192, 192))[0]; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="daftplugAdminFieldset -miniFieldset -pwaAppShortcut3">
                        <h5 class="daftplugAdminFieldset_title"><?php esc_html_e('App Shortcut 3', $this->textDomain); ?></h5>
                    	<label class="daftplugAdminInputCheckbox -flexAuto -hidden">
                    		<input type="checkbox" name="pwaAppShortcut3" id="pwaAppShortcut3" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaAppShortcut3'), 'on'); ?>>
                    	</label>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputText -flexAuto">
                                <input type="text" name="pwaAppShortcut3Name" id="pwaAppShortcut3Name" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaAppShortcut3Name'); ?>" data-placeholder="<?php esc_html_e('Name', $this->textDomain); ?>" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputText -flexAuto">
                                <input type="url" name="pwaAppShortcut3Url" id="pwaAppShortcut3Url" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaAppShortcut3Url'); ?>" data-placeholder="<?php esc_html_e('URL', $this->textDomain); ?>" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputUpload -flexAuto">
                                <input type="text" name="pwaAppShortcut3Icon" id="pwaAppShortcut3Icon" class="daftplugAdminInputUpload_field" value="<?php echo daftplugInstantify::getSetting('pwaAppShortcut3Icon'); ?>" data-mimes="png" data-min-width="192" data-max-width="" data-min-height="192" data-max-height="" data-attach-url="<?php echo wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaAppShortcut3Icon'), array(192, 192))[0]; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="daftplugAdminFieldset -miniFieldset -pwaAppShortcut4">
                        <h5 class="daftplugAdminFieldset_title"><?php esc_html_e('App Shortcut 4', $this->textDomain); ?></h5>
                    	<label class="daftplugAdminInputCheckbox -flexAuto -hidden">
                    		<input type="checkbox" name="pwaAppShortcut4" id="pwaAppShortcut4" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaAppShortcut4'), 'on'); ?>>
                    	</label>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputText -flexAuto">
                                <input type="text" name="pwaAppShortcut4Name" id="pwaAppShortcut4Name" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaAppShortcut4Name'); ?>" data-placeholder="<?php esc_html_e('Name', $this->textDomain); ?>" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputText -flexAuto">
                                <input type="url" name="pwaAppShortcut4Url" id="pwaAppShortcut4Url" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaAppShortcut4Url'); ?>" data-placeholder="<?php esc_html_e('URL', $this->textDomain); ?>" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputUpload -flexAuto">
                                <input type="text" name="pwaAppShortcut4Icon" id="pwaAppShortcut4Icon" class="daftplugAdminInputUpload_field" value="<?php echo daftplugInstantify::getSetting('pwaAppShortcut4Icon'); ?>" data-mimes="png" data-min-width="192" data-max-width="" data-min-height="192" data-max-height="" data-attach-url="<?php echo wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaAppShortcut4Icon'), array(192, 192))[0]; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <div class="daftplugAdminInputAddField -flexAuto">
                            <span class="daftplugAdminButton -addField" data-add="pwaAppShortcut" data-max="4"><?php esc_html_e('+ Add App Shortcut', $this->textDomain); ?></span>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Installation Overlays', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('Display an "Add to Home Screen" overlays for major mobile browsers to make your website installable and grant a prominent place on the user\'s home screen, right next to the native apps.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable installation overlays.', $this->textDomain); ?></p>
                        <label for="pwaOverlays" class="daftplugAdminField_label -flex4"><?php esc_html_e('Installation Overlays', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaOverlays" id="pwaOverlays" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaOverlays'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField -pwaOverlaysDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the supported web browsers for installation overlays. We recommend to choose all the browsers available.', $this->textDomain); ?></p>
                        <label for="pwaOverlaysBrowsers" class="daftplugAdminField_label -flex4"><?php esc_html_e('Supported Browsers', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select multiple name="pwaOverlaysBrowsers" id="pwaOverlaysBrowsers" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Supported Browsers', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="chrome" <?php selected(true, in_array('chrome', (array)daftplugInstantify::getSetting('pwaOverlaysBrowsers'))); ?>><?php esc_html_e('Google Chrome', $this->textDomain); ?></option>
                                <option value="firefox" <?php selected(true, in_array('firefox', (array)daftplugInstantify::getSetting('pwaOverlaysBrowsers'))); ?>><?php esc_html_e('Mozilla Firefox', $this->textDomain); ?></option>
                                <option value="safari" <?php selected(true, in_array('safari', (array)daftplugInstantify::getSetting('pwaOverlaysBrowsers'))); ?>><?php esc_html_e('Apple Safari', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaOverlaysDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the installation banner types for your overlays. We recommend not to choose fullscreen and header banner types together. Navigation menu overlay is an experimental feature and it may not look natural on your theme.', $this->textDomain); ?></p>
                        <label for="pwaOverlaysTypes" class="daftplugAdminField_label -flex4"><?php esc_html_e('Overlay Types', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select multiple name="pwaOverlaysTypes" id="pwaOverlaysTypes" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Overlay Types', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="fullscreen" <?php selected(true, in_array('fullscreen', (array)daftplugInstantify::getSetting('pwaOverlaysTypes'))); ?>><?php esc_html_e('Fullscreen', $this->textDomain); ?></option>
                                <option value="menu" <?php selected(true, in_array('menu', (array)daftplugInstantify::getSetting('pwaOverlaysTypes'))); ?>><?php esc_html_e('Navigation Menu', $this->textDomain); ?></option>
                                <option value="header" <?php selected(true, in_array('header', (array)daftplugInstantify::getSetting('pwaOverlaysTypes'))); ?>><?php esc_html_e('Header Banner', $this->textDomain); ?></option>
                                <option value="post" <?php selected(true, in_array('post', (array)daftplugInstantify::getSetting('pwaOverlaysTypes'))); ?>><?php esc_html_e('Post Popup', $this->textDomain); ?></option>
                                <?php if (daftplugInstantify::isWooCommerceActive()) { ?>
                                <option value="checkout" <?php selected(true, in_array('checkout', (array)daftplugInstantify::getSetting('pwaOverlaysTypes'))); ?>><?php esc_html_e('WooCommerce Checkout', $this->textDomain); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaOverlaysDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the background color of installation banners.', $this->textDomain); ?></p>
                        <label for="pwaOverlaysBackgroundColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Background Color', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputColor -flexAuto">
                            <input type="text" name="pwaOverlaysBackgroundColor" id="pwaOverlaysBackgroundColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysBackgroundColor'); ?>" data-placeholder="<?php esc_html_e('Background Color', $this->textDomain); ?>" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaOverlaysDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the text color of installation banners.', $this->textDomain); ?></p>
                        <label for="pwaOverlaysTextColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Text Color', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputColor -flexAuto">
                            <input type="text" name="pwaOverlaysTextColor" id="pwaOverlaysTextColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysTextColor'); ?>" data-placeholder="<?php esc_html_e('Text Color', $this->textDomain); ?>" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaOverlaysDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Choose when to show installation overlays again if they were dismissed. Timeout is in days.', $this->textDomain); ?></p>
                        <label for="pwaOverlaysShowAgain" class="daftplugAdminField_label -flex4"><?php esc_html_e('Timeout', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputNumber -flexAuto">
                            <input type="number" name="pwaOverlaysShowAgain" id="pwaOverlaysShowAgain" class="daftplugAdminInputNumber_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysShowAgain'); ?>" min="1" step="1" max="60" data-placeholder="<?php esc_html_e('Timeout', $this->textDomain); ?>" required>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Installation Button', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('You are able to insert an installation button anywhere on your website using the shortcode.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable installation button.', $this->textDomain); ?></p>
                        <label for="pwaInstallButton" class="daftplugAdminField_label -flex4"><?php esc_html_e('Installation Button', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaInstallButton" id="pwaInstallButton" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaInstallButton'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField -pwaInstallButtonDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('You can insert an installation button anywhere on your website using the shortcode below.', $this->textDomain); ?></p>
                        <label for="pwaInstallButtonShortcode" class="daftplugAdminField_label -flex4"><?php esc_html_e('Button Shortcode', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputText -flexAuto">
                            <input type="text" name="pwaInstallButtonShortcode" id="pwaInstallButtonShortcode" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaInstallButtonShortcode'); ?>" data-placeholder="<?php esc_html_e('Button Shortcode', $this->textDomain); ?>" readonly disabled>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaInstallButtonDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the supported web browsers for installation button. We recommend to choose all the browsers available.', $this->textDomain); ?></p>
                        <label for="pwaInstallButtonBrowsers" class="daftplugAdminField_label -flex4"><?php esc_html_e('Supported Browsers', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select multiple name="pwaInstallButtonBrowsers" id="pwaInstallButtonBrowsers" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Supported Browsers', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="chrome" <?php selected(true, in_array('chrome', (array)daftplugInstantify::getSetting('pwaInstallButtonBrowsers'))); ?>><?php esc_html_e('Google Chrome', $this->textDomain); ?></option>
                                <option value="firefox" <?php selected(true, in_array('firefox', (array)daftplugInstantify::getSetting('pwaInstallButtonBrowsers'))); ?>><?php esc_html_e('Mozilla Firefox', $this->textDomain); ?></option>
                                <option value="safari" <?php selected(true, in_array('safari', (array)daftplugInstantify::getSetting('pwaInstallButtonBrowsers'))); ?>><?php esc_html_e('Apple Safari', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaInstallButtonDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Insert your installation text which will be displayed on the button.', $this->textDomain); ?></p>
                        <label for="pwaInstallButtonText" class="daftplugAdminField_label -flex4"><?php esc_html_e('Button Text', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputText -flexAuto">
                            <input type="text" name="pwaInstallButtonText" id="pwaInstallButtonText" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaInstallButtonText'); ?>" data-placeholder="<?php esc_html_e('Button Text', $this->textDomain); ?>" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaInstallButtonDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the background color of installation button.', $this->textDomain); ?></p>
                        <label for="pwaInstallButtonBackgroundColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Background Color', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputColor -flexAuto">
                            <input type="text" name="pwaInstallButtonBackgroundColor" id="pwaInstallButtonBackgroundColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaInstallButtonBackgroundColor'); ?>" data-placeholder="<?php esc_html_e('Background Color', $this->textDomain); ?>" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaInstallButtonDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the text color of installation button.', $this->textDomain); ?></p>
                        <label for="pwaInstallButtonTextColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Text Color', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputColor -flexAuto">
                            <input type="text" name="pwaInstallButtonTextColor" id="pwaInstallButtonTextColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaInstallButtonTextColor'); ?>" data-placeholder="<?php esc_html_e('Text Color', $this->textDomain); ?>" required>
                        </div>
                    </div>
                </fieldset>
                <div class="daftplugAdminSettings_submit">
                    <button type="submit" class="daftplugAdminButton -submit" data-submit="<?php esc_html_e('Save Settings', $this->textDomain); ?>" data-waiting="<?php esc_html_e('Waiting', $this->textDomain); ?>" data-submitted="<?php esc_html_e('Settings Saved', $this->textDomain); ?>" data-failed="<?php esc_html_e('Saving Failed', $this->textDomain); ?>"></button>
                </div>
            </form>
        </div>
    </div>
</div>