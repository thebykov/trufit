<?php

/*
Name:    gdr2_Settings
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

if (!class_exists('gdr2_Product')) {
    class gdr2_Product {
        public $name;
        public $menu;
        public $code;
        public $edition;
        public $status;
        public $version;
        public $revision;
        public $build;
        public $date;
        public $base = null;

        function __construct() { }
    }
}

if (!class_exists('gdr2_Settings')) {
    class gdr2_Settings {
        public $activated = false;

        public $bool_values = array(1, 0);
        public $system_keys = array('__core__', '__scope__', '__name__', '__date__');

        public $installed = array('site' => false, 'network' => false);
        public $updated = array('site' => false, 'network' => false);
        public $skip_auto_update = array('site' => array(), 'network' => array());

        public $_product = 'gdr2_Product';
        public $_scope = array();
        public $_defaults = array();

        public $settings = array();

        function __construct() {
            $class_name = $this->_product;
            $this->_product = new $class_name();

            $this->_actions();
        }

        private function _default($scope, $name) {
            $o = array(
                '__core__' => clone($this->_product),
                '__scope__' => $scope,
                '__name__' => $name,
                '__date__' => date('r')
            );

            return array_merge($o, $this->_defaults[$scope][$name]);
        }

        public function _actions() {
            add_action('plugins_loaded', array(&$this, '_activate'), 1);
            add_action('admin_init', array(&$this, '_validate'));
        }

        public function _activate() {
            $this->_load();
            $this->_update();

            $this->activated = true;
        }

        public function _validate() { }

        public function _manual($scope, $name, $update = false, $build = 0) { }

        public function _base($name = '') {
            $base = '';

            if (isset($this->_product->base) && !is_null($this->_product->base)) {
                $base = $this->_product->base;
            } else {
                $base = $this->_product->code;
            }

            if ($name != '') {
                $base.= '-'.$name;
            }

            return $base;
        }

        private function _load() {
            foreach ($this->_scope as $scope => $names) {
                if (empty($names)) continue;

                foreach ($names as $name) {
                    if ($scope == 'site') {
                        $this->settings[$scope][$name] = get_option($this->_base($name));
                    } else if ($scope == 'network') {
                        $this->settings[$scope][$name] = get_site_option($this->_base($name));
                    }

                    if (!is_array($this->settings[$scope][$name])) {
                        $this->installed[$scope] = true;
                        $this->settings[$scope][$name] = $this->_default($scope, $name);

                        $this->_manual($scope, $name);

                        $this->save($scope, $name);
                    }                        
                }
            }
        }

        private function _update() {
            if (!is_admin()) return;

            foreach ($this->_scope as $scope => $names) {
                if (empty($names)) continue;

                foreach ($names as $name) {
                    if (!isset($this->settings[$scope][$name]['__core__']) || $this->settings[$scope][$name]['__core__']->build != $this->_product->build) {
                        $this->updated[$scope] = true;

                        $old_build = intval($this->settings[$scope][$name]['__core__']->build);

                        if (!in_array($name, $this->skip_auto_update[$scope])) {
                            $this->settings[$scope][$name] = gdr2_Core::upgrade_settings($this->settings[$scope][$name], $this->_defaults[$scope][$name]);
                        }

                        $this->settings[$scope][$name]['__core__'] = clone($this->_product);
                        $this->settings[$scope][$name]['__scope__'] = $scope;
                        $this->settings[$scope][$name]['__name__'] = $name;
                        $this->settings[$scope][$name]['__date__'] = date('r');

                        $this->_manual($scope, $name, true, $old_build);

                        $this->save($scope, $name);
                    }
                }
            }
        }

        public function get_default($scope, $name, $option) {
            return isset($this->_defaults[$scope][$name][$option]) ? 
                $this->_defaults[$scope][$name][$option] :
                null;
        }

        public function module_get($scope, $module, $option) {
            if (!isset($this->settings[$scope]['modules'])) {
                return null;
            }

            return isset($this->settings[$scope]['modules'][$module][$option]) ? 
                $this->settings[$scope]['modules'][$module][$option] : 
                null;
        }

        public function module_set($scope, $module, $option, $value) {
            if (!isset($this->settings[$scope]['modules'])) {
                return null;
            }

            $this->settings[$scope]['modules'][$module][$option] = $value;
        }

        public function module_has($module, $scope = 'site') {
            if (!isset($this->settings[$scope]['modules'])) {
                return false;
            }

            return isset($this->settings[$scope]['modules'][$module]);
        }

        public function is_module_active($module, $scope = 'site') {
            if (!isset($this->settings[$scope]['modules'])) {
                return false;
            }

            if (isset($this->settings[$scope]['modules']['__status__'][$module])) {
                return $this->settings[$scope]['modules']['__status__'][$module];
            } else {
                return false;
            }
        }

        public function get($scope, $name, $option) {
            $value = isset($this->settings[$scope][$name][$option]) ? 
                $this->settings[$scope][$name][$option] : 
                $this->get_default($scope, $name, $option);

            return apply_filters('gdr2_setting_option_get_'.$scope.'_'.$name.'_'.$option, $value);
        }

        public function set($scope, $name, $option, $value) {
            $value = apply_filters('gdr2_setting_option_set_'.$scope.'_'.$name.'_'.$option, $value);
            $this->settings[$scope][$name][$option] = $value;
        }

        public function save_all($scope) {
            foreach ($this->_scope[$scope] as $name) {
                $this->save($scope, $name);
            }
        }

        public function save($scope, $name) {
            if ($scope == 'site') {
                update_option($this->_base($name), $this->settings[$scope][$name]);
            } else if ($scope == 'network') {
                update_site_option($this->_base($name), $this->settings[$scope][$name]);
            }
        }

        public function reset($scope, $name) {
            if ($scope == 'site') {
                delete_option($this->_base($name));
            } else if ($scope == 'network') {
                delete_site_option($this->_base($name));
            }
        }

        public function parse_post_element($post, $element, $key, $input) {
            $value = null;

            switch ($input) {
                case 'x_by_y':
                    $value = $post[$key]['x'].'x'.$post[$key]['y'];
                    break;
                case 'html':
                case 'text_rich':
                    $value = stripslashes(htmlentities($post[$key], ENT_QUOTES, GDR2_CHARSET));
                    break;
                case 'bool':
                case 'boolean':
                    $value = isset($post[$key]) ? $this->bool_values[0] : $this->bool_values[1];
                    break;
                case 'number':
                    $value = intval($post[$key]);
                    break;
                case 'text_list':
                case 'list':
                    $value = gdr2_split_textarea($post[$key]);
                    break;
                case 'media':
                    $value = 0;
                    if ($post[$key] != '') {
                        $value = intval(substr($post[$key], 3));
                    }
                    break;
                case 'skip':
                case 'info':
                    $value = null;
                    break;
                case 'select_check':
                case 'select_multi':
                case 'select_grouped_multi':
                    if (!isset($post[$key])) {
                        $value = array();
                    } else {
                        $value = (array)$post[$key];

                        if ($value[0] == '(all)') {
                            unset($value[0]);
                            $value = array_values($value);
                        }
                    }
                    break;
                default:
                case 'text':
                case 'image':
                case 'text_block':
                case 'hidden':
                case 'select':
                case 'select_radio':
                case 'select_grouped':
                    $value = strip_tags($post[$key]);
                    break;
            }

            return $value;
        }

        public function settings($panel = '', $scope = 'site', $name = 'settings') {
            return array();
        }

        public function modules($panel = '', $scope = 'site', $name = 'modules') {
            return array();
        }

        public function post_save($post, $method = 'classic', $get = 'settings') {
            $action = $post['gdr2_action'];
            $scope = $post['gdr2_scope'];
            $type = $post['gdr2_type'];
            $base = isset($post['gdr2_base']) ? $post['gdr2_base'] : 'gdr2_setting_';

            $settings = $this->$get('', $scope, $type);
            foreach ($settings as $el) {
                $key = $method == 'classic' ? $base.$el->name : $el->name;
                $value = $this->parse_post_element($post, $el, $key, $el->input);
                $this->set($scope, $type, $el->name, $value);
            }

            $this->save($scope, $type);
        }
    }
}

?>