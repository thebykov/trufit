<?php

/*
Name:    gdr2_DB
Version: 2.8.8
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.dev4press.com/libs/gdr2/
Info:    Class to handle database tables installation and upgrade

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

if (!class_exists('gdr2_DB')) {
    /**
     * Database tables installation and upgrade.
     */
    class gdr2_DB {
        var $queries;
        var $collation;
        var $root;
        var $code;

        function __construct($root, $code) {
            $this->collation = $this->get_collation();
            $this->root = trailingslashit($root);
            $this->code = $code;
            $this->queries = array();
        }

        function run($code, $ident, $sql) {
            global $wpdb;

            $wpdb->query($sql);

            $this->queries[$code][$ident]['sql'] = $sql;
            $this->queries[$code][$ident]['error'] = $wpdb->last_error;
        }

        function alter() {
            global $wpdb;

            $path = $this->root.'alter.txt';
            if (file_exists($path)) {
                $ctrl = file($path);

                foreach ($ctrl as $line) {
                    $line = trim($line);
                    if ($line != '') {
                        $line = str_replace('%PREFIX_BASE%', $wpdb->base_prefix, $line);
                        $line = str_replace('%PREFIX_SITE%', $wpdb->prefix, $line);
                        $line = str_replace('%PREFIX_PLUGIN%', $this->code, $line);

                        $wpdb->query($line);
                    }
                }
            }
        }
        
        function install($network = true, $site = true) {
            if ($network) {
                $this->install_folder('tables_site');
            }

            if ($site) {
                $this->install_folder('tables_blog');
            }
        }

        function install_folder($folder) {
            if (!file_exists($this->root.$folder.'/')) return;

            $tables = gdr2_Core::scan_dir($this->root.$folder.'/', 'files', array('txt'));
            if (!empty($tables)) {
                foreach ($tables as $table) {
                    $table = substr($table, 0, -4);
                    $this->table($folder, $table);
                }
            }
        }

        function table($folder, $table) {
            global $wpdb;

            $prefix = $folder == 'tables_site' && is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
            $prefix.= $this->code.'_';
            $file_path = $this->root.$folder.'/'.$table.'.txt';
            $table_name = $prefix.$table;

            $this->table_create($table_name, $file_path);
        }

        function table_upgrade($table_name, $file_path) {
            global $wpdb;

            if (file_exists($file_path)) {
                $columns = $wpdb->get_results(sprintf("SHOW COLUMNS FROM %s", $table_name));
                $file = file($file_path);
                $after = '';

                foreach ($file as $f) {
                    $f = trim($f);
                    if (substr($f, 0, 1) == "`") {
                        $column = substr($f, 1);
                        $column = substr($column, 0, strpos($column, "`"));
                        if (!$this->column_check($columns, $column)) {
                            $this->column_add($table_name, $column, $f, $after);
                        }
                        $after = $column;
                    }
                }
            }
        }

        function table_create($table_name, $file_path) {
            global $wpdb;

            if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                if (file_exists($file_path)) {
                    $sql = file_get_contents($file_path);
                    $sql = str_replace("%TABLE%", $table_name, $sql);
                    $sql = str_replace("%COLLATE%", $this->collation, $sql);
                    $this->run("table_create", $table_name, $sql);
                }
            } else {
                $this->table_upgrade($table_name, $file_path);
            }
        }

        function column_add($table_name, $column_name, $column_info, $position = '') {
            global $wpdb;

            if (substr($column_info, -1) == ",") {
                $column_info = substr($column_info, 0, strlen($column_info) - 1);
            }

            if ($position == '') {
                $position = "FIRST";
            } else {
                $position = "AFTER ".$position;
            }

            $sql = sprintf("ALTER TABLE %s ADD %s %s", $table_name, $column_info, $position);
            $this->run("column_create", $table_name." :: ".$column_name, $sql);
        }

        function column_check($all_columns, $column) {
            foreach ($all_columns as $c) {
                if ($c->Field == $column) return true;
            }

            return false;
        }

        function get_collation() {
            global $wpdb;
            $charset_collate = "";

            if ($wpdb->has_cap("collation")) {
                if (!empty($wpdb->charset)) $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
                if (!empty($wpdb->collate)) $charset_collate .= " COLLATE $wpdb->collate";
            }

            return $charset_collate;
        }
    }
}

?>