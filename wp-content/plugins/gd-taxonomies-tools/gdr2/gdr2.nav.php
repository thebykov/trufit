<?php

/*
Name:    gdr2_Navigator
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

if (!class_exists('gdr2_Navigator')) {
    /**
     * Rendering of various navigation elements.
     */
    class gdr2_Navigator {
        public $nav_pager_variable = 'page';

        function __construct() { }

        /**
         * Modify current URL to include page number variable.
         *
         * @param int $id page id
         * @return string modified url
         */
        public function pager_nav_link($id = 1) {
            if ($id > 1) {
                return esc_url_raw(add_query_arg($this->nav_pager_variable, $id));
            } else {
                return esc_url_raw(remove_query_arg($this->nav_pager_variable));
            }
        }

        /**
         * Render simple next/previous navigation.
         *
         * @param array $args navigation settings
         * @param bool $echo echo content or return it as a string
         * @return string rendered navigation control
         */
        public function pager_simple($args = array(), $echo = true) {
            $defaults = array(
                'next_text' => null,
                'prev_text' => null);
            $args = wp_parse_args($args, $defaults);

            $result = '<div class="nav-next">'.get_previous_posts_link($args['prev_text']).'</div>';
            $result.= '<div class="nav-previous">'.get_next_posts_link($args['next_text']).'</div>';

            if ($echo) {
                echo $result;
            } else {
                return $result;
            }
        }

        /**
         * Render numbered pages navigation.
         *
         * @param array $args navigation settings
         * @param bool $echo echo content or return it as a string
         * @return string rendered navigation control
         */
        public function pager_advanced($args = array(), $echo = true) {
            global $wp_query;
            $current_page = intval(get_query_var('paged'));
            if (empty($current_page) || $current_page == 0) $current_page = 1;
            $max_pages = intval($wp_query->max_num_pages);

            $result = $this->pager_advanced_core($max_pages, $current_page, $args, false);

            if ($echo) {
                echo $result;
            } else {
                return $result;
            }
        }
        
        /**
         * Render numbered pages navigation, basic navigation code for custom
         * page values, independent from WP Query.
         * 
         * Function is based on the function from the WP-PageNavi plugin by Lester Chan.
         * Original Plugin URL: http://lesterchan.net/portfolio/programming/php/#wp-pagenavi
         *
         * @param int $max_pages total pages
         * @param int $current_page current page
         * @param array $args navigation settings
         * @param bool $echo echo content or return it as a string
         * @return string rendered navigation control
         */
        public function pager_advanced_core($max_pages, $current_page, $args = array(), $echo = true) {
            $defaults = array(
                'link_callback' => 'get_pagenum_link',
                'pages_text' => __("Page %CURRENT_PAGE% of %TOTAL_PAGES%", "gdr2"),
                'current_text' => __("%PAGE_NUMBER%", "gdr2"),
                'page_text' => __("%PAGE_NUMBER%", "gdr2"),
                'first_text' => __("&laquo; First", "gdr2"),
                'last_text' => __("Last &raquo;", "gdr2"),
                'next_text' => __("&raquo;", "gdr2"),
                'prev_text' => __("&laquo;", "gdr2"),
                'dotright_text' => __("...", "gdr2"),
                'dotleft_text' => __("...", "gdr2"),
                'num_pages' => 5,
                'always_show' => 0,
                'num_larger_page_numbers' => 3,
                'larger_page_numbers_multiple' => 10);
            $args = wp_parse_args($args, $defaults);

            $pages_to_show = intval($args['num_pages']);
            $larger_page_to_show = intval($args['num_larger_page_numbers']);
            $larger_page_multiple = intval($args['larger_page_numbers_multiple']);
            $pages_to_show_minus_1 = $pages_to_show - 1;
            $half_page_start = floor($pages_to_show_minus_1 / 2);
            $half_page_end = ceil($pages_to_show_minus_1 / 2);
            $start_page = $current_page - $half_page_start;

            if ($start_page <= 0) $start_page = 1;

            $end_page = $current_page + $half_page_end;
            if (($end_page - $start_page) != $pages_to_show_minus_1) {
                $end_page = $start_page + $pages_to_show_minus_1;
            }

            if ($end_page > $max_pages) {
                $start_page = $max_pages - $pages_to_show_minus_1;
                $end_page = $max_pages;
            }

            if ($start_page <= 0) {
                $start_page = 1;
            }

            $larger_pages_array = array();
            if ($larger_page_multiple) {
                for ($i = $larger_page_multiple; $i <= $max_pages; $i += $larger_page_multiple) {
                    $larger_pages_array[] = $i;
                }
            }

            $result = "";
            if ($max_pages > 1 || intval($args['always_show'])) {
                $pages_text = str_replace('%CURRENT_PAGE%', number_format_i18n($current_page), $args['pages_text']);
                $pages_text = str_replace('%TOTAL_PAGES%', number_format_i18n($max_pages), $pages_text);
                $result.= '<div class="gdr2-nav-pager">'."\n";
                if (!empty($pages_text)) {
                    $result.= '<span class="pages">'.$pages_text.'</span>';
                }
                if ($start_page >= 2 && $pages_to_show < $max_pages) {
                    $first_page_text = str_replace('%TOTAL_PAGES%', number_format_i18n($max_pages), $args['first_text']);
                    $result.= '<a href="'.esc_url(call_user_func($args['link_callback'])).'" class="first" title="'.$first_page_text.'">'.$first_page_text.'</a>';
                    if (!empty($args['dotleft_text'])) {
                        $result.= '<span class="extend">'.$args['dotleft_text'].'</span>';
                    }
                }
                $larger_page_start = 0;
                foreach($larger_pages_array as $larger_page) {
                    if ($larger_page < $start_page && $larger_page_start < $larger_page_to_show) {
                        $page_text = str_replace('%PAGE_NUMBER%', number_format_i18n($larger_page), $args['page_text']);
                        $result.= '<a href="'.esc_url(call_user_func($args['link_callback'], $larger_page)).'" class="page" title="'.$page_text.'">'.$page_text.'</a>';
                        $larger_page_start++;
                    }
                }

                for($i = $start_page; $i  <= $end_page; $i++) {
                    if ($i == $current_page) {
                        $current_page_text = str_replace('%PAGE_NUMBER%', number_format_i18n($i), $args['current_text']);
                        $result.= '<span class="current">'.$current_page_text.'</span>';
                    } else {
                        $page_text = str_replace('%PAGE_NUMBER%', number_format_i18n($i), $args['page_text']);
                        $result.= '<a href="'.esc_url(call_user_func($args['link_callback'], $i)).'" class="page" title="'.$page_text.'">'.$page_text.'</a>';
                    }
                }

                $larger_page_end = 0;
                foreach($larger_pages_array as $larger_page) {
                    if ($larger_page > $end_page && $larger_page_end < $larger_page_to_show) {
                        $page_text = str_replace('%PAGE_NUMBER%', number_format_i18n($larger_page), $args['page_text']);
                        $result.= '<a href="'.esc_url(call_user_func($args['link_callback'], $larger_page)).'" class="page" title="'.$page_text.'">'.$page_text.'</a>';
                        $larger_page_end++;
                    }
                }
                if ($end_page < $max_pages) {
                    if (!empty($args['dotright_text'])) {
                        $result.= '<span class="extend">'.$args['dotright_text'].'</span>';
                    }
                    $last_page_text = str_replace('%TOTAL_PAGES%', number_format_i18n($max_pages), $args['last_text']);
                    $result.= '<a href="'.esc_url(call_user_func($args['link_callback'], $max_pages)).'" class="last" title="'.$last_page_text.'">'.$last_page_text.'</a>';
                }
                $result.= '</div>'."\n";
            }

            if ($echo) {
                echo $result;
            } else {
                return $result;
            }
        }
    }
}

?>