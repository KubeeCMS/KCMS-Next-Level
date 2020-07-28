<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyAmp')) {
    class daftplugInstantifyAmp {
        public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;

        public $ampGenerationStrategy;

        public $daftplugInstantifyAmpPublic;
        public $daftplugInstantifyAmpPublicGeneral;
        public $daftplugInstantifyAmpPublicAdvertisements;
        public $daftplugInstantifyAmpPublicAnalytics;
        public $daftplugInstantifyAmpPublicGdprconsent;

        public $daftplugInstantifyAmpAdmin;
        public $daftplugInstantifyAmpAdminGeneral;
        public $daftplugInstantifyAmpAdminAdvertisements;
        public $daftplugInstantifyAmpAdminAnalytics;
        public $daftplugInstantifyAmpAdminGdprconsent;

        public function __construct($config) {
            $this->name = $config['name'];
            $this->description = $config['description'];
            $this->slug = $config['slug'];
            $this->version = $config['version'];
            $this->textDomain = $config['text_domain'];
            $this->optionName = $config['option_name'];

            $this->pluginFile = $config['plugin_file'];
            $this->pluginBasename = $config['plugin_basename'];

            $this->ampGenerationStrategy = daftplugInstantify::getSetting('ampGenerationStrategy');

            if (!$this->isAmpPluginActive()) {
                require_once(plugin_dir_path(dirname(__FILE__)) . "includes/libs/{$this->ampGenerationStrategy}/amp/amp.php");

	            if (daftplugInstantify::isPublic()) {
                    require_once(plugin_dir_path(dirname(__FILE__)) . 'public/class-public.php');
                    $this->daftplugInstantifyAmpPublic = new daftplugInstantifyAmpPublic($config);

                    require_once(plugin_dir_path(dirname(__FILE__)) . 'public/class-general.php');
                    $this->daftplugInstantifyAmpPublicGeneral = new daftplugInstantifyAmpPublicGeneral($config, $this->daftplugInstantifyAmpPublic);

                    require_once(plugin_dir_path(dirname(__FILE__)) . 'public/class-advertisements.php');
                    $this->daftplugInstantifyAmpPublicAdvertisements = new daftplugInstantifyAmpPublicAdvertisements($config, $this->daftplugInstantifyAmpPublic);

                    require_once(plugin_dir_path(dirname(__FILE__)) . 'public/class-analytics.php');
                    $this->daftplugInstantifyAmpPublicAnalytics = new daftplugInstantifyAmpPublicAnalytics($config, $this->daftplugInstantifyAmpPublic);

                    require_once(plugin_dir_path(dirname(__FILE__)) . 'public/class-gdprconsent.php');
                    $this->daftplugInstantifyAmpPublicGdprconsent = new daftplugInstantifyAmpPublicGdprconsent($config, $this->daftplugInstantifyAmpPublic);
	            }

                if (daftplugInstantify::isAdmin()) {
                    require_once(plugin_dir_path(dirname(__FILE__)) . 'admin/class-general.php');
                    $this->daftplugInstantifyAmpAdminGeneral = new daftplugInstantifyAmpAdminGeneral($config);

                    require_once(plugin_dir_path(dirname(__FILE__)) . 'admin/class-advertisements.php');
                    $this->daftplugInstantifyAmpAdminAdvertisements = new daftplugInstantifyAmpAdminAdvertisements($config);

                    require_once(plugin_dir_path(dirname(__FILE__)) . 'admin/class-analytics.php');
                    $this->daftplugInstantifyAmpAdminAnalytics = new daftplugInstantifyAmpAdminAnalytics($config);

                    require_once(plugin_dir_path(dirname(__FILE__)) . 'admin/class-gdprconsent.php');
                    $this->daftplugInstantifyAmpAdminGdprconsent = new daftplugInstantifyAmpAdminGdprconsent($config);

                    require_once(plugin_dir_path(dirname(__FILE__)) . 'admin/class-admin.php');
                    $this->daftplugInstantifyAmpAdmin = new daftplugInstantifyAmpAdmin($config, $this->daftplugInstantifyAmpAdminGeneral, $this->daftplugInstantifyAmpAdminAdvertisements, $this->daftplugInstantifyAmpAdminAnalytics, $this->daftplugInstantifyAmpAdminGdprconsent);
                }
        	}
        }

        public static function isAmpPluginActive() {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
            if (is_plugin_active('amp/amp.php') || is_plugin_active('better-amp/better-amp.php') || is_plugin_active('accelerated-mobile-pages/accelerated-mobile-pages.php') || is_plugin_active('weeblramp/weeblramp.php') || is_plugin_active('amp-wp/amp-wp.php')) {
            	return true;
            } else {
            	return false;
            }
        }
    }
}