<?php

if (!defined('ABSPATH')) exit;

?>
<div class="daftplugAdminPage_subpage -offlineusage -flex12" data-subpage="offlineusage">
    <div class="daftplugAdminPage_content -flex8">
        <div class="daftplugAdminSettings -flexAuto">
            <form name="daftplugAdminSettings_form" class="daftplugAdminSettings_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>" spellcheck="false" autocomplete="off">
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Offline Usage', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('Offline usage adds offline support and reliable performance on your web app. This allows a visitor to load any previously viewed page while they are offline or on low connectivity network. The plugin also defines a special “Offline Page”, which allows you to customize a message and the experience if the app is offline and the page is not in the offline cache.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the special offline fallback page for your web application. This page will show up your users when they navigate your website without an Internet connection.', $this->textDomain); ?></p>
                        <label for="pwaOfflinePage" class="daftplugAdminField_label -flex4"><?php esc_html_e('Offline Page', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaOfflinePage" id="pwaOfflinePage" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Offline Page', $this->textDomain); ?>" autocomplete="off" required>
                                <?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(daftplugInstantify::getSetting('pwaOfflinePage'), get_page_link($page->ID)) ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the pages that should be available for offline usage. This pages will be accessible when the user navigates your website without an internet connection.', $this->textDomain); ?></p>
                        <label for="pwaOfflineContent" class="daftplugAdminField_label -flex4"><?php esc_html_e('Offline Content', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select multiple name="pwaOfflineContent" id="pwaOfflineContent" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Offline Content', $this->textDomain); ?>" autocomplete="off" required>
                                <?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(true, in_array(get_page_link($page->ID), (array)daftplugInstantify::getSetting('pwaOfflineContent'))); ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable live reconnecting notification for your users when they go offline or their connection interrupts on your website.', $this->textDomain); ?></p>
                        <label for="pwaOfflineNotification" class="daftplugAdminField_label -flex4"><?php esc_html_e('Offline Notification', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaOfflineNotification" id="pwaOfflineNotification" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaOfflineNotification'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable offline form submission. If enabled, the forms data will be saved, then your website will automatically sync it once the user is online and submit pending forms in background using AJAX.', $this->textDomain); ?></p>
                        <label for="pwaOfflineForms" class="daftplugAdminField_label -flex4"><?php esc_html_e('Offline Forms', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaOfflineForms" id="pwaOfflineForms" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaOfflineForms'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable offline Google Analytics support. If enabled, Instantify will store the data and sync it as soon as the user is online again.', $this->textDomain); ?></p>
                        <label for="pwaOfflineGoogleAnalytics" class="daftplugAdminField_label -flex4"><?php esc_html_e('Offline Google Analytics', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaOfflineGoogleAnalytics" id="pwaOfflineGoogleAnalytics" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaOfflineGoogleAnalytics'), 'on'); ?>>
                        </label>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Caching Strategies', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('All network requests are cached by Instantify, so that your website can serve content from cache if available and display requested content as fast as possible. Here you are able to manually change the caching strategy for some request types. Available caching strategies (hover for description):', $this->textDomain); ?>
                        <strong class="daftplugAdminCacheStrategy" data-tooltip="<?php esc_html_e('This strategy will use a cached response for a request if it is available and update the cache in the background with a response form the network. (If it’s not cached it will wait for the network response and use that). This is a fairly safe strategy as it means users are regularly updating their cache. The downside of this strategy is that it’s always requesting an asset from the network, using up the user’s bandwidth.', $this->textDomain); ?>"><?php esc_html_e('Stale While Revalidate', $this->textDomain); ?></strong>
                        <strong class="daftplugAdminCacheStrategy" data-tooltip="<?php esc_html_e('This will try and get a request from the network first. If it receives a response, it’ll pass that to the browser and also save it to a cache. If the network request fails, the last cached response will be used.', $this->textDomain); ?>"><?php esc_html_e('Network First', $this->textDomain); ?></strong>
                        <strong class="daftplugAdminCacheStrategy" data-tooltip="<?php esc_html_e('This strategy will check the cache for a response first and use that if one is available. If the request isn’t in the cache, the network will be used and any valid response will be added to the cache before being passed to the browser.', $this->textDomain); ?>"><?php esc_html_e('Cache First', $this->textDomain); ?></strong>
                        <strong class="daftplugAdminCacheStrategy" data-tooltip="<?php esc_html_e('This strategy won\'t chache anything. The network will be used and the response will be passed directly to the browser (That\'s how the browser would handle the request without the Instantify).', $this->textDomain); ?>"><?php esc_html_e('Network Only', $this->textDomain); ?></strong>
                    </p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the default caching strategy for your web application. This strategy will be used on all content except assets, fonts and images.', $this->textDomain); ?></p>
                        <label for="pwaOfflineDefaultStrategy" class="daftplugAdminField_label -flex4"><?php esc_html_e('Default Strategy', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaOfflineDefaultStrategy" id="pwaOfflineDefaultStrategy" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Default Strategy', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="staleWhileRevalidate" <?php selected(daftplugInstantify::getSetting('pwaOfflineDefaultStrategy'), 'staleWhileRevalidate') ?>><?php esc_html_e('Stale While Revalidate', $this->textDomain); ?></option>
                                <option value="networkFirst" <?php selected(daftplugInstantify::getSetting('pwaOfflineDefaultStrategy'), 'networkFirst') ?>><?php esc_html_e('Network First', $this->textDomain); ?></option>
                                <option value="cacheFirst" <?php selected(daftplugInstantify::getSetting('pwaOfflineDefaultStrategy'), 'cacheFirst') ?>><?php esc_html_e('Cache First', $this->textDomain); ?></option>
                                <option value="networkOnly" <?php selected(daftplugInstantify::getSetting('pwaOfflineDefaultStrategy'), 'networkOnly') ?>><?php esc_html_e('Network Only', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the caching strategy for your assets (CSS and JS files). We recommend you to set it on Stale While Revalidate to improve performance and always update users with latest version of your website in their cache.', $this->textDomain); ?></p>
                        <label for="pwaOfflineAssetsStrategy" class="daftplugAdminField_label -flex4"><?php esc_html_e('Assets Strategy', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaOfflineAssetsStrategy" id="pwaOfflineAssetsStrategy" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Assets Strategy', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="staleWhileRevalidate" <?php selected(daftplugInstantify::getSetting('pwaOfflineAssetsStrategy'), 'staleWhileRevalidate') ?>><?php esc_html_e('Stale While Revalidate', $this->textDomain); ?></option>
                                <option value="networkFirst" <?php selected(daftplugInstantify::getSetting('pwaOfflineAssetsStrategy'), 'networkFirst') ?>><?php esc_html_e('Network First', $this->textDomain); ?></option>
                                <option value="cacheFirst" <?php selected(daftplugInstantify::getSetting('pwaOfflineAssetsStrategy'), 'cacheFirst') ?>><?php esc_html_e('Cache First', $this->textDomain); ?></option>
                                <option value="networkOnly" <?php selected(daftplugInstantify::getSetting('pwaOfflineAssetsStrategy'), 'networkOnly') ?>><?php esc_html_e('Network Only', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the caching strategy for your fonts (WOFF, WOFF2, EOT, TTF and SVG files). We recommend you to set it on Cache First to load fonts faster.', $this->textDomain); ?></p>
                        <label for="pwaOfflineFontsStrategy" class="daftplugAdminField_label -flex4"><?php esc_html_e('Fonts Strategy', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaOfflineFontsStrategy" id="pwaOfflineFontsStrategy" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Fonts Strategy', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="staleWhileRevalidate" <?php selected(daftplugInstantify::getSetting('pwaOfflineFontsStrategy'), 'staleWhileRevalidate') ?>><?php esc_html_e('Stale While Revalidate', $this->textDomain); ?></option>
                                <option value="networkFirst" <?php selected(daftplugInstantify::getSetting('pwaOfflineFontsStrategy'), 'networkFirst') ?>><?php esc_html_e('Network First', $this->textDomain); ?></option>
                                <option value="cacheFirst" <?php selected(daftplugInstantify::getSetting('pwaOfflineFontsStrategy'), 'cacheFirst') ?>><?php esc_html_e('Cache First', $this->textDomain); ?></option>
                                <option value="networkOnly" <?php selected(daftplugInstantify::getSetting('pwaOfflineFontsStrategy'), 'networkOnly') ?>><?php esc_html_e('Network Only', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the caching strategy for your images (PNG, JPG, JPEG, GIF and ICO files). We recommend you to set it on Cache First to load images faster.', $this->textDomain); ?></p>
                        <label for="pwaOfflineImagesStrategy" class="daftplugAdminField_label -flex4"><?php esc_html_e('Images Strategy', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaOfflineImagesStrategy" id="pwaOfflineImagesStrategy" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Images Strategy', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="staleWhileRevalidate" <?php selected(daftplugInstantify::getSetting('pwaOfflineImagesStrategy'), 'staleWhileRevalidate') ?>><?php esc_html_e('Stale While Revalidate', $this->textDomain); ?></option>
                                <option value="networkFirst" <?php selected(daftplugInstantify::getSetting('pwaOfflineImagesStrategy'), 'networkFirst') ?>><?php esc_html_e('Network First', $this->textDomain); ?></option>
                                <option value="cacheFirst" <?php selected(daftplugInstantify::getSetting('pwaOfflineImagesStrategy'), 'cacheFirst') ?>><?php esc_html_e('Cache First', $this->textDomain); ?></option>
                                <option value="networkOnly" <?php selected(daftplugInstantify::getSetting('pwaOfflineImagesStrategy'), 'networkOnly') ?>><?php esc_html_e('Network Only', $this->textDomain); ?></option>
                            </select>
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