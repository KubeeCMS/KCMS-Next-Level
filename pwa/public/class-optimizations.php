<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyPwaPublicOptimizations')) {
    class daftplugInstantifyPwaPublicOptimizations {
    	public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;
        public $pluginUploadDir;

        public $settings;

        public $daftplugInstantifyPwaPublic;

        public $basePath;
        public $baseUrl;
        public $defaultCachePath;
        public $minifyFiles;

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

            $this->settings = $config['settings'];

            $this->daftplugInstantifyPwaPublic = $daftplugInstantifyPwaPublic;

            $this->basePath = trailingslashit(ABSPATH);
            $this->baseUrl = trailingslashit(get_site_url());
            $this->defaultCachePath = trailingslashit(WP_CONTENT_DIR) . trailingslashit($this->slug);
            $this->minifyFiles = array('js', 'css');

            if (daftplugInstantify::getSetting('pwaCacheMinify') == 'on') {
                add_filter('script_loader_src', array($this, 'cacheAndMinify'), 30, 1);
                add_filter('style_loader_src', array($this, 'cacheAndMinify'), 30, 1);
            }

            if (daftplugInstantify::getSetting('pwaCssDeliveryOptimization') == 'on') {
                add_filter('style_loader_tag', array($this, 'optimizeCss'), 999, 4);
                add_filter("{$this->optionName}_public_html", array($this, 'renderPreloadCss'));
            }

            if (daftplugInstantify::getSetting('pwaJsDeliveryOptimization') == 'on') {
                add_filter('script_loader_tag', array($this, 'optimizeJs'), 10, 2);
            }
    	}
        
        public function cacheAndMinify($url) {
            if (strstr($url, '/js/jquery/jquery.js')) {
                return $url;
            }

            $cacheDir = $this->getCacheDir();

            $type = '';
            foreach ($this->minifyFiles as $file) {
                if (strpos($url, '.' . $file) !== false) {
                    $type = $file;
                }
            }

            if ($type == '') {
                return $url;
            }

            if (strpos($url, $this->baseUrl) === false) {
                return $url;
            }

            $newFilename = str_replace($this->baseUrl, '', $url);
            $newFilename = hash('crc32', $newFilename, false);
            $newFilename = $newFilename . '.' . $type;

            $cacheTypeDir = $cacheDir . $type . '/';

            $newPath = $cacheTypeDir . $newFilename;
            $oldPath = str_replace($this->baseUrl, $this->basePath, $url);
            $newUrl  = str_replace($this->basePath, $this->baseUrl, $newPath);

            if (strpos($oldPath, '?') != false) {
                $oldPath = explode('?', $oldPath)[0];
            }

            if (file_exists($newPath)) {
                return $newUrl;
            }

            if (!file_exists($cacheTypeDir)) {
                wp_mkdir_p($cacheTypeDir);
            }

            $path = plugin_dir_path($this->pluginFile) . implode(DIRECTORY_SEPARATOR, array('pwa', 'includes', 'libs'));
            require_once $path . '/minify/autoload.php';
            require_once $path . '/path-converter/autoload.php';

            if ($type == 'js') {
                $minifier = new \MatthiasMullie\Minify\JS($oldPath);
            } else {
                $minifier = new \MatthiasMullie\Minify\CSS($oldPath);
            }

            $minifier->minify($newPath);

            return $newUrl;
        }

        public function optimizeCss($html, $handle, $href, $media) {
            $html = str_replace('\'', '"', $html);
            $html = str_replace('rel="stylesheet"', 'rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"', $html);

            return "$html<noscript><link rel='stylesheet' data-push-id='$handle' id='$handle' href='$href' type='text/css' media='$media'></noscript>\n";
        }

        public function renderPreloadCss() {
            echo '<script type="text/javascript" id="loadCSS">
                      !function(t){"use strict";t.loadCSS||(t.loadCSS=function(){});var e=loadCSS.relpreload={};if(e.support=function(){var e;try{e=t.document.createElement("link").relList.supports("preload")}catch(t){e=!1}return function(){return e}}(),e.bindMediaToggle=function(t){function e(){t.media=a}var a=t.media||"all";t.addEventListener?t.addEventListener("load",e):t.attachEvent&&t.attachEvent("onload",e),setTimeout(function(){t.rel="stylesheet",t.media="only x"}),setTimeout(e,3e3)},e.poly=function(){if(!e.support())for(var a=t.document.getElementsByTagName("link"),n=0;n<a.length;n++){var o=a[n];"preload"!==o.rel||"style"!==o.getAttribute("as")||o.getAttribute("data-loadcss")||(o.setAttribute("data-loadcss",!0),e.bindMediaToggle(o))}},!e.support()){e.poly();var a=t.setInterval(e.poly,500);t.addEventListener?t.addEventListener("load",function(){e.poly(),t.clearInterval(a)}):t.attachEvent&&t.attachEvent("onload",function(){e.poly(),t.clearInterval(a)})}"undefined"!=typeof exports?exports.loadCSS=loadCSS:t.loadCSS=loadCSS}("undefined"!=typeof global?global:this);
                  </script>';
        }

        public function optimizeJs($tag, $handle) {
            if (strstr( $tag, '/js/jquery/jquery.js')) {
                return $tag;
            }

            return str_replace('src', 'defer="defer" src', $tag);
        }

        public function getCacheDir() {
            $cacheDir = trailingslashit($this->defaultCachePath);

            if (strpos($cacheDir, $this->baseUrl) !== false) {
                $cacheDir = str_replace( $this->baseUrl, ABSPATH, $cacheDir);
            }

            if ('' == $cacheDir || '/' == $cacheDir) {
                $cacheDir = $this->defaultCachePath;
            }

            if (is_multisite()) {
                $cacheDir = trailingslashit($cacheDir) . get_current_blog_id() . '/';
            }

            return $cacheDir;
        }
    }
}