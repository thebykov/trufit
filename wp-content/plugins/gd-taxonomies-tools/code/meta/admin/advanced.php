<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Field_Admin_Color extends gdCPT_Core_Field_Admin {
    public $_name = 'color';
    public $_repeater = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Advanced", "gd-taxonomies-tools");
        $this->_label = __("Color", "gd-taxonomies-tools");
        $this->_description = __("Color picker field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        $new = trim(strip_tags($value));
        return substr(trim(str_replace('#', '', $new)), 0, 6);
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function render($value, $field, $id, $name) {
        $render = '<div id="'.$id.'_div" class="colorSelector"><div class="xs-back" style="background-color: #000000"></div></div>';
        $render.= '<input class="gdtt-field-text gdtt-field-colorpicker" maxlength="6" type="text" name="'.$name.'" id="'.$id.'_input" value="'.$value.'" gdtt-reset="'.$this->get_default($field).'" />';

        return $render;
    }

    public function get_default($field) {
        return ' ';
    }
}

class gdCPT_Field_Admin_Editor extends gdCPT_Core_Field_Admin {
    public $_name = 'editor';
    public $_repeater = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Advanced", "gd-taxonomies-tools");
        $this->_label = __("Rich Editor", "gd-taxonomies-tools");
        $this->_description = __("Full WP like editor field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        return stripslashes(htmlentities($value, ENT_QUOTES, GDR2_CHARSET));
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function render($value, $field, $id, $name) {
        wp_editor(gdr2_entity_decode($value), $id, 
                array('dfw' => false,
                    'textarea_name' => $name, 
                    'css' => 'css-'.$name,
                    'teeny' => false,
                    'textarea_rows' => 10)
        );
    }

    public function get_default($field) {
        return ' ';
    }
}

class gdCPT_Field_Admin_Image extends gdCPT_Core_Field_Admin {
    public $_name = 'image';
    public $_repeater = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Advanced", "gd-taxonomies-tools");
        $this->_label = __("Image", "gd-taxonomies-tools");
        $this->_description = __("Image attach field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        $value = (array)$value;

        if (isset($value['id']) && is_numeric($value['id'])) {
            return intval($value['id']);
        } else if (isset($value['url']) && !empty($value['url'])) {
            return trim($value['url']);
        }

        return '';
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function shortcode_attributes() {
        return array(
            'image' => array('attr' => 'img', 'type' => 'dropdown', 'default' => 'img', 'label' => __("Display as", "gd-taxonomies-tools"), 'description' => __("How to represent the image URL.", "gd-taxonomies-tools"), 'values' => array('img' => __("IMG tag", "gd-taxonomies-tools"), 'url' => __("Just URL", "gd-taxonomies-tools"))),
            'size' => array('attr' => 'full', 'type' => 'input', 'default' => 'full', 'label' => __("Size", "gd-taxonomies-tools"), 'description' => __("WordPress size strings or specify size as WIDTHxHEIGHT.", "gd-taxonomies-tools"))
        );
    }

    public function render($value, $field, $id, $name) {
        global $gdtt_fields;

        $value = $gdtt_fields->get_field_display($value, $field);

        $render = '<div class="gdtt-cf-icons">';
            $render.= '<div class="gdtt-ui-button"><span gdtt-id="'.$id.'" gdtt-field="'.$f.'" title="'.__("Preview selected image.", "gd-taxonomies-tools").'" class="ui-icon ui-icon-image"></span></div>';
            $render.= '<div class="gdtt-ui-button"><span gdtt-id="'.$id.'" gdtt-field="'.$f.'" title="'.__("Select image from WP media library.", "gd-taxonomies-tools").'" class="ui-icon ui-icon-folder-open"></span></div>';
        $render.= '</div><div class="gdtt-cf-image">';
            $render.= '<input class="gdtt-field-text gdtt-field-image" type="text" id="'.$id.'_url" name="'.$name.'[url]" value="'.$value['url'].'" gdtt-reset="'.$this->get_default($field).'" />';
            $render.= '<span class="gdtt-field-spacer"></span>';
            $render.= '<span class="gdtt-field-title-id">'.__("ID", "gd-taxonomies-tools").':</span>';
            $render.= '<input class="gdtt-field-text gdtt-field-image-id" type="text" id="'.$id.'_id" name="'.$name.'[id]" value="'.$value['id'].'" gdtt-reset="'.$this->get_default($field).'" />';
        $render.= '</div>';

        return $render;
    }

    public function get_default($field) {
        return ' ';
    }
}

class gdCPT_Field_Admin_Rewrite extends gdCPT_Core_Field_Admin {
    public $_name = 'rewrite';
    public $_repeater = false;

    function __construct() {
        parent::__construct();

        $this->_class = __("Advanced", "gd-taxonomies-tools");
        $this->_label = __("Rewrite", "gd-taxonomies-tools");
        $this->_description = __("Special value to use in URL field", "gd-taxonomies-tools");
    }
    
    public function clean($value, $field) {
        if ($field['rewrite'] == '__none__') {
            return gdr2_sanitize_custom($value, array('strip_spaces' => true));
        } else {
            return '';
        }
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function render($value, $field, $id, $name) {
        $render = '';

        if ($field['rewrite'] == '__none__') {
            $render.= '<input class="gdtt-field-rewrite gdtt-field-text'.($field['limit'] > 0 ? ' gdtt-limit-field' : '').'" type="text" id="'.$id.'" name="'.$name.'" value="'.__($value).'" gdtt-reset="'.$this->get_default($field).'" />';
            $render.= '<script type="text/javascript">jQuery(document).ready(function(){jQuery("#'.$id.'").limitkeypress({ rexp: /^[0-9a-z\-]*$/ });});</script>';
        } else {
            $render.= __("This field value will mirror value for another field.", "gd-taxonomies-tools");
        }

        return $render;
    }

    public function get_default($field) {
        return ' ';
    }
}

?>