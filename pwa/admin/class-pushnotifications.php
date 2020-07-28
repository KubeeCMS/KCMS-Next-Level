<?php

if (!defined('ABSPATH')) exit;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

if (!class_exists('daftplugInstantifyPwaAdminPushnotifications')) {
    class daftplugInstantifyPwaAdminPushnotifications {
        public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;

        public $settings;
        public $subscribedDevices;

        public function __construct($config) {
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

            add_action("wp_ajax_{$this->optionName}_send_notification", array($this, 'doModalPush'));
            add_action('add_meta_boxes', array($this, 'addMetaBoxes'), 10, 2);
            add_filter('wp_insert_post_data', array($this, 'filterWooCommercePostData'), 10, 2);
            add_action('save_post',  array($this, 'doAutoPush'), 10, 2);
        }

        public function doModalPush() {
            $pushData = array(
                'title' => sanitize_text_field($_POST['pushTitle']),
                'body' => sanitize_text_field($_POST['pushBody']),
                'image' => esc_url_raw(wp_get_attachment_image_src($_POST['pushImage'], 'full')[0]),
                'icon' => esc_url_raw(wp_get_attachment_image_src($_POST['pushIcon'], 'full')[0]),
                'data' => array(
                    'url' => trailingslashit(esc_url_raw($_POST['pushUrl'])).'?utm_source=pwa-notification',
                ),
            );

            if (!empty($_POST['pushActionbutton1Text'])) {
                $pushData['actions'][] = array('action' => 'action1', 'title' => sanitize_text_field($_POST['pushActionbutton1Text']));
                $pushData['data']['pushActionbutton1Url'] = trailingslashit(esc_url_raw($_POST['pushActionbutton1Url']));
            }

            if (!empty($_POST['pushActionbutton2Text'])) {
                $pushData['actions'][] = array('action' => 'action2', 'title' => sanitize_text_field($_POST['pushActionbutton2Text']));
                $pushData['data']['pushActionbutton2Url'] = trailingslashit(esc_url_raw($_POST['pushActionbutton2Url']));
            }

            $segment = sanitize_text_field($_POST['pushSegment']);

            if (wp_verify_nonce($_POST['nonce'], "{$this->optionName}_send_notification_nonce")) {
                $sendNotification = $this->sendNotification($pushData, $segment);
                wp_die($sendNotification);
            } else {
                wp_die('0');
            }
        }

        public function renderMetaBoxContent($post, $callbackArgs) {
            $pwaNoPushNewPost = get_post_meta($post->ID, 'pwaNoPushNewPost', true);
            $pwaNoPushNewProduct = get_post_meta($post->ID, 'pwaNoPushNewProduct', true);
            $pwaNoPushPriceDrop = get_post_meta($post->ID, 'pwaNoPushPriceDrop', true);
            $pwaNoPushSalePrice = get_post_meta($post->ID, 'pwaNoPushSalePrice', true);
            $pwaNoPushBackInStock = get_post_meta($post->ID, 'pwaNoPushBackInStock', true);
            wp_nonce_field("{$this->optionName}_no_push_meta_nonce", "{$this->optionName}_no_push_meta_nonce");
            if ($post->post_type == 'product') {
                if (daftplugInstantify::getSetting('pwaPushNewProduct') == 'on') { ?>
                    <label style="display: block; margin: 5px;">
                        <input type="checkbox" name="pwaNoPushNewProduct" value="on" <?php checked($pwaNoPushNewProduct, 'on'); ?>>
                        <?php esc_html_e('Don\'t Send New Product Notification', $this->textDomain); ?>
                    </label style="display: block; margin: 5px;">
                <?php }
                if (daftplugInstantify::getSetting('pwaPushPriceDrop') == 'on') { ?>
                    <label style="display: block; margin: 5px;">
                        <input type="checkbox" name="pwaNoPushPriceDrop" value="on" <?php checked($pwaNoPushPriceDrop, 'on'); ?>>
                        <?php esc_html_e('Don\'t Send Price Drop Notification', $this->textDomain); ?>
                    </label>
                <?php }
                if (daftplugInstantify::getSetting('pwaPushSalePrice') == 'on') { ?>
                    <label style="display: block; margin: 5px;">
                        <input type="checkbox" name="pwaNoPushSalePrice" value="on" <?php checked($pwaNoPushSalePrice, 'on'); ?>>
                        <?php esc_html_e('Don\'t Send Sale Price Notification', $this->textDomain); ?>
                    </label>
                <?php }
                if (daftplugInstantify::getSetting('pwaPushBackInStock') == 'on') { ?>
                    <label style="display: block; margin: 5px;">
                        <input type="checkbox" name="pwaNoPushBackInStock" value="on" <?php checked($pwaNoPushBackInStock, 'on'); ?>>
                        <?php esc_html_e('Don\'t Send Back In Stock Notification', $this->textDomain); ?>
                    </label>
                <?php }
            } else {
                if (daftplugInstantify::getSetting('pwaPushNewPost') == 'on') { ?>
                    <label style="display: block; margin: 5px;">
                        <input type="checkbox" name="pwaNoPushNewPost" value="on" <?php checked($pwaNoPushNewPost, 'on'); ?>>
                        <?php esc_html_e('Don\'t Send New Post Notification', $this->textDomain); ?>
                    </label>
                <?php }
            }
        }

        public function addMetaBoxes($postType, $post)  {
            if (in_array($post->post_type, (array)daftplugInstantify::getSetting('pwaPushNewPostPostTypes')) || $post->post_type == 'product') {
                add_meta_box("{$this->optionName}_no_push_meta_box", esc_html__('Push Notifications', $this->textDomain), array($this, 'renderMetaBoxContent'), $postType, 'side', 'default', array());
            }
        }

        public function filterWooCommercePostData($data, $postArr) {
            global $post;

            if (!$post || $post->post_type != 'product') {
                return $data;
            }

            $wooCurrency = html_entity_decode(get_woocommerce_currency_symbol(get_option('woocommerce_currency')));
            $priceFormat = get_woocommerce_price_format();
            $oldSalePrice = get_post_meta($post->ID, '_sale_price', true);
            $newSalePrice = $postArr['_sale_price'];
            $oldRegularPrice = get_post_meta($post->ID, '_regular_price', true);
            $newRegularPrice = $postArr['_regular_price'];
            $oldStock = get_post_meta($post->ID, '_stock', true );
            $newStock = $postArr['_stock'];

            if ($oldRegularPrice) {
                set_transient("{$this->optionName}_regular_price", sprintf($priceFormat, $wooCurrency, $oldRegularPrice), 5);
            } else {
                set_transient("{$this->optionName}_regular_price", sprintf($priceFormat, $wooCurrency, $newRegularPrice), 5);
            }

            if ((!$oldSalePrice && $newSalePrice) || ($oldSalePrice > $newSalePrice && $newSalePrice != 0)) {
                set_transient("{$this->optionName}_sale_price", sprintf($priceFormat, $wooCurrency, $newSalePrice), 5);
            }

            if ($newRegularPrice < $oldRegularPrice) {
                set_transient("{$this->optionName}_dropped_price", sprintf($priceFormat, $wooCurrency, $newRegularPrice), 5);
            }

            if ($oldStock == 0 && $newStock > 0) {
                set_transient("{$this->optionName}_back_in_stock", true, 5);
            }

            return $data;
        }

        public function doAutoPush($id, $post) {
            $isAutosave = (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_autosave($id);
            $isRevision = wp_is_post_revision($id);
            $isValidNonce = (isset($_POST["{$this->optionName}_no_push_meta_nonce"]) && wp_verify_nonce($_POST["{$this->optionName}_no_push_meta_nonce"], $this->pluginBasename)) ? 'true' : 'false';
            $pwaNoPushNewPostMeta = (isset($_POST['pwaNoPushNewPost']) ? $_POST['pwaNoPushNewPost'] : 'off');
            $pwaNoPushNewProductMeta = (isset($_POST['pwaNoPushNewProduct']) ? $_POST['pwaNoPushNewProduct'] : 'off');
            $pwaNoPushPriceDropMeta = (isset($_POST['pwaNoPushPriceDrop']) ? $_POST['pwaNoPushPriceDrop'] : 'off');
            $pwaNoPushSalePriceMeta = (isset($_POST['pwaNoPushSalePrice']) ? $_POST['pwaNoPushSalePrice'] : 'off');
            $pwaNoPushBackInStockMeta = (isset($_POST['pwaNoPushBackInStock']) ? $_POST['pwaNoPushBackInStock'] : 'off');

            if ($isAutosave || $isRevision || !$isValidNonce) {
                return;
            }

            if ($post->post_type !== 'product') {
                // New Post Push
                if (daftplugInstantify::getSetting('pwaPushNewPost') == 'on' && $pwaNoPushNewPostMeta == 'off' && in_array($post->post_type, (array)daftplugInstantify::getSetting('pwaPushNewPostPostTypes'))) {
                    if (isset($post->post_status) && 'auto-draft' == $post->post_status) {
                        return;
                    }

                    if ($post->post_date === $post->post_modified) {
                        $pushData = array(
                            'title' => sprintf(__('New %s - %s', $this->textDomain), get_post_type_labels($post)->singular_name, $post->post_title),
                            'body' => strip_tags($post->post_content),
                            'data' => array(
                                'url' => trailingslashit(get_permalink($id)),
                            ),
                        );

                        if (has_post_thumbnail($id)) {
                            $pushData['image'] = get_the_post_thumbnail_url($id);
                        }

                        $this->sendNotification($pushData, 'all');
                    }
                }
            } else {
                // New Product Push
                if (daftplugInstantify::getSetting('pwaPushNewProduct') == 'on' && $pwaNoPushNewProductMeta == 'off') {
                    if (isset($post->post_status) && 'auto-draft' == $post->post_status) {
                        return;
                    }

                    if ($post->post_date === $post->post_modified) {
                        $pushData = array(
                            'title' => sprintf(__('New Product - %s', $this->textDomain), $post->post_title),
                            'body' => strip_tags($post->post_content),
                            'data' => array(
                                'url' => trailingslashit(get_permalink($id)),
                            ),
                        );

                        if (has_post_thumbnail($id)) {
                            $pushData['image'] = get_the_post_thumbnail_url($id);
                        }

                        $this->sendNotification($pushData, 'all');
                    }
                }

                // Price Drop Push
                if (daftplugInstantify::getSetting('pwaPushPriceDrop') == 'on' && $pwaNoPushPriceDropMeta == 'off' && get_transient("{$this->optionName}_dropped_price")) {
                    $pushData = array(
                        'title' => sprintf(__('Price Drop - %s', $this->textDomain), $post->post_title),
                        'body' => sprintf(__('Price dropped from %s to %s', $this->textDomain), get_transient("{$this->optionName}_regular_price"), get_transient("{$this->optionName}_dropped_price")),
                        'data' => array(
                            'url' => trailingslashit(get_permalink($id)),
                        ),
                    );

                    if (has_post_thumbnail($id)) {
                        $pushData['image'] = get_the_post_thumbnail_url($id);
                    }

                    $this->sendNotification($pushData, 'all');
                }

                // Sale Price Push
                if (daftplugInstantify::getSetting('pwaPushSalePrice') == 'on' && $pwaNoPushSalePriceMeta == 'off' && get_transient("{$this->optionName}_sale_price")) {
                    $pushData = array(
                        'title' => sprintf(__('New Sale Price - %s', $this->textDomain), $post->post_title),
                        'body' => sprintf(__('New Sale Price: %s', $this->textDomain), get_transient("{$this->optionName}_sale_price")),
                        'data' => array(
                            'url' => trailingslashit(get_permalink($id)),
                        ),
                    );

                    if (has_post_thumbnail($id)) {
                        $pushData['image'] = get_the_post_thumbnail_url($id);
                    }

                    $this->sendNotification($pushData, 'all');
                }

                // Back In Stock Push
                if (daftplugInstantify::getSetting('pwaPushBackInStock') == 'on' && $pwaNoPushBackInStockMeta == 'off' && get_transient("{$this->optionName}_back_in_stock")) {
                    $pushData = array(
                        'title' => sprintf(__('Back In Stock - %s', $this->textDomain), $post->post_title),
                        'body' => sprintf(__('%s is now back in stock', $this->textDomain), $post->post_title),
                        'data' => array(
                            'url' => trailingslashit(get_permalink($id)),
                        ),
                    );

                    if (has_post_thumbnail($id)) {
                        $pushData['image'] = get_the_post_thumbnail_url($id);
                    }

                    $this->sendNotification($pushData, 'all');
                }
            }
        }

        public function sendNotification($pushData, $segment) {
            require_once plugin_dir_path($this->pluginFile) . implode(DIRECTORY_SEPARATOR, array('pwa', 'includes', 'libs', 'web-push-php', 'autoload.php'));

            $auth = array(
                'VAPID' => array(
                    'subject' => get_bloginfo('wpurl'),
                    'publicKey' => $this->vapidKeys['pwaPublicKey'],
                    'privateKey' => $this->vapidKeys['pwaPrivateKey'],
                ),
            );

            $webPush = new WebPush($auth);

            $badge = daftplugInstantify::getSetting('pwaNotificationBarIcon');

            if (get_post_type($badge) == 'attachment') {
                $badgeImg = daftplugInstantifyPwa::resizeImage($badge, 96, 96, true, '');
                if ($badgeImg) {
                    $badgeUrl = $badgeImg[0];
                }
            } else {
                $badgeUrl = '';
            }

            $pushData = wp_parse_args($pushData, array(
                'title' => '',
                'badge' => $badgeUrl,
                'body' => '',
                'icon' => '',
                'image' => '',
                'data' => '',
            ));

            if ($segment == 'all') {
                $subscriptions = array();
                foreach ($this->subscribedDevices as $subscribedDevice) {
                    $subscriptions[] =  array(
                                            'subscription' => Subscription::create(
                                                array(
                                                    'endpoint' => $subscribedDevice['endpoint'],
                                                    'publicKey' => $subscribedDevice['userKey'],
                                                    'authToken' => $subscribedDevice['userAuth'],
                                                )
                                            ),
                                            'payload' => null
                                        );
                }

                foreach ($subscriptions as $subscription) {
                    $webPush->sendNotification(
                        $subscription['subscription'],
                        json_encode($pushData)
                    );
                }
            } elseif ($segment == 'mobile') {
                $subscriptions = array();
                foreach ($this->subscribedDevices as $subscribedDevice) {
                    if (preg_match('[Android|iOS]', $subscribedDevice['deviceInfo'])) {
                        $subscriptions[] =  array(
                                                'subscription' => Subscription::create(
                                                    array(
                                                        'endpoint' => $subscribedDevice['endpoint'],
                                                        'publicKey' => $subscribedDevice['userKey'],
                                                        'authToken' => $subscribedDevice['userAuth'],
                                                    )
                                                ),
                                                'payload' => null
                                            );
                    }
                }

                foreach ($subscriptions as $subscription) {
                    $webPush->sendNotification(
                        $subscription['subscription'],
                        json_encode($pushData)
                    );
                }
            } elseif ($segment == 'desktop') {
                $subscriptions = array();
                foreach ($this->subscribedDevices as $subscribedDevice) {
                    if (preg_match('[Windows|Linux|Mac|Ubuntu|Solaris]', $subscribedDevice['deviceInfo'])) {
                        $subscriptions[] =  array(
                                                'subscription' => Subscription::create(
                                                    array(
                                                        'endpoint' => $subscribedDevice['endpoint'],
                                                        'publicKey' => $subscribedDevice['userKey'],
                                                        'authToken' => $subscribedDevice['userAuth'],
                                                    )
                                                ),
                                                'payload' => null
                                            );
                    }
                }

                foreach ($subscriptions as $subscription) {
                    $webPush->sendNotification(
                        $subscription['subscription'],
                        json_encode($pushData)
                    );
                }
            } else {
                $subscription = array(
                                    'subscription' => Subscription::create(
                                        array(
                                            'endpoint' => $this->subscribedDevices[$segment]['endpoint'],
                                            'publicKey' => $this->subscribedDevices[$segment]['userKey'],
                                            'authToken' => $this->subscribedDevices[$segment]['userAuth'],
                                        )
                                    ),
                                    'payload' => null
                                );

                $webPush->sendNotification(
                    $subscription['subscription'],
                    json_encode($pushData)
                );
            }

            foreach ($webPush->flush() as $report) {
                $endpoint = $report->getRequest()->getUri()->__toString();
                if (!$report->isSuccess()) {
                    unset($this->subscribedDevices[$endpoint]);
                    update_option("{$this->optionName}_subscribed_devices", $this->subscribedDevices);
                    wp_die("Message failed to sent for subscription {$endpoint}: {$report->getReason()}");
                } else {
                    wp_die('1');
                }
            }
        }

        public function getPostTypes() {
            $excludes = array('product', 'attachment');
            $postTypes = get_post_types(
                            array(
                                'public' => true,
                            ),
                            'names'
                         );

            foreach ($excludes as $exclude) {
                unset($postTypes[$exclude]);
            }

            return array_values($postTypes);
        }
    }
}