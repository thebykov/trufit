<?php

/*
Name:    gdr2_Core
Version: 2.8.8
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.dev4press.com/libs/gdr2/
Info:    Core class with static functions with extra functionality classes

== Copyright ==
Copyright 2008 - 2015 Milan Petrovic (email: milan@gdragon.info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Library version and build
 */
if (!defined('GDR2_VERSION')) { 
    define('GDR2_VERSION', '2.8.8');
    define('GDR2_BUILD', '1830');
}

/**
 * Main library constants
 */
if (!defined('GDR2_EOL')) { define('GDR2_EOL', "\r\n"); }
if (!defined('GDR2_TAB')) { define('GDR2_TAB', "\t"); }
if (!defined('GDR2_EOL2')) { define('GDR2_EOL2', "\r\n\r\n"); }
if (!defined('GDR2_CHARSET')) { define('GDR2_CHARSET', get_option('blog_charset')); }

if (!class_exists('gdr2_Core')) {
    /**
     * Collection of useful functions.
     */
    class gdr2_Core {
        /**
         * Count posts authors.
         *
         * @param array $status statuses to include
         * @return int number of authors
         */
        public static function count_authors($status = array("publish")) {
            global $wpdb;

            $sql = sprintf("select count(distinct post_author) from %s where post_status in ('%s')", $wpdb->posts, join("', '", $status));
            return intval($wpdb->get_var($sql));
        }

        /**
         * Unload all jQueryUI components on the page.
         */
        public static function unload_jquery() {
            wp_deregister_script('thesis-admin-js');
            wp_deregister_script('aiow-plugin-script3');
            wp_deregister_script('aiow-plugin-script4');
        }

        /**
         * Scans the folder and returns all the files and folder in it.
         *
         * @param string $path path of the folder to scan
         * @param string $filter what to include: all, files, folders
         * @param array $extensions list of estensions to include, empty includes all
         * @param string $reg_expr regular expression to match names
         * @return array list of files and folders in the folder
         */
        public static function scan_dir($path, $filter = 'files', $extensions = array(), $reg_expr = '') {
            $extensions = (array)$extensions;
            $filter = !in_array($filter, array('folders', 'files', 'all')) ? 'files' : $filter;
            $path = str_replace('\\', '/', $path);

            $files = array();
            $final = array();

            if (file_exists($path)) {
                $files = scandir($path);

                $path = rtrim($path, '/').'/';
                foreach ($files as $file) {
                    $ext = get_extension($file);

                    if (empty($extensions) || in_array($ext, $extensions)) {
                        if (substr($file, 0, 1) != '.') {
                            if ((is_dir($path.$file) && (in_array($filter, array('folders', 'all')))) ||
                                (is_file($path.$file) && (in_array($filter, array('files', 'all')))) ||
                                ((is_file($path.$file) || is_dir($path.$file)) && (in_array($filter, array('all'))))) {
                                    if ($reg_expr == '') {
                                        $final[] = $file;
                                    } else if (preg_match($reg_expr, $file)) {
                                        $final[] = $file;
                                    }
                            }
                        }
                    }
                }
            }

            return $final;
        }

        /**
         * Get size of the path with recursion.
         *
         * @param string $path
         * @param array $extensions list of estensions to include, empty includes all
         * @return array size, count and directory count
         */
        public static function dir_size($path, $extensions = array()) {
            $totalsize = 0;
            $totalcount = 0;
            $dircount = 0;

            if ($handle = opendir($path)) {
                while (false !== ($file = readdir($handle))) {
                    $nextpath = $path . '/' . $file;

                    if ($file != '.' && $file != '..' && !is_link($nextpath)) {
                        if (is_dir ($nextpath)) {
                            $dircount++;
                            $result = gdr2_Core::dir_size($nextpath, $extensions);
                            $totalsize+= $result['size'];
                            $totalcount+= $result['count'];
                            $dircount+= $result['directories'];
                        } else if (is_file($nextpath)) {
                            $ext = get_extension($file);

                            if (empty($extensions) || in_array($ext, $extensions)) {
                                $totalsize += filesize ($nextpath);
                                $totalcount++;
                            }
                        }
                    }
                }
            }

            closedir($handle);

            return array('size' => $totalsize, 'count' => $totalcount, 'directories' => $dircount);
        }

        /**
         * Adds characters to set length before or after main text.
         *
         * @param string $text original number
         * @param int $len max number of zeroes
         * @param string $character chracter to use for fill
         * @param bool $before add it before (true) or after (false)
         * @return string filled text
         */
        public static function fill_length($text, $len, $character = '0', $before = true) {
            $count = strlen($text);
            $zeros = "";

            for ($i = 0; $i < $len - $count; $i++) {
                $zeros.= $character;
            }

            if ($before) {
                return $zeros.$text;
            } else {
                return $text.$zeros;
            }
        }

        /**
         * Check if the file is writeable and attempt to correct it.
         *
         * @param string $path file path to check
         * @return bool true if the file is writable
         */
        public static function is_writable($path) {
            if (!is_writable($path)) {
                if (!@chmod($path, 0644)) {
                    $dir_path = dirname($path);

                    if (!is_writable($dir_path)) {
                        if (!@chmod($dir_path, 0644)) {
                            return false;
                        }
                    }
                }
            }

            return true;
        }

        /**
         * Detect if visitor is a bot.
         *
         * @return bool is spider bot or not
         */
        public static function is_bot() {
            $spiders = array(
                "FAST", "WebBug", "Spade", "ZyBorg", "rabaz", "Baiduspider", "TechnoratiSnoop", "Rankivabot",
                "Sogou web spider", "WebAlta Crawler", "www.galaxy.com", "Slurp", "msnbot", "appie", "TECNOSEEK",
                "InfoSeek", "WebFindBot", "girafabot", "crawler", "inktomi", "looksmart", "URL_Spider_SQL",
                "Firefly", "NationalDirectory", "Teoma", "alexa", "froogle", "AdsBot-Google", "ia_archiver",
                "Scooter", "Ask Jeeves", "Baiduspider", "Exabot", "FAST Enterprise Crawler", "FAST-WebCrawler",
                "Gigabot", "Mediapartners-Google", "Google Desktop", "Feedfetcher-Google", "Googlebot",
                "heise-IT-Markt-Crawler", "heritrix", "ichiro", "MJ12bot", "MetagerBot", "OmniExplorer_Bot",
                "msnbot-NewsBlogs", "msnbot", "msnbot-media", "NG-Search", "NutchCVS", "Seekbot", "SynooBot",
                "Sensis Web Crawler", "SEO search Crawler", "Seoma", "SEOsearch", "voyager", "W3 SiteSearch Crawler",
                "crawleradmin.t-info@telekom.de", "TurnitinBot", "W3C-checklink", "yacybot", "Yahoo-MMCrawler",
                "Yahoo! DE Slurp", "Yahoo! Slurp", "YahooSeeker", "Pingdom.com");

            $spiders = apply_filters('gdr2_core_bots_list', $spiders);
            $str = $_SERVER['HTTP_USER_AGENT'];

            foreach($spiders as $spider) {
                if (preg_match("/".$spider."/", $str)) {
                    return true;
                }
            }

            return false;
        }

        /**
         * Get IP address of the visitor.
         *
         * @return string IP address
         */
        public static function visitor_ip() {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            $ip = preg_replace('/[^0-9a-fA-F:., ]/', '', $ip);

            return trim($ip);
        }

        /**
         * Finds image url from text.
         *
         * @param string $text text to search
         * @return string image url
         */
        public static function get_image_from_text($text) {
            $imageurl = '';
            preg_match('/<\s*img [^\>]*src\s*=\s*[\""\']?([^\""\'>]*)/i', $text, $matches);

            if (is_array($matches) && isset($matches[1])) {
                $imageurl = $matches[1];
            }

            return $imageurl;
        }

        /**
         * Get the function call backtrace.
         *
         * @return array all functions calls leading to the place of call to this one
         */
        public static function get_caller_backtrace() {
            if (!is_callable('debug_backtrace')) return array();
            $bt = debug_backtrace();
            $caller = array();

            $bt = array_reverse($bt);
            foreach ((array)$bt as $call) {
                $function = $call['function'];
                if (isset($call['class'])) {
                    $function = $call['class']."->$function";
                }
                $caller[] = $function;
            }

            unset($caller[count($caller) - 1]);
            return $caller;
        }

        /**
         * Formats byte based size into readable string
         *
         * @param int $size size in bytes
         * @return string formated string
         */
        public static function size_format($size) {
            $size = intval($size);

            if (strlen($size) <= 9 && strlen($size) >= 7) {
                $size = number_format($size / 1048576, 1);
                return "$size MB";
            } else if (strlen($size) >= 10) {
                $size = number_format($size / 1073741824, 1);
                return "$size GB";
            } else if (strlen($size) <= 6 && strlen($size) >= 4) {
                $size = number_format($size / 1024, 1);
                return "$size KB";
            } else {
                return "$size B";
            }
        }

        /**
         * Recalcuates size from weight based string.
         *
         * @param string $size input string with k/m/g/t ending
         * @return int resulting size
         */
        public static function recalculate_size($size) {
            switch (strtolower(substr($size, -1))) {
                case "k":
                    return $size * 1024;
                    break;
                case "m":
                    return $size * 1024 * 1024;
                    break;
                case "g":
                    return $size * 1024 * 1024 * 1024;
                    break;
                case "t":
                    return $size * 1024 * 1024 * 1024 * 1024;
                    break;
            }
            return $size;
        }

        /**
         * Trims the text to given number of words.
         *
         * @param string $text text to trim
         * @param int $words_count words to trim to
         * @return string trimmed text
         */
        public static function trim_to_words($text, $words_count = 10) {
            if ($words_count > 0) {
                $words = explode(" ", $text, $words_count + 1);

                if (count($words) > $words_count) {
                    $words = array_slice($words, 0, $words_count);
                    $text = implode(" ", $words)."...";
                }
            }
            return $text;
        }

        /**
         * Remove element value from the array.
         *
         * @param array $arr start array
         * @param mixed $val value to remove
         * @param bool $preserve_keys preserve keys or reindex
         * @return array new array
         */
        public static function unset_by_value($arr, $val, $preserve_keys = true) {
            if (empty($arr) || !is_array($arr)) {
                return false;
            }

            while (in_array($val, $arr)) {
                unset($arr[array_search($val, $arr)]);
            }

            $arr = $preserve_keys ? $arr : array_values($arr);
            return (array)$arr;
        }

        /**
         * Adds all new settings array elements and remove obsolete ones.
         *
         * @param array $old old settings
         * @param array $new new settings
         * @param array $skip elements to skip during unset
         * @return array upgraded array
         */
        public static function upgrade_settings($old, $new, $skip = array('__core__', '__date__')) {
            foreach ($new as $key => $value) {
                if (!isset($old[$key])) {
                    $old[$key] = $value;
                }
            }

            $unset = array();
            foreach ($old as $key => $value) {
                if (!isset($new[$key]) && !in_array($key, $skip)) {
                    $unset[] = $key;
                }
            }

            if (!empty($unset)) {
                foreach ($unset as $key) {
                    unset($old[$key]);
                }
            }

            return $old;
        }

        /**
         * Insert into meta data table array with meta key/value pairs.
         *
         * @param string $table database table to insert into
         * @param int $id id value for the owner record
         * @param array $data array with data to insert
         * @param string $id_name id column name
         * @param string $key_name key column name
         * @param string $value_name value column name
         */
        public static function db_insert_meta($table, $id, $data, $id_name = 'post_id', $key_name = 'meta_key', $value_name = "meta_value") {
            global $wpdb;

            foreach ($data as $key => $val) {
                $val = maybe_serialize($val);
                $insert = array(
                    $id_name => $id,
                    $key_name => $key,
                    $value_name => $val
                );
                $wpdb->insert($table, $insert);
            }
        }

        /**
         * Deactivates any plugin.
         *
         * @param string $plugin_name name of the plugin to deactivate
         */
        public static function deactivate_plugin($plugin_name) {
            $current = get_option('active_plugins');

            if(in_array($plugin_name, $current)) {
                array_splice($current, array_search($plugin_name, $current), 1);
            }

            update_option('active_plugins', $current);
        }

        /**
         * Finds all users with specified role.
         *
         * @param string $role role to find
         * @return array found users
         */
        public static function get_users_with_role($role) {
            $wp_user_search = new WP_User_Search('', '', $role);
            return $wp_user_search->get_results();
        }

        /**
         * Gets current category id.
         *
         * @global object $wp_query WP query object
         * @return int category id
         */
        public static function get_current_category_id() {
            global $wp_query;

            if (!$wp_query->is_category) {
                return 0;
            }

            $cat_obj = $wp_query->get_queried_object();
            return $cat_obj->term_id;
        }

        /**
         * Get all subcategories of a category.
         *
         * @param int $cat category id
         * @param bool $hide_empty hid or show empty categories
         * @return array subcatories
         */
        public static function get_subcategories_ids($cat, $hide_empty = true) {
            $categories = get_categories(array('child_of' => $cat, 'hide_empty' => $hide_empty));
            $results = array();

            foreach ($categories as $c) {
                $results[] = $c->cat_ID;
            }

            return $results;
        }

        /**
         * Get all custom fields from post meta.
         *
         * @global object $wpdb database object
         * @param bool $hidden include hidden fields
         * @return array field names
         */
        public static function get_all_custom_fieds($hidden = true) {
            global $wpdb;

            $sql = "select distinct meta_key from ".$wpdb->postmeta;
            if (!$hidden) {
                $sql.= " where SUBSTR(meta_key, 1, 1) != '_'";
            }

            $elements = $wpdb->get_results($sql);
            $result = array();
            foreach ($elements as $el) {
                $result[] = $el->meta_key;
            }

            return $result;
        }

        /**
         * Checks if the php is running in safe mode.
         *
         * @return bool
         */
        public static function php_in_safe_mode() {
            return (@ini_get('safe_mode') == 'On' || @ini_get('safe_mode') === 1) ? TRUE : FALSE;
        }

        /**
         * Returns mySQL version.
         *
         * @param bool $full return full version string or only main version number
         * @return string mySQL version
         */
        public static function mysql_version($full = false) {
            global $wpdb;
            $version = $wpdb->db_version();

            if ($full) {
                return $version;
            } else {
                return substr($version, 0, 1);
            }
        }

        /**
         * Get only array elements with keys starting with filter.
         *
         * @param array $input array to process
         * @param string $filter string to use to filter array
         * @param bool $trim_filter new array keys should contain filter part
         * @return array filtered array
         */
        public static function array_filter($input, $filter, $trim_filter = false) {
            $result = array();

            foreach ($input as $key => $value) {
                if (substr($key, 0, strlen($filter)) == $filter) {
                    $new_key = $key;

                    if ($trim_filter) {
                        $new_key = substr($key, strlen($filter));
                    }

                    $result[$new_key] = $value;
                }
            }

            return $result;
        }

        /**
         * Returns PHP version.
         *
         * @param bool $full return full version string or only main version number
         * @return string PHP version
         */
        public static function php_version($full = false) {
            if ($full) {
                return phpversion();
            } else {
                return substr(phpversion(), 0, 1);
            }
        }
    }

    add_action('init', 'gdr2_load_translations');

    function gdr2_load_translations() {
        $l = get_locale();
        if(!empty($l)) {
            $moFile = dirname(__FILE__).'/languages/gdr2-'.$l.'.mo';
            if (@file_exists($moFile) && is_readable($moFile)) {
                load_textdomain('gdr2', $moFile);
            }
        }
    }
}

if (!class_exists('gdr2_ObjectSort')) {
    /**
     * Object for sorting other object.
     */
    class gdr2_ObjectSort {
        var $properties;
        var $sorted;

        function  __construct($objects_array, $properties = array()) {
            if (count($properties) > 0) {
                $this->properties = $properties;
                usort($objects_array, array(&$this, 'array_compare'));
            }
            $this->sorted = $objects_array;
        }

        function array_compare($one, $two, $i = 0) {
            $column = $this->properties[$i]['property'];
            $order = $this->properties[$i]['order'];

            if ($one->$column == $two->$column) {
                if ($i < count($this->properties) - 1) {
                    $i++;
                    return $this->array_compare($one, $two, $i);
                } else {
                    return 0;
                }
            }

            if (strtolower($order) == 'asc') {
                return ($one->$column < $two->$column) ? -1 : 1;
            } else {
                return ($one->$column < $two->$column) ? 1 : -1;
            }
        }
    }
}

if (!class_exists('gdrClass')) {
    /**
     * Similar to stdClass but can take array as argument and fill object with property/value pairs.
     */
    class gdrClass {
        function __construct($args = array()) {
            if (is_array($args) && !empty($args)) {
                foreach ($args as $key => $value) {
                    $this->$key = $value;
                }
            }
        }

        function __clone() {
            foreach($this as $key => $val) {
                if(is_object($val)||(is_array($val))){
                    $this->{$key} = unserialize(serialize($val));
                }
            }
        }
    }
}

if (!class_exists('gdrBase')) {
    /**
     * Empty base class similar to stdClass with some extra functionalities.
     */
    class gdrBase {
        function __construct() { }

        function __clone() {
            foreach ($this as $key => $val) {
                if (is_object($val) || (is_array($val))){
                    $this->{$key} = unserialize(serialize($val));
                }
            }
        }

        public function to_array() {
            return (array)$this;
        }

        public function from_array($arr) {
            foreach ($arr as $key => $value) {
                $this->$key = $value;
            }
        }
    }
}

if (!function_exists('gdr2_include')) {
    /**
     * Load gdr2 file specified by name
     *
     * @param string $name file name, no prefix and extension
     * @param bool $plugin load from plugins sub-folder
     * @param string $base_path base folder location
     */
    function gdr2_include($name, $plugin = false, $base_path = '') {
        if ($plugin) {
            require_once($base_path.'plugin/gdr2.'.$name.'.php');
        } else {
            require_once($base_path.'gdr2.'.$name.'.php');
        }
    }
}

require_once('gdr2.fnc.php');
require_once('gdr2.log.php');
require_once('gdr2.cache.php');

?>