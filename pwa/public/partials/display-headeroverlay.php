<?php

if (!defined('ABSPATH')) exit;

$appName = daftplugInstantify::getSetting('pwaName');
$backgroundColor = daftplugInstantify::getSetting('pwaOverlaysBackgroundColor');
$textColor = daftplugInstantify::getSetting('pwaOverlaysTextColor');
$text = esc_html__('Get our web app. It won\'t take up space on your phone.', $this->textDomain);
$buttonText = esc_html__('Install', $this->textDomain);

?>

<div class="daftplugPublicHeaderOverlay" style="background: <?php echo $backgroundColor; ?>; color: <?php echo $textColor; ?>;">
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="daftplugPublicHeaderOverlay_dismiss" style="stroke: <?php echo $textColor; ?>;">
        <g stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </g>
    </svg>
    <div class="daftplugPublicHeaderOverlay_message">
        <div class="daftplugPublicHeaderOverlay_appname"><?php echo $appName; ?></div>
        <div class="daftplugPublicHeaderOverlay_text"><?php echo $text; ?></div>
    </div>
    <div class="daftplugPublicHeaderOverlay_button" style="background: <?php echo $textColor; ?>; color: <?php echo $backgroundColor; ?>;">
        <?php echo $buttonText; ?>
    </div>
</div> 