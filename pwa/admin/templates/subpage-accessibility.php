<?php

if (!defined('ABSPATH')) exit;

?>
<div class="daftplugAdminPage_subpage -accessibility -flex12" data-subpage="accessibility">
	<div class="daftplugAdminPage_content -flex8">
        <div class="daftplugAdminSettings -flexAuto">
            <form name="daftplugAdminSettings_form" class="daftplugAdminSettings_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>" spellcheck="false" autocomplete="off">
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Ajaxify', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php _e('Ajaxify brings a true native app like experience by loading your content without reloading entire page. For the best results we recommend to also enable Preloader feature. If you want to exclude certain links or forms from ajaxify, just add <code>no-ajaxy</code> class on the element.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable ajaxifying your website.', $this->textDomain); ?></p>
                        <label for="pwaAjaxify" class="daftplugAdminField_label -flex4"><?php esc_html_e('Ajaxify', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaAjaxify" id="pwaAjaxify" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaAjaxify'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField -pwaAjaxifyDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable also ajaxifying forms.', $this->textDomain); ?></p>
                        <label for="pwaAjaxifyForms" class="daftplugAdminField_label -flex4"><?php esc_html_e('Ajaxify Forms', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaAjaxifyForms" id="pwaAjaxifyForms" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaAjaxifyForms'), 'on'); ?>>
                        </label>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Navigation Tab Bar', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php _e('Navigation tab bar provides app like experience by adding tabbed navigation menu bar on the bottom of your web app when accessed from mobile devices. If you want to hide some tabs, just select <code>*Disabled*</code> option.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable navigation tab bar.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBar" class="daftplugAdminField_label -flex4"><?php esc_html_e('Navigation Tab Bar', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaNavigationTabBar" id="pwaNavigationTabBar" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaNavigationTabBar'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField -pwaNavigationTabBarDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the background color of navigation tab bar.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBarBgColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Background Color', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputColor -flexAuto">
                            <input type="text" name="pwaNavigationTabBarBgColor" id="pwaNavigationTabBarBgColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaNavigationTabBarBgColor'); ?>" data-placeholder="<?php esc_html_e('Background Color', $this->textDomain); ?>" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaNavigationTabBarDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the color of icons in navigation tab bar.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBarIconColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Icon Color', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputColor -flexAuto">
                            <input type="text" name="pwaNavigationTabBarIconColor" id="pwaNavigationTabBarIconColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaNavigationTabBarIconColor'); ?>" data-placeholder="<?php esc_html_e('Icon Color', $this->textDomain); ?>" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaNavigationTabBarDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the color of active icon in navigation tab bar.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBarIconActiveColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Active Icon Color', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputColor -flexAuto">
                            <input type="text" name="pwaNavigationTabBarIconActiveColor" id="pwaNavigationTabBarIconActiveColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaNavigationTabBarIconActiveColor'); ?>" data-placeholder="<?php esc_html_e('Active Icon Color', $this->textDomain); ?>" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaNavigationTabBarDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the home page of your web app.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBarHome" class="daftplugAdminField_label -flex4"><?php esc_html_e('Home Page', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaNavigationTabBarHome" id="pwaNavigationTabBarHome" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Home Page', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="<?php echo trailingslashit(home_url('/', 'https')); ?>" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarHome'), trailingslashit(home_url('/', 'https'))) ?>><?php esc_html_e('Home Page', $this->textDomain); ?></option>
                            	<?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarHome'), get_page_link($page->ID)) ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                                <option value="*disabled*" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarHome'), '*disabled*') ?>><?php esc_html_e('*Disabled*', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaNavigationTabBarDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the search page of your web app.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBarSearch" class="daftplugAdminField_label -flex4"><?php esc_html_e('Search Page', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaNavigationTabBarSearch" id="pwaNavigationTabBarSearch" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Search Page', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="*directSearch*" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarSearch'), '*directSearch*') ?>><?php esc_html_e('*Direct Search*', $this->textDomain); ?></option>
                            	<?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarSearch'), get_page_link($page->ID)) ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                                <option value="*disabled*" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarSearch'), '*disabled*') ?>><?php esc_html_e('*Disabled*', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <?php if (daftplugInstantify::isWooCommerceActive()) { ?>
                    <div class="daftplugAdminField -pwaNavigationTabBarDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the shop page of your web app.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBarShop" class="daftplugAdminField_label -flex4"><?php esc_html_e('Shop Page', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaNavigationTabBarShop" id="pwaNavigationTabBarShop" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Shop Page', $this->textDomain); ?>" autocomplete="off" required>
                                <?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarShop'), get_page_link($page->ID)) ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                                <option value="*disabled*" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarShop'), '*disabled*') ?>><?php esc_html_e('*Disabled*', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaNavigationTabBarDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the cart page of your web app.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBarCart" class="daftplugAdminField_label -flex4"><?php esc_html_e('Cart Page', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaNavigationTabBarCart" id="pwaNavigationTabBarCart" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Cart Page', $this->textDomain); ?>" autocomplete="off" required>
                            	<?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarCart'), get_page_link($page->ID)) ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                                <option value="*disabled*" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarCart'), '*disabled*') ?>><?php esc_html_e('*Disabled*', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaNavigationTabBarDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the checkout page of your web app.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBarCheckout" class="daftplugAdminField_label -flex4"><?php esc_html_e('Checkout Page', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaNavigationTabBarCheckout" id="pwaNavigationTabBarCheckout" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Checkout Page', $this->textDomain); ?>" autocomplete="off" required>
                                <?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarCheckout'), get_page_link($page->ID)) ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                                <option value="*disabled*" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarCheckout'), '*disabled*') ?>><?php esc_html_e('*Disabled*', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="daftplugAdminField -pwaNavigationTabBarDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the notifications page of your web app.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBarNotifications" class="daftplugAdminField_label -flex4"><?php esc_html_e('Notifications Page', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaNavigationTabBarNotifications" id="pwaNavigationTabBarNotifications" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Notifications Page', $this->textDomain); ?>" autocomplete="off" required>
                            	<?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarNotifications'), get_page_link($page->ID)) ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                                <option value="*disabled*" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarNotifications'), '*disabled*') ?>><?php esc_html_e('*Disabled*', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaNavigationTabBarDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the categories page of your web app.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBarCategories" class="daftplugAdminField_label -flex4"><?php esc_html_e('Categories Page', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaNavigationTabBarCategories" id="pwaNavigationTabBarCategories" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Categories Page', $this->textDomain); ?>" autocomplete="off" required>
                            	<?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarCategories'), get_page_link($page->ID)) ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                                <option value="*disabled*" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarCategories'), '*disabled*') ?>><?php esc_html_e('*Disabled*', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaNavigationTabBarDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the profile page of your web app.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBarProfile" class="daftplugAdminField_label -flex4"><?php esc_html_e('Profile Page', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaNavigationTabBarProfile" id="pwaNavigationTabBarProfile" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Profile Page', $this->textDomain); ?>" autocomplete="off" required>
                            	<?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarProfile'), get_page_link($page->ID)) ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                                <option value="*disabled*" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarProfile'), '*disabled*') ?>><?php esc_html_e('*Disabled*', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaNavigationTabBarDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the about page of your web app.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBarAbout" class="daftplugAdminField_label -flex4"><?php esc_html_e('About Page', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaNavigationTabBarAbout" id="pwaNavigationTabBarAbout" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('About Page', $this->textDomain); ?>" autocomplete="off" required>
                                <?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarAbout'), get_page_link($page->ID)) ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                                <option value="*disabled*" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarAbout'), '*disabled*') ?>><?php esc_html_e('*Disabled*', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaNavigationTabBarDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the contact page of your web app.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBarContact" class="daftplugAdminField_label -flex4"><?php esc_html_e('Contact Page', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaNavigationTabBarContact" id="pwaNavigationTabBarContact" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Contact Page', $this->textDomain); ?>" autocomplete="off" required>
                            	<?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarContact'), get_page_link($page->ID)) ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                                <option value="*disabled*" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarContact'), '*disabled*') ?>><?php esc_html_e('*Disabled*', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaNavigationTabBarDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the settings page of your web app.', $this->textDomain); ?></p>
                        <label for="pwaNavigationTabBarSettings" class="daftplugAdminField_label -flex4"><?php esc_html_e('Settings Page', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaNavigationTabBarSettings" id="pwaNavigationTabBarSettings" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Settings Page', $this->textDomain); ?>" autocomplete="off" required>
                            	<?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarSettings'), get_page_link($page->ID)) ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                                <option value="*disabled*" <?php selected(daftplugInstantify::getSetting('pwaNavigationTabBarSettings'), '*disabled*') ?>><?php esc_html_e('*Disabled*', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                	<h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Pull Down Navigation', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('Pull down navigation touchscreen gesture allows your users to drag the screen and then release it, as a signal to the application to refresh contents or navigate your web app.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable pull down navigation touchscreen gesture.', $this->textDomain); ?></p>
                        <label for="pwaPullDownNavigation" class="daftplugAdminField_label -flex4"><?php esc_html_e('Pull Down Navigation', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaPullDownNavigation" id="pwaPullDownNavigation" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaPullDownNavigation'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField -pwaPullDownNavigationDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the background color of pull down navigation touchscreen gesture.', $this->textDomain); ?></p>
                        <label for="pwaPullDownNavigationBgColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Background Color', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputColor -flexAuto">
                            <input type="text" name="pwaPullDownNavigationBgColor" id="pwaPullDownNavigationBgColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaPullDownNavigationBgColor'); ?>" data-placeholder="<?php esc_html_e('Background Color', $this->textDomain); ?>" required>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                	<h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Swipe Navigation', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('Swipe Navigation allows your users to execute web browser\'s "Back" and "Next" actions by simple swiping on the screen anywhere on the website. Swiping left will result in going back and swiping right will result in going next.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable swipe navigation touchscreen gesture.', $this->textDomain); ?></p>
                        <label for="pwaSwipeNavigation" class="daftplugAdminField_label -flex4"><?php esc_html_e('Swipe Navigation', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaSwipeNavigation" id="pwaSwipeNavigation" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaSwipeNavigation'), 'on'); ?>>
                        </label>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                	<h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Shake To Refresh', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('Shake to refresh gesture brings amazing UX experience to your users by simply shaking phone as a signal to your web application to refresh the contents of the screen.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable shake to refresh gesture.', $this->textDomain); ?></p>
                        <label for="pwaShakeToRefresh" class="daftplugAdminField_label -flex4"><?php esc_html_e('Shake To Refresh', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaShakeToRefresh" id="pwaShakeToRefresh" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaShakeToRefresh'), 'on'); ?>>
                        </label>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                	<h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Vibration', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('Vibration feature creates vibes on tapping for mobile users. That can help mobile users recognize when they are tapping and clicking on your website.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable vibration support for your web app.', $this->textDomain); ?></p>
                        <label for="pwaVibration" class="daftplugAdminField_label -flex4"><?php esc_html_e('Vibration', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaVibration" id="pwaVibration" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaVibration'), 'on'); ?>>
                        </label>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                	<h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Preloader', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('Preloader feature adds a nice page loader with a bouncing site icon on your website\'s background color. Loader appears at the start of page load and disappears after it is fully loaded.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable preloader for your web app.', $this->textDomain); ?></p>
                        <label for="pwaPreloader" class="daftplugAdminField_label -flex4"><?php esc_html_e('Preloader', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaPreloader" id="pwaPreloader" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaPreloader'), 'on'); ?>>
                        </label>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                	<h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Toast Messages', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('Toast Messages provide simple feedback about an operation in a small popup. It only fills the amount of space required for the message and the current activity remains visible and interactive. Toasts automatically disappear after a timeout.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable toast messages for your web app.', $this->textDomain); ?></p>
                        <label for="pwaToastMessages" class="daftplugAdminField_label -flex4"><?php esc_html_e('Toast Messages', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaToastMessages" id="pwaToastMessages" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaToastMessages'), 'on'); ?>>
                        </label>
                    </div>
                </fieldset>
                <div class="daftplugAdminSettings_submit">
                    <button type="submit" class="daftplugAdminButton -submit" data-submit="<?php esc_html_e('Save Settings', $this->textDomain); ?>" data-waiting="<?php esc_html_e('Waiting', $this->textDomain); ?>" data-submitted="<?php esc_html_e('Settings Saved', $this->textDomain); ?>" data-failed="<?php esc_html_e('Saving Failed', $this->textDomain); ?>"></button>
                </div>
            </form>
        </div>
    </div>
</div>