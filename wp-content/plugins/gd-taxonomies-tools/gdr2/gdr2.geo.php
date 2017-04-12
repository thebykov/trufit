<?php

/*
Name:    gdr2_Geo
Version: 2.8.8
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.dev4press.com/libs/gdr2/
Info:    GEO Location functions

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

if (!function_exists('gdr2_get_country_code_by_ip')) {
    /**
     * Get country code by IP. Function is using HostIP service webiste, and 
     * supports caching of results.
     *
     * @param string $ip ip address. Leave empty to get current visitor ip.
     * @param int $timeout how long to keep the cached value
     * @return string country code will return found code, or XX if not found, or ERROR if request failed.
     */
    function gdr2_get_country_code_by_ip($ip = "", $timeout = 24) {
        $get = false;
        if ($ip == '') {
            $ip = gdr2_Core::visitor_ip();
        }
        $key = 'geo_cc_'.$ip;
        if ($timeout > 0) {
            $code = gdr2c_get_network($key);
            if ($code === false || is_null($code) || empty($code)) {
                $get = true;
            }
        } else {
            $get = true;
        }

        if ($get) {
            $code = wp_remote_get('http://api.hostip.info/country.php?ip='.$ip);
            if (is_wp_error($code)) {
                $code = 'ERROR';
            } else {
                $code = $code['body'];
                if ($timeout > 0) {
                    gdr2c_set_network($key, $code, $timeout * 3600);
                }
            }
        }

        return $code;
    }
}

?>