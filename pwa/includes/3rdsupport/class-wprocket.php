<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyPwaWprocket')) {
    class daftplugInstantifyPwaWprocket {
    	public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;

        public $settings;

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

            add_filter('rocket_exclude_static_dynamic_resources', array($this, 'excludeFiles'));
		}

        public function excludeFiles($excludedFiles) {
            $excludedFiles[] = '/wp-content/plugins/daftplug-instantify/pwa/public/assets/js/(.*).js';
            return $excludedFiles;
        }
    }
}