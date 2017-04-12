<?php

/*
Name:    gdr2_Cache
Version: 2.8.8
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.dev4press.com/libs/gdr2/
Info:    Cache class and functions

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

if (!class_exists('gdr2_Cache')) {
    /**
     * Cache data using different methods. Transient method supports both site 
     * and network database tables caching.
     */
    class gdr2_Cache {
        public $objects = array();
        public $hits = 0;
        public $size = 0;
        public $misses = 0;

        private $_methods = array('transient');
        private $_method = 'transient';
        private $_length;
        private $_prefix;

        /**
         * Construct the object.
         *
         * @param type $method main caching method to use.
         * @param type $prefix prefix to use to name the stored data
         */
        function __construct($method = 'transient', $prefix = 'gdr2_') {
            $this->_check_apc();

            if (in_array($method, $this->_methods)) {
                $this->_method = $method;
            }

            $this->_prefix = $prefix;
            $this->_length = 45 - strlen($prefix);
            define('GDR2_CACHE_ACTIVE', true);
        }

        private function _check_apc() {
            if (function_exists('apc_cache_info')) {
                $this->_methods[] = 'apc';
            }
        }

        private function _name($name) {
            return substr($name, 0, $this->_length);
        }

        /**
         * Display the caching statistics.
         */
	public function stats() {
            echo '<p>';
                echo '<strong>'.__("Cache Misses", "gdr2").':</strong> '.$this->misses.'<br />';
                echo '<strong>'.__("Cache Hits", "gdr2").':</strong> '.$this->hits.'<br />';
                echo '<strong>'.__("Cache Size", "gdr2").':</strong> '.gdr2_Core::size_format($this->size);
            echo '</p>';

            if (!empty($this->objects)) {
                echo '<ul>';
                foreach ($this->objects as $key => $size) {
                    echo '<li>'.$key.': '.gdr2_Core::size_format($size).'</li>';
                }
                echo '</ul>';
            }
	}

        /**
         * Get name for the current main cachine method.
         *
         * @return string method name
         */
        public function get_method() {
            return $this->_method;
        }

        /**
         * Set caching method.
         *
         * @param type $method main caching method to use.
         */
        public function set_method($method = 'transient') {
            if (in_array($method, $this->_methods)) {
                $this->_method = $method;
            }
        }

        /**
         * Delete object from cache. For transient cache, the is for network 
         * meta settings table.
         *
         * @param string $name name of the cached object
         * @param string $method set for method override
         * @return bool true if deletion is successful
         */
        public function del_network($name, $method = '') {
            if ($method == '') {
                $method = $this->_method;
            }

            switch ($method) {
                default:
                case 'transient':
                    return delete_site_transient($this->_prefix.$this->_name($name));
                    break;
                case 'apc':
                    return $this->del_site($name, $method);
                    break;
            }
        }

        /**
         * Delete object from cache. For transient cache, the is for site meta
         * settings table.
         *
         * @param string $name name of the cached object
         * @param string $method set for method override
         * @return bool true if deletion is successful
         */
        public function del_site($name, $method = '') {
            if ($method == '') {
                $method = $this->_method;
            }

            switch ($method) {
                default:
                case 'transient':
                    return delete_transient($this->_prefix.$this->_name($name));
                    break;
            }
        }

        /**
         * Get object from the cache. For transient cache, the is for network 
         * meta settings table.
         *
         * @param string $name name of the cached object
         * @param string $method set for method override
         * @return mixed cached object if found, false if it is not found 
         */
        public function get_network($name, $method = "") {
            if ($method == '') {
                $method = $this->_method;
            }

            $data = false;

            switch ($method) {
                default:
                case 'transient':
                    $data = get_site_transient($this->_prefix.$this->_name($name));
                    break;
                case 'apc':
                    $data = $this->get_site($name, $method);
                    break;
            }

            if ($data === false) {
                $this->misses++;
            } else {
                $size = strlen(serialize($data));
                if (defined('WP_DEBUG') && WP_DEBUG) $this->objects[$name] = $size;
                $this->size+= $size;
                $this->hits++;
            }

            return $data;
        }

        /**
         * Get object from the cache. For transient cache, the is for site meta 
         * settings table.
         *
         * @param string $name name of the cached object
         * @param string $method set for method override
         * @return mixed cached object if found, false if it is not found 
         */
        public function get_site($name, $method = '') {
            if ($method == '') {
                $method = $this->_method;
            }

            $data = false;

            switch ($method) {
                default:
                case 'transient':
                    $data = get_transient($this->_prefix.$this->_name($name));
                    break;
            }

            if ($data === false) {
                $this->misses++;
            } else {
                $this->size+= strlen(serialize($data));
                $this->hits++;
            }

            return $data;
        }

        /**
         * Store object into cache. For transient cache, the is for network 
         * meta settings table.
         *
         * @param string $name name of the cached object
         * @param mixed $value object or variable to store
         * @param int $ttl cache validity lenght in seconds
         * @param string $method set for method override
         * @return mixed cached object if found, false if it is not found 
         */
        public function set_network($name, $value, $ttl = 43200, $method = '') {
            if ($method == '') {
                $method = $this->_method;
            }

            switch ($method) {
                default:
                case 'transient':
                    return set_site_transient($this->_prefix.$this->_name($name), $value, $ttl);
                    break;
                case 'apc':
                    return $this->set_site($name, $value, $ttl, $method);
                    break;
            }
        }

        /**
         * Store object into cache. For transient cache, the is for site meta 
         * settings table.
         *
         * @param string $name name of the cached object
         * @param mixed $value object or variable to store
         * @param int $ttl cache validity lenght in seconds
         * @param string $method set for method override
         * @return mixed cached object if found, false if it is not found 
         */
        public function set_site($name, $value, $ttl = 43200, $method = "") {
            if ($method == '') {
                $method = $this->_method;
            }

            switch ($method) {
                default:
                case 'transient':
                    return set_transient($this->_prefix.$this->_name($name), $value, $ttl);
                    break;
            }
        }
    }

    global $gdr2_cache_core;
    $gdr2_cache_core = new gdr2_Cache();
}

if (!function_exists('gdr2c_del')) {
    function gdr2c_del($name, $method = '') {
        global $gdr2_cache_core;
        return $gdr2_cache_core->del_site($name, $method);
    }
}

if (!function_exists('gdr2c_get')) {
    function gdr2c_get($name, $method = '') {
        global $gdr2_cache_core;
        return $gdr2_cache_core->get_site($name, $method);
    }
}

if (!function_exists('gdr2c_set')) {
    function gdr2c_set($name, $value, $ttl = 43200, $method = '') {
        global $gdr2_cache_core;
        return $gdr2_cache_core->set_site($name, $value, $ttl, $method);
    }
}

if (!function_exists('gdr2c_del_network')) {
    function gdr2c_del_network($name, $method = '') {
        global $gdr2_cache_core;
        return $gdr2_cache_core->del_network($name, $method);
    }
}

if (!function_exists('gdr2c_get_network')) {
    function gdr2c_get_network($name, $method = '') {
        global $gdr2_cache_core;
        return $gdr2_cache_core->get_network($name, $method);
    }
}

if (!function_exists('gdr2c_set_network')) {
    function gdr2c_set_network($name, $value, $ttl = 43200, $method = '') {
        global $gdr2_cache_core;
        return $gdr2_cache_core->set_network($name, $value, $ttl, $method);
    }
}

?>