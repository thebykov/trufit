<?php

/*
Name:    gdr2_UI
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

if (!class_exists('gdr2_UI')) {
    /**
     * Rendering visual elements.
     */
    class gdr2_UI {
        /**
         * Get valid ID for control from name.
         *
         * @param string $name name for the control
         * @param string $id id for the control
         * @return string valid id
         */
        public static function get_id($name, $id = '') {
            return gdr2_html_id_from_name($name, $id);
        }

        public static function draw_heading($number, $content, $echo = true) {
            $render = '<h'.$number.'>'.$content.'</h'.$number.'>';

            if ($echo) {
                echo $render;
            } else {
                return $render;
            }
        }

        /**
         * Render the label HTML control.
         * 
         * @param string $title title for the label
         * @param string $id id for the control
         * @param bool $br add line break tag
         * @param bool $echo display or return rendered control
         * @return string rendered control
         */
        public static function draw_label($title, $id = '', $br = false, $echo = true) {
            $render = '<label for='.$id.'>'.$title.'</label>';

            if ($br) {
                $render.= '<br/>';
            }

            if ($echo) {
                echo $render;
            } else {
                return $render;
            }
        }

        /**
         * Render the select HTML control.
         *
         * @param array $values associated array with values
         * @param string $selected selected value
         * @param string $name name for the control
         * @param string $id id for the control
         * @param string $class classes to add to control
         * @param string $style styles to add to control
         * @param bool $echo display or return rendered control
         * @return string rendered control
         */
        public static function draw_select_checked($values, $selected, $name, $id = '', $class = '', $style = '', $echo = true) {
            $render = '';
            $selected = (array)$selected;
            $id = gdr2_html_id_from_name($name, $id);

            if ($class != '') {
                $class = ' class="'.$class.'"';
            }

            if ($style != '') {
                $style = ' style="'.$style.'"';
            }

            foreach ($values as $value => $display) {
                $sel = in_array($value, $selected) ? ' checked' : '';
                $elid = gdr2_sanitize_simple($id."-".$value);
                $render.= '<input type="checkbox" id="'.$elid.'" name="'.$name.'[]" value="'.esc_attr($value).'"'.$sel.$class.$style.' /><label for="'.$elid.'">'.$display.'</label><br/>';
            }

            if ($echo) {
                echo $render;
            } else {
                return $render;
            }
        }

        /**
         * Render the select HTML control.
         *
         * @param array $values associated array with values
         * @param array|string $selected selected value
         * @param string $name name for the control
         * @param string $id id for the control
         * @param string $class classes to add to control
         * @param string $style styles to add to control
         * @param bool $multi add multiple argument to select box
         * @param bool $echo display or return rendered control
         * @return string rendered control
         */
        public static function draw_select($values, $selected, $name, $id = '', $class = '', $style = '', $multi = false, $echo = true) {
            $render = '';
            $selected = (array)$selected;
            $id = gdr2_html_id_from_name($name, $id);

            if ($class != '') {
                $class = ' class="'.$class.'"';
            }

            if ($style != '') {
                $style = ' style="'.$style.'"';
            }

            $multi = $multi ? ' multiple' : '';
            $name = $multi ? $name."[]" : $name;

            $render.= '<select name="'.$name.'" id="'.$id.'"'.$class.$style.$multi.'>';
            foreach ($values as $value => $display) {
                $sel = in_array($value, $selected) ? ' selected="selected"' : '';
                $render.= '<option value="'.esc_attr($value).'"'.$sel.'>'.$display.'</option>';
            }
            $render.= '</select>';

            if ($echo) {
                echo $render;
            } else {
                return $render;
            }
        }

        /**
         * Render the select HTML control with grouped values.
         *
         * @param array $values associated array with values
         * @param array|string $selected selected value
         * @param string $name name for the control
         * @param string $id id for the control
         * @param string $class classes to add to control
         * @param string $style styles to add to control
         * @param bool $multi add multiple argument to select box
         * @param bool $echo display or return rendered control
         * @return string rendered control
         */
        public static function draw_select_grouped($values, $selected, $name, $id = '', $class = '', $style = '', $multi = false, $echo = true) {
            $render = '';
            $selected = (array)$selected;
            $id = gdr2_html_id_from_name($name, $id);

            if ($class != '') {
                $class = ' class="'.$class.'"';
            }

            if ($style != '') {
                $style = ' style="'.$style.'"';
            }

            $multi = $multi ? ' multiple' : '';
            $name = $multi ? $name."[]" : $name;

            $render.= '<select name="'.$name.'" id="'.$id.'"'.$class.$style.$multi.'>';
            foreach ($values as $group) {
                $render.= '<optgroup label="'.$group['title'].'">';
                foreach ($group['values'] as $value => $display) {
                    $sel = in_array($value, $selected) ? ' selected="selected"' : '';
                    $render.= '<option value="'.esc_attr($value).'"'.$sel.'>'.$display.'</option>';
                }
                $render.= '</optgroup>';
            }
            $render.= '</select>';

            if ($echo) {
                echo $render;
            } else {
                return $render;
            }
        }

        /**
         * Render the input hidden HTML control.
         *
         * @param string $value control value
         * @param string $name name for the control
         * @param string $id id for the control
         * @param string $class classes to add to control
         * @param string $style styles to add to control
         * @param bool $echo display or return rendered control
         * @return string rendered control
         */
        public static function draw_input_hidden($value, $name, $id = '', $class = '', $echo = true) {
            $id = gdr2_html_id_from_name($name, $id);

            if ($class != '') {
                $class = ' class="'.$class.'"';
            }

            $render = '<input type="hidden" name="'.$name.'" value="'.esc_attr($value).'" id="'.$id.'"'.$class.' />';

            if ($echo) {
                echo $render;
            } else {
                return $render;
            }
        }

        /**
         * Render the input button HTML control.
         *
         * @param string $value control value
         * @param string $name name for the control
         * @param string $id id for the control
         * @param string $type input button type
         * @param string $class classes to add to control
         * @param string $style styles to add to control
         * @param bool $echo display or return rendered control
         */
        public static function draw_input_button($value, $name, $id = '', $type = 'submit', $class = '', $style = '', $echo = true) {
            $id = gdr2_html_id_from_name($name, $id);

            if ($class != '') {
                $class = ' class="'.$class.'"';
            }

            if ($style != '') {
                $style = ' style="'.$style.'"';
            }

            $render = '<input type="'.$type.'" name="'.$name.'" value="'.esc_attr($value).'" id="'.$id.'"'.$class.' />';

            if ($echo) {
                echo $render;
            } else {
                return $render;
            }
        }
        
        /**
         * Render the input text HTML control.
         *
         * @param string $value control value
         * @param string $name name for the control
         * @param string $id id for the control
         * @param string $class classes to add to control
         * @param string $style styles to add to control
         * @param bool $echo display or return rendered control
         * @return string rendered control
         */
        public static function draw_input_text($value, $name, $id = '', $class = '', $style = '', $echo = true) {
            $id = gdr2_html_id_from_name($name, $id);

            if ($class != '') {
                $class = ' class="'.$class.'"';
            }

            if ($style != '') {
                $style = ' style="'.$style.'"';
            }

            $render = '<input type="text" name="'.$name.'" value="'.esc_attr($value).'" id="'.$id.'"'.$class.$style.' />';

            if ($echo) {
                echo $render;
            } else {
                return $render;
            }
        }

        /**
         * Render the input checbox or radio HTML control.
         *
         * @param bool $checked control checked or not
         * @param string $name name for the control
         * @param string $id id for the control
         * @param string $class classes to add to control
         * @param string $style styles to add to control
         * @param string $type control type: checkbox or radio
         * @param string $value control value
         * @param bool $echo display or return rendered control
         * @return string rendered control
         */
        public static function draw_input_checkbox($checked, $name, $id = '', $class = '', $style = '', $type = 'checkbox', $disabled = false, $value = 'on', $echo = true) {
            $id = gdr2_html_id_from_name($name, $id);

            if ($class != '') {
                $class = ' class="'.$class.'"';
            }

            if ($style != '') {
                $style = ' style="'.$style.'"';
            }

            $checked = $checked ? ' checked' : '';
            $disabled = $disabled ? ' disabled' : '';
            $render = '<input'.$checked.$disabled.' type="'.$type.'" name="'.$name.'" value="'.esc_attr($value).'" id="'.$id.'"'.$class.$style.' />';

            if ($echo) {
                echo $render;
            } else {
                return $render;
            }
        }

        /**
         * Render the textarea HTML control.
         *
         * @param string $value control value
         * @param string $name name for the control
         * @param string $id id for the control
         * @param string $class classes to add to control
         * @param string $style styles to add to control
         * @param bool $echo display or return rendered control
         * @return string rendered control
         */
        public static function draw_input_textarea($value, $name, $id = '', $class = '', $style = '', $echo = true) {
            $id = gdr2_html_id_from_name($name, $id);

            if ($class != '') {
                $class = ' class="'.$class.'"';
            }

            if ($style != '') {
                $style = ' style="'.$style.'"';
            }

            $render = '<textarea name="'.$name.'" id="'.$id.'"'.$class.$style.'>'.$value.'</textarea>';

            if ($echo) {
                echo $render;
            } else {
                return $render;
            }
        }

        /**
         * Creates a html with pager based on number of pages and position.
         *
         * @param int $total_pages total pages
         * @param int $current_page current page in pager
         * @param string $url base url
         * @param string $query page element for url query
         * @param string $sign what sign to add before page part
         * @param bool $echo display or return rendered control
         * @return string rendered control
         */
        public static function draw_pager($total_pages, $current_page, $url = '', $query = 'page', $sign = '&', $echo = false) {
            $render = '';
            $p = gdr2_UI::get_pages($total_pages, $current_page);
            extract($p);

            foreach ($pages as $page) {
                if ($page == $break_last) {
                    $render.= "... ";
                }
                if ($page == $current_page) {
                    $render.= sprintf('<span class="page-numbers current">%s</span>', $page);
                } else {
                    $render.= sprintf('<a class="page-numbers" href="%s%s%s=%s">%s</a>', $url, $sign, $query, $page, $page);
                }
                if ($page == $break_first) {
                    $render.= "... ";
                }
            }

            if ($current_page > 1) $render.= sprintf('<a class="next page-numbers" href="%s%s%s=%s">Previous</a>', $url, $sign, $query, $current_page - 1);
            if ($current_page < $total_pages) $render.= sprintf('<a class="next page-numbers" href="%s%s%s=%s">Next</a>', $url, $sign, $query, $current_page + 1);

            if ($echo) {
                echo $render;
            } else {
                return $render;
            }
        }

        /**
         * Internal function used for adding sorting element to a-href.
         *
         * @param string $column column name
         * @param string $sort_order sort order asc/desc
         * @param string $sort_column column for sorting
         * @return array array with sort elements to add to a-href tag
         */
        public static function column_sort_vars($column, $sort_order, $sort_column) {
            $col['url'] = '&amp;sc='.$column;
            $col['cls'] = ' class="sort-order"';

            if ($sort_column == $column) {
                if ($sort_order == "asc") {
                    $col['url'].= '&amp;so=desc';
                    $col['cls'] = ' class="sort-order-down"';
                } else {
                    $col['url'].= '&amp;so=asc';
                    $col['cls'] = ' class="sort-order-up"';
                }
            } else {
                $col['url'].= '&amp;so=asc';
            }

            return $col;
        }

        /**
         * Creates a html with pager based on number of pages and position.
         *
         * @param int $total_pages total pages
         * @param int $current_page current page in pager
         * @param array $settings
         */
        public static function advanced_pager($total_pages, $current_page, $args = array()) {
            $defaults = array(
                'text_pager' => __("Page %s of %s", "gdr2"), 
                'text_next' => __("Next", "gdr2"), 
                'text_previous' => __("Previous", "gdr2"),
                'text_break' => '... ',
                'query_page' => 'pid',
                'before_pages' => '',
                'display_page_of' => true,
                'echo' => true,
                'url' => $_SERVER['REQUEST_URI']
            );
            $settings = wp_parse_args($args, $defaults);
            $render = '';
            $p = gdr2_UI::get_pages($total_pages, $current_page);
            extract($p);

            if ($settings['display_page_of']) {
                $render = "<span class='pages'>".sprintf($settings["text_pager"], "<strong class='current'>".$current_page."</strong>", "<strong class='total'>".$total_pages."</strong>")."</span>";
            }
            $render.= $settings['before_pages'];
            foreach ($pages as $page) {
                if ($page == $break_last) $render.= $settings['text_break'];
                if ($page == $current_page) {
                    $render.= sprintf('<span class="page-numbers current">%s</span>', $page);
                } else {
                    $page_url = esc_url_raw(add_query_arg($settings['query_page'], $page, $settings["url"]));
                    $render.= sprintf('<a class="page-numbers" href="%s">%s</a>', $page_url, $page);
                }
                if ($page == $break_first) $render.= $settings["text_break"];
            }

            if ($current_page > 1) {
                $page_url = esc_url_raw(add_query_arg($settings['query_page'], $current_page - 1, $settings["url"]));
                $render.= sprintf('<a class="previous page-numbers" href="%s">%s</a>', $page_url, $settings["text_previous"]);
            }
            if ($current_page < $total_pages) {
                $page_url = esc_url_raw(add_query_arg($settings['query_page'], $current_page + 1, $settings["url"]));
                $render.= sprintf('<a class="next page-numbers" href="%s">%s</a>', $page_url, $settings["text_next"]);
            }

            if ($settings['echo']) {
                echo $render;
            } else {
                return $render;
            }
        }

        /**
         * Get valid pages for the pager.
         *
         * @param int $total_pages total pages
         * @param int $current_page current page in pager
         * @return array pages to appear in pager
         */
        public static function get_pages($total_pages, $current_page) {
            $pages = array();
            $break_first = -1;
            $break_last = -1;

            if ($total_pages < 10) {
                for ($i = 0; $i < $total_pages; $i++) {
                    $pages[] = $i + 1;
                }
            } else {
                $island_start = $current_page - 1;
                $island_end = $current_page + 1;

                if ($current_page == 1) {
                    $island_end = 3;
                }
                if ($current_page == $total_pages) {
                    $island_start = $island_start - 1;
                }

                if ($island_start > 4) {
                    for ($i = 0; $i < 3; $i++) $pages[] = $i + 1;
                    $break_first = 3;
                } else {
                    for ($i = 0; $i < $island_end; $i++) $pages[] = $i + 1;
                }

                if ($island_end < $total_pages - 4) {
                    for ($i = 0; $i < 3; $i++) $pages[] = $i + $total_pages - 2;
                    $break_last = $total_pages - 2;
                } else {
                    for ($i = 0; $i < $total_pages - $island_start + 1; $i++) {
                        $pages[] = $island_start + $i;
                    }
                }

                if ($island_start > 4 && $island_end < $total_pages - 4) {
                    for ($i = 0; $i < 3; $i++) {
                        $pages[] = $island_start + $i;
                    }
                }
            }
            sort($pages, SORT_NUMERIC);
            return array('pages' => $pages, 'break_first' => $break_first, 'break_last' => $break_last);
        }
    }
}

if (!class_exists('gdr2_jQueryUI')) {
    /**
     * Rendering visual elements powered by jQueryUI.
     */
    class gdr2_jQueryUI {
        /**
         * Display jQueryUI styled message box for error.
         *
         * @param string $message content to displa
         * @param bool $echo display or return content
         * @return string rendered content
         */
        public static function error($message, $echo = true) {
            $render = '<div class="ui-widget">';
            $render.= '<div style="margin-top: 20px; padding: 0 8px;" class="ui-state-error ui-corner-all">';
            $render.= '<p><span style="float: left; margin-right: 5px;" class="ui-icon ui-icon-alert"></span>';
            $render.= $message;
            $render.= '</p></div>';
            $render.= '</div>';

            if ($echo) {
                echo $render;
            } else {
                return $render;
            }
        }

        /**
         * Display jQueryUI styled message box for info.
         *
         * @param string $message content to displa
         * @param bool $echo display or return content
         * @return string rendered content
         */
        public static function highlight($message, $echo = true) {
            $render = '<div class="ui-widget">';
            $render.= '<div style="margin-top: 20px; padding: 0 8px;" class="ui-state-highlight ui-corner-all">';
            $render.= '<p><span style="float: left; margin-right: 5px;" class="ui-icon ui-icon-info"></span>';
            $render.= $message;
            $render.= '</p></div></div>';

            if ($echo) {
                echo $render;
            } else {
                return $render;
            }
        }
    }
}

?>