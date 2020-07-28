<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyPwaPublicAddtohomescreen')) {
    class daftplugInstantifyPwaPublicAddtohomescreen {
        public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;
        public $pluginUploadDir;
        public $pluginUploadUrl;

        public $settings;

        public $daftplugInstantifyPwaPublic;

        public $manifest;

        public function __construct($config, $daftplugInstantifyPwaPublic) {
            $this->name = $config['name'];
            $this->description = $config['description'];
            $this->slug = $config['slug'];
            $this->version = $config['version'];
            $this->textDomain = $config['text_domain'];
            $this->optionName = $config['option_name'];

            $this->pluginFile = $config['plugin_file'];
            $this->pluginBasename = $config['plugin_basename'];
            $this->pluginUploadDir = $config['plugin_upload_dir'];
            $this->pluginUploadUrl = wp_upload_dir()['baseurl'] . '/' . trailingslashit($config['slug']);

            $this->settings = $config['settings'];

            $this->daftplugInstantifyPwaPublic = $daftplugInstantifyPwaPublic;

            $this->manifest = array();

            add_action('parse_request', array($this, 'generateManifest'));
            add_action('wp_head', array($this, 'renderManifestMetaTagsInHeader'), 1);
            add_action("wp_ajax_{$this->optionName}_save_installation_analytics", array($this, 'saveInstallationAnalytics'));
            add_action("wp_ajax_nopriv_{$this->optionName}_save_installation_analytics", array($this, 'saveInstallationAnalytics'));
            add_filter('query_vars', array($this, 'addManifestQueryVar'));
            add_shortcode('pwa-install-button', array($this, 'renderInstallationButton'));

            if (wp_is_mobile()) {
                if (daftplugInstantify::getSetting('pwaOrientation') !== 'any') {
                    add_filter("{$this->optionName}_public_html", array($this, 'renderRotateNotice'));
                }
                if (daftplugInstantify::getSetting('pwaOverlays')  == 'on' || daftplugInstantify::getSetting('pwaInstallButton') == 'on') {
                    add_filter("{$this->optionName}_public_html", array($this, 'renderFullscreenOverlays'));
                    if (daftplugInstantify::getSetting('pwaOverlays')  == 'on') {
                        if (in_array('menu', (array)daftplugInstantify::getSetting('pwaOverlaysTypes'))) {
                            add_filter('wp_nav_menu_items', array($this, 'renderMenuOverlay'), 10, 2);
                        }
                        if (in_array('header', (array)daftplugInstantify::getSetting('pwaOverlaysTypes'))) {
                            add_filter("{$this->optionName}_public_html", array($this, 'renderHeaderOverlay'));
                        }
                        if (in_array('checkout', (array)daftplugInstantify::getSetting('pwaOverlaysTypes'))) {
                            add_action('woocommerce_review_order_after_payment', array($this, 'renderCheckoutOverlay'));
                        }
                        if (in_array('post', (array)daftplugInstantify::getSetting('pwaOverlaysTypes'))) {
                            add_action("{$this->optionName}_public_html", array($this, 'renderPostOverlay'));
                        }
                    }
                }
            }
        }
       
        public function generateManifest() {
            if (isset($GLOBALS['wp']->query_vars['daftplugInstantifyPwaManifest'])) {
                if ($GLOBALS['wp']->query_vars['daftplugInstantifyPwaManifest'] == 1) {
                    header('Content-Type: text/javascript; charset=utf-8');
                    $this->manifest['name'] = (!empty($_GET['name']) ? $_GET['name'] : daftplugInstantify::getSetting('pwaName'));

                    if (strlen(daftplugInstantify::getSetting('pwaShortName')) > 12) {
                        $manifestShortName = substr(daftplugInstantify::getSetting('pwaShortName'), 0, 9).'...';
                    } else {
                        $manifestShortName = daftplugInstantify::getSetting('pwaShortName');
                    }

                    $this->manifest['short_name'] = (!empty($_GET['short_name']) ? $_GET['short_name'] : $manifestShortName);
                    $this->manifest['start_url'] = (!empty($_GET['start_url']) ? $_GET['start_url'] : trailingslashit(daftplugInstantify::getSetting('pwaStartPage'))).'?utm_source=pwa-homescreen';
                    $this->manifest['description'] = (!empty($_GET['description']) ? $_GET['description'] : daftplugInstantify::getSetting('pwaDescription'));
                    $this->manifest['display'] = daftplugInstantify::getSetting('pwaDisplayMode');

                    $homeUrlParts = parse_url(trailingslashit(home_url('/', 'https')));
                    $scope = '/';

                    if (array_key_exists('path', $homeUrlParts)) {
                        $scope = $homeUrlParts['path'];
                    }

                    $this->manifest['scope'] = $scope;
                    $this->manifest['orientation'] = daftplugInstantify::getSetting('pwaOrientation');
                    $this->manifest['dir'] = is_rtl() ? 'rtl' : 'ltr';
                    $this->manifest['background_color'] = daftplugInstantify::getSetting('pwaBackgroundColor');
                    $this->manifest['theme_color'] = daftplugInstantify::getSetting('pwaThemeColor');

                    if (get_bloginfo('language')) {
                        $this->manifest['lang'] = get_bloginfo('language');
                    }

                    $icon = (has_site_icon()) ? get_option('site_icon') : daftplugInstantify::getSetting('pwaIcon');
                    $iconWidth = wp_get_attachment_image_src($icon, 'full')[1];
                    $iconSizes = array(180, 192, 512);

                    if (daftplugInstantify::getSetting('pwaIconMaskable') == 'on') {
                        $iconPurpose = 'maskable any';
                    } else {
                        $iconPurpose = 'any';
                    }

                    if (wp_attachment_is_image(intval($icon))) {
                        foreach ($iconSizes as $iconSize) {
                            if ($iconWidth < $iconSize) {
                                continue;
                            }

                            $newIcon = daftplugInstantifyPwa::resizeImage($icon, $iconSize, $iconSize, true, 'png');

                            if ($newIcon[1] != $iconSize) {
                                continue;
                            }

                            $this->manifest['icons'][] = array(
                                'src' => $newIcon[0],
                                'sizes' => "{$iconSize}x{$iconSize}",
                                'type' => 'image/png',
                                'purpose' => $iconPurpose,
                            );
                        }
                    }

                    $this->manifest['screenshots'][] = array(
                        'src' => 'http://s.wordpress.com/mshots/v1/'.urlencode(trailingslashit(daftplugInstantify::getSetting('pwaStartPage'))).'?w=1280&h=800',
                        'sizes' => '1280x800',
                        'type' => 'image/png',
                    );

                    $appShortcutOptionNames = array('pwaAppShortcut1', 'pwaAppShortcut2', 'pwaAppShortcut3', 'pwaAppShortcut4');
                    foreach ($appShortcutOptionNames as $appShortcutOptionName) {
                        $icon = daftplugInstantifyPwa::resizeImage(daftplugInstantify::getSetting($appShortcutOptionName.'Icon'), '192', '192', true, 'png');
                        if (daftplugInstantify::getSetting($appShortcutOptionName) == 'on') {
                            $this->manifest['shortcuts'][] = array(
                                'name' => daftplugInstantify::getSetting($appShortcutOptionName.'Name'),
                                'short_name' => substr(daftplugInstantify::getSetting($appShortcutOptionName.'Name'), 0, 12),
                                'url' => daftplugInstantify::getSetting($appShortcutOptionName.'Url'),
                            );

                            if (wp_attachment_is_image(intval(daftplugInstantify::getSetting($appShortcutOptionName.'Icon')))) {
                                $this->manifest['shortcuts'][0]['icons'][] = array(
                                    'src' => $icon[0],
                                    'sizes' => '192x192',
                                    'type' => 'image/png',
                                );
                            }
                        }
                    }

                    $this->manifest = apply_filters("{$this->optionName}_pwa_manifest", $this->manifest);
                    $this->manifest = json_encode($this->manifest, JSON_UNESCAPED_SLASHES);

                    echo $this->manifest;
                    exit;
                }
            }
        }

        public function addManifestQueryVar($queryVars) {
            $queryVars[] = 'daftplugInstantifyPwaManifest';

            return $queryVars;
        }

        public function renderManifestMetaTagsInHeader() {
            include_once($this->daftplugInstantifyPwaPublic->partials['manifestMetaTags']);
        }

        public function renderRotateNotice() {
            if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
                return;
            }
            
            include_once($this->daftplugInstantifyPwaPublic->partials['rotateNotice']);
        }

        public function renderFullscreenOverlays() {
            if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
                return;
            }
            
            include_once($this->daftplugInstantifyPwaPublic->partials['fullscreenOverlays']);
        }

        public function renderMenuOverlay($items, $args) {
            if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
                return;
            }

            $appIcon = (has_site_icon()) ? get_site_icon_url(150) : wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaIcon'), array(150, 150))[0];
            $message = esc_html__('Find what you need faster with our web app!', $this->textDomain);
            $backgroundColor = daftplugInstantify::getSetting('pwaOverlaysBackgroundColor');
            $textColor = daftplugInstantify::getSetting('pwaOverlaysTextColor');
            $notNow = esc_html__('Not now', $this->textDomain);
            $install = esc_html__('Install', $this->textDomain);

            $items.= '<div class="daftplugPublic" data-daftplug-plugin="'.$this->optionName.'">
                          <div class="daftplugPublicMenuOverlay" style="background: '.$backgroundColor.'; color: '.$textColor.';">
                              <div class="daftplugPublicMenuOverlay_content">
                                  <img class="daftplugPublicMenuOverlay_icon" src="'.$appIcon.'">
                                  <span class="daftplugPublicMenuOverlay_msg">'.$message.'</span>
                              </div>
                              <div class="daftplugPublicMenuOverlay_buttons">
                                  <div class="daftplugPublicMenuOverlay_dismiss" style="color: '.$textColor.'99;">'.$notNow.'</div>
                                  <div class="daftplugPublicMenuOverlay_install" style="background: '.$textColor.'; color: '.$backgroundColor.';">'.$install.'</div>
                              </div>
                          </div>
                      </div>';

            return $items;
        }

        public function renderHeaderOverlay() {
            if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
                return;
            }
            
            include_once($this->daftplugInstantifyPwaPublic->partials['headerOverlay']);
        }

        public function renderCheckoutOverlay() {
            if (function_exists('is_amp_endpoint') && is_amp_endpoint() || !daftplugInstantify::isWooCommerceActive()) {
                return;
            }
            
            include_once($this->daftplugInstantifyPwaPublic->partials['checkoutOverlay']);
        }

        public function renderPostOverlay() {
            if (function_exists('is_amp_endpoint') && is_amp_endpoint() || !is_single()) {
                return;
            }
            
            include_once($this->daftplugInstantifyPwaPublic->partials['postOverlay']);
        }

        public function renderInstallationButton($atts) {
            if (function_exists('is_amp_endpoint') && is_amp_endpoint() || !wp_is_mobile() || daftplugInstantify::getSetting('pwaInstallButton') !== 'on') {
                return;
            }

            $backgroundColor = daftplugInstantify::getSetting('pwaInstallButtonBackgroundColor');
            $textColor = daftplugInstantify::getSetting('pwaInstallButtonTextColor');
            $text = daftplugInstantify::getSetting('pwaInstallButtonText');

            $installButton = '<div class="daftplugPublic" data-daftplug-plugin="'.$this->optionName.'">
                                <button class="daftplugPublicInstallButton" style="background: '.$backgroundColor.'; color: '.$textColor.';">'.$text.'</button>
                              </div>';

            return $installButton;
        }

        public function saveInstallationAnalytics() {
            $data = get_transient("{$this->optionName}_installation_analytics");
            $data[(date('j M Y'))] += 1;
            set_transient("{$this->optionName}_installation_analytics", $data, 604800);

            wp_die();
        }

        public function getManifestUrl($encoded = true) {
            $queryArgs = array('daftplugInstantifyPwaManifest' => 1);

            if (daftplugInstantify::getSetting('pwaDynamicManifest')  == 'on' && is_singular()) {
                $queryArgs['name'] = get_the_title();

                if (strlen(get_the_title()) > 12) {
                    $queryArgs['short_name'] = substr(get_the_title(), 0, 9).'...';
                } else {
                    $queryArgs['short_name'] = get_the_title();
                }

                if (strlen(strip_tags(get_the_excerpt())) > 70) {
                    $queryArgs['description'] = substr(strip_tags(get_the_excerpt()), 0, 70).'...';
                } else {
                    $queryArgs['description'] = strip_tags(get_the_excerpt());
                }

                $queryArgs['start_url'] = daftplugInstantify::getCurrentUrl();
            }

            $manifestUrl = add_query_arg($queryArgs, home_url('/', 'https'));

            if ($encoded) {
                return wp_json_encode($manifestUrl);
            }

            return $manifestUrl;
        }
    }
}