<?php

/*
Name:    gdr2_Settings_Admin
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

if (!class_exists('gdr2_Setting_Type')) {
    /**
     * List of settings elements types. 
     */
    class gdr2_Setting_Type {
        const INFO = 'info';
        const CUSTOM = 'custom';

        const IMAGE = 'image';
        const MEDIA = 'media';
        const BOOLEAN = 'bool';
        const TEXT = 'text';
        const TEXT_RICH = 'text_rich';
        const TEXT_LIST = 'text_list';
        const TEXT_BLOCK = 'text_block';
        const HTML = 'html';
        const BUTTON_LINK = 'button_link';
        const SELECT = 'select';
        const SELECT_RADIO = 'select_radio';
        const SELECT_CHECK = 'select_check';
        const SELECT_MULTI = 'select_multi';
        const SELECT_GROUPED = 'select_grouped';
        const SELECT_GROUPED_MULTI = 'select_grouped_multi';
        const UPLOAD = 'upload';
        const NUMBER = 'number';
        const X_BY_Y = 'x_by_y';
        const SPINNER = 'spinner';
        const HIDDEN = 'hidden';
        const LISTING = 'listing';

        public static $_values = array(
            'info' => self::INFO,
            'custom' => self::CUSTOM,

            'media' => self::MEDIA,
            'text' => self::TEXT,
            'text_rich' => self::TEXT_RICH,
            'text_list' => self::TEXT_LIST,
            'text_block' => self::TEXT_BLOCK,
            'html' => self::HTML,
            'button_link' => self::BUTTON_LINK,
            'select' => self::SELECT,
            'select_radio' => self::SELECT_RADIO,
            'select_check' => self::SELECT_CHECK,
            'select_multi' => self::SELECT_MULTI,
            'select_grouped' => self::SELECT_GROUPED,
            'select_grouped_multi' => self::SELECT_GROUPED_MULTI,
            'upload' => self::UPLOAD,
            'number' => self::NUMBER,
            'x_by_y' => self::X_BY_Y,
            'spinner' => self::SPINNER,
            'bool' => self::BOOLEAN,
            'listing' => self::LISTING,
            'hidden' => self::HIDDEN
        );

        public static function to_string($value) {
            if (is_null($value)) {
                return null;
            }

            if (array_key_exists($value, self::$_values)) {
                return self::$_values[$value];
            }

            return 'UNKNOWN';
        }
    }
}

if (!class_exists('gdr2_Setting_Element')) {
    /*
     * Definition for the settings element.
     */
    class gdr2_Setting_Element {
        public $type;
        public $name;
        public $panel;
        public $group;
        public $title;
        public $description;
        public $input;
        public $value;
        public $source;
        public $data;
        public $args;

        function __construct($type, $name, $panel = '', $group = '', $title = '', $description = '', 
                             $input = gdr2_Setting_Type::TEXT, $value = '', $source = '', $data = '', $args = array()) {
            $this->type = $type;
            $this->name = $name;
            $this->panel = $panel;
            $this->group = $group;
            $this->title = $title;
            $this->description = $description;
            $this->input = $input;
            $this->value = $value;
            $this->source = $source;
            $this->data = $data;
            $this->args = $args;
        }
    }
}

if (!class_exists('gdr2_Setting_Custom')) {
    /*
     * Definition for the settings element.
     */
    class gdr2_Setting_Custom {
        public $input = gdr2_Setting_Type::CUSTOM;

        public $type;
        public $name;
        public $panel;
        public $group;
        public $title;
        public $description;
        public $function;
        public $value;
        public $args;

        function __construct($type, $name, $panel = '', $group = '', $title = '', $description = '', $function = '', $value = '', $args = array()) {
            $this->type = $type;
            $this->name = $name;
            $this->panel = $panel;
            $this->group = $group;
            $this->title = $title;
            $this->description = $description;
            $this->function = $function;
            $this->value = $value;
            $this->args = $args;
        }
    }
}

if (!class_exists('gdr2_Setting_Panel')) {
    /*
     * Definition for the settings panel.
     */
    class gdr2_Setting_Panel {
        var $name;
        var $title;
        var $subtitle;
        var $description;
        var $groups = array();

        function __construct($name, $title, $subtitle, $description, $groups = array()) {
            $this->name = $name;
            $this->title = $title;
            $this->subtitle = $subtitle;
            $this->description = $description;
            $this->groups = $groups;
        }

        /**
         * Add new group into the panel
         *
         * @param string $name group code
         * @param string $title group title
         * @param string $description group description
         */
        public function add_group($name, $title, $description = '') {
            $this->groups[] = new gdr2_Setting_Group($name, $title, $description);
        }
    }
}

if (!class_exists('gdr2_Setting_Group')) {
    /**
     * Definition for the settings group.
     */
    class gdr2_Setting_Group {
        var $base_url;
        var $name;
        var $title;
        var $description;
        var $open;
        var $toggle;
        var $save;
        var $link;

        function __construct($name, $title, $description = '', $open = false, $toggle = true, $save = true, $link = '') {
            $this->name = $name;
            $this->title = $title;
            $this->description = $description;
            $this->open = $open;
            $this->toggle = $toggle;
            $this->save = $save;
            $this->link = $link;

            if ($this->toggle === false) {
                $this->open = true;
            }
        }

        /**
         * Render group and all the elements in it.
         *
         * @param gdr2_Settings_Render $render object for rendering
         * @param string $panel panel to render to
         * @param array $elements list of settings elements
         * @param string $type rendering code
         * @param string $scope settings scope
         */
        public function render($render, $panel, $elements, $type = 'settings', $scope = 'site') {
            $render->panel = $panel;

            echo '<div class="gdr2-group gdr2-group-'.$this->name.'">';
            echo '<h2>';
                if ($this->save) {
                    $this->render_save_button($type, $scope);
                }

                if ($this->link != '') {
                    $this->render_link_button($type, $scope);
                }

                if ($this->toggle) {
                    $this->render_toggle_button($type, $scope);
                }

                echo $this->title.'<span style="display: none"> | '.__("has errors", "gdr2").'</span>';
            echo '</h2>';

            if (!empty($this->description)) {
                echo '<em>'.$this->description.'</em>';
            }

            echo '<div'.($this->open ? ' style="display: block;"' : '').' class="gdr2-group-elements gdr2-group-elements-'.$type."-".$this->name.'">';

            if (is_array($elements) && !empty($elements)) {
                foreach ($elements as $el) {
                    if ($el->group == $this->name) {
                        $render->render($el, $type, $scope);
                    }
                }
            }

            echo '</div><div class="gdr2-group-spacer gdr2-group-spacer-'.$this->name.'"></div></div>';
        }

        public function render_save_button($type = 'settings', $scope = 'site') {
            echo '<input type="submit" value="'.__("Save", "gdr2").'" name="gdr2-save" class="pressbutton" />';
        }

        public function render_link_button($type = 'settings', $scope = 'site') {
            echo '<a href="'.$this->link.'" target="_blank" class="linkbutton">'.__("Information", "gdr2").'</a>';
        }

        public function render_toggle_button($type = 'settings', $scope = 'site') {
            echo '<a class="gdr2-group-elements-toggle '.($this->open ? 'toggle-opened' : 'toggle-closed').'" id="gdr2-toggle-'.$type."-".$this->name.'" href="#"><img src="'.$this->base_url.'gfx/blank.gif" /></a>';
        }
    }
}

if (!class_exists('gdr2_Settings_Render')) {
    /**
     * Main settings rendering class. 
     */
    class gdr2_Settings_Render {
        public $method = 'classic';
        public $base = 'gdr2_setting';
        public $panel = '';

        function __construct() { }

        /**
         * Render a single settings elements.
         *
         * @param gdr2_Setting_Element $el element to render
         * @param string $type code to use for form elements
         * @param string $scope name for the settings scope
         */
        public function render($el, $type = "settings", $scope = "site") {
            $value = $id_base = "";
            $element = $el->name;
            $name_base = $this->base;

            switch ($this->method) {
                default:
                case 'classic':
                    $name_base.= '_'.$element;
                    break;
                case 'arrayed':
                    $name_base.= '['.$type.']['.$element.']';
                    break;
            }

            $call_function = array(&$this, 'render_'.$el->input);
            $this->call($call_function, $el, $value, $name_base, $id_base);
        }

        /**
         * Rendering invocation function.
         *
         * @param string $call_function function to run for element rendering
         * @param gdr2_Setting_Element $element element to render
         * @param mixed $value value(s) for the element
         * @param string $name_base base value for name attribute
         * @param string $id_base base value for id attribute
         */
        public function call($call_function, $element, $value, $name_base, $id_base) {
            $id_base = $this->to_id($name_base, $id_base);

            if ($element->input == gdr2_Setting_Type::INFO) {
                call_user_func($call_function, $element, $value, $name_base, $id_base);
            } else if ($element->input == gdr2_Setting_Type::CUSTOM) {
                $call_function[1] = 'custom_'.$element->function;
                $cls = '';

                if (isset($element->args["class"]) && !empty($element->args["class"])) {
                    $cls.= " ".$element->args["class"];
                }

                $this->base_header_simple($element, $id_base, $cls);
                call_user_func($call_function, $element, $value, $name_base, $id_base);
                $this->base_footer_simple($element, $id_base);
            } else if ($element->input != gdr2_Setting_Type::HIDDEN) {
                $cls = '';

                if (isset($element->args["readonly"]) && $element->args["readonly"]) {
                    $cls.= "gdr2-disabled";
                }

                if (isset($element->args["class"]) && !empty($element->args["class"])) {
                    $cls.= " ".$element->args["class"];
                }

                if (isset($element->args["control"]) && $element->args["control"] == "inverted") {
                    $this->base_header_invert($element, $id_base);
                } else {
                    $this->base_header_normal($element, $id_base);
                }

                call_user_func($call_function, $element, $value, $name_base, $id_base, $cls);
                $this->base_footer($element, $id_base);
            } else {
                call_user_func($call_function, $element, $value, $name_base, $id_base);
            }
        }

        private function base_header_simple($element, $id, $cls = '') {
            echo '<div class="gdr2-element gdr2-element-'.$element->input.' gdr2-element-'.$id.' '.$cls.'" id="gdr2-el-'.$id.'">';
            do_action("gdr2_settings_render_header_".$element->panel."_".$element->group."_".$id);
        }

        private function base_header_invert($element, $id, $cls = '') {
            echo '<div class="gdr2-element gdr2-element-'.$element->input.' gdr2-element-'.$id.' '.$cls.'" id="gdr2-el-'.$id.'">';
            echo '<div class="gdr2-label gdr2-label-'.$element->input.'">';

            if (!empty($element->title)) {
                echo $element->title.":";
            }

            echo '</div>';
            do_action("gdr2_settings_render_header_".$element->panel."_".$element->group."_".$id);
            echo '<div class="gdr2-control gdr2-control-'.$element->input.'">';
        }

        private function base_header_normal($element, $id, $cls = '') {
            echo '<div class="gdr2-element gdr2-element-'.$element->input.' gdr2-element-'.$id.' '.$cls.'" id="gdr2-el-'.$id.'">';
            echo '<div class="gdr2-label gdr2-label-'.$element->input.'">';

            if (!empty($element->title)) {
                echo $element->title.":";
            }

            echo '</div>';
            do_action("gdr2_settings_render_header_".$element->panel."_".$element->group."_".$id);
            echo '<div class="gdr2-control gdr2-control-'.$element->input.'">';
        }

        private function base_footer_simple($element, $id) {
            echo '<div class="gdr2-clear"></div>';
            do_action("gdr2_settings_render_footer_".$element->panel."_".$element->group."_".$id);
            echo '</div>';
        }

        private function base_footer($element, $id = '', $cls = '') {
            echo '</div>';

            if ($element->description != ""){
                echo '<div class="gdr2-description">';
                echo '<div class="ui-state-default ui-corner-all"><span qtip-content="'.$element->description.'" class="ui-icon ui-icon-help gdr2-qtip"></span></div>';
                echo '</div>';
            }

            echo '<div class="gdr2-description-for-error">';
            echo '<div class="ui-state-active ui-corner-all"><span qtip-content="Error" class="ui-icon ui-icon-notice gdr2-qtip-error"></span></div>';
            echo '</div>';

            echo '<div class="gdr2-clear"></div>';
            do_action("gdr2_settings_render_footer_".$element->panel."_".$element->group."_".$id);
            echo '</div>';
        }

        private function to_id($name, $id = "") {
            if (!empty($id)) return $id;
            return str_replace("[", "_", str_replace("]", "", $name));
        }

        private function dropdown_pages($args = '') {
            $defaults = array(
                'depth' => 0, 'child_of' => 0, 'selected' => 0,
                'name' => 'page_id', 'id' => '', 'multiple' => 0);

            $r = wp_parse_args($args, $defaults);
            extract($r, EXTR_SKIP);

            $pages = get_pages($r);
            $output = '';

            if (!empty($pages)) {
                $output = "<select name='".esc_attr($name)."' id='".esc_attr($id)."'".($multiple ? " multiple='multiple'" : "").">\n";

                $output.= walk_page_dropdown_tree($pages, $depth, $r);
                $output.= "</select>\n";
            }

            echo $output;
        }

        private function dropdown_categories($args = '') {
            $defaults = array(
                'orderby' => 'id', 'order' => 'ASC',
                'show_last_update' => 0, 'show_count' => 0,
                'hide_empty' => 1, 'child_of' => 0,
                'exclude' => '', 'echo' => 1,
                'selected' => 0, 'hierarchical' => 0,
                'name' => 'cat', 'id' => '',
                'class' => 'postform', 'depth' => 0,
                'tab_index' => 0, 'taxonomy' => 'category',
                'hide_if_empty' => false, 'multiple' => 0);

            $defaults['selected'] = (is_category()) ? get_query_var('cat') : 0;

            $r = wp_parse_args($args, $defaults);
            if (!isset($r['pad_counts']) && $r['show_count'] && $r['hierarchical']) {
                $r['pad_counts'] = true;
            }

            $r['include_last_update_time'] = $r['show_last_update'];
            extract( $r );

            $tab_index_attribute = '';
            if ((int)$tab_index > 0) {
                $tab_index_attribute = " tabindex=\"$tab_index\"";
            }

            $categories = get_terms($taxonomy, $r);
            $name = esc_attr($name);
            $class = esc_attr($class);
            $id = $id ? esc_attr($id) : $name;

            if (!$r['hide_if_empty'] || ! empty($categories)) {
                $output = "<select name='$name' id='$id' class='$class'".($multiple ? " multiple='multiple'" : "")." $tab_index_attribute>\n";
            } else {
                $output = '';
            }

            if (!empty($categories)) {
                if ($hierarchical) {
                    $depth = $r['depth'];
                } else {
                    $depth = -1;
                }

                $output.= walk_category_dropdown_tree($categories, $depth, $r);
            }

            if (!$r['hide_if_empty'] || ! empty($categories)) {
                $output.= "</select>\n";
            }

            echo $output;
        }

        private function render_info($element, $value, $name_base, $id_base = "", $cls = '') {
            echo '<div class="gdr2-element gdr2-element-info gdr2-element-'.$id_base.' '.$cls.'" id="gdr2-el-'.$id_base.'">';
            echo $element->description;
            echo '<div class="gdr2-clear"></div></div>';
        }

        private function render_button_link($element, $value, $name_base, $id_base, $cls = "") {
            echo sprintf('<a class="pressbutton '.$cls.'" href="%s">%s</a>', $element->args["href"], $element->args["link"]);
        }

        private function render_media($element, $value, $name_base, $id_base = "", $cls = '') {
            $url = wp_get_attachment_image_src($value, "full");
            echo sprintf('<input type="hidden" id="%s-img" value="%s" />',
                    $id_base, $url[0]);
            echo sprintf('<input%s type="text" name="%s" id="%s" value="ID: %s"%s />',
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $name_base, $id_base, str_replace('"', '&quot;', $value), $cls != "" ? ' class="'.$cls.'"' : '');
            echo '<div class="gdr2-media-preview"><div class="ui-state-default ui-corner-all"><span gdr2-id="'.$id_base.'" qtip-content="'.__("Click to open currently selected image.", "gdr2").'" class="ui-icon ui-icon-image gdr2-qtip"></span></div></div>';
            echo '<div class="gdr2-media-open"><div class="ui-state-default ui-corner-all"><span gdr2-id="'.$id_base.'" qtip-content="'.__("Click to open Media library dialog.", "gdr2").'" class="ui-icon ui-icon-folder-open gdr2-qtip"></span></div></div>';
        }

        private function render_image($element, $value, $name_base, $id_base = "", $cls = '') {
            echo sprintf('<input%s type="text" name="%s" id="%s" value="%s"%s />',
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $name_base, $id_base, str_replace('"', '&quot;', $value), $cls != "" ? ' class="'.$cls.'"' : '');
            echo '<div class="gdr2-media-preview"><div class="ui-state-default ui-corner-all"><span gdr2-id="'.$id_base.'" qtip-content="'.__("Click to open currently selected image.", "gdr2").'" class="ui-icon ui-icon-image gdr2-qtip"></span></div></div>';
            echo '<div class="gdr2-media-open"><div class="ui-state-default ui-corner-all"><span gdr2-id="'.$id_base.'" qtip-content="'.__("Click to open Media library dialog.", "gdr2").'" class="ui-icon ui-icon-folder-open gdr2-qtip"></span></div></div>';
        }

        private function render_bool($element, $value, $name_base, $id_base = "", $cls = '') {
            $selected = $value == 1 ? " checked" : "";
            echo sprintf('<input%s type="checkbox" name="%s" id="%s"%s%s />',
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $name_base, $id_base, $selected, $cls != "" ? ' class="'.$cls.'"' : '');
        }

        private function render_text($element, $value, $name_base, $id_base, $cls = '') {
            echo sprintf('<input%s type="text" name="%s" id="%s" value="%s"%s />',
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $name_base, $id_base, str_replace('"', '&quot;', $value), $cls != "" ? ' class="'.$cls.'"' : '');
        }

        private function render_text_rich($element, $value, $name_base, $id_base, $cls = '') {
            echo sprintf('<input%s type="text" name="%s" id="%s" value="%s"%s />',
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $name_base, $id_base, str_replace('"', '&quot;', esc_html($value)),
                    $cls != "" ? ' class="'.$cls.'"' : '');
        }

        private function render_text_list($element, $value, $name_base, $id_base, $cls = '') {
            echo sprintf('<textarea%s name="%s" id="%s"%s>%s</textarea>',
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $name_base, $id_base, $cls != "" ? ' class="'.$cls.'"' : '', join(GDR2_EOL, $value));
        }

        private function render_text_block($element, $value, $name_base, $id_base, $cls = '') {
            echo sprintf('<textarea%s name="%s" id="%s"%s>%s</textarea>',
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $name_base, $id_base, $cls != "" ? ' class="'.$cls.'"' : '', $value);
        }

        private function render_listing($element, $value, $name_base, $id_base, $cls = '') {
            $this->render_text_list($element, $value, $name_base, $id_base, $cls);
        }

        private function render_number($element, $value, $name_base, $id_base, $cls = '') {
            echo sprintf('<input%s type="text" name="%s" id="%s" value="%s"%s />',
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $name_base, $id_base, $value, $cls != "" ? ' class="'.$cls.'"' : '');
        }

        private function render_x_by_y($element, $value, $name_base, $id_base, $cls = '') {
            $pairs = explode("x", $value);
            echo sprintf('<input%s type="text" name="%s[x]" id="%s_x" value="%s"%s />',
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $name_base, $id_base, $pairs[0], $cls != "" ? ' class="'.$cls.'"' : '');
            echo ' x ';
            echo sprintf('<input%s type="text" name="%s[y]" id="%s_y" value="%s"%s />',
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $name_base, $id_base, $pairs[1], $cls != "" ? ' class="'.$cls.'"' : '');
        }

        private function render_spinner($element, $value, $name_base, $id_base, $cls = '') {
            $defaults = array('readonly' => false, 'min' => 0, 'max' => 100, 'step' => 1);
            $args = wp_parse_args($element->args, $defaults);

            echo sprintf('<input%s type="text" name="%s" id="%s" value="%s"%s gdr2-min="%s" gdr2-max="%s" gdr2-step="%s" />',
                    $args["readonly"] ? " readonly" : "", $name_base, $id_base, $value, $cls != "" ? ' class="'.$cls.'"' : '', 
                    $args['min'], $args['max'], $args['step']);
        }

        private function render_upload($element, $value, $name_base, $id_base, $cls = '') {
            echo sprintf('<input%s type="file" name="%s" id="%s" %s />',
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $name_base, $id_base, $cls != "" ? ' class="'.$cls.'"' : '');
        }

        private function render_html($element, $value, $name_base, $id_base, $cls = '') {
            echo sprintf('<textarea%s name="%s" id="%s"%s>%s</textarea>',
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $name_base, $id_base, $cls != "" ? ' class="'.$cls.'"' : '', esc_html($value));
        }

        private function render_hidden($element, $value, $name_base, $id_base, $cls = '') {
            echo sprintf('<input%s type="hidden" name="%s" id="%s" value="%s"%s />',
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $name_base, $id_base, $value, $cls != "" ? ' class="'.$cls.'"' : '');
        }

        private function render_select($element, $val, $name_base, $id_base, $cls = '') {
            $this->render_select_multi($element, $val, $name_base, $id_base, $cls, 0);
        }

        private function render_select_multi($element, $val, $name_base, $id_base, $cls = '', $multiple = 1) {
            $data = array();
            switch ($element->source) {
                case "pages":
                    $attr = array('multiple' => $multiple, 'name' => $name_base, 'id' => $id_base, 'selected' => $val, 'hierarchical' => 1);
                    $this->dropdown_pages($attr);
                    return;
                    break;
                case "categories":
                    $attr = array('multiple' => $multiple, 'name' => $name_base, 'id' => $id_base, 'selected' => $val, 'hierarchical' => 1);
                    $this->dropdown_categories($attr);
                    return;
                    break;
                case "class":
                    $data = call_user_func($element->data."::to_array");
                    break;
                case "function":
                    $data = call_user_func($element->data);
                    break;
                default:
                case "":
                case "array":
                    $data = $element->data;
                    break;
            }

            $val = is_null($val) ? array_keys($data) : (array)$val;

            echo sprintf('<select%s id="%s" name="%s%s"%s%s>', 
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $id_base, $name_base, $multiple == 1 ? '[]' : '',
                    $cls != "" ? ' class="'.$cls.'"' : '',
                    $multiple == 1 ? ' multiple="multiple"' : '');

            $assoc = !gdr2_is_array_associative($data);
            foreach ($data as $value => $title) {
                $real_value = $assoc ? $title : $value;
                $sel = in_array($real_value, $val) ? ' selected="selected"' : '';

                echo sprintf('<option value="%s"%s>%s</option>', $real_value, $sel, $title);
            }

            echo '</select>';
        }

        private function render_select_radio($element, $val, $name_base, $id_base, $cls = '') {
            $this->render_select_check($element, $val, $name_base, $id_base, $cls, 0);
        }

        private function render_select_check($element, $val, $name_base, $id_base, $cls = '', $multiple = 1) {
            $data = array();
            switch ($element->source) {
                case "class":
                    $data = call_user_func($element->data."::to_array");
                    break;
                case "function":
                    $data = call_user_func($element->data);
                    break;
                default:
                case "":
                case "array":
                    $data = $element->data;
                    break;
            }

            $val = is_null($val) ? array_keys($data) : (array)$val;

            $assoc = !gdr2_is_array_associative($data);
            foreach ($data as $value => $title) {
                $real_value = $assoc ? $title : $value;
                $sel = in_array($real_value, $val) ? ' checked' : '';

                echo sprintf('<div class="gdg2-single-select-value"><input class="gdr2-field-boolean" type="%s" id="%s" value="%s" name="%s%s"%s /> <span>%s</span></div>', 
                        $multiple == 1 ? 'checkbox' : 'radio', $id_base, $real_value, $name_base, $multiple == 1 ? '[]' : '', $sel, $title);
            }
        }

        private function render_select_grouped($element, $val, $name_base, $id_base, $cls = '') {
            $this->render_select_grouped_multi($element, $val, $name_base, $id_base, $cls, 0);
        }

        private function render_select_grouped_multi($element, $val, $name_base, $id_base, $cls = '', $multiple = 1) {
            $data = array();
            switch ($element->source) {
                case "class":
                    $data = call_user_func($element->data."::to_array");
                    break;
                case "function":
                    $data = call_user_func($element->data);
                    break;
                default:
                case "":
                case "array":
                    $data = $element->data;
                    break;
            }

            $val = is_null($val) ? array_keys($data) : (array)$val;

            echo sprintf('<select%s id="%s" name="%s%s"%s%s>', 
                    isset($element->args["readonly"]) && $element->args["readonly"] ? " readonly" : "",
                    $id_base, $name_base, $multiple == 1 ? '[]' : '',
                    $cls != "" ? ' class="'.$cls.'"' : '',
                    $multiple == 1 ? ' multiple="multiple"' : '');

            foreach ($data as $block => $group) {
                echo sprintf('<optgroup label="%s">', $group["title"]);

                $assoc = !gdr2_is_array_associative($group["items"]);
                foreach ($group["items"] as $value => $title) {
                    $real_value = $assoc ? $title : $value;
                    $sel = in_array($real_value, $val) ? ' selected="selected"' : '';

                    echo sprintf('<option value="%s"%s>%s</option>', $real_value, $sel, $title);
                }

                echo '</optgroup>';
            }

            echo '</select>';
        }
    }
}

if (!class_exists('gdr2_Settings_Admin')) {
    /**
     * Administrative class to define settings panels and elements.
     */
    class gdr2_Settings_Admin {
        function __construct() { }

        /**
         * Initiate panels.
         *
         * @param string $scope panel scope
         * @param string $name name for the panels list
         * @return array list with panels.
         */
        public function panels($scope = 'site', $name = 'settings') {
            return array();
        }

        /**
         * Initiate settings
         *
         * @param string $panel panel to get settings for
         * @param string $scope settings scope
         * @param string $name name of the settings list
         * @return array list with settings
         */
        public function settings($panel = '', $scope = 'site', $name = 'settings') {
            return array();
        }
    }
}

if (!function_exists('gdr2_settings_form_hidden')) {
    /**
     * Print hidden input fields for the settings form.
     *
     * @param array $data overrides for the default settings
     */
    function gdr2_settings_form_hidden($data = array()) {
        $defaults = array(
            'action' => 'gdr2_remote',
            'gdr2_action' => 'settings',
            'gdr2_type' => 'settings',
            'gdr2_scope' => 'site',
            'gdr2_response' => 'json',
        );
        $data = wp_parse_args($data, $defaults);

        foreach ($data as $key => $value) {
            echo '<input name="'.$key.'" type="hidden" value="'.$value.'" />'.GDR2_EOL;
        }
    }
}

?>