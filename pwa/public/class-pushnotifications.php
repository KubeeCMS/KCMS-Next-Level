<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyPwaPublicPushnotifications')) {
    class daftplugInstantifyPwaPublicPushnotifications {
        public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;

        public $settings;
        public $vapidKeys;
        public $subscribedDevices;

        public $daftplugInstantifyPwaPublic;

        public function __construct($config, $daftplugInstantifyPwaPublic) {
            $this->name = $config['name'];
            $this->description = $config['description'];
            $this->slug = $config['slug'];
            $this->version = $config['version'];
            $this->textDomain = $config['text_domain'];
            $this->optionName = $config['option_name'];

            $this->pluginFile = $config['plugin_file'];
            $this->pluginBasename = $config['plugin_basename'];

            $this->settings = $config['settings'];
            $this->vapidKeys = get_option("{$this->optionName}_vapid_keys", true);            
            $this->subscribedDevices = get_option("{$this->optionName}_subscribed_devices", true);

            $this->daftplugInstantifyPwaPublic = $daftplugInstantifyPwaPublic;

            add_filter("{$this->optionName}_pwa_serviceworker", array($this, 'addPushToServiceWorker'));
            add_filter("{$this->optionName}_public_js_vars", array($this, 'addPublicKeyToJsVars'));
            add_action("wp_ajax_{$this->optionName}_handle_subscription", array($this, 'handleSubscription'));
            add_action("wp_ajax_nopriv_{$this->optionName}_handle_subscription", array($this, 'handleSubscription'));

            if (daftplugInstantify::getSetting('pwaSubscribeButton') == 'on') {
                add_filter("{$this->optionName}_public_html", array($this, 'renderSubscribeButton'));
            }
        }

        public function handleSubscription() {
            $subscribedDevices = $this->subscribedDevices;
            $endpoint = $_REQUEST['endpoint'];
            $userKey = $_REQUEST['userKey'];
            $userAuth = $_REQUEST['userAuth'];
            $deviceInfo = $_REQUEST['deviceInfo'];
            $date = date('j M Y');
            $country = json_decode(file_get_contents('http://www.geoplugin.net/json.gp?ip='.$_SERVER['REMOTE_ADDR']), true);
            $method = $_REQUEST['method'];

            if ($method == 'add') {
                $subscribedDevices[$endpoint] = array(
                    'endpoint' => $endpoint,
                    'userKey' => $userKey,
                    'userAuth' => $userAuth,
                    'deviceInfo' => $deviceInfo,
                    'date' => $date,
                    'country' => @$country['geoplugin_countryName'],
                );
            } elseif ($method == 'remove') {
                unset($subscribedDevices[$endpoint]);
            }

            $handled = update_option("{$this->optionName}_subscribed_devices", $subscribedDevices);

            if ($handled) {
                wp_die('1');
            } else {
                wp_die('0');
            }
        }

        public function addPushToServiceWorker($serviceWorker) {
            $serviceWorker .= file_get_contents(plugins_url('pwa/public/assets/js/script-push.js', $this->pluginFile));

            return $serviceWorker;
        }

        public function addPublicKeyToJsVars($vars) {
            $vars['pwaPublicKey'] = $this->vapidKeys['pwaPublicKey'];

            return $vars;
        }

        public function renderSubscribeButton() {
            if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
                return;
            }
            
            include_once($this->daftplugInstantifyPwaPublic->partials['subscribeButton']);
        }
    }
}