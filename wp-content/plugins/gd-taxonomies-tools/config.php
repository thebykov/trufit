<?php

/**
 * Full path to wp-load file. Use only if the location of wp-content folder is changed.
 *
 * example: define('GDTAXTOOLS_WPLOAD', '/home/path/to/wp-load.php');
 */
if (!defined('GDTAXTOOLS_WPLOAD')) define('GDTAXTOOLS_WPLOAD', '');

if (!function_exists('get_gdtt_wpload_path')) {
    /**
     * Returns the path to wp-load.php file
     *
     * @return string wp-load.php path
     */
    function get_gdtt_wpload_path() {
        if (GDTAXTOOLS_WPLOAD == '') {
            $d = 0;
            while (!file_exists(str_repeat('../', $d).'wp-load.php'))
                if (++$d > 16) exit;
            return str_repeat('../', $d).'wp-load.php';
        } else return GDTAXTOOLS_WPLOAD;
    }
}

?>