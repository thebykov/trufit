<?php

/*
Name:    gdr2_Shortcodes
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

if (!class_exists('gdr2_Shortcodes')) {
    abstract class gdr2_Shortcodes {
        public $aliases = true;
        public $atts_standard = true;
        public $atts_limiter = true;
        public $access_filter = 'gdr2_access_shortcode_';
        public $prefix = 'shortcode_';
        public $shortcodes = array();

        function __construct() {
            $data_defaults = array('name', 'args', 'atts', 'alias');

            $this->init();

            foreach ($this->shortcodes as $shortcode => $data) {
                $data = wp_parse_args($data, $data_defaults);
                add_shortcode($shortcode, array(&$this, $this->prefix.$shortcode));

                if ($this->aliases && !empty($data['alias'])) {
                    foreach ((array)$data['alias'] as $alias) {
                        add_shortcode($alias, array(&$this, $this->prefix.$shortcode));
                    }
                }
            }
        }

        public function atts($code, $atts) {
            $defaults = $this->shortcodes[$code]['atts'];

            if ($this->atts_standard) {
                $standard = array('id' => '', 'class' => '', 'style' => '');
                $defaults = array_merge($defaults, $standard);
            }

            if ($this->atts_limiter) {
                $limit = array('access_logged' => null, 'access_role' => null, 'access_caps' => null, 'access_message' => '', 'access_class' => '', 'access_style' => '');
                $defaults = array_merge($defaults, $limit);
            }

            return shortcode_atts($defaults, $atts);
        }

        public function tag($tag, $content = '', $atts = array(), $do_shortcode = true) {
            $defaults = array('id' => '', 'class' => '', 'style' => '');
            $atts = wp_parse_args($atts, $defaults);

            $render = '<'.$tag;

            foreach ($atts as $attribute => $value) {
                if ($value != '') {
                    $render.= ' '.$attribute.'="'.$value.'"';
                }
            }

            $render.= '>'.do_shortcode($content).'</'.$tag.'>';

            return $render;
        }

        public function to_show($code, $atts) {
            $to_show = true;

            if (isset($atts['access_logged']) && !is_null($atts['access_logged'])) {
                if ($atts['access_logged'] === true || $atts['access_logged'] == 'true') {
                    $to_show = is_user_logged_in();
                } else {
                    $to_show = !is_user_logged_in();
                }
            }

            if (isset($atts['access_role']) && !is_null($atts['access_role'])) {
                $to_show = gdr2_is_current_user_roles($atts['access_role']);
            }

            if (isset($atts['access_caps']) && !is_null($atts['access_caps'])) {
                $to_show = current_user_can($atts['access_caps']);
            }

            return apply_filters($this->access_filter.$code, $to_show);
        }

        public function show($render, $code, $atts) {
            if ($this->to_show($code, $atts)) {
                return $render;
            } else {
                if ($atts['access_message'] != '') {
                    $message = $this->tag('div', $atts['access_message'], array('class' => $atts['access_class'], 'style' => $atts['access_style']));
                    return $message;
                } else {
                    return '';
                }
            }
        }

        public function render($code, $atts, $content) {
            $fnc = $this->prefix.$code;

            return call_user_func(array(&$this, $fnc), $atts, $content);
        }

        public function _content($content) {
            if (substr($content, 0, 7) == '<p></p>') {
                $content = substr($content, 7);
            } else if (substr($content, 0, 4) == '</p>') {
                $content = substr($content, 4);
            } else if (substr($content, 0, 6) == '<br />') {
                $content = substr($content, 6);
            }

            if (substr($content, -7) == '<p></p>') {
                $content = substr($content, strlen($content) - 7);
            } else if (substr($content, -3) == '<p>') {
                $content = substr($content, 0, strlen($content) - 3);
            } else if (substr($content, -4) == '</p>') {
                $content = substr($content, 0, strlen($content) - 4);
            } else if (substr($content, -7) == '<br />') {
                $content = substr($content, 0, strlen($content) - 7);
            }

            $content = str_replace('<p></p>', '', $content);

            return trim($content);
        }

        abstract public function init();
    }
}

?>