<?php

/*
Name:    gdr2_Plugin
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

if (!class_exists('gdr2_Plugin_Core')) {
    class gdr2_Plugin_Core {
        public $plugin_product = 'gdr2_Product';
        public $plugin_define = '';
        public $plugin_code = '';
        public $plugin_file = '';
        public $plugin_prefix = '';
        public $plugin_prefix_db = 'gdr2';
        public $plugin_caps = array();
        public $plugin_nonce = '';

        public $wp_version = '';
        public $_product;

        private $plugin_path;
        private $plugin_base;
        private $plugin_url;

        function __construct($base_path, $base_file) {
            $class_name = $this->plugin_product;
            $this->_product = new $class_name();

            if ($this->plugin_nonce == '') {
                $this->plugin_nonce = $this->plugin_code;
            }

            $this->plugin_path = $base_path.'/';
            $this->plugin_base = $base_file;

            $this->plugin_path_url();
            $this->install_plugin();
            $this->actions_filters();

            define($this->plugin_define.'_INSTALLED', $this->_product->version.' Pro');
            define($this->plugin_define.'_VERSION', $this->_product->version.'_b'.$this->_product->build.'_pro');
        }

        private function _h($name) {
            return $this->plugin_prefix.'_'.$name;
        }

        private function _d($name) {
            return constant($this->plugin_define.'_'.$name);
        }

        public function get_nonce() {
            return $this->plugin_nonce;
        }
        
        public function plugin_path_url() {
            $this->plugin_url = plugins_url('/'.$this->plugin_code.'/');

            define($this->plugin_define.'_URL', $this->plugin_url);
            define($this->plugin_define.'_PATH', $this->plugin_path);
        }

        public function check_install() { return false; }
        public function check_update() { return false; }

        public function plugin_activation() { }
        public function plugin_deactivation() { }

        public function install_plugin() {
            global $wp_version;
            $this->wp_version = substr(str_replace('.', '', $wp_version), 0, 2);
        
            define($this->plugin_define.'_WPV', $this->wp_version);

            if ($this->check_install() || $this->check_update()) {
                require_once($this->plugin_path.'gdr2/gdr2.db.php');

                $db = new gdr2_DB($this->plugin_path.'install/', $this->plugin_prefix_db);
                $db->install();
            }
        }

        public function actions_filters() {
            register_activation_hook($this->plugin_base, array(&$this, 'plugin_activation'));
            register_deactivation_hook($this->plugin_base, array(&$this, 'plugin_deactivation'));

            add_action('init', array(&$this, 'init_language'));
            add_action('init', array(&$this, 'init_load'));
        }

        public function init_language() {
            $this->l = get_locale();

            if(!empty($this->l)) {
                $moFile = $this->plugin_path.'languages/'.$this->plugin_code.'-'.$this->l.'.mo';

                if (@file_exists($moFile) && is_readable($moFile)) {
                    load_plugin_textdomain($this->plugin_code, false, $this->plugin_code.'/languages');
                }
            }
        }

        public function init_load() {
            if (empty($this->plugin_caps)) {
                $this->roles_caps();
            }

            foreach ($this->plugin_caps as $role => $caps) {
                $role = get_role($role);
                foreach ($caps as $cap) $role->add_cap($cap);
            }
        }

        public function roles_caps() {
            $this->plugin_caps = array('administrator' => array($this->plugin_prefix.'_basic'));
        }
    }
}

?>