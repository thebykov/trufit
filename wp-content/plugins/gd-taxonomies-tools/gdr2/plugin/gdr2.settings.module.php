<?php

/*
Name:    gdr2_Settings_Module
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

if (!class_exists('gdr2_Module')) {
    class gdr2_Module {
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
        public $subtitle = '';
        public $description = '';
        public $url = '';

        public $author_name = '';
        public $author_email = '';
        public $author_web = '';

        function __construct() { }
    }
}

if (!class_exists('gdr2_Module_Settings')) {
    class gdr2_Module_Settings {
        public $_product = 'gdr2_Module';
        public $_settings = array();
        public $system_keys = array('__core__', '__scope__', '__name__', '__date__');

        public $auto_load = false;

        function __construct() {
            $class = $this->_product;
            $this->_product = new $class();
        }

        public function get_defaults($scope = 'site') {
            $informal = array('__core__' => (array)$this->_product,
                              '__scope__' => $scope,
                              '__name__' => $this->_product->name,
                              '__date__' => date('r'));

            return array_merge($informal, $this->_settings);
        }

        public function update($current, $scope = 'site') {
            $current = gdr2_Core::upgrade_settings($current, $this->get_defaults($scope));

            $current['__core__'] = (array)$this->_product;
            $current['__scope__'] = $scope;
            $current['__name__'] = $this->_product->name;
            $current['__date__'] = date('r');

            return $current;
        }
    }
}

?>