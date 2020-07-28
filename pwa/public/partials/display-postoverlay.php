<?php

if (!defined('ABSPATH')) exit;

if (daftplugInstantify::getSetting('pwaDynamicManifest')  == 'on' && is_singular()) {
    $appName = get_the_title();
} else {
    $appName = daftplugInstantify::getSetting('pwaName');
}

$appIcon = (has_site_icon()) ? get_site_icon_url(150) : wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaIcon'), array(150, 150))[0];
$backgroundColor = daftplugInstantify::getSetting('pwaOverlaysBackgroundColor');
$textColor = daftplugInstantify::getSetting('pwaOverlaysTextColor');
$header = esc_html__('See this post in...', $this->textDomain);
$open = esc_html__('Open', $this->textDomain);
$continue = esc_html__('Continue', $this->textDomain);
$userAgent = $_SERVER["HTTP_USER_AGENT"]; 

switch (true) {
    case strpos($userAgent, 'Chrome'):
        $browserName = 'Chrome';
        $browserIcon = plugins_url('pwa/public/assets/img/icon-chrome.png', $this->pluginFile);
    break;
    case strpos($userAgent, 'Firefox'):
        $browserName = 'Firefox';
        $browserIcon = plugins_url('pwa/public/assets/img/icon-firefox.png', $this->pluginFile);
    break;
    case strpos($userAgent, 'Safari'):
        $browserName = 'Safari';
        $browserIcon = plugins_url('pwa/public/assets/img/icon-safari.png', $this->pluginFile);
    break;
}

?>

<div class="daftplugPublicPostOverlay">
    <div class="daftplugPublicPostOverlay_background"></div>
    <div class="daftplugPublicPostOverlay_content">
        <div class="daftplugPublicPostOverlay_header"><?php echo $header; ?></div>
        <div class="daftplugPublicPostOverlay_actions">
            <div class="daftplugPublicPostOverlayAction -app">
                <img class="daftplugPublicPostOverlayAction_logo" src="<?php echo $appIcon; ?>" alt="<?php echo $appName; ?>">
                <span class="daftplugPublicPostOverlayAction_name"><?php echo $appName; ?></span>
                <button class="daftplugPublicPostOverlayAction_button -install" style="background: <?php echo $backgroundColor; ?>; color: <?php echo $textColor; ?>;"><?php echo $open; ?></button>
            </div>
            <div class="daftplugPublicPostOverlayAction -browser">
                <img class="daftplugPublicPostOverlayAction_logo" src="<?php echo $browserIcon; ?>" alt="<?php echo $browserName; ?>">
                <span class="daftplugPublicPostOverlayAction_name"><?php echo $browserName; ?></span>
                <button class="daftplugPublicPostOverlayAction_button -dismiss"><?php echo $continue; ?></button>
            </div>
        </div>
    </div>
</div>