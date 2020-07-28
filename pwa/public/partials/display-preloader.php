<?php

if (!defined('ABSPATH')) exit;

$appIcon = (has_site_icon()) ? get_site_icon_url(150) : wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaIcon'), array(150, 150))[0];
$bgColor = daftplugInstantify::getSetting('pwaBackgroundColor');

?>

<div class="daftplugPublicPreloader" style="background-color: <?php echo $bgColor; ?>">
	<div class="daftplugPublicPreloader_icon" style="background-image: url(<?php echo $appIcon; ?>);"></div>
</div>