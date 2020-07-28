<?php

if (!defined('ABSPATH')) exit;

$bgColor = daftplugInstantify::getSetting('pwaNavigationTabBarBgColor');
$iconColor = daftplugInstantify::getSetting('pwaNavigationTabBarIconColor');
$iconActiveColor = daftplugInstantify::getSetting('pwaNavigationTabBarIconActiveColor');
$homePage = daftplugInstantify::getSetting('pwaNavigationTabBarHome');
$searchPage = daftplugInstantify::getSetting('pwaNavigationTabBarSearch');
$shopPage = daftplugInstantify::getSetting('pwaNavigationTabBarShop');
$cartPage = daftplugInstantify::getSetting('pwaNavigationTabBarCart');
$checkoutPage = daftplugInstantify::getSetting('pwaNavigationTabBarCheckout');
$notificationsPage = daftplugInstantify::getSetting('pwaNavigationTabBarNotifications');
$categoriesPage = daftplugInstantify::getSetting('pwaNavigationTabBarCategories');
$profilePage = daftplugInstantify::getSetting('pwaNavigationTabBarProfile');
$aboutPage = daftplugInstantify::getSetting('pwaNavigationTabBarAbout');
$contactPage = daftplugInstantify::getSetting('pwaNavigationTabBarContact');
$settingsPage = daftplugInstantify::getSetting('pwaNavigationTabBarSettings');
$currentUrl = daftplugInstantify::getCurrentUrl();

?>

<nav class="daftplugPublicNavigationTabBar" style="background-color: <?php echo $bgColor?>;">
	<ul class="daftplugPublicNavigationTabBar_list">
		<?php if ($homePage !== '*disabled*') { ?>
		<li class="daftplugPublicNavigationTabBar_item -home <?php if ($currentUrl == $homePage) {echo '-active';} ?>">
			<a class="daftplugPublicNavigationTabBar_link" href="<?php echo $homePage; ?>">
				<svg class="daftplugPublicNavigationTabBar_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: <?php if ($currentUrl == $homePage) {echo $iconActiveColor;} else {echo $iconColor;} ?>">
					<path d="M20,8h0L14,2.74a3,3,0,0,0-4,0L4,8a3,3,0,0,0-1,2.26V19a3,3,0,0,0,3,3H18a3,3,0,0,0,3-3V10.25A3,3,0,0,0,20,8ZM14,20H10V15a1,1,0,0,1,1-1h2a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H16V15a3,3,0,0,0-3-3H11a3,3,0,0,0-3,3v5H6a1,1,0,0,1-1-1V10.25a1,1,0,0,1,.34-.75l6-5.25a1,1,0,0,1,1.32,0l6,5.25a1,1,0,0,1,.34.75Z"/>
				</svg>
			</a>
		</li>
		<?php } if ($searchPage !== '*disabled*') { ?>
		<li class="daftplugPublicNavigationTabBar_item -search <?php if ($currentUrl == $searchPage) {echo '-active';} ?>">
			<div class="daftplugPublicNavigationTabBar_search">
				<form role="search" method="get" action="<?php echo esc_url(home_url('/', 'https')); ?>" class="daftplugPublicNavigationTabBar_searchForm">
					<input type="search" name="s" class="daftplugPublicNavigationTabBar_searchField" placeholder="<?php esc_html_e('Search for something...'); ?>" value="<?php echo get_search_query(); ?>" required>
				</form>
			</div>
			<a class="daftplugPublicNavigationTabBar_link" href="<?php if ($searchPage == '*directSearch*') {echo 'javascript:void(0)';} else {echo $searchPage;} ?>">
				<svg class="daftplugPublicNavigationTabBar_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: <?php if ($currentUrl == $searchPage || is_search()) {echo $iconActiveColor;} else {echo $iconColor;} ?>">
					<path d="M21.71,20.29,18,16.61A9,9,0,1,0,16.61,18l3.68,3.68a1,1,0,0,0,1.42,0A1,1,0,0,0,21.71,20.29ZM11,18a7,7,0,1,1,7-7A7,7,0,0,1,11,18Z"/>
				</svg>
			</a>
		</li>
		<?php } if (($shopPage !== '*disabled*') && daftplugInstantify::isWooCommerceActive()) { ?>
		<li class="daftplugPublicNavigationTabBar_item -shop <?php if ($currentUrl == $shopPage) {echo '-active';} ?>">
			<a class="daftplugPublicNavigationTabBar_link" href="<?php echo $shopPage; ?>">
				<svg class="daftplugPublicNavigationTabBar_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: <?php if ($currentUrl == $shopPage) {echo $iconActiveColor;} else {echo $iconColor;} ?>">>
					<path d="M19,7H16V6A4,4,0,0,0,8,6V7H5A1,1,0,0,0,4,8V19a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V8A1,1,0,0,0,19,7ZM10,6a2,2,0,0,1,4,0V7H10Zm8,13a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V9H8v1a1,1,0,0,0,2,0V9h4v1a1,1,0,0,0,2,0V9h2Z"/>
				</svg>
			</a>
		</li>
		<?php } if (($cartPage !== '*disabled*') && daftplugInstantify::isWooCommerceActive()) { ?>
		<li class="daftplugPublicNavigationTabBar_item -cart <?php if ($currentUrl == $cartPage) {echo '-active';} ?>">
			<a class="daftplugPublicNavigationTabBar_link" href="<?php echo $cartPage; ?>">
				<svg class="daftplugPublicNavigationTabBar_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: <?php if ($currentUrl == $cartPage) {echo $iconActiveColor;} else {echo $iconColor;} ?>">>
					<path d="M8.5,19A1.5,1.5,0,1,0,10,20.5,1.5,1.5,0,0,0,8.5,19ZM19,16H7a1,1,0,0,1,0-2h8.49121A3.0132,3.0132,0,0,0,18.376,11.82422L19.96143,6.2749A1.00009,1.00009,0,0,0,19,5H6.73907A3.00666,3.00666,0,0,0,3.92139,3H3A1,1,0,0,0,3,5h.92139a1.00459,1.00459,0,0,1,.96142.7251l.15552.54474.00024.00506L6.6792,12.01709A3.00006,3.00006,0,0,0,7,18H19a1,1,0,0,0,0-2ZM17.67432,7l-1.2212,4.27441A1.00458,1.00458,0,0,1,15.49121,12H8.75439l-.25494-.89221L7.32642,7ZM16.5,19A1.5,1.5,0,1,0,18,20.5,1.5,1.5,0,0,0,16.5,19Z"/>
				</svg>
			</a>
		</li>
		<?php } if (($checkoutPage !== '*disabled*') && daftplugInstantify::isWooCommerceActive()) { ?>
		<li class="daftplugPublicNavigationTabBar_item -checkout <?php if ($currentUrl == $checkoutPage) {echo '-active';} ?>">
			<a class="daftplugPublicNavigationTabBar_link" href="<?php echo $checkoutPage; ?>">
				<svg class="daftplugPublicNavigationTabBar_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: <?php if ($currentUrl == $checkoutPage) {echo $iconActiveColor;} else {echo $iconColor;} ?>">>
					<path d="M7,15h3a1,1,0,0,0,0-2H7a1,1,0,0,0,0,2ZM19,5H5A3,3,0,0,0,2,8v9a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V8A3,3,0,0,0,19,5Zm1,12a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V11H20Zm0-8H4V8A1,1,0,0,1,5,7H19a1,1,0,0,1,1,1Z"/>
				</svg>
			</a>
		</li>
		<?php } if ($notificationsPage !== '*disabled*') { ?>
		<li class="daftplugPublicNavigationTabBar_item -notifications <?php if ($currentUrl == $notificationsPage) {echo '-active';} ?>">
			<a class="daftplugPublicNavigationTabBar_link" href="<?php echo $notificationsPage; ?>">
				<svg class="daftplugPublicNavigationTabBar_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: <?php if ($currentUrl == $notificationsPage) {echo $iconActiveColor;} else {echo $iconColor;} ?>">>
					<path d="M18,13.18V10a6,6,0,0,0-5-5.91V3a1,1,0,0,0-2,0V4.09A6,6,0,0,0,6,10v3.18A3,3,0,0,0,4,16v2a1,1,0,0,0,1,1H8.14a4,4,0,0,0,7.72,0H19a1,1,0,0,0,1-1V16A3,3,0,0,0,18,13.18ZM8,10a4,4,0,0,1,8,0v3H8Zm4,10a2,2,0,0,1-1.72-1h3.44A2,2,0,0,1,12,20Zm6-3H6V16a1,1,0,0,1,1-1H17a1,1,0,0,1,1,1Z"/>
				</svg>
			</a>
		</li>
		<?php } if ($categoriesPage !== '*disabled*') { ?>
		<li class="daftplugPublicNavigationTabBar_item -categories <?php if ($currentUrl == $categoriesPage) {echo '-active';} ?>">
			<a class="daftplugPublicNavigationTabBar_link" href="<?php echo $categoriesPage; ?>">
				<svg class="daftplugPublicNavigationTabBar_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: <?php if ($currentUrl == $categoriesPage) {echo $iconActiveColor;} else {echo $iconColor;} ?>">>
					<path d="M2.5,8.86l9,5.2a1,1,0,0,0,1,0l9-5.2A1,1,0,0,0,22,8a1,1,0,0,0-.5-.87l-9-5.19a1,1,0,0,0-1,0l-9,5.19A1,1,0,0,0,2,8,1,1,0,0,0,2.5,8.86ZM12,4l7,4-7,4L5,8Zm8.5,7.17L12,16,3.5,11.13a1,1,0,0,0-1.37.37,1,1,0,0,0,.37,1.36l9,5.2a1,1,0,0,0,1,0l9-5.2a1,1,0,0,0,.37-1.36A1,1,0,0,0,20.5,11.13Zm0,4L12,20,3.5,15.13a1,1,0,0,0-1.37.37,1,1,0,0,0,.37,1.36l9,5.2a1,1,0,0,0,1,0l9-5.2a1,1,0,0,0,.37-1.36A1,1,0,0,0,20.5,15.13Z"/>
				</svg>
			</a>
		</li>
		<?php } if ($profilePage !== '*disabled*') { ?>
		<li class="daftplugPublicNavigationTabBar_item -profile <?php if ($currentUrl == $profilePage) {echo '-active';} ?>">
			<a class="daftplugPublicNavigationTabBar_link" href="<?php echo $profilePage; ?>">
				<svg class="daftplugPublicNavigationTabBar_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: <?php if ($currentUrl == $profilePage) {echo $iconActiveColor;} else {echo $iconColor;} ?>">>
					<path d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1,1,0,0,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1A10,10,0,0,0,15.71,12.71ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"/>
				</svg>
			</a>
		</li>
		<?php } if ($aboutPage !== '*disabled*') { ?>
		<li class="daftplugPublicNavigationTabBar_item -about <?php if ($currentUrl == $aboutPage) {echo '-active';} ?>">
			<a class="daftplugPublicNavigationTabBar_link" href="<?php echo $aboutPage; ?>">
				<svg class="daftplugPublicNavigationTabBar_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: <?php if ($currentUrl == $aboutPage) {echo $iconActiveColor;} else {echo $iconColor;} ?>">>
					<path d="M12,2A10,10,0,1,0,22,12,10.01114,10.01114,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8.00917,8.00917,0,0,1,12,20Zm0-8.5a1,1,0,0,0-1,1v3a1,1,0,0,0,2,0v-3A1,1,0,0,0,12,11.5Zm0-4a1.25,1.25,0,1,0,1.25,1.25A1.25,1.25,0,0,0,12,7.5Z"/>
				</svg>
			</a>
		</li>
		<?php } if ($contactPage !== '*disabled*') { ?>
		<li class="daftplugPublicNavigationTabBar_item -contact <?php if ($currentUrl == $contactPage) {echo '-active';} ?>">
			<a class="daftplugPublicNavigationTabBar_link" href="<?php echo $contactPage; ?>">
				<svg class="daftplugPublicNavigationTabBar_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: <?php if ($currentUrl == $contactPage) {echo $iconActiveColor;} else {echo $iconColor;} ?>">>
					<path d="M19,4H5A3,3,0,0,0,2,7V17a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V7A3,3,0,0,0,19,4Zm-.41,2-5.88,5.88a1,1,0,0,1-1.42,0L5.41,6ZM20,17a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V7.41l5.88,5.88a3,3,0,0,0,4.24,0L20,7.41Z"/>
				</svg>
			</a>
		</li>
		<?php } if ($settingsPage !== '*disabled*') { ?>
		<li class="daftplugPublicNavigationTabBar_item -settings <?php if ($currentUrl == $settingsPage) {echo '-active';} ?>">
			<a class="daftplugPublicNavigationTabBar_link" href="<?php echo $settingsPage; ?>">
				<svg class="daftplugPublicNavigationTabBar_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: <?php if ($currentUrl == $settingsPage) {echo $iconActiveColor;} else {echo $iconColor;} ?>">>
					<path d="M19.9,12.66a1,1,0,0,1,0-1.32L21.18,9.9a1,1,0,0,0,.12-1.17l-2-3.46a1,1,0,0,0-1.07-.48l-1.88.38a1,1,0,0,1-1.15-.66l-.61-1.83A1,1,0,0,0,13.64,2h-4a1,1,0,0,0-1,.68L8.08,4.51a1,1,0,0,1-1.15.66L5,4.79A1,1,0,0,0,4,5.27L2,8.73A1,1,0,0,0,2.1,9.9l1.27,1.44a1,1,0,0,1,0,1.32L2.1,14.1A1,1,0,0,0,2,15.27l2,3.46a1,1,0,0,0,1.07.48l1.88-.38a1,1,0,0,1,1.15.66l.61,1.83a1,1,0,0,0,1,.68h4a1,1,0,0,0,.95-.68l.61-1.83a1,1,0,0,1,1.15-.66l1.88.38a1,1,0,0,0,1.07-.48l2-3.46a1,1,0,0,0-.12-1.17ZM18.41,14l.8.9-1.28,2.22-1.18-.24a3,3,0,0,0-3.45,2L12.92,20H10.36L10,18.86a3,3,0,0,0-3.45-2l-1.18.24L4.07,14.89l.8-.9a3,3,0,0,0,0-4l-.8-.9L5.35,6.89l1.18.24a3,3,0,0,0,3.45-2L10.36,4h2.56l.38,1.14a3,3,0,0,0,3.45,2l1.18-.24,1.28,2.22-.8.9A3,3,0,0,0,18.41,14ZM11.64,8a4,4,0,1,0,4,4A4,4,0,0,0,11.64,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,11.64,14Z"/>
				</svg>
			</a>
		</li>
		<?php } ?>
	</ul>
</nav>