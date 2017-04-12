<?php

/*
Name:    gdr2_Log
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

if (!class_exists('gdr2_Log')) {
    /**
     * Class for saving debug dumps into file.
     */
    class gdr2_Log {
        var $log_file;
        var $active = false;

        /**
         * Constructor
         *
         * @param string $log_path full path to the log file.
         */
        function __construct($log_path = '') {
            $this->log_file = $log_path;

            if ($this->log_file != '') {
                if (file_exists($this->log_file) && is_writable($this->log_file)) {
                    $this->active = true;
                }
            }
        }

        /**
         * Prepare array name/value pairs for logging.
         *
         * @param array $info value pairs to prepare
         * @return string resulting values
         */
        private function prepare_array($info) {
            $wr = "";
            foreach ($info as $name => $value) {
                $wr.= $name.': '.$value."\r\n";
            }
            return $wr;
        }

        /**
         * Truncates log file to zero lenght deleting all data inside.
         */
        public function truncate() {
            $f = fopen($this->log_file, 'w+');
            fclose($f);
        }

        /**
         * Writes log info name/value into the file withou heading.
         *
         * @param mixed $object object to dump
         * @param string $mode file open mode
        */
        public function slog($info, $mode = 'a+') {
            if ($this->active) {
                $f = fopen($this->log_file, $mode);
                fwrite ($f, "$info");
                fwrite ($f, "\r\n");
                fclose($f);
            }
        }

        /**
         * Writes log info name/value into the file.
         *
         * @param string $msg log entry message
         * @param mixed $object object to dump
         * @param string $mode file open mode
        */
        public function log($msg, $info, $mode = 'a+') {
            if ($this->active) {
                $info = $this->prepare_array($info);
                $f = fopen($this->log_file, $mode);
                fwrite ($f, sprintf("[%s] : %s\r\n", date('Y-m-d h:i:s'), $msg));
                fwrite ($f, "$info");
                fwrite ($f, "\r\n");
                fclose($f);
            }
        }

        /**
         * Writes object dump into the log file withou heading.
         *
         * @param mixed $object object to dump
         * @param string $mode file open mode
        */
        public function sdump($object, $mode = 'a+') {
            if ($this->active) {
                $obj = print_r($object, true);
                $f = fopen($this->log_file, $mode);
                fwrite ($f, "$obj");
                fwrite ($f, "\r\n");
                fclose($f);
            }
        }

        /**
         * Writes a object dump into the log file.
         *
         * @param string $msg log entry message
         * @param mixed $object object to dump
         * @param string $block adds start or end dump limiters { none | start | end }
         * @param string $mode file open mode
        */
        public function dump($msg, $object, $block = 'none', $mode = 'a+') {
            if ($this->active) {
                $obj = print_r($object, true);
                $f = fopen($this->log_file, $mode);

                if ($block == 'start') {
                    fwrite ($f, "-- DUMP BLOCK STARTED ---------------------------------- \r\n");
                }

                fwrite ($f, sprintf("[%s] : %s\r\n", date('Y-m-d h:i:s'), $msg));
                fwrite ($f, "$obj");
                fwrite ($f, "\r\n");

                if ($block == 'end') {
                    fwrite ($f, "-------------------------------------------------------- \r\n");
                }

                fclose($f);
            }
        }
    }
}

?>