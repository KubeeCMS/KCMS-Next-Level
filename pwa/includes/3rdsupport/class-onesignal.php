<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyPwaOnesignal')) {
    class daftplugInstantifyPwaOnesignal {
    	public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;

        public $settings;
        public $osSettings = array();

        public $daftplugInstantifyPwaPublicAddtohomescreen;
        public $daftplugInstantifyPwaPublicOfflineusage;

		public function __construct($config, $daftplugInstantifyPwaPublicAddtohomescreen, $daftplugInstantifyPwaPublicOfflineusage) {
    		$this->name = $config['name'];
            $this->description = $config['description'];
            $this->slug = $config['slug'];
            $this->version = $config['version'];
            $this->textDomain = $config['text_domain'];
            $this->optionName = $config['option_name'];

            $this->pluginFile = $config['plugin_file'];
            $this->pluginBasename = $config['plugin_basename'];

            $this->settings = $config['settings'];

            $this->daftplugInstantifyPwaPublicAddtohomescreen = $daftplugInstantifyPwaPublicAddtohomescreen;
            $this->daftplugInstantifyPwaPublicOfflineusage = $daftplugInstantifyPwaPublicOfflineusage;

            add_action('plugins_loaded', array($this, 'getOsSettings'));
			add_action('admin_init', array($this, 'setOsSettings'));
			add_action("{$this->optionName}_on_deactivate", array($this, 'removeOsSettings'));
			add_filter("{$this->optionName}_pwa_manifest", array($this, 'addSenderIdToManifest'));
			add_filter("{$this->optionName}_pwa_serviceworker", array($this, 'addSdkToServiceWorker'));
			add_action('wp_loaded', array($this, 'removeHeader'));
			add_action('wp_head', array($this, 'initOnesignal'));
		}

		public function getOsSettings() {
			$this->osSettings = OneSignal::get_onesignal_settings();
		}

		public function setOsSettings() {
			$osSettings = \OneSignal::get_onesignal_settings();
			$osSettings['use_custom_manifest'] = true;
			$osSettings['custom_manifest_url'] = esc_url($this->daftplugInstantifyPwaPublicAddtohomescreen->getManifestUrl(false));
			\OneSignal::save_onesignal_settings($osSettings);
		}

		public function removeOsSettings() {
			$osSettings = \OneSignal::get_onesignal_settings();
			$osSettings['use_custom_manifest'] = false;
			\OneSignal::save_onesignal_settings($osSettings);
		}

		public function removeHeader() {
			remove_action('wp_head', array('OneSignal_Public', 'onesignal_header'));
		}

		public function addSenderIdToManifest($manifest) {
			if (array_key_exists('gcm_sender_id', $this->osSettings) && $this->osSettings['gcm_sender_id']) {
				$gcm_sender_id = $this->osSettings['gcm_sender_id'];
			} else {
				$gcm_sender_id = '482941778795';
			}

			$manifest['gcm_sender_id'] = $gcm_sender_id;
			$manifest['gcm_user_visible_only'] = true;

			return $manifest;
		}

		public function addSdkToServiceWorker($serviceWorker) {
			$serviceWorker = "importScripts('https://cdn.onesignal.com/sdks/OneSignalSDKWorker.js');";

			return $serviceWorker;
		}

		public function initOnesignal() {
			$siteUrlParts = parse_url(trailingslashit(home_url('/', 'https')));
			$path = '/';
			
			if (array_key_exists('path', $siteUrlParts)) {
			    $path = $siteUrlParts['path'];
			}
			?>
			<link rel="manifest" href="<?php echo $this->osSettings['custom_manifest_url'] ?>"/>
			<meta name="onesignal" content="wordpress-plugin"/>
			<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
			<script>
				window.OneSignal = window.OneSignal || [];

				OneSignal.push(function () {
					OneSignal.SERVICE_WORKER_UPDATER_PATH = "<?php echo esc_url($this->daftplugInstantifyPwaPublicOfflineusage->getServiceWorkerUrl(false)); ?>";
					OneSignal.SERVICE_WORKER_PATH = "<?php echo esc_url($this->daftplugInstantifyPwaPublicOfflineusage->getServiceWorkerUrl(false)); ?>";
					OneSignal.SERVICE_WORKER_PARAM = {
						scope: '<?php echo str_replace('/', '\/', $path); ?>'
					};

					<?php
					if ($this->osSettings['default_icon'] != "") {
						echo "OneSignal.setDefaultIcon(\"" . \OneSignalUtils::decode_entities($this->osSettings['default_icon']) . "\");\n";
					}
					if ($this->osSettings['default_url'] != "") {
						echo "OneSignal.setDefaultNotificationUrl(\"" . \OneSignalUtils::decode_entities($this->osSettings['default_url']) . "\");";
					} else {
						echo "OneSignal.setDefaultNotificationUrl(\"" . \OneSignalUtils::decode_entities(trailingslashit(home_url('/', 'https'))) . "\");\n";
					}
					?>

					var oneSignal_options = {};
					window._oneSignalInitOptions = oneSignal_options;

					<?php
					echo "oneSignal_options['wordpress'] = true;\n";
					echo "oneSignal_options['appId'] = '" . $this->osSettings["app_id"] . "';\n";

					if ($this->osSettings["prompt_auto_register"] == "1") {
						echo "oneSignal_options['autoRegister'] = true;\n";
					} else {
						echo "oneSignal_options['autoRegister'] = false;\n";
					}
					if ($this->osSettings["use_http_permission_request"] == "1") {
						echo "oneSignal_options['httpPermissionRequest'] = { };\n";
						echo "oneSignal_options['httpPermissionRequest']['enable'] = true;\n";

						if (array_key_exists('customize_http_permission_request', $this->osSettings) && $this->osSettings["customize_http_permission_request"] == "1" ) {
							echo "oneSignal_options['httpPermissionRequest']['modalTitle'] = \"" . \OneSignalUtils::html_safe($this->osSettings["http_permission_request_modal_title"]) . "\";\n";
							echo "oneSignal_options['httpPermissionRequest']['modalMessage'] = \"" . \OneSignalUtils::html_safe($this->osSettings["http_permission_request_modal_message"]) . "\";\n";
							echo "oneSignal_options['httpPermissionRequest']['modalButtonText'] = \"" . \OneSignalUtils::html_safe($this->osSettings["http_permission_request_modal_button_text"]) . "\";\n";
						}
					}
					if ($this->osSettings["send_welcome_notification"] == "1") {
						echo "oneSignal_options['welcomeNotification'] = { };\n";
						echo "oneSignal_options['welcomeNotification']['title'] = \"" . \OneSignalUtils::html_safe($this->osSettings["welcome_notification_title"]) . "\";\n";
						echo "oneSignal_options['welcomeNotification']['message'] = \"" . \OneSignalUtils::html_safe($this->osSettings["welcome_notification_message"]) . "\";\n";
						if ($this->osSettings["welcome_notification_url"] != "") {
							echo "oneSignal_options['welcomeNotification']['url'] = \"" . \OneSignalUtils::html_safe($this->osSettings["welcome_notification_url"]) . "\";\n";
						}
					} else {
						echo "oneSignal_options['welcomeNotification'] = { };\n";
						echo "oneSignal_options['welcomeNotification']['disable'] = true;\n";
					}
					if ($this->osSettings["subdomain"] != "") {
						echo "oneSignal_options['subdomainName'] = \"" . $this->osSettings["subdomain"] . "\";\n";
					} else {
						echo "oneSignal_options['path'] = \"" . $path . "sdk_files/\";\n";
					}
					if ($this->osSettings["safari_web_id"]) {
						echo "oneSignal_options['safari_web_id'] = \"" . $this->osSettings["safari_web_id"] . "\";\n";
					}
					if ($this->osSettings["persist_notifications"] == "platform-default") {
						echo "oneSignal_options['persistNotification'] = false;\n";
					} else if ($this->osSettings["persist_notifications"] == "yes-all") {
						echo "oneSignal_options['persistNotification'] = true;\n";
					}

					echo "oneSignal_options['promptOptions'] = { };\n";

					if (array_key_exists('prompt_customize_enable', $this->osSettings) && $this->osSettings["prompt_customize_enable"] == "1") {
						if ($this->osSettings["prompt_action_message"] != "") {
							echo "oneSignal_options['promptOptions']['actionMessage'] = '" . \OneSignalUtils::html_safe($this->osSettings["prompt_action_message"] ) . "';\n";
						}
						if ($this->osSettings["prompt_example_notification_title_desktop"] != "") {
							echo "oneSignal_options['promptOptions']['exampleNotificationTitleDesktop'] = '" . \OneSignalUtils::html_safe($this->osSettings["prompt_example_notification_title_desktop"]) . "';\n";
						}
						if ($this->osSettings["prompt_example_notification_message_desktop"] != "") {
							echo "oneSignal_options['promptOptions']['exampleNotificationMessageDesktop'] = '" . \OneSignalUtils::html_safe($this->osSettings["prompt_example_notification_message_desktop"]) . "';\n";
						}
						if ($this->osSettings["prompt_example_notification_title_mobile"] != "") {
							echo "oneSignal_options['promptOptions']['exampleNotificationTitleMobile'] = '" . \OneSignalUtils::html_safe($this->osSettings["prompt_example_notification_title_mobile"]) . "';\n";
						}
						if ($this->osSettings["prompt_example_notification_message_mobile"] != "") {
							echo "oneSignal_options['promptOptions']['exampleNotificationMessageMobile'] = '" . \OneSignalUtils::html_safe($this->osSettings["prompt_example_notification_message_mobile"]) . "';\n";
						}
						if ($this->osSettings["prompt_example_notification_caption"] != "") {
							echo "oneSignal_options['promptOptions']['exampleNotificationCaption'] = '" . \OneSignalUtils::html_safe($this->osSettings["prompt_example_notification_caption"]) . "';\n";
						}
						if ($this->osSettings["prompt_accept_button_text"] != "") {
							echo "oneSignal_options['promptOptions']['acceptButtonText'] = '" . \OneSignalUtils::html_safe($this->osSettings["prompt_accept_button_text"]) . "';\n";
						}
						if ($this->osSettings["prompt_cancel_button_text"] != "") {
							echo "oneSignal_options['promptOptions']['cancelButtonText'] = '" . \OneSignalUtils::html_safe($this->osSettings["prompt_cancel_button_text"]) . "';\n";
						}
						if ($this->osSettings["prompt_site_name"] != "") {
							echo "oneSignal_options['promptOptions']['siteName'] = '" . \OneSignalUtils::html_safe($this->osSettings["prompt_site_name"]) . "';\n";
						}
						if ($this->osSettings["prompt_auto_accept_title"] != "") {
							echo "oneSignal_options['promptOptions']['autoAcceptTitle'] = '" . \OneSignalUtils::html_safe($this->osSettings["prompt_auto_accept_title"]) . "';\n";
						}
					}

					if (array_key_exists('notifyButton_enable', $this->osSettings) && $this->osSettings["notifyButton_enable"] == "1") {
						echo "oneSignal_options['notifyButton'] = { };\n";
						echo "oneSignal_options['notifyButton']['enable'] = true;\n";

						if (array_key_exists('notifyButton_position', $this->osSettings) && $this->osSettings['notifyButton_position'] != "") {
							echo "oneSignal_options['notifyButton']['position'] = '" . $this->osSettings["notifyButton_position"] . "';\n";
						}
						if (array_key_exists('notifyButton_theme', $this->osSettings) && $this->osSettings['notifyButton_theme'] != "") {
							echo "oneSignal_options['notifyButton']['theme'] = '" . $this->osSettings["notifyButton_theme"] . "';\n";
						}
						if (array_key_exists('notifyButton_size', $this->osSettings) && $this->osSettings['notifyButton_size'] != "") {
							echo "oneSignal_options['notifyButton']['size'] = '" . $this->osSettings["notifyButton_size"] . "';\n";
						}
						if ($this->osSettings["notifyButton_prenotify"] == "1") {
							echo "oneSignal_options['notifyButton']['prenotify'] = true;\n";
						} else {
							echo "oneSignal_options['notifyButton']['prenotify'] = false;\n";
						}
						if ($this->osSettings["notifyButton_showAfterSubscribed"] !== true) {
							echo "oneSignal_options['notifyButton']['displayPredicate'] = function() { return OneSignal.isPushNotificationsEnabled().then(function(isPushEnabled) { return !isPushEnabled; }); };\n";
						}
						if ($this->osSettings["use_modal_prompt"] == "1") {
							echo "oneSignal_options['notifyButton']['modalPrompt'] = true;\n";
						}
						if ($this->osSettings["notifyButton_showcredit"] == "1") {
							echo "oneSignal_options['notifyButton']['showCredit'] = true;\n";
						} else {
							echo "oneSignal_options['notifyButton']['showCredit'] = false;\n";
						}

						if (array_key_exists('notifyButton_customize_enable', $this->osSettings) && $this->osSettings["notifyButton_customize_enable"] == "1") {
							echo "oneSignal_options['notifyButton']['text'] = {};\n";
							if ($this->osSettings["notifyButton_message_prenotify"] != "") {
								echo "oneSignal_options['notifyButton']['text']['message.prenotify'] = '" . \OneSignalUtils::html_safe($this->osSettings["notifyButton_message_prenotify"]) . "';\n";
							}
							if ($this->osSettings["notifyButton_tip_state_unsubscribed"] != "") {
								echo "oneSignal_options['notifyButton']['text']['tip.state.unsubscribed'] = '" . \OneSignalUtils::html_safe($this->osSettings["notifyButton_tip_state_unsubscribed"]) . "';\n";
							}
							if ($this->osSettings["notifyButton_tip_state_subscribed"] != "") {
								echo "oneSignal_options['notifyButton']['text']['tip.state.subscribed'] = '" . \OneSignalUtils::html_safe($this->osSettings["notifyButton_tip_state_subscribed"]) . "';\n";
							}
							if ($this->osSettings["notifyButton_tip_state_blocked"] != "") {
								echo "oneSignal_options['notifyButton']['text']['tip.state.blocked'] = '" . \OneSignalUtils::html_safe($this->osSettings["notifyButton_tip_state_blocked"]) . "';\n";
							}
							if ($this->osSettings["notifyButton_message_action_subscribed"] != "") {
								echo "oneSignal_options['notifyButton']['text']['message.action.subscribed'] = '" . \OneSignalUtils::html_safe($this->osSettings["notifyButton_message_action_subscribed"]) . "';\n";
							}
							if ($this->osSettings["notifyButton_message_action_resubscribed"] != "") {
								echo "oneSignal_options['notifyButton']['text']['message.action.resubscribed'] = '" . \OneSignalUtils::html_safe($this->osSettings["notifyButton_message_action_resubscribed"]) . "';\n";
							}
							if ($this->osSettings["notifyButton_message_action_unsubscribed"] != "") {
								echo "oneSignal_options['notifyButton']['text']['message.action.unsubscribed'] = '" . \OneSignalUtils::html_safe($this->osSettings["notifyButton_message_action_unsubscribed"]) . "';\n";
							}
							if ($this->osSettings["notifyButton_dialog_main_title"] != "") {
								echo "oneSignal_options['notifyButton']['text']['dialog.main.title'] = '" . \OneSignalUtils::html_safe($this->osSettings["notifyButton_dialog_main_title"]) . "';\n";
							}
							if ($this->osSettings["notifyButton_dialog_main_button_subscribe"] != "") {
								echo "oneSignal_options['notifyButton']['text']['dialog.main.button.subscribe'] = '" . \OneSignalUtils::html_safe($this->osSettings["notifyButton_dialog_main_button_subscribe"]) . "';\n";
							}
							if ($this->osSettings["notifyButton_dialog_main_button_unsubscribe"] != "") {
								echo "oneSignal_options['notifyButton']['text']['dialog.main.button.unsubscribe'] = '" . \OneSignalUtils::html_safe($this->osSettings["notifyButton_dialog_main_button_unsubscribe"]) . "';\n";
							}
							if ($this->osSettings["notifyButton_dialog_blocked_title"] != "") {
								echo "oneSignal_options['notifyButton']['text']['dialog.blocked.title'] = '" . \OneSignalUtils::html_safe($this->osSettings["notifyButton_dialog_blocked_title"]) . "';\n";
							}
							if ($this->osSettings["notifyButton_dialog_blocked_message"] != "") {
								echo "oneSignal_options['notifyButton']['text']['dialog.blocked.message'] = '" . \OneSignalUtils::html_safe($this->osSettings["notifyButton_dialog_blocked_message"]) . "';\n";
							}
						}

						if (array_key_exists('notifyButton_customize_colors_enable', $this->osSettings) && $this->osSettings["notifyButton_customize_colors_enable"] == "1") {
							echo "oneSignal_options['notifyButton']['colors'] = {};\n";
							if ($this->osSettings["notifyButton_color_background"] != "") {
								echo "oneSignal_options['notifyButton']['colors']['circle.background'] = '" . $this->osSettings["notifyButton_color_background"] . "';\n";
							}
							if ($this->osSettings["notifyButton_color_foreground"] != "") {
								echo "oneSignal_options['notifyButton']['colors']['circle.foreground'] = '" . $this->osSettings["notifyButton_color_foreground"] . "';\n";
							}
							if ($this->osSettings["notifyButton_color_badge_background"] != "") {
								echo "oneSignal_options['notifyButton']['colors']['badge.background'] = '" . $this->osSettings["notifyButton_color_badge_background"] . "';\n";
							}
							if ($this->osSettings["notifyButton_color_badge_foreground"] != "") {
								echo "oneSignal_options['notifyButton']['colors']['badge.foreground'] = '" . $this->osSettings["notifyButton_color_badge_foreground"] . "';\n";
							}
							if ($this->osSettings["notifyButton_color_badge_border"] != "") {
								echo "oneSignal_options['notifyButton']['colors']['badge.bordercolor'] = '" . $this->osSettings["notifyButton_color_badge_border"] . "';\n";
							}
							if ($this->osSettings["notifyButton_color_pulse"] != "") {
								echo "oneSignal_options['notifyButton']['colors']['pulse.color'] = '" . $this->osSettings["notifyButton_color_pulse"] . "';\n";
							}
							if ($this->osSettings["notifyButton_color_popup_button_background"] != "") {
								echo "oneSignal_options['notifyButton']['colors']['dialog.button.background'] = '" . $this->osSettings["notifyButton_color_popup_button_background"] . "';\n";
							}
							if ($this->osSettings["notifyButton_color_popup_button_background_hover"] != "") {
								echo "oneSignal_options['notifyButton']['colors']['dialog.button.background.hovering'] = '" . $this->osSettings["notifyButton_color_popup_button_background_hover"] . "';\n";
							}
							if ($this->osSettings["notifyButton_color_popup_button_background_active"] != "") {
								echo "oneSignal_options['notifyButton']['colors']['dialog.button.background.active'] = '" . $this->osSettings["notifyButton_color_popup_button_background_active"] . "';\n";
							}
							if ($this->osSettings["notifyButton_color_popup_button_color"] != "") {
								echo "oneSignal_options['notifyButton']['colors']['dialog.button.foreground'] = '" . $this->osSettings["notifyButton_color_popup_button_color"] . "';\n";
							}
						}

						if (array_key_exists('notifyButton_customize_offset_enable', $this->osSettings) && $this->osSettings["notifyButton_customize_offset_enable"] == "1") {
							echo "oneSignal_options['notifyButton']['offset'] = {};\n";
							if ($this->osSettings["notifyButton_offset_bottom"] != "") {
								echo "oneSignal_options['notifyButton']['offset']['bottom'] = '" . $this->osSettings["notifyButton_offset_bottom"] . "';\n";
							}
							if ($this->osSettings["notifyButton_offset_left"] != "") {
								echo "oneSignal_options['notifyButton']['offset']['left'] = '" . $this->osSettings["notifyButton_offset_left"] . "';\n";
							}
							if ($this->osSettings["notifyButton_offset_right"] != "") {
								echo "oneSignal_options['notifyButton']['offset']['right'] = '" . $this->osSettings["notifyButton_offset_right"] . "';\n";
							}
						}
					}

					$use_custom_sdk_init = $this->osSettings['use_custom_sdk_init'];

					if (!$use_custom_sdk_init) {
						if (has_filter('onesignal_initialize_sdk')) {
							onesignal_debug('Applying onesignal_initialize_sdk filter.');
							if (apply_filters('onesignal_initialize_sdk', $this->osSettings)) {
								?>
								OneSignal.init(window._oneSignalInitOptions);
								<?php
							} else {
								?>
								/* OneSignal: onesignal_initialize_sdk filter preventing SDK initialization. */
								<?php
							}
						} else {
							if (array_key_exists('use_slidedown_permission_message_for_https', $this->osSettings) && $this->osSettings["use_slidedown_permission_message_for_https"] == "1") {
								?>
								oneSignal_options['autoRegister'] = false;
								OneSignal.showHttpPrompt();
								OneSignal.init(window._oneSignalInitOptions);
								<?php
							} else {
								?>
								OneSignal.init(window._oneSignalInitOptions);
								<?php
							}
						}
					} else {
						?>
						/* OneSignal: Using custom SDK initialization. */
						<?php
					}
					?>
				});

				function documentInitOneSignal() {
					var oneSignal_elements = document.getElementsByClassName("OneSignal-prompt");

					<?php
					if ( $this->osSettings["use_modal_prompt"] == "1" ) {
						echo "var oneSignalLinkClickHandler = function(event) { OneSignal.push(['registerForPushNotifications', {modalPrompt: true}]); event.preventDefault(); };";
					} else {
						echo "var oneSignalLinkClickHandler = function(event) { OneSignal.push(['registerForPushNotifications']); event.preventDefault(); };";
					}
					?>
					
					for (var i = 0; i < oneSignal_elements.length; i++) {
						oneSignal_elements[i].addEventListener('click', oneSignalLinkClickHandler, false);
					}
				}

				if (document.readyState === 'complete') {
					documentInitOneSignal();
				} else {
					window.addEventListener("load", function (event) {
						documentInitOneSignal();
					});
				}
			</script>
			<?php
		}
	}
}