<?php
/*
Plugin Name: KCMS Next Level
Description: Take the user experience to the next level with future of mobile web...
Plugin URI: https://github.com/KubeeCMS/KCMS-Next-Level/
Version: 4.6
Author: KubeCMS
Author URI: https://github.com/KubeeCMS/
Text Domain: daftplug-instantify
Domain Path: /languages
Requires at least: 4.9
Requires PHP: 7.0
*/

if (!defined('ABSPATH')) exit;

update_option( 'daftplug_instantify_purchase_code', '*************' );

require_once('includes/class-plugin.php');

new daftplugInstantify(array(
        'name' => esc_html__('DaftPlug Instantify - PWA & Google AMP & Facebook IA'),
        'description' => esc_html__('Take the user experience to the next level with future of mobile web: Progressive Web Apps (PWA), Google Accelerated Mobile Pages (AMP) and Facebook Instant Articles (FBIA).'),
        'slug' => 'daftplug-instantify',
        'version' => '4.6',
        'text_domain' => 'daftplug-instantify',
        'option_name' => 'daftplug_instantify',

        'plugin_file' => __FILE__,
        'plugin_basename' => plugin_basename(__FILE__),
        'plugin_upload_dir' => wp_upload_dir()['basedir'] . '/kcms-next-level/',

        'menu_title' => esc_html__('Instantify'),
        'menu_icon' => plugins_url('admin/assets/img/icon-menu.png', __FILE__),

        'settings' => get_option('daftplug_instantify_settings', true),

        'verify_url' => 'https://daftplug.com/purchase-verify/',
        'item_id' => '25757693'
    )
);
