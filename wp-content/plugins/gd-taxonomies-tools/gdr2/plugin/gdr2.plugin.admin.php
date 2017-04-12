<?php

/*
Name:    gdr2_Plugin_Admin
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

if (!class_exists('gdr2_Plugin_Admin')) {
    class gdr2_Plugin_Admin {
        public $script;

        public $plugin_product = 'gdr2_Product';
        public $plugin_define = '';
        public $plugin_code = '';
        public $plugin_file = '';
        public $plugin_prefix = 'gdr2';
        public $plugin_menus = array('site' => '__top__', 'network' => '__none__');
        public $plugin_nonce = '';

        public $menu_site = array();
        public $menu_network = array();

        public $page_ids = array();
        public $tutorials = array();

        public $queue_js = array(
            'jquery-ui' => array('file' => 'jquery-ui.js', 'loc' => false, 'footer' => false, 'has_source' => false, 'depend' => 'jquery'),
            'utilities' => array('file' => 'utilities.js', 'loc' => false, 'footer' => false, 'has_source' => false, 'depend' => 'jquery'),
            'admin' => array('file' => 'admin.js', 'loc' => true, 'loc_name' => 'Init', 'footer' => false, 'has_source' => true, 'depend' => '%utilities')
        );

        public $queue_css = array(
            'jquery-ui' => array('file' => 'jquery_ui.css', 'depend' => array()),
            'admin' => array('file' => 'admin.css', 'depend' => array())
        );

        public $enqueue_blocks = array(
            'form' => true,
            'thickbox' => true,
            'media' => false
        );

        public $nonplugin_queue_js = array();
        public $nonplugin_queue_css = array();

        public $admin_plugin;
        public $admin_plugin_page;

        function __construct() {
            $class_name = $this->plugin_product;
            $this->plugin_product = new $class_name();

            if ($this->plugin_nonce == '') {
                $this->plugin_nonce = $this->plugin_code;
            }

            $this->script = explode('/', $_SERVER['PHP_SELF']);
            $this->script = end($this->script);

            $this->actions_filters();
            $this->tutorials();
        }

        private function _jsdev() {
            $own = $this->plugin_define.'_JS_DEV';

            if (defined($own)) {
                return $this->_d('JS_DEV');
            } else {
                if (defined('SCRIPT_DEBUG')) {
                    return SCRIPT_DEBUG;
                }
            }

            return false;
        }

        private function _h($name) {
            return $this->plugin_prefix.'_'.$name;
        }

        private function _d($name) {
            return constant($this->plugin_define.'_'.$name);
        }

        public function is_jsdev() {
            return $this->_jsdev();
        }

        public function actions_filters() {
            add_action('admin_head', array(&$this, 'admin_head'));
            add_action('admin_footer', array(&$this, 'admin_footer'));

            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_init', array(&$this, 'unload_jqueryui'), 10000);

            add_action('admin_enqueue_scripts', array(&$this, 'enqueue_files'));

            if ($this->plugin_menus['site'] != '__none__') {
                add_action('admin_menu', array(&$this, 'admin_menu'));
            }
            if ($this->plugin_menus['network'] != '__none__') {
                add_action('network_admin_menu', array(&$this, 'network_admin_menu'));
            }

            add_filter('plugin_row_meta', array(&$this, 'plugin_links'), 10, 2);
            add_filter('plugin_action_links_'.$this->plugin_file, array(&$this, 'plugin_actions'));
            add_filter('wp_loaded', array(&$this, 'wp_loaded'));

            add_action('wp_ajax_'.$this->plugin_prefix.'_save_settings', array(&$this, 'save_settings'));
        }

        public function tutorials() { }
        public function wp_loaded() { }
        public function load_admin_page() { }

        public function save_settings() {
            check_ajax_referer($this->plugin_code);
        }

        public function plugin_links($links, $file) {
            if ($file == $this->plugin_file) { }
            return $links;
        }

        public function plugin_actions($links) {
            $settings_link = '<a href="admin.php?page='.$this->plugin_code.'-settings">'.__("Settings", "gdr2").'</a>';
            array_unshift($links, $settings_link);
            return $links;
        }

        public function admin_menu() {
            if ($this->plugin_menus['site'] == '__top__') {
                $parent = $this->plugin_code.'-front';
                $this->page_ids[] = add_menu_page($this->plugin_product->name, $this->plugin_product->menu, $this->plugin_prefix.'_basic', $parent, array(&$this, 'panel_site_front'), plugins_url($this->plugin_code.'/gfx/menu/icon_16.png'));

                foreach($this->menu_site as $item => $data) {
                    $this->page_ids[] = add_submenu_page($parent, 
                                     $this->plugin_product->name.': '.$data['title'], 
                                     $data['title'], 
                                     $this->plugin_prefix.'_'.$data['caps'], 
                                     $this->plugin_code.'-'.$item, 
                                     array(&$this, 'panel_site_'.$item));
                }
            } else {
                $this->page_ids[] = add_submenu_page($this->plugin_menus['site'], $this->plugin_product->name, $this->plugin_product->name, $this->plugin_prefix.'_basic', $this->plugin_code.'_settings', array($this, 'admin_menu_site_settings'));
            }

            $this->admin_load_hooks();
        }

        public function network_admin_menu() {
            if ($this->plugin_menus['network'] == '__top__') {
                $parent = $this->plugin_code.'-front';
                $this->page_ids[] = add_menu_page($this->plugin_product->name, $this->plugin_product->menu, $this->plugin_prefix."_basic", $parent, array(&$this, "panel_network_front"), plugins_url($this->plugin_code."/gfx/menu/icon_16.png"));

                foreach($this->menu_network as $item => $data) {
                    $this->page_ids[] = add_submenu_page($parent, 
                                     $this->plugin_product->name.': '.$data['title'], 
                                     $data['title'], 
                                     $this->plugin_prefix.'_'.$data['caps'], 
                                     $this->plugin_code.'-'.$item, 
                                     array(&$this, 'panel_site_'.$item));
                }
            } else {
                $this->page_ids[] = add_submenu_page($this->plugin_menus['network'], $this->plugin_product->name, $this->plugin_product->name, $this->plugin_prefix.'_basic', $this->plugin_code.'_settings', array($this, 'admin_menu_network_settings'));
            }

            $this->admin_load_hooks();
        }

        public function admin_load_hooks() {
            foreach ($this->page_ids as $id) {
                add_action('load-'.$id, array(&$this, 'load_admin_page'));
            }
        }

        public function enqueue_files($hook) {
            if ($this->admin_plugin) {
                wp_enqueue_script('jquery');

                if ($this->enqueue_blocks['form']) {
                    wp_enqueue_script('jquery-form');
                }

                if ($this->enqueue_blocks['thickbox']) {
                    wp_enqueue_script('media-upload');
                    wp_enqueue_script('thickbox');
                    wp_enqueue_style('thickbox');
                }

                if ($this->enqueue_blocks['media']) {
                    wp_enqueue_media();
                }

                foreach ($this->queue_js as $name => $file) {
                    $fn = $file['file'];
                    $dp = (array)$file['depend'];

                    for ($i = 0; $i < count($dp); $i++) {
                        if (substr($dp[$i], 0, 1) == '%') $dp[$i] = $this->plugin_prefix.'-'.substr($dp[$i], 1);
                    }

                    if ($file['has_source'] && $this->is_jsdev()) {
                        $fn = 'src/'.$fn;
                    }

                    wp_enqueue_script($this->plugin_prefix.'-'.$name, $this->_d('URL').'js/'.$fn, $dp, $this->_d('VERSION'), $file['footer']);

                    if ($file['loc']) {
                        wp_localize_script($this->plugin_prefix.'-'.$name, $this->plugin_prefix.$file['loc_name'], $this->localize_values($name));
                    }
                }

                foreach ($this->queue_css as $name => $file) {
                    $fn = $file['file'];
                    $dp = (array)$file['depend'];

                    wp_enqueue_style($this->plugin_prefix.'-'.$name, $this->_d('URL').'css/'.$fn, $dp, $this->_d('VERSION'));
                }

                do_action($this->_h('admin_enqueue_scripts'));
            }

            foreach ($this->nonplugin_queue_js as $name => $file) {
                if (in_array('__any__', $file['script']) || in_array($hook, $file['script'])) {
                    $fn = $file['file'];
                    $dp = (array)$file['depend'];

                    for ($i = 0; $i < count($dp); $i++) {
                        if (substr($dp[$i], 0, 1) == '%') $dp[$i] = $this->plugin_prefix.'-'.substr($dp[$i], 1);
                    }

                    if ($file['has_source'] && $this->is_jsdev()) {
                        $fn = 'src/'.$fn;
                    }

                    wp_enqueue_script($this->plugin_prefix.'-'.$name, $this->_d('URL').'js/'.$fn, $dp, $this->_d('VERSION'), $file["footer"]);

                    if ($file['loc']) {
                        wp_localize_script($this->plugin_prefix.'-'.$name, $this->plugin_prefix.$file['loc_name'], $this->localize_values($name));
                    }
                }
            };

            foreach ($this->nonplugin_queue_css as $name => $file) {
                if (in_array('__any__', $file['script']) || in_array($hook, $file['script'])) {
                    $fn = $file['file'];
                    $dp = isset($file['depend']) ? (array)$file['depend'] : array();

                    wp_enqueue_style($this->plugin_prefix.'-'.$name, $this->_d('URL').'css/'.$fn, $dp, $this->_d('VERSION'));
                }
            }
        }

        public function admin_head() {
            if ($this->admin_plugin) {
                do_action($this->_h('admin_head'));
            }
        }

        public function admin_footer() {
            if ($this->admin_plugin) {
                do_action($this->_h('admin_footer'));
            }
        }

        public function admin_init() {
            if (isset($_GET['page'])) {
                if (substr($_GET['page'], 0, strlen($this->plugin_code)) == $this->plugin_code) {
                    $this->admin_plugin = true;
                    $this->admin_plugin_page = substr($_GET['page'], strlen($this->plugin_code) + 1);

                    do_action($this->_h('admin_init'));
                }
            }
        }

        public function localize_values($name) {
            $values = array('url' => $this->_d('URL'), 
                            'wpv' => $this->_d('WPV'), 
                            'nonce' => wp_create_nonce($this->plugin_nonce), 
                            'ui_enhance' => 'on');
            return $values;
        }

        public function unload_jqueryui() {
            if ($this->admin_plugin) {
                wp_deregister_script('thesis-admin-js');
                wp_deregister_script('aiow-plugin-script3');
                wp_deregister_script('aiow-plugin-script4');
            }
        }

        public function admin_menu_site_settings() { }

        public function admin_menu_network_settings() { }
    }
}

?>