<?php

/*
Name:    gdr2_Grid
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

if (!class_exists('gdr2_Column')) {
    class gdr2_Column {
        public $code;
        public $type;
        public $name;
        public $sort_by;
        public $align;
        public $width;
        public $class;
        public $template;

        function __construct($code, $type, $name, $class = array(), $align = '', $width = 0, $sort_by = '', $template = '') {
            $this->code = $code;
            $this->type = $type;
            $this->name = $name;
            $this->align = $align;
            $this->width = $width;
            $this->sort_by = $sort_by;
            $this->template = $template;

            $this->class = (array)$class;

            if ($type == 'check') {
                $this->class[] = 'check-column';
            }

            if ($type == 'options') {
                $this->class[] = 'options-column';
            }
        }

        public function get_class() {
            return join(" ", $this->class);
        }
    }

    class gdr2_Column_Option {
        public $code;
        public $name;
        public $class;
        public $link;

        function __construct($code, $name, $link = '', $class = array()) {
            $this->code = $code;
            $this->name = $name;
            $this->link = $link;
            $this->class = (array)$class;
        }

        public function create($link = '', $class = '') {
            $link = $link == '' ? $this->link : $link;
            $classes = $this->class;

            if ($class != '') {
                $classes[] = $class;
            }

            return '<a href="'.$link.'" class="'.join(" ", $classes).'">'.$this->name.'</a>';
        }
    }
}

if (!class_exists('gdr2_Grid_Filter')) {
    class gdr2_Grid_Filter {
        public $code;
        public $type;
        public $values;
        public $multi;

        function __construct($code, $type = 'callback', $values = array(), $multi = false) {
            $this->code = $code;
            $this->type = $type;
            $this->values = $values;
            $this->multi = $multi;
        }
    }
}

if (!class_exists('gdr2_Grid')) {
    abstract class gdr2_Grid {
        private $actions_row = '';
        private $sort_order;
        private $sort_column;
        private $base_url;
        private $bulks;
        private $filters;
        private $columns;
        private $options;
        private $actions;

        private $page_current;
        private $page_maximum;

        public $splitter = ' | ';

        public function __construct($base_url = '', $sort_column = '', $sort_order = '') {
            $this->sort($base_url, $sort_column, $sort_order);

            $this->bulks['-1'] = __("Bluk Actions", "gdr2");
        }

        public function add_bulk_operation($code, $name) {
            $this->bulks[$code] = $name;
        }

        public function add_action($code, $link) {
            $this->actions[$code] = $link;
        }
        
        public function add_filter($code, $type = 'callback', $values = array(), $multi = false) {
            $this->filters[$code] = new gdr2_Grid_Filter($code, $type, $values, $multi);
        }

        public function add_option($code, $name, $link = '', $class = array()) {
            $this->options[$code] = new gdr2_Column_Option($code, $name, $link, $class);
        }

        public function add_column($code, $type, $name = '', $class = array(), $align = '', $width = 0, $sort_by = '', $template = '') {
            $this->columns[$code] = new gdr2_Column($code, $type, $name, $class, $align, $width, $sort_by, $template);
        }

        public function reset_actions() {
            $this->actions = array();
        }

        public function set_action_row($name) {
            $this->actions_row = $name;
        }

        public function set_pager($current, $maximum) {
            $this->page_current = $current;
            $this->page_maximum = $maximum;
        }

        public function get_column($code) {
            return $this->columns[$code];
        }

        public function sort($base_url = '', $sort_column = '', $sort_order = '') {
            $this->base_url = $base_url;
            $this->sort_column = $sort_column;
            $this->sort_order = $sort_order;

            if (isset($_GET['sc'])) {
                $this->sort_column = $_GET['sc'];
                $this->sort_order = $_GET['so'];
            }
        }

        public function render_filter_top() {
            echo '<div class="tablenav top">';

            if (count($this->bulks) > 1) {
                $this->_bulks();
            }

            if (!empty($this->filters)) {
                $this->_filters();
            }

            if ($this->page_maximum > 1) {
                $this->_pager();
            }

            echo '</div>';
        }

        public function render_filter_bottom() {
            echo '<div class="tablenav bottom">';

            if (count($this->bulks) > 1) {
                $this->_bulks();
            }

            if ($this->page_maximum > 1) {
                $this->_pager();
            }

            echo '</div>';
        }

        public function render_actions() {
            $out = '';
            if (count($this->actions) > 0) {
                $out.= '<div class="row-actions">';
                $actions = array();
                foreach ($this->actions as $action => $link) {
                    $actions[] = '<span class="'.$action.'">'.$link.'</span>';
                }
                $out.= join(" | ", $actions);
                $out.= '</div>';
            }
            return $out;
        }

        public function render_top($id = '', $class = '', $style = '') {
            echo '<table id="'.$id.'" class="'.$class.'" style="'.$style.'">';
        }

        public function render_bottom() {
            echo '</table>';
        }

        public function render_body_top() {
            echo '<tbody>';
        }

        public function render_body_bottom() {
            echo '</tbody>';
        }

        public function render_row($class = '', $data = array(), $options_links = array(), $id = '') {
            echo '<tr class="'.$class.'"'.($id != '' ? ' id="'.$id.'"' : '').'>';

            $i = 0;
            foreach ($this->columns as $name => $column) {
                $style = array();
                $class = $column->class;
                array_unshift($class, 'column-'.$name);

                if ($column->align != '') {
                    $style[] = 'text-align: '.$column->align;
                }

                if ($column->width != '' && $column->width > 0) {
                    $style[] = 'width: '.$column->width.'px';
                }

                $style = !empty($style) ? ' style="'.join('; ', $style).'"' : '';
                $class = ' class="'.join(' ', $class).'"';

                $d = $data[$i];
                echo '<td'.$class.$style.'>';
                    if ($column->template != '' && is_array($d)) {
                        echo sprintfa($column->template, $d);
                    } else if ($column->type == 'checkbox') {
                        echo '<input type="checkbox" class="check-item" value="'.$d.'" />';
                    } else if ($column->type == 'listed') {
                        echo $this->_listed($d);
                    } else if ($column->type == 'tabled') {
                        echo $this->_tabled($d);
                    } else if ($column->type == 'options') {
                        echo $this->_options($d, $options_links);
                    } else {
                        echo $d;
                    }

                    if ($this->actions_row == $column->code) {
                        echo $this->render_actions();
                    }
                echo '</td>';
                $i++;
            }
            echo '</tr>';
        }

        public function render_header() {
            $this->_header();
        }

        public function render_empty_row($class, $content) {
            echo '<tr class="'.$class.'">';
            echo '<td colspan="'.count($this->columns).'">'.$content.'</td>';
            echo '</tr>';
        }

        public function render_footer() {
            $this->_header("tfoot");
        }

        private function _bulks() {
            echo '<div class="alignleft actions">';
            gdr2_UI::draw_select($this->bulks, '', 'action');
            gdr2_UI::draw_input_button(__("Apply", "gdr2"), 'doaction');
            echo '</div>';
        }

        private function _filters() {
            echo '<div class="alignleft actions">';
            foreach ($this->filters as $key => $data) {
                if ($data->type == 'callback') {
                    call_user_func($data->values['function'], $data->values['args']);
                } else {
                    gdr2_UI::draw_select($data->values, '', $key);
                }
            }
            gdr2_UI::draw_input_button(__("Filter", "gdr2"), 'post-query-submit');
            echo '</div>';
        }

        private function _pager() {
            echo '<div class="tablenav-pages">';
            gdr2_UI::advanced_pager($this->page_maximum, $this->page_current, array('display_page_of' => false, 'url' => $this->base_url));
            echo '</div>';
        }

        private function _listed($values) {
            return join("<br/>", $values);
        }

        private function _tabled($values) {
            if (count($values) < 3) {
                return join("<br/>", $values);
            }

            $parts = ceil(count($values) / 2);

            $rendered = '<table><tr><td>';
            $rendered.= join("<br/>", array_slice($values, 0, $parts));
            $rendered.= '</td><td>';
            $rendered.= join("<br/>", array_slice($values, $parts));
            $rendered.= '</td></tr></table>';

            return $rendered;
        }
        
        private function _options($settings = array(), $options_links = array()) {
            $elements = array();

            foreach ($settings as $name) {
                $class = '';
                $xlink = isset($options_links[$name]) ? $options_links[$name] : '';

                if (is_array($xlink)) {
                    $link = $xlink[0];
                    $class = $xlink[1];
                } else {
                    $link = $xlink;
                }

                $elements[] = $this->options[$name]->create($link, $class);
            }

            return join($this->splitter, $elements);
        }

        private function _header($tag = 'thead') {
            echo '<'.$tag.'><tr>';

            foreach ($this->columns as $name => $column) {
                $style = array();
                $class = $column->class;
                array_unshift($class, "column-".$name);

                if ($column->align != '') {
                    $style[] = "text-align: ".$column->align;
                }

                if ($column->width != '') {
                    $style[] = "width: ".$column->width."px";
                }

                $style = !empty($style) ? ' style="'.join('; ', $style).'"' : '';
                $class = ' class="'.join(' ', $class).'"';

                echo '<th scope="col"'.$class.$style.'>';

                if ($column->type == "checkbox") {
                    echo '<input class="check-list-'.$tag.'" type="checkbox" />';
                } else {
                    if ($column->sort_by == '') {
                        echo $column->name;
                    } else {
                        $col = gdr2_UI::column_sort_vars($column->code, $this->sort_order, $this->sort_column);
                        echo '<a href="'.$this->base_url.$col["url"].'"'.$col["cls"].'>'.$column->name.'</a>';
                    }
                }

                echo '</th>';
            }

            echo '</tr></'.$tag.'>';
        }
    }
}

if (!class_exists('gdr2_Grid_Generic')) {
    class gdr2_Grid_Generic extends gdr2_Grid {
        
    }
}

?>