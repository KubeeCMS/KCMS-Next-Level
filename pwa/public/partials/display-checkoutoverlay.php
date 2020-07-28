<?php

if (!defined('ABSPATH')) exit;

$appIcon = (has_site_icon()) ? get_site_icon_url(150) : wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaIcon'), array(150, 150))[0];
$message = esc_html__('Keep track of your orders. Our web app is fast, small and works offline.', $this->textDomain);
$backgroundColor = daftplugInstantify::getSetting('pwaOverlaysBackgroundColor');
$textColor = daftplugInstantify::getSetting('pwaOverlaysTextColor');
$notNow = esc_html__('Not now', $this->textDomain);
$install = esc_html__('Install', $this->textDomain);

?>

<div class="daftplugPublic" data-daftplug-plugin="<?php echo $this->optionName; ?>">
    <div class="daftplugPublicCheckoutOverlay" style="background: <?php echo $backgroundColor; ?>; color: <?php echo $textColor; ?>;">
        <div class="daftplugPublicCheckoutOverlay_content">
            <img class="daftplugPublicCheckoutOverlay_icon" src="<?php echo $appIcon; ?>">
            <span class="daftplugPublicCheckoutOverlay_msg"><?php echo $message; ?></span>
        </div>
        <div class="daftplugPublicCheckoutOverlay_buttons">
            <div class="daftplugPublicCheckoutOverlay_dismiss" style="color: <?php echo $textColor.'99'; ?>;"><?php echo $notNow; ?></div>
            <div class="daftplugPublicCheckoutOverlay_install" style="background: <?php echo $textColor; ?>; color: <?php echo $backgroundColor; ?>;"><?php echo $install; ?></div>
        </div>
    </div>
</div>