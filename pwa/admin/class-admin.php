<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyPwaAdmin')) {
    class daftplugInstantifyPwaAdmin {
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

        public $daftplugInstantifyPwaAdminAddtohomescreen;
        public $daftplugInstantifyPwaAdminOfflineusage;
        public $daftplugInstantifyPwaAdminAccessibility;
        public $daftplugInstantifyPwaAdminOptimizations;
        public $daftplugInstantifyPwaAdminPushnotifications;

    	public function __construct($config, $daftplugInstantifyPwaAdminAddtohomescreen, $daftplugInstantifyPwaAdminOfflineusage, $daftplugInstantifyPwaAdminAccessibility,
                    $daftplugInstantifyPwaAdminOptimizations, $daftplugInstantifyPwaAdminPushnotifications) {
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

            $this->daftplugInstantifyPwaAdminAddtohomescreen = $daftplugInstantifyPwaAdminAddtohomescreen;
            $this->daftplugInstantifyPwaAdminOfflineusage = $daftplugInstantifyPwaAdminAddtohomescreen;
            $this->daftplugInstantifyPwaAdminAccessibility = $daftplugInstantifyPwaAdminAccessibility;
            $this->daftplugInstantifyPwaAdminOptimizations = $daftplugInstantifyPwaAdminOptimizations;
            $this->daftplugInstantifyPwaAdminPushnotifications = $daftplugInstantifyPwaAdminPushnotifications;

            add_action('admin_enqueue_scripts', array($this, 'loadAssets'));
    	}

        public function loadAssets() {
            $this->dependencies[] = 'jquery';

            wp_enqueue_style("{$this->slug}-pwa-admin", plugins_url('pwa/admin/assets/css/style-pwa.css', $this->pluginFile), array(), $this->version);
            wp_enqueue_script("{$this->slug}-pwa-admin", plugins_url('pwa/admin/assets/js/script-pwa.js', $this->pluginFile), $this->dependencies, $this->version, true);
        }

        public function generateSubpages() {
            $subpages = array(
                array(
                    'id' => 'addtohomescreen',
                    'title' => esc_html__('Add To Home Screen', $this->textDomain),
                    'template' => plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'subpage-addtohomescreen.php'))
                ),
                array(
                    'id' => 'offlineusage',
                    'title' => esc_html__('Offline Usage', $this->textDomain),
                    'template' => plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'subpage-offlineusage.php')),
                ),
                array(
                    'id' => 'accessibility',
                    'title' => esc_html__('Accessibility', $this->textDomain),
                    'template' => plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'subpage-accessibility.php')),
                ),
                array(
                    'id' => 'optimizations',
                    'title' => esc_html__('Optimizations', $this->textDomain),
                    'template' => plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'subpage-optimizations.php'))
                ),
                array(
                    'id' => 'pushnotifications',
                    'title' => esc_html__('Push Notifications', $this->textDomain),
                    'template' => plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('templates', 'subpage-pushnotifications.php'))
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
                        <a class="daftplugAdminSubmenu_link" href="#/pwa-<?php esc_html_e($subpage['id']); ?>/" data-subpage="<?php esc_html_e($subpage['id']); ?>">
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