<?php

/*
Name:    gdr2_DB
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

if (!class_exists('gdr2_Plugin_DB')) {
    class gdr2_Plugin_DB {
        public $plugin_prefix_db = 'gdr2';
        public $db_tables = array(
            'network' => array(),
            'site' => array()
        );
        public $db_metas = array();
        public $statistics = array('prepare' => 0, 'query' => 0, 
            'get_var' => 0, 'get_col' => 0, 'get_row' => 0,
            'get_results' => 0, 'insert' => 0, 'update' => 0
        );
        public $db;

        function __construct() {
            global $wpdb;

            $tables = array_merge($this->db_tables['network'], $this->db_tables['site']);
            $plugin = new gdrBase();
            $this->db = new gdrBase();

            foreach ($tables as $name) {
                $real_name = $wpdb->prefix.$this->plugin_prefix_db.'_'.$name;

                $plugin->$name = $real_name;
                $this->db->$name = $real_name;
            }

            $wpdb->{$this->plugin_prefix_db} = $plugin;
        }

        public function t($name) {
            return $this->db->$name;
        }

        public function query($query) {
            global $wpdb;

            $this->statistics['query']++;
            return $wpdb->query($query);
        }

        public function get_insert_id() {
            global $wpdb;
            return $wpdb->insert_id;
        }

        public function get_datetime($timestamp = null) {
            if (is_null($timestamp)) {
                $timestamp = time();
            }

            return date('Y-m-d H:i:s', $timestamp);
        }

        public function get_var($query, $x = 0, $y = 0) {
            global $wpdb;

            $this->statistics['get_var']++;
            return $wpdb->get_var($query, $x, $y);
        }

        public function get_row($query = null, $output = OBJECT, $y = 0) {
            global $wpdb;

            $this->statistics['get_row']++;
            return $wpdb->get_row($query, $output, $y);
        }

        public function get_col($query = null , $x = 0) {
            global $wpdb;

            $this->statistics['get_col']++;
            return $wpdb->get_col($query, $x);
        }

        public function get_results($query = null, $output = OBJECT) {
            global $wpdb;

            $this->statistics['get_results']++;
            return $wpdb->get_results($query, $output);
        }

        public function insert($table, $data, $format = null) {
            global $wpdb;

            $this->statistics['insert']++;
            return $wpdb->insert($this->t($table), $data, $format);
        }

        public function update($table, $data, $where, $format = null, $where_format = null) {
            global $wpdb;

            $this->statistics['update']++;
            return $wpdb->update($this->t($table), $data, $where, $format, $where_format);
        }

        public function get_metadata($table, $object_id, $meta_key = '', $single = false) {
            if (!$column = $this->db_metas[$table]) {
                return;
            }

            global $wpdb;
            $_table = $this->t($table);

            $cache = array();
            $id_list = join(',', array($object_id));

            $sql = $wpdb->prepare("SELECT $column, meta_key, meta_value FROM $_table WHERE $column IN (%s)", $id_list);
            $meta_list = $this->get_results($sql, ARRAY_A);

            if (!empty($meta_list)) {
		foreach ($meta_list as $metarow) {
                    $mpid = intval($metarow[$column]);
                    $mkey = $metarow['meta_key'];
                    $mval = $metarow['meta_value'];

                    if (!isset($cache[$mpid]) || !is_array($cache[$mpid])) {
                        $cache[$mpid] = array();
                    }

                    if (!isset($cache[$mpid][$mkey]) || !is_array($cache[$mpid][$mkey])) {
                        $cache[$mpid][$mkey] = array();
                    }

                    $cache[$mpid][$mkey][] = $mval;
		}
            }

            if (isset($cache[$object_id])) {
                $meta_cache = $cache[$object_id];

                if (!$meta_key) {
                    return $meta_cache;
                }

                if (isset($meta_cache[$meta_key])) {
                    if ($single) {
                        return maybe_unserialize($meta_cache[$meta_key][0]);
                    } else {
			return array_map('maybe_unserialize', $meta_cache[$meta_key]);
                    }
                }
            } else {
                if ($single) {
                    return '';
                } else {
                    return array();
                }
            }
        }

        public function add_metadata($table, $object_id, $meta_key, $meta_value, $unique = false) {
            if (!$column = $this->db_metas[$table]) {
                return;
            }

            $_table = $this->t($table);
            $meta_key = stripslashes($meta_key);
            $meta_value = maybe_serialize(stripslashes_deep($meta_value));

            global $wpdb;
            $sql = $wpdb->prepare("SELECT COUNT(*) FROM $_table WHERE meta_key = %s AND $column = %d", $meta_key, $object_id);

            if ($unique && $this->get_var($sql)) {
		return false;
            }

            $result = $this->insert($table, array($column => $object_id, 'meta_key' => $meta_key, 'meta_value' => $meta_value));

            if (!$result) {
		return false;
            } else {
            	return (int)$this->get_insert_id();
            }
        }

        public function update_metadata($table, $object_id, $meta_key, $meta_value, $prev_value = '') {            
            if (!$column = $this->db_metas[$table]) {
                return;
            }

            $_table = $this->t($table);
            $meta_key = stripslashes($meta_key);
            $passed_value = $meta_value;
            $meta_value = stripslashes_deep($meta_value);

            global $wpdb;
            $sql = $wpdb->prepare("SELECT meta_id FROM $_table WHERE meta_key = %s AND $column = %d", $meta_key, $object_id);

            if (!$meta_id = $this->get_var($sql)) {
		return $this->add_metadata($table, $object_id, $meta_key, $passed_value);
            }

            if (empty($prev_value)) {
		$old_value = $this->get_metadata($table, $object_id, $meta_key);
		if (count($old_value) == 1) {
                    if ($old_value[0] === $meta_value) {
                        return false;
                    }
		}
            }

            $meta_value = maybe_serialize($meta_value);
            $data  = compact('meta_value');
            $where = array($column => $object_id, 'meta_key' => $meta_key);

            if (!empty($prev_value)) {
		$prev_value = maybe_serialize($prev_value);
		$where['meta_value'] = $prev_value;
            }

            $this->update($table, $data, $where);

            return true;
        }

        public function delete_metadata($table, $object_id, $meta_key, $meta_value = '', $delete_all = false) {
            if (!$column = $this->db_metas[$table]) {
                return;
            }

            $_table = $this->t($table);
            $meta_key = stripslashes($meta_key);
            $meta_value = maybe_serialize(stripslashes_deep($meta_value));

            global $wpdb;
            $query = $wpdb->prepare("SELECT meta_id FROM $_table WHERE meta_key = %s", $meta_key);

            if (!$delete_all) {
                $query.= $wpdb->prepare(" AND $column = %d", $object_id);
            }

            if ($meta_value) {
                $query.= $wpdb->prepare(" AND meta_value = %s", $meta_value);
            }

            $meta_ids = $this->get_col($query);
            if (!count($meta_ids)) {
		return false;
            }

            $query = "DELETE FROM $_table WHERE meta_id IN (".implode(',', $meta_ids).")";
            $count = $this->query($query);

            return $count === false ? false : true;
        }
    }
}

?>