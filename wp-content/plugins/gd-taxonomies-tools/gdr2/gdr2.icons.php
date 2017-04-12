<?php

/*
Name:    gdr2_MenuIcons
Version: 2.8.8
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.dev4press.com/libs/gdr2/
Info:    Core class with static functions with extra functionality classes

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

if (!class_exists('gdr2_MenuIcons')) {
    /**
     * Class to implement icons for the main menu. 
     */
    abstract class gdr2_MenuIcons {
        public $icons = array();
        public $wp_version = '';

        public $url_blank = '';
        public $url_types = '';

        /**
         * Create the object for icons for WordPress main menu.
         */
        function __construct() {
            global $wp_version;
            $this->wp_version = substr(str_replace('.', '', $wp_version), 0, 2);

            $this->init_links();
            $this->init_icons();
        }

        /**
         * Function to set up links to blank icon and post types icons files.
         */
        abstract public function init_links();

        /**
         * Function to init names for the images in post types icons file.
         */
        abstract public function init_icons();

        /**
         * Get IMG to be used for icon.
         *
         * @param string $name icon name
         * @return string css for the icon
         */
        public function get_img($name) {
            $x = $this->get_location($name) - 7;

            $img = '<img src="'.$this->url_blank.'" width="16" height="16"';
            $img.= ' style="background: url('.$this->url_types.') no-repeat scroll ';
            $img.= $x.'px -8px transparent !important; }" />';
            return $img;
        }

        /**
         * Get CSS to be used for icon in the WordPress main menu.
         *
         * @param string $name icon name
         * @param string $post_type name for the post type
         * @return string css for the icon
         */
        public function get_css($name, $post_type) {
            $css = GDR2_EOL.'/* '.$post_type.': '.$name.' */'.GDR2_EOL;

            switch ($this->wp_version) {
                default:
                    $css.= $this->css_wp32($name, $post_type);
                    break;
                case '38':
                    $css.= $this->css_wp38($name, $post_type);
                    break;
                case '39':
                case '40':
                case '41':
                case '42':
                case '43':
                case '44':
                case '45':
                    $css.= $this->css_wp39($name, $post_type);
                    break;
            }

            return $css;
        }

        /**
         * Get location for the icon.
         *
         * @param string $name icon name
         * @return int pixels location
         */
        public function get_location($name) {
            if (gdr2_is_array_associative($this->icons)) {
                $id = array_search($name, array_keys($this->icons));
            } else {
                $id = array_search($name, $this->icons);
            }

            return -$id * 30;
        }

        private function css_wp32($name, $post_type) {
            $x = $this->get_location($name);

            $css = '#menu-posts-'.$post_type.' .wp-menu-image,'.GDR2_EOL;
            $css.= '#menu-posts-'.$post_type.':hover .wp-menu-image,'.GDR2_EOL;
            $css.= '#menu-posts-'.$post_type.'.wp-has-current-submenu .wp-menu-image { ';
            $css.= 'background: url('.$this->url_types.') !important; background-repeat: no-repeat !important; background-color: transparent !important; }'.GDR2_EOL;
            $css.= '#menu-posts-'.$post_type.' .wp-menu-image { ';
            $css.= 'background-position: '.$x.'px -33px !important; }'.GDR2_EOL.GDR2_EOL;

            $css.= '#menu-posts-'.$post_type.':hover .wp-menu-image,'.GDR2_EOL;
            $css.= '#menu-posts-'.$post_type.'.wp-has-current-submenu .wp-menu-image { ';
            $css.= 'background-position: '.$x.'px -1px !important; }'.GDR2_EOL;

            return $css;
        }

        private function css_wp38($name, $post_type) {
            $css = $this->css_wp32($name, $post_type);

            $css.= '#menu-posts-'.$post_type.' .wp-menu-image:before { display: none; }'.GDR2_EOL;
            $css.= '#menu-posts-'.$post_type.' .wp-menu-image { margin-top: 3px !important; }'.GDR2_EOL;

            return $css;
        }

        private function css_wp39($name, $post_type) {
            $css = $this->css_wp32($name, $post_type);

            $css.= '#menu-posts-'.$post_type.' .wp-menu-image:before { display: none; }'.GDR2_EOL;
            $css.= '#menu-posts-'.$post_type.' .wp-menu-image { height: 28px !important; margin-top: 3px !important; }'.GDR2_EOL;

            return $css;
        }
    }
}

?>