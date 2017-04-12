<?php

/*
Name:    gdr2_Utils
Version: 2.8.8
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.dev4press.com/libs/gdr2/

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

if (!class_exists('gdr2_Utils')) {
    /**
     * Collection of utlilities
     */
    class gdr2_Utils {
        function __construct() { }

        private function _version_compare($current, $check) {
            $result = array('current' => $current, 'required' => $check);
            $compare = version_compare($check, $current);
            $result['result'] = $compare != 1;
            return $result;
        }

        private function _draw_time_display($timers) {
            $timezone_format = _x('Y-m-d G:i:s', 'timezone date format', 'gdr2');
            $els = array(
                __("System", "gdr2").": <code>".date_i18n($timezone_format, $timers["system"])."</code>",
                __("GMT / UTC", "gdr2").": <code>".date_i18n($timezone_format, $timers["utc"])."</code>",
                __("Local", "gdr2").": <code>".date_i18n($timezone_format, $timers["local"])."</code>"
            );
            echo '<div class="timers">';
            echo join(" ", $els);
            echo '</div>';
        }

        private function _draw_requirements($req) {
            $els = array();
            foreach ($req as $key => $data) {
                if ($key == 'php_extensions') {
                    $el = '<span class="reqtitle">'.$data["name"].':</span> ';
                    foreach ($data["list"] as $ext => $status) {
                        $el.= '<span class="reqversion req'.($status ? "true" : "false").'">'.$ext.'</span>';
                    }
                } else {
                    $el = '<span class="reqtitle">'.$data["name"].':</span> <span class="reqversion req'.($data["result"] ? "true" : "false").'">'.$data["current"].'</span>';
                    if (!$data["result"]) $el.= ' <span class="reqmin">('.$data["required"].')</span>';
                }
                $els[] = $el;
            }
            echo '<div class="requirements">';
            echo join(" | ", $els);
            echo '</div>';
        }

        public function system_timers($render = false) {
            $results = array(
                'system' => time(),
                'utc' => current_time('timestamp', true),
                'local' => gdr2_current_timestamp()
            );
            if ($render) $this->_draw_time_display($results);
            else return $results;
        }

        public function system_requirements($list = array(), $render = false) {
            $results = array();
            foreach ($list as $key => $data) {
                $compare = call_user_func(array($this, 'sysreq_'.$key), $data);
                $compare['name'] = $data['name'];
                $results[$key] = $compare;
            }

            if ($render) {
                $this->_draw_requirements($results);
            } else {
                return $results;
            }
        }

        public function sysreq_php_extensions($data) {
            $results = array();
            foreach ($data['list'] as $ext) {
                $results[$ext] = extension_loaded($ext);
            }
            return array('list' => $results);
        }

        public function sysreq_php($data) {
            $current = PHP_VERSION;
            $version = $data['version'];
            return $this->_version_compare($current, $version);
        }

        public function sysreq_mysql($data) {
            global $wpdb;

            $current = $wpdb->db_version();
            $version = $data['version'];
            return $this->_version_compare($current, $version);
        }

        public function sysreq_wordpress($data) {
            global $wp_version;

            $version = $data['version'];
            return $this->_version_compare($wp_version, $version);
        }
    }
}

$g2_utils = new gdr2_Utils();

?>