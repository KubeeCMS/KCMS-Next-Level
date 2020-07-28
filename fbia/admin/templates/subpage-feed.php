<?php

if (!defined('ABSPATH')) exit;

?>
<div class="daftplugAdminPage_subpage -feed -flex12" data-subpage="feed">
	<div class="daftplugAdminPage_content -flex8">
        <div class="daftplugAdminSettings -flexAuto">
            <form name="daftplugAdminSettings_form" class="daftplugAdminSettings_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>" spellcheck="false" autocomplete="off">
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Feed Preferences', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('From this section you are allowed to set FBIA feed preferences. You can set custom copyright details, enable RTL publishing and define number of latest articles in your feed.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enter the standard copyright details for your instant articles.', $this->textDomain); ?></p>
                        <label for="fbiaCopyright" class="daftplugAdminField_label -flex4"><?php esc_html_e('Copyright', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputTextarea -flexAuto">
                            <textarea name="fbiaCopyright" id="fbiaCopyright" class="daftplugAdminInputTextarea_field" data-placeholder="<?php esc_html_e('Copyright', $this->textDomain); ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" rows="4"><?php echo daftplugInstantify::getSetting('fbiaCopyright'); ?></textarea>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable Right to Left Publishing for Arabic, Hebrew, Persian and other languages.', $this->textDomain); ?></p>
                        <label for="fbiaRtlPublishing" class="daftplugAdminField_label -flex4"><?php esc_html_e('RTL Publishing', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="fbiaRtlPublishing" id="fbiaRtlPublishing" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('fbiaRtlPublishing'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Choose the number of latest articles, that will be rendered on your FBIA feed.', $this->textDomain); ?></p>
                        <label for="fbiaArticleQuantity" class="daftplugAdminField_label -flex4"><?php esc_html_e('Article Quantity', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputRange -flexAuto">
                            <input type="range" name="fbiaArticleQuantity" id="fbiaArticleQuantity" class="daftplugAdminInputRange_field" value="<?php echo daftplugInstantify::getSetting('fbiaArticleQuantity'); ?>" min="1" step="1" max="100" data-placeholder="<?php esc_html_e('Article Quantity', $this->textDomain); ?>" required>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Article Interaction', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('Article interaction allow users to like and comment the whole single article in your Facebook Instant Articles feed.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable Article Interaction.', $this->textDomain); ?></p>
                        <label for="fbiaArticleInteraction" class="daftplugAdminField_label -flex4"><?php esc_html_e('Article Interaction', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="fbiaArticleInteraction" id="fbiaArticleInteraction" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('fbiaArticleInteraction'), 'on'); ?>>
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