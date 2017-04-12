<?php

/*
Name:    gdr2_Fnc
Version: 2.8.8
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.dev4press.com/libs/gdr2/
Info:    Collection of functions

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

if (!function_exists('is_odd')) {
    /**
     * Check if the number is odd or even.
     *
     * @param int $number number to check
     * @return bool true for odd, false for even number
     */
    function is_odd($number) {
        return $number&1;
    }
}

if (!function_exists('is_divisible')) {
    /**
     * Check if one number is divisible by another
     *
     * @param int $number number to check if is divisible
     * @param int $by_number to check if is divisible by
     * @return bool true is divisible, false is not
     */
    function is_divisible($number, $by_number) {
        return $number%$by_number == 0;
    }
}

if (!function_exists('php_in_safe_mode')) {
    /**
     * Checks if the php is running in safe mode.
     *
     * @return bool
     */
    function php_in_safe_mode() {
        return (@ini_get('safe_mode') == 'On' || @ini_get('safe_mode') === 1) ? TRUE : FALSE;
    }
}

if (!function_exists('is_bot')) {
    /**
     * Detect if visitor is a bot.
     *
     * @return bool is spider bot or not
     */
    function is_bot() {
        return gdr2_Core::is_bot();
    }
}

if (!function_exists('get_extension')) {
    /**
     * Get extension part of the file name.
     *
     * @return string extension
     */
    function get_extension($input) {
        return pathinfo($input, PATHINFO_EXTENSION);
    }
}

if (!function_exists('get_authors')) {
    /**
     * Get the list of all blog authors.
     *
     * @return array list of authors
     */
    function get_authors() {
        global $wpdb;

        $sql = sprintf("select distinct u.*, count(p.ID) as count from %s u inner join %s p on p.post_author = u.ID group by u.ID order by u.ID asc", $wpdb->users, $wpdb->posts);

        return $wpdb->get_results($sql);
    }
}

if (!function_exists('is_msie_6')) {
    /**
     * Determines if the browser accessing the page is MS Internet Explorer 6.
     *
     * @return bool true if the browser is IE6
     */
    function is_msie_6() {
        return is_msie_version(6, false);
    }
}

if (!function_exists('is_msie_version')) {
    /**
     * Determines if the browser accessing the page is MS Internet Explorer.
     *
     * @param int $version version of IE to detect
     * @param bool $exclusive only that version or to include all lower than wanted version
     * @return bool true if the browser is requested IE version
     */
    function is_msie_version($version = 6, $exclusive = true) {
        $agent = $_SERVER['HTTP_USER_AGENT'];

        if (preg_match("/msie/i", $agent) && !preg_match("/opera/i", $agent)) {
            $val = explode(' ', stristr($agent, 'msie'));
            $v = intval(substr($val[1], 0, 1));

            if ($exclusive) {
                return $v == $version;
            } else {
                return $v <= $version;
            }
        }

        return false;
    }
}

if (!function_exists('is_windows')) {
    /**
     * Determines if the server OS is Windows.
     *
     * @return bool true if the server is Windows
     */
    function is_windows() {
        $os = strtolower(php_uname('s'));
        return strpos($os, 'windows') !== false;
    }
}

if (!function_exists('get_msie_version')) {
    /**
     * Get MS Internet Explorer version.
     *
     * @return int if IE get version number, if not return 0
     */
    function get_msie_version() {
        $agent = $_SERVER['HTTP_USER_AGENT'];

        if (preg_match("/msie/i", $agent) && !preg_match("/opera/i", $agent)) {
            $val = explode(' ', stristr($agent, 'msie'));
            $v = intval(substr($val[1], 0, 1));

            return $v;
        }

        return 0;
    }
}

if (!function_exists('wp_redirect_self')) {
    /**
     * Redirects back to the same page.
     */
    function wp_redirect_self() {
        wp_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
    }
}

if (!function_exists('prefill_zeros')) {
    /**
     * Prepend zeros to specified lenght.
     *
     * @param string $text string to add zeros to
     * @param int $len number of zeros to prepend
     * @param string $zero what to use for zero
     * @param bool $before add before or after
     * @return string full string
     */
    function prefill_zeros($text, $len, $zero = '0', $before = true) {
        return gdr2_Core::fill_length($text, $len, $zero, $before);
    }
}

if (!function_exists('prefill_attributes')) {
    /**
     * Adds missing default parameters into parameters array.
     *
     * @param array $defaults default parameters
     * @param array $attributes input parameters
     * @return array result
     */
    function prefill_attributes($defaults, $attributes) {
        $attributes = (array)$attributes;
        $result = array();

        foreach($defaults as $name => $default) {
            if (array_key_exists($name, $attributes)) {
                $result[$name] = $attributes[$name];
            } else {
                $result[$name] = $default;
            }
        }

        return $result;
    }
}

if (!function_exists('esc_xml')) {
    /**
     * Escaping for XML blocks.
     *
     * @param string $text
     * @return string with escaped XML entities.
     */
    function esc_xml($text) {
        $safe_text = str_replace(array('&', '"', "'", '<', '>'), array ('&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;'), $text);
        return apply_filters('esc_xml', $safe_text, $text);
    }
}

if (!function_exists('gdr2_split_textarea')) {
    /**
     * Safe split of text block into lines of array
     *
     * @param string $value input text to split
     * @param bool $empty_lines keep or remove empty lines
     * @return array list of elements
     */
    function gdr2_split_textarea($value, $empty_lines = false) {
        $elements = preg_split("/[\n\r]/", $value);

        if (!$empty_lines) {
            $results = array();

            foreach ($elements as $el) {
                if (trim($el) != "") {
                    $results[] = $el;
                }
            }

            return $results;
        } else {
            return $elements;
        }
    }
}

if (!function_exists('gdr2_wp_flush_rewrite_rules')) {
    /**
     * Flush WordPress rewrite rules
     */
    function gdr2_wp_flush_rewrite_rules() {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }
}

if (!function_exists('gdr2_wp_flush')) {
    /**
     * Flush WordPress caching entities
     *
     * @param bool $cache wp objects cache
     * @param bool $queries wp db logged queries
     */
    function gdr2_wp_flush($cache = true, $queries = true) {
        if ($cache) {
            wp_cache_flush();
        }

        if ($queries) {
            global $wpdb;
            if (is_array($wpdb->queries) && !empty($wpdb->queries)) {
                unset($wpdb->queries);
                $wpdb->queries = array();
            }
        }
    }
}

if (!function_exists('gdr2_days_between')) {
    /**
     * Get number of days between two dates.
     *
     * @param timestamp $end end time
     * @param timestamp $start start time
     * @return int number of days
     */
    function gdr2_days_between($end, $start) {
        $year_end = date('Y', $end);
        $year_start = date('Y', $start);
        $days_end = date('z', $end);
        $days_start = date('z', $start);

        if ($year_end == $year_start) {
            return $days_end - $days_start;
        } else {
            return $days_end - $days_start + ($year_end - $year_start) * 365;
        }
    }
}

if (!function_exists('gdr2_from_url_to_library')) {
    /**
    * Add file to media library from any URL.
    *
    * @param type $url url to get image from
    * @param type $post_id post to attach it to
    * @return mixed id in library on success, wp_error on failure 
    */
    function gdr2_from_url_to_library($url, $post_id = 0) {
        $downloaded = download_url($url);

        if (is_wp_error($downloaded)) {
            return $downloaded;
        }

        $real_file_name = basename($url);
        $upload_folder = wp_upload_dir();
        $file_name = wp_unique_filename($upload_folder['path'], $real_file_name);
        $upload_path = $upload_folder['path'].'/'.$file_name;

        rename($downloaded, $upload_path);

        $wp_filetype = wp_check_filetype(basename($file_name), null );
        $attachment = array('post_mime_type' => $wp_filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
            'post_content' => '', 'post_status' => 'inherit');
        $attach_id = wp_insert_attachment($attachment, $upload_path, $post_id);

        if (!is_wp_error($attach_id)) {
            $attach_data = wp_generate_attachment_metadata($attach_id, $upload_path);
            wp_update_attachment_metadata($attach_id, $attach_data);
        }

        return $attach_id;
    }
}

if (!function_exists('gdr2_post_featured_image_url')) {
    /**
     * Get featured image for a post if set.
     *
     * @param int $post_id post to get image from
     * @param string size name
     * @return string url for the image
     */
    function gdr2_post_featured_image_url($post_id, $size = 'thumbnail') {
        $post_thumbnail_id = get_post_thumbnail_id($post_id);

        if ($post_thumbnail_id) {
            $image = wp_get_attachment_image_src($post_thumbnail_id, $size);

            if (is_array($image) && !empty($image)) {
                return $image[0];
            }
        }

        return '';
    }
}

if (!function_exists('wp_count_users')) {
    /**
     * Get count of registered users.
     *
     * @return int number of registered users
     */
    function wp_count_users() {
        global $wpdb;
        return intval($wpdb->get_var('SELECT COUNT(ID) FROM '.$wpdb->users));
    }
}

if (!function_exists('gdr2_array_property_to_array')) {
    /**
     * Convert array with objects to array containing only one property from the object.
     *
     * @param array $arr array with objects
     * @param string $property name of the property to get from objects
     * @return array 
     */
    function gdr2_array_property_to_array($arr, $property) {
        $ids = array();

        foreach ($arr as $a) {
            $ids[] = $a->$property;
        }

        return $ids;
    }
}

if (!function_exists('gdr2_object_to_array')) {
    /**
     * Converts object to array.
     *
     * @param obkect $object object to convert to array
     * @return array converted array
     */
    function gdr2_object_to_array($object) {
        if (!is_object($object) && !is_array($object)) {
            return $object;
        }

        if (is_object($object)) {
            $object = get_object_vars($object);
        }

        return array_map('gdr2_object_to_array', $object);
    }
}

if (!function_exists('gdr2_entity_decode')) {
    /**
     * Decoded string with HTML and quote entities.
     *
     * @param string $content string to decode
     * @param string $quote_style type of quotes
     * @param string $charset charset to use
     * @return string decoded string 
     */
    function gdr2_entity_decode($content, $quote_style = null, $charset = null) {
        if (null === $quote_style) $quote_style = ENT_QUOTES;
        if (null === $charset) $charset = GDR2_CHARSET;

        return html_entity_decode($content, $quote_style, $charset);
    }
}

if (!function_exists('gdr2_local_to_server_timestamp')) {
    /**
     * Return server timestamp based on the time zone setting from local timestamp.
     *
     * @return int server timestamp
     */
    function gdr2_local_to_server_timestamp($local) {
        $server = $local - get_option('gmt_offset') * 3600;
        return $server;
    }
}

if (!function_exists('gdr2_server_to_local_timestamp')) {
    /**
     * Return local timestamp based on the time zone setting from server timestamp.
     *
     * @return int local timestamp
     */
    function gdr2_server_to_local_timestamp($server) {
        $local = $server + get_option('gmt_offset') * 3600;
        return $local;
    }
}

if (!function_exists('gdr2_current_timestamp')) {
    /**
     * Return current timestamp based on the time zone setting.
     *
     * @return int current timestamp
     */
    function gdr2_current_timestamp($gmt = false) {
        $blogtime = current_time('mysql', $gmt);
        list($year, $month, $day, $hour, $minute, $second) = preg_split('/[^0-9]/', $blogtime);
        return mktime($hour, $minute, $second, $month, $day, $year);
    }
}

if (!function_exists('gdr2_current_date')) {
    /**
     * Return current date based on the time zone setting.
     *
     * @param string $format date format string
     * @return string formated date
     */
    function gdr2_current_date($format) {
        if ($format == 'mysql') $format = 'Y-m-d H:i:s';
        return date($format, gdr2_current_timestamp());
    }
}

if (!function_exists('gdr2_remove_from_array_by_value')) {
    /**
     * Remove from array by value.
     *
     * @param array $arr input array
     * @param string $val value to remove
     * @param bool $preserve preserver keys
     * @return array new array
     */
    function gdr2_remove_from_array_by_value($arr, $val, $preserve = false) {
        return gdr2_Core::unset_by_value($arr, $val, $preserve);
    }
}

if (!function_exists('gdr2_clone_r')) {
    /**
     * Attempt to clone value tha can be mix of deep arrays with objects using recursion.
     *
     * @param mixed $value what to clone
     * @return mixed cloned value
     */
    function gdr2_clone_r($value) {
        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = gdr2_clone_r($val);
            }

            return $value;
        } else if (is_object($value)) {
            return clone($value);
        } else {
            return $value;
        }
    }
}

if (!function_exists('gdr2_clone')) {
    /**
     * Attempt to clone value. If it's not object, value is returned.
     *
     * @param mixed $value what to clone
     * @return mixed cloned value
     */
    function gdr2_clone($value) {
        return is_object($value) ? clone($value) : $value;
    }
}

if (!function_exists('gdr2_strip_tags')) {
    /**
     * Strip tags through array.
     *
     * @param mixed $value what to strip tags from (not an object)
     * @return mixed striped value
     */
    function gdr2_strip_tags($value) {
        if (is_object($value)) {
            return $value;
        }

        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = gdr2_strip_tags($val);
            }

            return $value;
        } else {
            return strip_tags($value);
        }
    }
}

if (!function_exists('gdr2_current_url')) {
    /**
     * Get URL of the current page.
     *
     * @return string URL of the current page
     */
    function gdr2_current_url() {
        $s = empty($_SERVER['HTTPS']) ? '' : ($_SERVER['HTTPS'] == 'on') ? 's' : '';
        $protocol = gdr2_strleft(strtolower($_SERVER['SERVER_PROTOCOL']), '/').$s;
        $port = ($_SERVER['SERVER_PORT'] == '80') ? '' : (':'.$_SERVER['SERVER_PORT']);
        return $protocol.'://'.$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
    }
}

if (!function_exists('gdr2_strleft')) {
    /**
     * Strip first string starting from the position of second string.
     *
     * @param string $s1 first string
     * @param string $s2 locator string
     * @return string result string
     */
    function gdr2_strleft($s1, $s2) {
        $values = substr($s1, 0, strpos($s1, $s2));
        return $values;
    }
}

if (!function_exists('gdr2_array_map')) {
    /**
     * Run function on array values.
     *
     * @param string $function string function to run
     * @param mixed $value what to strip tags from (not an object) 
     * @return mixed striped value
     */
    function gdr2_array_map($function, $value) {
        if (is_object($value)) {
            return $value;
        }

        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = gdr2_array_map($function, $val);
            }

            return $value;
        } else {
            return call_user_func($function, $value);
        }
    }
}

if (!function_exists('gdr2_html_id_from_name')) {
    /**
     * Get valid ID for HTML control from name.
     *
     * @param string $name name for the control
     * @param string $id id for the control
     * @return string valid id
     */
    function gdr2_html_id_from_name($name, $id = '') {
        if ($id == '') {
            $id = str_replace(']', '', $name);
            $id = str_replace('[', '_', $id);
        } else if ($id == '_') {
            $id = '';
        }

        return $id;
    }
}

if (!function_exists('gdr2_get_select_values')) {
    /**
     * Get multiple selected values from select box from $_POST
     *
     * @param string $name name for the select field
     * @param string $all value for the all field to skip
     * @return array selected values
     */
    function gdr2_get_select_values($name, $all = '(all)') {
        $items = (array)$_POST[$name];

        if (count($items) > 0 && $items[0] == $all) {
            unset($items[0]);
            $items = array_values($items);
        }

        return $items;
    }
}

if (!function_exists('gdr2_mime_content_type')) {
    /**
     * Detect mime type for a file.
     * Based on: http://www.php.net/manual/en/function.mime-content-type.php#87856
     *
     * @param type $filename file to get mime from
     * @return string mime type
     */
    function gdr2_mime_content_type($filename) {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = get_extension($filename);
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } else if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);

            return $mimetype;
        } else {
            return 'application/octet-stream';
        }
    }
}

if (!function_exists('gdr2_sanitize_simple')) {
    /**
     * Sanitize string with only some simple replacements.
     *
     * @param string $name input string
     * @return string sanitized name
     */
    function gdr2_sanitize_simple($name) {
        $name = trim(strip_tags($name));
        $name = str_replace(array('.', ' '), '-', $name);
        return $name;
    }
}

if (!function_exists('gdr2_sanitize_full')) {
    /**
     * Sanitize string with full series of transformations.
     *
     * @param string $name input string
     * @return string sanitized name
     */
    function gdr2_sanitize_full($name) {
        $name = trim(strip_tags($name));
        $name = strtolower($name);
        $name = sanitize_user($name, true);
        $name = str_replace(array("'", '"'), "", $name);
        $name = str_replace(array(".", " "), "-", $name);
        return $name;
    }
}

if (!function_exists('gdr2_sanitize_custom')) {
    /**
     * Sanitize string with full series of transformations and custom settings.
     *
     * @param string $name input string
     * @param array $args settings
     * @return string sanitized name
     */
    function gdr2_sanitize_custom($name, $args = array()) {
        $defaults = array('strip_spaces' => false, 'replacement' => '-');
        $args = wp_parse_args($args, $defaults);
        extract($args);

        $name = trim(strip_tags($name));
        $name = strtolower($name);
        $name = sanitize_user($name, true);
        $name = str_replace(array("'", '"'), '', $name);

        if ($strip_spaces) {
            $name = str_replace(' ', '', $name);
        }

        $name = str_replace(array('.', '-', '_', ' '), $replacement, $name);

        return $name;
    }
}

if (!function_exists('gdr2_null')) {
    /**
     * Null function. Returns null. Does nothing.
     *
     * @return null
     */
    function gdr2_null() {
        return null;
    }
}

if (!function_exists('gdr2_unset')) {
    function gdr2_unset($arr, $keys) {
        foreach ($keys as $key) {
            unset($arr[$key]);
        }

        return $arr;
    }
}

if (!function_exists('gdr2_is_array_associative')) {
    /**
     * Check if the array is associative.
     *
     * @param mixed $array array to check
     * @return boolean true if the array is associative, false if it is not.
     */
    function gdr2_is_array_associative($array) {
        return is_array($array) && (0 !== count(array_diff_key($array, array_keys(array_keys($array)))) || count($array) == 0);
    }
}

if (!function_exists('gdr2_is_oembed_link')) {
    /**
     * Check if the link is valid oEmbed link.
     *
     * @param string $url link to check
     * @return boolean true if the link is valid oEmbed link, false if it is not.
     */
    function gdr2_is_oembed_link($url) {
        require_once(ABSPATH.WPINC.'/class-oembed.php');
        $oembed = _wp_oembed_get_object();
        $result = $oembed->get_html($url);
        return $result === false ? false : true;
    }
}

if (!function_exists('gdr2_post_has_parent')) {
    /**
     * Check if post has parent post.
     *
     * @param int $post_id post ID to check
     * @return bool true if has parent, false if it has no parent
     */
    function gdr2_post_has_parent($post_id) {
        $post = get_post($post_id);

        if (is_null($post)) {
            return false;
        } else {
            return $post->post_parent != 0;
        }
    }
}

if (!function_exists('gdr2_get_post_parent')) {
    /**
     * Get post ID for post parent.
     *
     * @param int $post_id post ID to check
     * @return int ID for post parent
     */
    function gdr2_get_post_parent($post_id) {
        $post = get_post($post_id);

        if (is_null($post)) {
            return 0;
        } else {
            return $post->post_parent;
        }
    }
}

if (!function_exists('php_array_to_js_object')) {
    /**
     * Convert array to JavaScript object.
     *
     * @param array $array array to convert
     * @return string json formated object string
     */
    function php_array_to_js_object($array){
        $obj = array();

        foreach ($array as $key => $value) {
            $el = $key.': ';

            if (is_bool($value)) {
                $el.= $value ? 'true' : 'false';
            } else if (!is_numeric($value)) {
                $el.= "'".$value."'";
            } else {
                $el.= $value;
            }

            $obj[] = $el;
        }

        return '{ '.join(', ', $obj).' }';
    }
}

if (!function_exists('in_iarray')) {
    /**
     * Check if the string is in array, using case-insensitive comparison.
     *
     * @param string $str string to check in array
     * @param array $a array to check for the string
     * @return bool is the string is in array or not.
     */
    function in_iarray($str, $a){
        foreach($a as $v){
            if (strcasecmp($str, $v) == 0){
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('array_iunique')) {
    /**
     * Removes duplicates from array using case-insensitive comparison.
     *
     * @param array $a array to check for duplicates
     * @return array cleaned up array
     */
    function array_iunique($a){
        $n = array();

        foreach($a as $k => $v) {
            if (!in_iarray($v, $n)){
                $n[$k] = $v;
            }
        }

        return $n;
    }
}

if (!function_exists('sprintfa')) {
    /**
     * Returns formated strings with array of the arguments. Useful if the number
     * of arguments isn't fixed.
     *
     * @param string $format basic format string
     * @param array $args replacement strings array
     * @return string formatted string
     */
    function sprintfa($format, $args){
        return call_user_func_array('sprintf', array_merge((array)$format, $args));
    }
}

if (!function_exists('printfa')) {
    /**
     * Prints formated strings with array of the arguments. Useful if the number
     * of arguments isn't fixed.
     *
     * @param string $format basic format string
     * @param array $args replacement strings array
     * @return int length of formatted string
     */
    function printfa($format, $args){
        return call_user_func_array('printf', array_merge((array)$format, $args));
    }
}

if (!function_exists('gdr2_print_array_lines')) {
    /**
     * Output lines from array into string.
     *
     * @param array $array array to print to string
     * @param string $none what to use for empty arrays
     * @return string formatted string output
     */
    function gdr2_print_array_lines($array, $none = "<em>none</em>") {
        if (is_array($array)) {
            foreach($array as $key => $item) {
                if (is_array($item)) {
                    $array[$key] = gdr2_print_array_lines($item, $none);
                }
            }

            return join("<br/>", $array).'<br/>';
        } else if (empty($array)) {
            return $none.'<br/>';
        } else {
            return $array.'<br/>';
        }
    }
}

if (!function_exists('gdr2_is_url_valid')) {
    /**
     * Attempt to match the URL and check if it is valid.
     * 
     * @link http://stackoverflow.com/questions/161738/what-is-the-best-regular-expression-to-check-if-a-string-is-a-valid-url#answer-2015516
     *
     * @param string $url url to check
     * @return bool 
     */
    function gdr2_is_url_valid($url) {
        $url_matching = '/^(https?):\/\/'.
            '(([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+'.
            '(:([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+)?'.
            '@)?(?#'.
            ')((([a-z0-9][a-z0-9-]*[a-z0-9]\.)*'.
            '[a-z][a-z0-9-]*[a-z0-9]'.
            '|((\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])\.){3}'.
            '(\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])'.
            ')(:\d+)?'.
            ')(((\/+([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)*'.
            '(\?([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)'.
            '?)?)?'.
            '(#([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)?'.
            '$/i';
        return preg_match($url_matching, $url);
    }
}

if (!function_exists('gdr2_color_to_hex')) {
    /**
     * Convert named, hex or short hex color into hex color.
     *
     * @param string $color color value to conver
     * @return string|null null if the color is not valid.
     */
    function gdr2_color_to_hex($color) {
        $named_colors = array(
            'aliceblue' => '#F0F8FF',
            'antiquewhite' => '#FAEBD7',
            'aqua' => '#00FFFF',
            'aquamarine' => '#7FFFD4',
            'azure' => '#F0FFFF',
            'beige' => '#F5F5DC',
            'bisque' => '#FFE4C4',
            'black' => '#000000',
            'blanchedalmond ' => '#FFEBCD',
            'blue' => '#0000FF',
            'blueviolet' => '#8A2BE2',
            'brown' => '#A52A2A',
            'burlywood' => '#DEB887',
            'cadetblue' => '#5F9EA0',
            'chartreuse' => '#7FFF00',
            'chocolate' => '#D2691E',
            'coral' => '#FF7F50',
            'cornflowerblue' => '#6495ED',
            'cornsilk' => '#FFF8DC',
            'crimson' => '#DC143C',
            'cyan' => '#00FFFF',
            'darkblue' => '#00008B',
            'darkcyan' => '#008B8B',
            'darkgoldenrod' => '#B8860B',
            'darkgray' => '#A9A9A9',
            'darkgreen' => '#006400',
            'darkgrey' => '#A9A9A9',
            'darkkhaki' => '#BDB76B',
            'darkmagenta' => '#8B008B',
            'darkolivegreen' => '#556B2F',
            'darkorange' => '#FF8C00',
            'darkorchid' => '#9932CC',
            'darkred' => '#8B0000',
            'darksalmon' => '#E9967A',
            'darkseagreen' => '#8FBC8F',
            'darkslateblue' => '#483D8B',
            'darkslategray' => '#2F4F4F',
            'darkslategrey' => '#2F4F4F',
            'darkturquoise' => '#00CED1',
            'darkviolet' => '#9400D3',
            'deeppink' => '#FF1493',
            'deepskyblue' => '#00BFFF',
            'dimgray' => '#696969',
            'dimgrey' => '#696969',
            'dodgerblue' => '#1E90FF',
            'firebrick' => '#B22222',
            'floralwhite' => '#FFFAF0',
            'forestgreen' => '#228B22',
            'fuchsia' => '#FF00FF',
            'gainsboro' => '#DCDCDC',
            'ghostwhite' => '#F8F8FF',
            'gold' => '#FFD700',
            'goldenrod' => '#DAA520',
            'gray' => '#808080',
            'green' => '#008000',
            'greenyellow' => '#ADFF2F',
            'grey' => '#808080',
            'honeydew' => '#F0FFF0',
            'hotpink' => '#FF69B4',
            'indianred' => '#CD5C5C',
            'indigo' => '#4B0082',
            'ivory' => '#FFFFF0',
            'khaki' => '#F0E68C',
            'lavender' => '#E6E6FA',
            'lavenderblush' => '#FFF0F5',
            'lawngreen' => '#7CFC00',
            'lemonchiffon' => '#FFFACD',
            'lightblue' => '#ADD8E6',
            'lightcoral' => '#F08080',
            'lightcyan' => '#E0FFFF',
            'lightgoldenrodyellow' => '#FAFAD2',
            'lightgray' => '#D3D3D3',
            'lightgreen' => '#90EE90',
            'lightgrey' => '#D3D3D3',
            'lightpink' => '#FFB6C1',
            'lightsalmon' => '#FFA07A',
            'lightseagreen' => '#20B2AA',
            'lightskyblue' => '#87CEFA',
            'lightslategray' => '#778899',
            'lightslategrey' => '#778899',
            'lightsteelblue' => '#B0C4DE',
            'lightyellow' => '#FFFFE0',
            'lime' => '#00FF00',
            'limegreen' => '#32CD32',
            'linen' => '#FAF0E6',
            'magenta' => '#FF00FF',
            'maroon' => '#800000',
            'mediumaquamarine' => '#66CDAA',
            'mediumblue' => '#0000CD',
            'mediumorchid' => '#BA55D3',
            'mediumpurple' => '#9370D0',
            'mediumseagreen' => '#3CB371',
            'mediumslateblue' => '#7B68EE',
            'mediumspringgreen' => '#00FA9A',
            'mediumturquoise' => '#48D1CC',
            'mediumvioletred' => '#C71585',
            'midnightblue' => '#191970',
            'mintcream' => '#F5FFFA',
            'mistyrose' => '#FFE4E1',
            'moccasin' => '#FFE4B5',
            'navajowhite' => '#FFDEAD',
            'navy' => '#000080',
            'oldlace' => '#FDF5E6',
            'olive' => '#808000',
            'olivedrab' => '#6B8E23',
            'orange' => '#FFA500',
            'orangered' => '#FF4500',
            'orchid' => '#DA70D6',
            'palegoldenrod' => '#EEE8AA',
            'palegreen' => '#98FB98',
            'paleturquoise' => '#AFEEEE',
            'palevioletred' => '#DB7093',
            'papayawhip' => '#FFEFD5',
            'peachpuff' => '#FFDAB9',
            'peru' => '#CD853F',
            'pink' => '#FFC0CB',
            'plum' => '#DDA0DD',
            'powderblue' => '#B0E0E6',
            'purple' => '#800080',
            'red' => '#FF0000',
            'rosybrown' => '#BC8F8F',
            'royalblue' => '#4169E1',
            'saddlebrown' => '#8B4513',
            'salmon' => '#FA8072',
            'sandybrown' => '#F4A460',
            'seagreen' => '#2E8B57',
            'seashell' => '#FFF5EE',
            'sienna' => '#A0522D',
            'silver' => '#C0C0C0',
            'skyblue' => '#87CEEB',
            'slateblue' => '#6A5ACD',
            'slategray' => '#708090',
            'slategrey' => '#708090',
            'snow' => '#FFFAFA',
            'springgreen' => '#00FF7F',
            'steelblue' => '#4682B4',
            'tan' => '#D2B48C',
            'teal' => '#008080',
            'thistle' => '#D8BFD8',
            'tomato' => '#FF6347',
            'turquoise' => '#40E0D0',
            'violet' => '#EE82EE',
            'wheat' => '#F5DEB3',
            'white' => '#FFFFFF',
            'whitesmoke' => '#F5F5F5',
            'yellow' => '#FFFF00',
            'yellowgreen' => '#9ACD32');

        if (strlen($color) == 4 && substr($color, 0, 1) == '#') {
            $color[5] = $color[2];
            $color[4] = $color[2];
            $color[3] = $color[1];
            $color[2] = $color[1];
            $color[1] = $color[0];
            return '#'.$color;
        } else if (strlen($color) == 7 && substr($color, 0, 1) == '#') {
            return $color;
        } else {
            if (isset($named_colors[$color])) {
                return $named_colors[$color];
            } else {
                if (strlen($color == 6) && ctype_xdigit($color)) {
                    return '#'.$color;
                }
            }
        }

        return null;
    }
}

if (!function_exists('gdr2_arrays_equal')) {
    /**
     * Check if the two arrays are equal
     *
     * @param array $array_one First array
     * @param array $array_two Second array
     * @return bool
     */
    function gdr2_arrays_equal($array_one, $array_two) {
        return count(array_diff($array_one, $array_two)) == 0 && 
               count(array_diff($array_two, $array_one)) == 0;
    }
}

if (!function_exists('gdr2_var_replace_encodedecode')) {
    /**
     * Replace characters in string recursevly for the whole input variable. It
     * can be used to encode and decode same set of characters.
     *
     * @param mixed $object input
     * @param bool $encode true for the encoding, false for decoding process
     * @param array $input string to replace
     * @param array $output replacements strings
     * @return mixed converted var 
     */
    function gdr2_var_replace_encodedecode($object, $encode = true, $input = array("\n", "\r"), $output = array("%%NL%%", "%%CR%%")) {
        foreach ($object as $key => &$data) {
            if (is_array($data) || is_object($data)) {
                $data = gdr2_var_replace_encodedecode($data, $encode, $input, $output);
            } else if (is_string($data)) {
                if ($encode) {
                    $data = str_replace($input, $output, $data);
                } else {
                    $data = str_replace($output, $input, $data);
                }
            }
        }

        return $object;
    }
}

if (!function_exists('gdr2_to_string')) {
    /**
     * Cast any value into string.
     *
     * @param mixed $value input value
     * @return string string value
     */
    function gdr2_to_string($value) {
        return (string)$value;
    }
}

if (!function_exists('gdr2_is_current_user_admin')) {
    /**
     * Checks to see if the currently logged user is admin.
     *
     * @return bool is user admin or not
     */
    function gdr2_is_current_user_admin() {
        return gdr2_is_current_user_role();
    }
}

if (!function_exists('gdr2_is_current_user_role')) {
    /**
     * Checks to see if the currently logged user has a given role.
     *
     * @param string $role name of the role
     * @return bool is user has a given role or not
     */
    function gdr2_is_current_user_role($role = 'administrator') {
        global $current_user;

        if (is_array($current_user->roles)) {
            return in_array($role, $current_user->roles);
        } else {
            return false;
        }
    }
}

if (!function_exists('gdr2_is_current_user_roles')) {
    /**
     * Checks to see if the currently logged user has one of given roles.
     *
     * @param array $roles list of roles
     * @return bool is user has any of given roles or not
     */
    function gdr2_is_current_user_roles($roles = array()) {
        global $current_user;
        $roles = (array)$roles;

        if (is_array($current_user->roles) && !empty($roles)) {
            $match = array_intersect($roles, $current_user->roles);
            return !empty($match);
        } else {
            return false;
        }
    }
}

if (!function_exists('gdr2_switch_to_default_theme')) {
    /**
     * Switch from current theme to default theme.
     */
    function gdr2_switch_to_default_theme() {
        switch_theme(WP_DEFAULT_THEME, WP_DEFAULT_THEME);
    }
}

if (!function_exists('gdr2_sort_objects_array')) {
    /**
     * Sort array with objects by specified properties and order.
     */
    function gdr2_sort_objects_array($objects_array, $properties = array()) {
        $list = new gdr2_ObjectSort($objects_array, $properties);
        return $list->sorted;
    }
}

if (!function_exists('gdr2_array_unshift_assoc')) {
    function gdr2_array_unshift_assoc(&$arr, $key, $val) { 
        $arr = array_reverse($arr, true); 
        $arr[$key] = $val; 
        $arr = array_reverse($arr, true); 
    }
}

?>