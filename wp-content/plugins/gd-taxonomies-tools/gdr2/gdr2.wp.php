<?php

/*
Name:    gdr2_WP
Version: 2.8.8
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.dev4press.com/libs/gdr2/
Info:    Functions for compatibility with older WordPress installations

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

if (!function_exists('is_wpmu')) {
    function is_wpmu() {
        if (function_exists('is_multisite')) return is_multisite();
        else return false;
    }
}

if (!function_exists('is_network_admin')) {
    function is_network_admin() {
        if (defined('WP_NETWORK_ADMIN')) return WP_NETWORK_ADMIN;
	return true;
    }
}

if (!function_exists('is_multisite')) {
    function is_multisite() {
        return false;
    }
}

if (!function_exists('update_user_meta')) {
    function update_user_meta($user_id, $meta_key, $meta_value) {
        return update_usermeta($user_id, $meta_key, $meta_value);
    }
}

if (!function_exists('get_user_meta')) {
    function get_user_meta($user_id, $key, $single = false) {
        return get_usermeta($user_id, $key);
    }
}

if (!function_exists('is_super_admin')) {
    function is_super_admin() {
        return is_site_admin();
    }
}

if (!function_exists('is_site_admin')) {
    function is_site_admin() {
        return true;
    }
}

if (!function_exists('update_site_option')) {
    function update_site_option($option, $value) {
        return update_option($option, $value);
    }
}

if (!function_exists('get_site_option')) {
    function get_site_option($option, $default = false) {
        return get_option($option, $default);
    }
}

if (!function_exists('is_post_type_archive')) {
    function is_post_type_archive() {
        return false;
    }
}

?>