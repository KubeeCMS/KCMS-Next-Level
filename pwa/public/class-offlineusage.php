<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyPwaPublicOfflineusage')) {
    class daftplugInstantifyPwaPublicOfflineusage {
        public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;

        public $settings;

        public $daftplugInstantifyPwaPublic;

        public $serviceWorker;

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

            $this->daftplugInstantifyPwaPublic = $daftplugInstantifyPwaPublic;

            $this->serviceWorker = '';

            add_action('parse_request', array($this, 'generateServiceWorker'));
            add_filter('query_vars', array($this, 'addServiceWorkerQueryVar'));
            add_action('wp_head', array($this, 'renderRegisterServiceWorker'));
        }

        public function generateServiceWorker() {
            if (isset($GLOBALS['wp']->query_vars['daftplugInstantifyPwaServiceWorker'])) {
                if ($GLOBALS['wp']->query_vars['daftplugInstantifyPwaServiceWorker'] == 1) {
                    header('Content-Type: text/javascript; charset=utf-8');
                    $homePage = trailingslashit(home_url('/', 'https'));
                    $offlinePage = daftplugInstantify::getSetting('pwaOfflinePage');
                    $offlineContents = (array)daftplugInstantify::getSetting('pwaOfflineContent');
                    array_unshift($offlineContents, $offlinePage);
                    array_unshift($offlineContents, $homePage);
                    $cachedPages = json_encode(array_values(array_unique(array_filter($offlineContents, 'strlen'))));

                    $routes = array(
                        'assets'  => array(
                            'regex'   => trailingslashit(home_url('/', 'https')).'.*\.(css|js)',
                            'strategy' => daftplugInstantify::getSetting('pwaOfflineAssetsStrategy'),
                        ),
                        'fonts'   => array(
                            'regex'   => trailingslashit(home_url('/', 'https')).'.*\.(woff|eot|woff2|ttf|svg)',
                            'strategy' => daftplugInstantify::getSetting('pwaOfflineFontsStrategy'),
                        ),
                        'images'  => array(
                            'regex' => trailingslashit(home_url('/', 'https')).'.*\.(png|jpg|jpeg|gif|ico)',
                            'strategy' => daftplugInstantify::getSetting('pwaOfflineImagesStrategy'),
                        ),
                        'default' => array(
                            'regex' => trailingslashit(home_url('/', 'https')).'.*',
                            'strategy' => daftplugInstantify::getSetting('pwaOfflineDefaultStrategy'),
                        ),
                    );

                    $this->serviceWorker .= "importScripts('https://storage.googleapis.com/workbox-cdn/releases/4.0.0/workbox-sw.js');\n\n";
                    $this->serviceWorker .= "self.addEventListener('message', (event) => {
                                                if (event.data && event.data.type === 'SKIP_WAITING') {
                                                    self.skipWaiting();
                                                }
                                             });\n";
                    $this->serviceWorker .= "\nif (workbox) {\n";
                    $this->serviceWorker .= "workbox.precaching.precache({$cachedPages});\n";
                    $this->serviceWorker .= "workbox.routing.registerRoute(/wp-admin(.*)|wp-json(.*)|(.*)preview=true(.*)/, workbox.strategies.networkOnly());\n";
                    $this->serviceWorker .= "workbox.routing.registerRoute(/(.*)fonts\.googleapis\.com(.*)/, workbox.strategies.staleWhileRevalidate({
                        cacheName: cacheName + '-google-fonts', plugins: [new workbox.cacheableResponse.Plugin({statuses: [0, 200]})]}));\n";
                    $this->serviceWorker .= "workbox.routing.registerRoute(/(.*)fonts\.gstatic\.com(.*)/, workbox.strategies.staleWhileRevalidate({cacheName: cacheName + '-google-fonts', plugins: [new workbox.cacheableResponse.Plugin({statuses: [0, 200]})]}));\n";
                    $this->serviceWorker .= "workbox.routing.registerRoute(/(.*)secure\.gravatar\.com(.*)/, workbox.strategies.staleWhileRevalidate({cacheName: cacheName + '-gravatar', plugins: [new workbox.cacheableResponse.Plugin({statuses: [0, 200]})]}));\n";
                    foreach ($routes as $key => $values) {
                        if ($key == 'default') {
                            $this->serviceWorker .= "workbox.routing.registerRoute(new RegExp('{$values['regex']}'),
                                async (args) => {
                                    try {
                                        const response = await workbox.strategies.{$values['strategy']}({cacheName: cacheName + '-{$key}'}).handle(args);
                                        return response || await caches.match('{$offlinePage}');
                                    } catch (error) {
                                        console.log('catch:',error);
                                        return await caches.match('{$offlinePage}');
                                    }
                                }
                            );\n";
                        } else {
                            $this->serviceWorker .= "workbox.routing.registerRoute(new RegExp('{$values['regex']}'), workbox.strategies.{$values['strategy']}({cacheName: cacheName + '-{$key}'}));\n";
                        }
                    }

                    if (daftplugInstantify::getSetting('pwaOfflineGoogleAnalytics') == 'on') {
                        $this->serviceWorker .= "workbox.googleAnalytics.initialize();\n";
                    }

                    $this->serviceWorker .= "}\n\n";
                    $this->serviceWorker .= "self.addEventListener('activate', (event) => {
                                                event.waitUntil(
                                                    caches.keys()
                                                        .then(keys => {
                                                            return Promise.all(
                                                                keys.map(key => {
                                                                    if (/^(workbox-precache)/.test(key)) {
                                                                        //console.log(key);
                                                                    } else if (/^(([a-zA-Z0-9]{8})-([a-z]*))/.test(key)) {
                                                                        //console.log(key);
                                                                        if (key.indexOf(cacheName) !== 0) {
                                                                            //console.log('delete');
                                                                            return caches.delete(key);
                                                                        }
                                                                    }
                                                                })
                                                            );
                                                        })
                                                );
                                            });\n\n";
                    $this->serviceWorker = apply_filters("{$this->optionName}_pwa_serviceworker", $this->serviceWorker);
                    $cacheName = hash('crc32', $this->serviceWorker, false);
                    
                    echo "(function() {\n'use strict';\n\nconst cacheName = '{$cacheName}';\n\n".$this->serviceWorker."\n})();\n";
                    exit;
                }
            }
        }

        public function addServiceWorkerQueryVar($queryVars) {
            $queryVars[] = 'daftplugInstantifyPwaServiceWorker';

            return $queryVars;
        }

        public function renderRegisterServiceWorker() {
            include_once($this->daftplugInstantifyPwaPublic->partials['registerServiceWorker']);
        }

        public static function getServiceWorkerUrl($encoded = true) {
            $url = add_query_arg(array('daftplugInstantifyPwaServiceWorker' => 1), home_url('/', 'https'));
            if ($encoded) {
                return wp_json_encode($url);
            }

            return $url;
        }
    }
}