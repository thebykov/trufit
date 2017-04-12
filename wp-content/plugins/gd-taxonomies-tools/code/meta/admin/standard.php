<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Field_Admin_Text extends gdCPT_Core_Field_Admin {
    public $_name = 'text';
    public $_repeater = true;
    public $_rewriter = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Standard", "gd-taxonomies-tools");
        $this->_label = __("Text", "gd-taxonomies-tools");
        $this->_description = __("Standard text field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        return trim(strip_tags($value));
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function get_type($field) {
        $type = $this->_label;

        if ($field['regex'] == '__custom__') {
            $type.= ' | '.__("Regex", "gd-taxonomies-tools");
        } else if ($field['regex'] == '__custom_mask__') {
            $type.= ' | '.__("Mask", "gd-taxonomies-tools");
        } else if ($field['regex'] != '__none__') {
            $type.= ' | '.__("Custom", "gd-taxonomies-tools");
        }

        return $type;
    }

    public function render($value, $field, $id, $name) {
        $render = '<input class="gdtt-field-text'.(isset($field['limit']) && $field['limit'] > 0 ? ' gdtt-limit-field' : '').'" type="text" id="'.$id.'" name="'.$name.'" value="'.__($value).'" gdtt-reset="'.$this->get_default($field).'" />';

        $do_regex = $do_mask = null;
        if (isset($field['regex'])) {
            if ($field['regex'] == '__custom__') {
                $do_regex = $field['regex_custom'];
            } else if ($field['regex'] == '__custom_mask__') {
                $do_mask = $field['mask_custom'];
            } else if ($field['regex'] != '__none__') {
                $val = explode('|', $field['regex'], 2);

                if ($val[0] == 'regex') {
                    $do_regex = call_user_func($val[1]);
                } else if ($val[0] == 'mask') {
                    $do_mask = call_user_func($val[1]);
                }
            }
        }

        if (!is_null($do_regex)) {
            $render.= '<script type="text/javascript">jQuery(document).ready(function(){jQuery("#'.$id.'").limitkeypress({ rexp: '.$do_regex.' });});</script>';
        } else if (!is_null($do_mask)) {
            $render.= '<script type="text/javascript">jQuery(document).ready(function(){jQuery("#'.$id.'").mask("'.$do_mask.'");});</script>';
        }

        return $render;
    }

    public function get_default($field) {
        return ' ';
    }
}

class gdCPT_Field_Admin_Number extends gdCPT_Core_Field_Admin {
    public $_name = 'number';
    public $_repeater = true;
    public $_rewriter = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Standard", "gd-taxonomies-tools");
        $this->_label = __("Number", "gd-taxonomies-tools");
        $this->_description = __("Standard number field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        return trim(strip_tags($value));
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function shortcode_attributes() {
        return array(
            'decimals' => array('attr' => '', 'type' => 'number', 'default' => '', 'label' => __("Decimals", "gd-taxonomies-tools"), 'description' => __("Number of decimals to show. Leave emtpy to show unmodified.", "gd-taxonomies-tools"))
        );
    }

    public function render($value, $field, $id, $name) {
        $render = '<input class="gdtt-field-text gdtt-field-number" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" gdtt-reset="'.$this->get_default($field).'" />';

        return $render;
    }

    public function get_default($field) {
        return '0';
    }
}

class gdCPT_Field_Admin_Boolean extends gdCPT_Core_Field_Admin {
    public $_name = 'boolean';
    public $_repeater = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Standard", "gd-taxonomies-tools");
        $this->_label = __("Boolean", "gd-taxonomies-tools");
        $this->_description = __("Standard boolean field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        return isset($value) ? 1 : 0;
    }

    public function check($value, $field) {
        return true;
    }

    public function render($value, $field, $id, $name) {
        $render = '<input class="gdtt-field-boolean" type="checkbox" id="'.$id.'" name="'.$name.'"'.($value == "1" ? " checked" : "").' />';

        return $render;
    }

    public function get_values($field, $functions_list = array()) {
        return 'true<br/>false';
    }

    public function get_default($field) {
        return false;
    }
}

class gdCPT_Field_Admin_HTML extends gdCPT_Core_Field_Admin {
    public $_name = 'html';
    public $_repeater = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Standard", "gd-taxonomies-tools");
        $this->_label = __("HTML", "gd-taxonomies-tools");
        $this->_description = __("Standard HTML ready field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        return stripslashes(htmlentities($value, ENT_QUOTES, GDR2_CHARSET));
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function render($value, $field, $id, $name) {
        $render = '<textarea class="gdtt-field-html'.($field['limit'] > 0 ? ' gdtt-limit-field' : '').'" id="'.$id.'" name="'.$name.'" gdtt-reset="'.$this->get_default($field).'">'.esc_html($value).'</textarea>';

        return $render;
    }

    public function get_default($field) {
        return ' ';
    }
}

class gdCPT_Field_Admin_Listing extends gdCPT_Core_Field_Admin {
    public $_name = 'listing';

    function __construct() {
        parent::__construct();

        $this->_class = __("Standard", "gd-taxonomies-tools");
        $this->_label = __("Listing", "gd-taxonomies-tools");
        $this->_description = __("Standard listing field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        $new = trim(strip_tags($value));
        return gdr2_split_textarea($new);
    }

    public function check($value, $field) {
        return is_array($value) && !empty($value);
    }

    public function render($value, $field, $id, $name) {
        $render = '<textarea class="gdtt-field-html'.($field['limit'] > 0 ? ' gdtt-limit-field' : '').'" id="'.$id.'" name="'.$name.'" gdtt-reset="'.$this->get_default($field).'">'.join("\n", (array)$value).'</textarea>';

        return $render;
    }

    public function get_default($field) {
        return ' ';
    }
}

class gdCPT_Field_Admin_Link extends gdCPT_Core_Field_Admin {
    public $_name = 'link';
    public $_repeater = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Standard", "gd-taxonomies-tools");
        $this->_label = __("Website Link", "gd-taxonomies-tools");
        $this->_description = __("Standard website link field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        return trim(strip_tags($value));
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function shortcode_attributes() {
        return array(
            'format' => array('attr' => 'link', 'type' => 'dropdown', 'default' => 'link', 'label' => __("Format", "gd-taxonomies-tools"), 'description' => __("Format for the value to display.", "gd-taxonomies-tools"), 'values' => array('link' => __("Clickable link", "gd-taxonomies-tools"), 'raw' => __("Raw value", "gd-taxonomies-tools")))
        );
    }

    public function render($value, $field, $id, $name) {
        $render = '<input class="gdtt-field-text gdtt-field-link" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" gdtt-reset="'.$this->get_default($field).'" />';

        return $render;
    }

    public function get_default($field) {
        return ' ';
    }
}

class gdCPT_Field_Admin_Email extends gdCPT_Core_Field_Admin {
    public $_name = 'email';
    public $_repeater = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Standard", "gd-taxonomies-tools");
        $this->_label = __("Email", "gd-taxonomies-tools");
        $this->_description = __("Standard email field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        return trim(strip_tags($value));
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function shortcode_attributes() {
        return array(
            'format' => array('attr' => 'link', 'type' => 'dropdown', 'default' => 'link', 'label' => __("Format", "gd-taxonomies-tools"), 'description' => __("Format for the value to display.", "gd-taxonomies-tools"), 'values' => array('link' => __("Clickable link", "gd-taxonomies-tools"), 'raw' => __("Raw value", "gd-taxonomies-tools")))
        );
    }

    public function render($value, $field, $id, $name) {
        $render = '<input class="gdtt-field-text gdtt-field-link" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" gdtt-reset="'.$this->get_default($field).'" />';

        return $render;
    }

    public function get_default($field) {
        return ' ';
    }
}

class gdCPT_Field_Admin_Select extends gdCPT_Core_Field_Admin {
    public $_name = 'select';
    public $_rewriter = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Standard", "gd-taxonomies-tools");
        $this->_label = __("Select", "gd-taxonomies-tools");
        $this->_description = __("Standard selection field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        if ($field['selection'] == 'select' || $field['selection'] == 'radio') {
            return trim(strip_tags($value));
        } else {
            return (array)$value;
        }
    }

    public function check($value, $field) {
        if ($field['selection'] == 'select' || $field['selection'] == 'radio') {
            return $value !== '';
        } else {
            return !empty($value);
        }
    }

    public function get_values($field, $functions_list = array()) {
        $out = '/';

        switch ($field['selmethod']) {
            case 'normal':
                if (is_array($field['values'])) {
                    $out = join('<br/>', $field['values']);
                }
                break;
            case 'associative':
                if (is_array($field['assoc_values'])) {
                    $out = join('<br/>', $field['assoc_values']);
                }
                break;
            case 'function':
                if ($field['fnc_name'] !== '__none__' && !empty($field['fnc_name'])) {
                    $fnc_name = isset($functions_list[$field['fnc_name']]) ? $functions_list[$field['fnc_name']] : $field['fnc_name']."()";
                    $out = '<strong>'.__("function", "gd-taxonomies-tools").'</strong><br/><em>'.$fnc_name.'</em>';
                }
                break;
        }

        return $out;
    }

    public function render($value, $field, $id, $name) {
        global $gdtt_fields;

        $value = (array)$value;
        $p_values = $gdtt_fields->get_field_default($field);
        $multiple = '';
        $real_name = $name;

        if ($field['selection'] == 'multi' || $field['selection'] == 'checkbox') {
            $multiple = ' multiple="multiple"';
            $real_name.= '[]';
        }

        $render = '';
        switch ($field['selection']) {
            case 'multi':
            case 'select':
                $render.= '<select class="gdtt-field-select gdtt-chosen-'.$field['selection'].'" name="'.$real_name.'" id="'.$id.'"'.$multiple.'>';
                $render.= '<option value=""> </option>';

                foreach ($p_values as $f_val => $f_cap) {
                    $sel = in_array($f_val, $value) ? ' selected="selected"' : (in_array($f_cap, $value) ? ' selected="selected"' : '');
                    $render.= '<option value="'.$f_val.'"'.$sel.'>'.__($f_cap).'</option>';
                }

                $render.= '</select>';
                break;
            case 'radio':
                foreach ($p_values as $f_val => $f_cap) {
                    $sel = in_array($f_val, $value) ? ' checked' : (in_array($f_cap, $value) ? ' checked' : '');
                    $render.= '<div class="gdtt-single-select-value"><input class="gdtt-field-boolean" type="radio" id="'.$id.'" value="'.$f_val.'" name="'.$name.'"'.$sel.' /> <span>'.__($f_cap).'</span></div>';
                }
                break;
            case 'checkbox':
                foreach ($p_values as $f_val => $f_cap) {
                    $sel = in_array($f_val, $value) ? ' checked' : (in_array($f_cap, $value) ? ' checked' : '');
                    $render.= '<div class="gdtt-single-select-value"><input class="gdtt-field-boolean" type="checkbox" id="'.$id.'" value="'.$f_val.'" name="'.$real_name.'"'.$sel.' /> <span>'.__($f_cap).'</span></div>';
                }
                break;
        }

        return $render;
    }

    public function get_default($field) {
        return '';
    }
}

?>