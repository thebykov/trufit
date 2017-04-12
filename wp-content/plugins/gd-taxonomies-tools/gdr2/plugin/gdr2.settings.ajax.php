<?php

/*
Name:    gdr2_Settings_Ajax
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

if (!class_exists('gdr2_Settings_Ajax')) {
    class gdr2_Settings_Ajax {
        public $remote = 'gdr2_remote';
        public $nonce = 'gdr2_remote';
        public $request = array();
        public $action = '';
        public $scope = 'site';
        public $response = 'json';

        function __construct() {
            add_action('wp_ajax_'.$this->remote, array(&$this, 'remote'));
        }

        public function remote() {
            check_ajax_referer($this->nonce);

            $this->request = $_REQUEST;
            $this->action = $this->request['gdr2_action'];

            if (isset($this->request['gdr2_scope'])) {
                $this->scope = $this->request['gdr2_scope'];
            }

            if (isset($this->request['gdr2_response'])) {
                $this->response = $this->request['gdr2_response'];
            }
        }

        public function respond($data) {
            switch ($this->response) {
                case 'json':
                    $data = json_encode($data);
                    break;
            }

            die($data);
        }
    }
}

?>