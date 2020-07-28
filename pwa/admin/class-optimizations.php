<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyPwaAdminOptimizations')) {
    class daftplugInstantifyPwaAdminOptimizations {
    	public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;
        public $pluginUploadDir;

        public $basePath;
        public $baseUrl;
        public $defaultCachePath;
        public $minifyFiles;

        public $fileCount;
        public $fileSize;

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
            $this->pluginUploadDir = $config['plugin_upload_dir'];

            $this->basePath = trailingslashit(ABSPATH);
            $this->baseUrl = trailingslashit(get_site_url());
            $this->defaultCachePath = trailingslashit(WP_CONTENT_DIR) . trailingslashit($this->slug);
            $this->minifyFiles = array('js', 'css');

            $this->fileCount = $this->getCacheInfo()[0];
            $this->fileSize = $this->getCacheInfo()[1];

            $this->settings = $config['settings'];

            add_action("wp_ajax_{$this->optionName}_clear_cache", array($this, 'clearCache'));

            if (daftplugInstantify::getSetting('pwaCompression') == 'on') {
                add_filter('mod_rewrite_rules', array($this, 'addCompression'));
                add_action("{$this->optionName}_on_save_settings", array($this, 'rewriteHtaccess'));
            }

            if (daftplugInstantify::getSetting('pwaCachingHeaders') == 'on') {
                add_filter('mod_rewrite_rules', array($this, 'addCachingHeaders'));
                add_action("{$this->optionName}_on_save_settings", array($this, 'rewriteHtaccess'));
            }
    	}

        public function getCacheInfo() {
            $cacheDir = $this->getCacheDir();
            $fileCount = 0;
            $fileSize  = 0;

            foreach ($this->minifyFiles as $folder) {
                $dir = $cacheDir . $folder . '/';

                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }

                $files = scandir($dir);

                foreach ($files as $file) {
                    if ('.' == $file || '..' == $file) {
                        continue;
                    }
                    $fileCount ++;
                    $fileSize = $fileSize + filesize( $dir . $file );
                }
            }

            return array($fileCount, $this->formatBytes($fileSize));
        }

        public function clearCache() {
            $cacheDir = $this->getCacheDir();
            $filesDeleted = 0;
            foreach ($this->minifyFiles as $folder) {
                $filesDeleted = $filesDeleted + $this->rrmdir("{$cacheDir}{$folder}/");
            }

            wp_die('1');
        }

        public function rrmdir($path) {
            if (!is_dir($path)) {
                return false;
            }

            $objects = scandir($path);
            $count = 0;
            foreach ($objects as $object) {
                if ('.' != $object && '..' != $object) {
                    if (filetype($path . '/' . $object) == 'dir') {
                        $count = $count + $this->rrmdir($path . '/' . $object);
                    } else {
                        $count ++;
                        unlink($path . '/' . $object);
                    }
                }
            }

            return $count;
        }

        public function formatBytes($bytes, $precision = 2) {
            $units = array('B', 'KB', 'MB', 'GB', 'TB');
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log( $bytes) : 0) / log(1024));
            $pow = min($pow, count( $units) - 1);
            $bytes /= (1 << (10 * $pow));

            return round($bytes, $precision) . ' ' . $units[$pow];
        }

        public function addCompression($existingRules) {
            $customRules = '
                ## START Compress static files ##
                <IfModule mod_deflate.c>
                    <IfModule mod_filter.c>
                        AddOutputFilterByType DEFLATE            application/atom+xml
                        AddOutputFilterByType DEFLATE            application/javascript
                        AddOutputFilterByType DEFLATE            application/x-javascript
                        AddOutputFilterByType DEFLATE            application/json
                        AddOutputFilterByType DEFLATE            application/rss+xml
                        AddOutputFilterByType DEFLATE            application/vnd.ms-fontobject
                        AddOutputFilterByType DEFLATE            application/x-font
                        AddOutputFilterByType DEFLATE            application/x-font-opentype
                        AddOutputFilterByType DEFLATE            application/x-font-otf
                        AddOutputFilterByType DEFLATE            application/x-font-truetype
                        AddOutputFilterByType DEFLATE            application/x-font-ttf
                        AddOutputFilterByType DEFLATE            application/xhtml+xml
                        AddOutputFilterByType DEFLATE            application/xml
                        AddOutputFilterByType DEFLATE            font/otf
                        AddOutputFilterByType DEFLATE            font/ttf
                        AddOutputFilterByType DEFLATE            font/opentype
                        AddOutputFilterByType DEFLATE            image/svg+xml
                        AddOutputFilterByType DEFLATE            image/x-icon
                        AddOutputFilterByType DEFLATE            text/css
                        AddOutputFilterByType DEFLATE            text/html
                        AddOutputFilterByType DEFLATE            text/javascript
                        AddOutputFilterByType DEFLATE            text/plain
                        AddOutputFilterByType DEFLATE            text/x-component
                        AddOutputFilterByType DEFLATE            text/xhtml
                        AddOutputFilterByType DEFLATE            text/xml
                    </IfModule>
                    <IfModule mod_setenvif.c>
                        <IfModule mod_headers.c>
                            SetEnvIfNoCase ^(Accept-EncodXng|X-cept-Encoding|X{15}|~{15}|-{15})$ ^((gzip|deflate)\s*,?\s*)+|[X~-]{4,13}$ HAVE_Accept-Encoding
                            RequestHeader append Accept-Encoding "gzip,deflate" env=HAVE_Accept-Encoding
                        </IfModule>
                    </IfModule>
                    BrowserMatch ^Mozilla/4 gzip-only-text/html
                    BrowserMatch ^Mozilla/4\.0[678] no-gzip
                    #BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
                    SetEnvIfNoCase Request_URI \.(?:gif|jpe?g|png)$ no-gzip dont-vary
                    Header append Vary User-Agent env=!dont-vary
                </IfModule>
                ## END Compress static files ##
            ';

            return $existingRules . $customRules;
        }

        public function addCachingHeaders($existingRules) {
            $customRules = '
                ## START Caching files in the browser ##
                <IfModule mod_expires.c>
                    ExpiresActive On
                
                    # Perhaps better to whitelist expires rules? Perhaps.
                    ExpiresDefault                              "access plus 1 month"
                
                    # cache.appcache needs re-requests in FF 3.6 (thanks Remy ~Introducing HTML5)
                    ExpiresByType text/cache-manifest           "access plus 0 seconds"
                
                    # Your document html
                    ExpiresByType text/html                     "access plus 0 seconds"
                
                    # Data
                    ExpiresByType text/xml                      "access plus 0 seconds"
                    ExpiresByType application/xml               "access plus 0 seconds"
                    ExpiresByType application/json              "access plus 0 seconds"
                    ExpiresByType application/pdf               "access plus 0 seconds"
                
                    # Feed
                    ExpiresByType application/rss+xml           "access plus 1 hour"
                    ExpiresByType application/atom+xml          "access plus 1 hour"
                
                    # Webfonts
                    ExpiresByType application/x-font-ttf        "access plus 1 month"
                    ExpiresByType font/opentype                 "access plus 1 month"
                    ExpiresByType application/x-font-woff       "access plus 1 month"
                    ExpiresByType application/x-font-woff2      "access plus 1 month"
                    ExpiresByType image/svg+xml                 "access plus 1 month"
                    ExpiresByType application/vnd.ms-fontobject "access plus 1 week"
                
                    # Media: images, video, audio
                    ExpiresByType image/gif                     "access plus 1 month"
                    ExpiresByType image/png                     "access plus 1 month"
                    ExpiresByType image/PNG                     "access plus 1 month"
                    ExpiresByType image/jpeg                    "access plus 1 month"
                    ExpiresByType image/jpg                     "access plus 1 month"
                    ExpiresByType video/ogg                     "access plus 1 month"
                    ExpiresByType audio/ogg                     "access plus 1 month"
                    ExpiresByType video/mp4                     "access plus 1 month"
                    ExpiresByType video/webm                    "access plus 1 month"
                
                    # HTC files  (css3pie)
                    ExpiresByType text/x-component              "access plus 1 month"
                
                    # CSS and JavaScript
                    ExpiresByType text/css                      "access plus 3 week"
                    ExpiresByType application/javascript        "access plus 3 week"
                
                    # Favicon (cannot be renamed)
                    ExpiresByType image/x-icon                  "access plus 1 week"
                    ExpiresByType application/x-shockwave-flash "access plus 1 week"
                </IfModule>
                <ifModule mod_headers.c>
                    <filesMatch "\.(ico|pdf|flv|jpg|jpeg|jpe?g|png|PNG|gif|swf|mp3|mp4)$">
                        Header set Cache-Control "public"
                    </filesMatch>
                    <filesMatch "\.(css)$">
                        Header set Cache-Control "public"
                    </filesMatch>
                    <filesMatch "\.(js)$">
                        Header set Cache-Control "public"
                    </filesMatch>
                    <filesMatch "\.(x?html?|php)$">
                        Header set Cache-Control "public, must-revalidate"
                    </filesMatch>
                </ifModule>
                # ----------------------------------------------------------------------
                # Proper MIME type for all files
                # ----------------------------------------------------------------------
                
                AddType application/javascript                   js jsonp
                AddType application/json                         json
                
                # Audio
                AddType audio/ogg                                oga ogg
                AddType audio/mp4                                m4a f4a f4b
                
                # Video
                AddType video/ogg                                ogv
                AddType video/mp4                                mp4 m4v f4v f4p
                AddType video/webm                               webm
                AddType video/x-flv                              flv
                
                # SVG
                #   Required for svg webfonts on iPad
                #   twitter.com/FontSquirrel/status/14855840545
                AddType     image/svg+xml                        svg svgz
                AddEncoding gzip                                 svgz
                
                # Webfonts
                AddType application/vnd.ms-fontobject            eot
                AddType application/x-font-ttf                   ttf ttc
                AddType font/opentype                            otf
                AddType application/x-font-woff                  woff
                
                # Assorted types
                AddType image/x-icon                             ico
                AddType image/webp                               webp
                AddType text/cache-manifest                      appcache manifest
                AddType text/x-component                         htc
                AddType application/xml                          rss atom xml rdf
                AddType application/x-chrome-extension           crx
                AddType application/x-opera-extension            oex
                AddType application/x-xpinstall                  xpi
                AddType application/octet-stream                 safariextz
                AddType application/x-web-app-manifest+json      webapp
                AddType text/x-vcard                             vcf
                AddType application/x-shockwave-flash            swf
                AddType text/vtt                                 vtt
                ## END Caching files in the browser ##
            ';

            return $existingRules . $customRules;
        }

        public function rewriteHtaccess() {
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
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