<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyAmpAdmin')) {
    class daftplugInstantifyAmpAdmin {
    	public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;

        protected $dependencies;

        public $settings;

        public $subpages;

        public $daftplugInstantifyAmpAdminGeneral;
        public $daftplugInstantifyAmpAdminAdvertisements;
        public $daftplugInstantifyAmpAdminAnalytics;
        public $daftplugInstantifyAmpAdminGdprconsent;

    	public function __construct($config, $daftplugInstantifyAmpAdminGeneral, $daftplugInstantifyAmpAdminAdvertisements, $daftplugInstantifyAmpAdminAnalytics, $daftplugInstantifyAmpAdminGdprconsent) {
    		$this->name = $config['name'];
            $this->description = $config['description'];
            $this->slug = $config['slug'];
            $this->version = $config['version'];
            $this->textDomain = $config['text_domain'];
            $this->optionName = $config['option_name'];

            $this->pluginFile = $config['plugin_file'];
            $this->pluginBasename = $config['plugin_basename'];

            $this->dependencies = array();

            $this->settings = $config['settings'];

            $this->subpages = $this->generateSubpages();

            $this->daftplugInstantifyAmpAdminGeneral = $daftplugInstantifyAmpAdminGeneral;
            $this->daftplugInstantifyAmpAdminAdvertisements = $daftplugInstantifyAmpAdminAdvertisements;
            $this->daftplugInstantifyAmpAdminAnalytics = $daftplugInstantifyAmpAdminAnalytics;
            $this->daftplugInstantifyAmpAdminGdprconsent = $daftplugInstantifyAmpAdminGdprconsent;

            add_action('admin_enqueue_scripts', array($this, 'loadAssets'));
    	}

        public function loadAssets() {
            $this->dependencies[] = 'jquery';

            wp_enqueue_style("{$this->slug}-amp-admin", plugins_url('amp/admin/assets/css/style-amp.css', $this->pluginFile), array(), $this->version);
            wp_enqueue_script("{$this->slug}-amp-admin", plugins_url('amp/admin/assets/js/script-amp.js', $this->pluginFile), $this->dependencies, $this->version, true);
        }

        public function generateSubpages() {
            $subpages = array(
                array(
                    'id' => 'general',
                    'title' => esc_html__('General', $this->textDomain),
                    'template' => plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'subpage-general.php'))
                ),
                array(
                    'id' => 'advertisements',
                    'title' => esc_html__('Advertisements', $this->textDomain),
                    'template' => plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'subpage-advertisements.php')),
                ),
                array(
                    'id' => 'analytics',
                    'title' => esc_html__('Analytics', $this->textDomain),
                    'template' => plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'subpage-analytics.php'))
                ),
                array(
                    'id' => 'gdprconsent',
                    'title' => esc_html__('GDPR Consent', $this->textDomain),
                    'template' => plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'subpage-gdprconsent.php'))
                )
            );

            return $subpages;
        }

        public function getSubpages() {
            ?>
            <div class="daftplugAdminSubmenu">
                <ul class="daftplugAdminSubmenu_list">
                <?php
                foreach ($this->subpages as $subpage) {
                    ?>
                    <li class="daftplugAdminSubmenu_item -<?php esc_html_e($subpage['id']); ?>">
                        <a class="daftplugAdminSubmenu_link" href="#/amp-<?php esc_html_e($subpage['id']); ?>/" data-subpage="<?php esc_html_e($subpage['id']); ?>">
                            <?php esc_html_e($subpage['title']); ?>
                        </a>
                    </li>
                    <?php
                }
                ?>
                </ul>
            </div>
            <?php
            foreach ($this->subpages as $subpage) {
                include_once($subpage['template']);
            }
        }
    }
}