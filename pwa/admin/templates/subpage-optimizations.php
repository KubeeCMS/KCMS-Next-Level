<?php

if (!defined('ABSPATH')) exit;

?>
<div class="daftplugAdminPage_subpage -optimizations -flex12" data-supage="optimizations">
	<div class="daftplugAdminPage_content -flex8">
        <div class="daftplugAdminSettings -flexAuto">
            <form name="daftplugAdminSettings_form" class="daftplugAdminSettings_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>" spellcheck="false" autocomplete="off">
                <fieldset class="daftplugAdminFieldset">
                	<h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Cache And Minify', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('Caching And minifying assets help your website load faster by minifying and storing your CSS and JS files in cache to serve assets from there when necessary.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable caching and minifying your assets.', $this->textDomain); ?></p>
                        <label for="pwaCacheMinify" class="daftplugAdminField_label -flex4"><?php esc_html_e('Cache And Minify', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaCacheMinify" id="pwaCacheMinify" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaCacheMinify'), 'on'); ?>>
                        </label>
                        <div class="daftplugAdminCacheInfo">
                            <span class="daftplugAdminCacheInfo_meta"><?php echo $this->daftplugInstantifyPwaAdminOptimizations->fileCount.' Files, '.$this->daftplugInstantifyPwaAdminOptimizations->fileSize; ?>
                            </span>
                            <span class="daftplugAdminCacheInfo_button <?php if ($this->daftplugInstantifyPwaAdminOptimizations->fileCount == 0) {echo '-disabled';} ?>"><?php esc_html_e('Clear Cache', $this->textDomain); ?></span>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                	<h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Delivery Optimization', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('Delivery optimization feature optimizes your CSS and JS files delivery by loading them asynchronously in the correct order for better performance.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable optimization for your CSS files delivery.', $this->textDomain); ?></p>
                        <label for="pwaCssDeliveryOptimization" class="daftplugAdminField_label -flex4"><?php esc_html_e('Optimize CSS Delivery', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaCssDeliveryOptimization" id="pwaCssDeliveryOptimization" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaCssDeliveryOptimization'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable optimization for your JS files delivery.', $this->textDomain); ?></p>
                        <label for="pwaJsDeliveryOptimization" class="daftplugAdminField_label -flex4"><?php esc_html_e('Optimize JS Delivery', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaJsDeliveryOptimization" id="pwaJsDeliveryOptimization" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaJsDeliveryOptimization'), 'on'); ?>>
                        </label>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                	<h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Compression and Caching Headers', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('Compression and caching headers feature allows your web server to compress static files and set Cache-Control Headers to your .htaccess for better assets serving.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable static files compression.', $this->textDomain); ?></p>
                        <label for="pwaCompression" class="daftplugAdminField_label -flex4"><?php esc_html_e('Compress Static Files', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaCompression" id="pwaCompression" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaCompression'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable caching headers in your .htaccess.', $this->textDomain); ?></p>
                        <label for="pwaCachingHeaders" class="daftplugAdminField_label -flex4"><?php esc_html_e('Set Caching Headers', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaCachingHeaders" id="pwaCachingHeaders" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaCachingHeaders'), 'on'); ?>>
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