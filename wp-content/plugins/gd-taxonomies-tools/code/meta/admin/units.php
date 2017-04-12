<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Field_Admin_Unit extends gdCPT_Core_Field_Admin {
    public $_name = 'unit';
    public $_repeater = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Units", "gd-taxonomies-tools");
        $this->_label = __("Custom Unit", "gd-taxonomies-tools");
        $this->_description = __("Selection of units field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        return (array)$value;
    }

    public function check($value, $field) {
        return $value['unit'] != '' || $value['value'] != '0';
    }

    public function get_type($field) {
        return $this->_label.' | '.$field['unit'];
    }

    public function shortcode_attributes() {
        return array(
            'format' => array('attr' => '%value% %unit%', 'type' => 'dropdown', 'default' => '%value% %unit%', 'label' => __("Format", "gd-taxonomies-tools"), 'description' => __("Format for the value to display.", "gd-taxonomies-tools"), 'values' => array('%value% %unit%' => __("Space separated", "gd-taxonomies-tools"), '%value%%unit%' => __("No space", "gd-taxonomies-tools"))),
            'decimals' => array('attr' => '', 'type' => 'number', 'default' => '', 'label' => __("Decimals", "gd-taxonomies-tools"), 'description' => __("Number of decimals to show. Leave emtpy to show unmodified.", "gd-taxonomies-tools"))
        );
    }

    public function render($value, $field, $id, $name) {
        global $gdr2_units, $gdtt_fields;

        $value = $gdtt_fields->get_field_display($value, $field);
        $units = $gdr2_units->get_values($field['unit']);
        $default = $this->get_default($field);

        $render = '<span class="gdtt-field-title-half">'.__("Value", "gd-taxonomies-tools").':</span>';
        $render.= '<input class="gdtt-field-text-half gdtt-field-currency" type="text" id="'.$id.'_value" name="'.$name.'[value]" value="'.$value['value'].'" gdtt-reset="'.$default['value'].'" />';
        $render.= '<span class="gdtt-field-spacer"></span>';
        $render.= '<span class="gdtt-field-title-half">'.__("Unit", "gd-taxonomies-tools").':</span>';

        $render.= '<select class="gdtt-field-select-half gdtt-chosen-'.$field['selection'].'" name="'.$name.'[unit]" id="'.$id.'_unit" gdtt-reset="'.$default['unit'].'">';
        foreach ($units as $f_val => $f_cap) {
            $sel = $f_val == $value['unit'] ? ' selected="selected"' : '';
            $render.= '<option value="'.$f_val.'"'.$sel.'>'.__($f_cap).'</option>';
        }
        $render.= '</select>';

        return $render;
    }

    public function get_default($field) {
        return array('value' => '0', 'unit' => '');
    }
}

class gdCPT_Field_Admin_Currency extends gdCPT_Core_Field_Admin {
    public $_name = 'currency';
    public $_repeater = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Units", "gd-taxonomies-tools");
        $this->_label = __("Currency", "gd-taxonomies-tools");
        $this->_description = __("Currency field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        return (array)$value;
    }

    public function check($value, $field) {
        return $value['currency'] != '' || $value['value'] != '';;
    }

    public function shortcode_attributes() {
        return array(
            'format' => array('attr' => '%value% %unit%', 'type' => 'dropdown', 'default' => '%value% %unit%', 'label' => __("Format", "gd-taxonomies-tools"), 'description' => __("Format for the value to display.", "gd-taxonomies-tools"), 'values' => array('%value% %unit%' => __("Value Currency Code", "gd-taxonomies-tools"), '%unit% %value%' => __("Currency Code Value", "gd-taxonomies-tools"), '%sign% %value%' => __("Currency Sign Value", "gd-taxonomies-tools"))),
            'decimals' => array('attr' => '2', 'type' => 'number', 'default' => '2', 'label' => __("Decimals", "gd-taxonomies-tools"), 'description' => __("Number of decimals to show. Leave emtpy to show unmodified.", "gd-taxonomies-tools"))
        );
    }

    public function render($value, $field, $id, $name) {
        global $gdr2_units, $gdtt_fields;

        $value = $gdtt_fields->get_field_display($value, $field);
        $currencies = $gdr2_units->get_values('currency');
        $default = $this->get_default($field);

        $render = '<span class="gdtt-field-title-half">'.__("Value", "gd-taxonomies-tools").':</span>';
        $render.= '<input class="gdtt-field-text-half gdtt-field-currency" type="text" id="'.$id.'_value" name="'.$name.'[value]" value="'.$value['value'].'" gdtt-reset="'.$default['value'].'" />';
        $render.= '<span class="gdtt-field-spacer"></span>';
        $render.= '<span class="gdtt-field-title-half">'.__("Currency", "gd-taxonomies-tools").':</span>';

        $render.= '<select class="gdtt-field-select-half gdtt-chosen-'.$field['selection'].'" name="'.$name.'[currency]" id="'.$id.'_currency" gdtt-reset="'.$default['currency'].'">';
        foreach ($currencies as $f_val => $f_cap) {
            $sel = $f_val == $value['currency'] ? ' selected="selected"' : '';
            $render.= '<option value="'.$f_val.'"'.$sel.'>'.__($f_cap).'</option>';
        }
        $render.= '</select>';

        return $render;
    }

    public function get_default($field) {
        return array('value' => '0', 'currency' => 'USD');
    }
}

class gdCPT_Field_Admin_Resolution extends gdCPT_Core_Field_Admin {
    public $_name = 'resolution';
    public $_repeater = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Units", "gd-taxonomies-tools");
        $this->_label = __("Resolution", "gd-taxonomies-tools");
        $this->_description = __("Resolution field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        return (array)$value;
    }

    public function check($value, $field) {
        return $value['x'] != '0' || $value['y'] != '0';
    }

    public function render($value, $field, $id, $name) {
        global $gdtt_fields;

        $value = $gdtt_fields->get_field_display($value, $field);
        $default = $this->get_default($field);

        $render = '<span class="gdtt-field-title-half">X ('.__("pixels", "gd-taxonomies-tools").'):</span>';
        $render.= '<input class="gdtt-field-text-half gdtt-field-int" type="text" id="'.$id.'_x" name="'.$name.'[x]" value="'.$value['x'].'" gdtt-reset="'.$default['x'].'" />';
        $render.= '<span class="gdtt-field-spacer"></span>';
        $render.= '<span class="gdtt-field-title-half">Y ('.__("pixels", "gd-taxonomies-tools").'):</span>';
        $render.= '<input class="gdtt-field-text-half gdtt-field-int" type="text" id="'.$id.'_y" name="'.$name.'[y]" value="'.$value['y'].'" gdtt-reset="'.$default['y'].'" />';

        return $render;
    }

    public function get_default($field) {
        return array('x' => '0', 'y' => '0');
    }
}

class gdCPT_Field_Admin_Dimensions extends gdCPT_Core_Field_Admin {
    public $_name = 'dimensions';
    public $_repeater = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Units", "gd-taxonomies-tools");
        $this->_label = __("Dimensions", "gd-taxonomies-tools");
        $this->_description = __("Dimensions field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        return (array)$value;
    }

    public function check($value, $field) {
        return $value['x']['value'] != '0' || $value['y']['value'] != '0' || $value['z']['value'] != '0';
    }

    public function render($value, $field, $id, $name) {
        global $gdr2_units, $gdtt_fields;

        $value = $gdtt_fields->get_field_display($value, $field);
        $units = $gdr2_units->get_values('length');
        $default = $this->get_default($field);

        $render = '<div class="gdtt-one-dim"><span class="gdtt-field-title-half">X:</span>';
        $render.= '<input class="gdtt-field-text-half gdtt-field-int" type="text" id="'.$id.'_x_value" name="'.$name.'[x][value]" value="'.$value['x']['value'].'" gdtt-reset="'.$default['x']['value'].'" />';
        $render.= '<span class="gdtt-field-spacer"></span>';
        $render.= '<span class="gdtt-field-title-half gdtt-field-title-unit">'.__("Unit", "gd-taxonomies-tools").':</span>';
        $render.= '<select class="gdtt-field-select-half gdtt-chosen-'.$field['selection'].'" name="'.$name.'[x][unit]" id="'.$id.'_x_unit" gdtt-reset="'.$default['x']['unit'].'">';
        foreach ($units as $f_val => $f_cap) {
            $sel = $f_val == $value['x']['unit'] ? ' selected="selected"' : '';
            $render.= '<option value="'.$f_val.'"'.$sel.'>'.__($f_cap).'</option>';
        }
        $render.= '</select></div>';

        $render.= '<div class="gdtt-one-dim"><span class="gdtt-field-title-half">Y:</span>';
        $render.= '<input class="gdtt-field-text-half gdtt-field-int" type="text" id="'.$id.'_y_value" name="'.$name.'[y][value]" value="'.$value['y']['value'].'" gdtt-reset="'.$default['y']['value'].'" />';
        $render.= '<span class="gdtt-field-spacer"></span>';
        $render.= '<span class="gdtt-field-title-half gdtt-field-title-unit">'.__("Unit", "gd-taxonomies-tools").':</span>';
        $render.= '<select class="gdtt-field-select-half gdtt-chosen-'.$field['selection'].'" name="'.$name.'[y][unit]" id="'.$id.'_y_unit" gdtt-reset="'.$default['y']['unit'].'">';
        foreach ($units as $f_val => $f_cap) {
            $sel = $f_val == $value['y']['unit'] ? ' selected="selected"' : '';
            $render.= '<option value="'.$f_val.'"'.$sel.'>'.__($f_cap).'</option>';
        }
        $render.= '</select></div>';

        $render.= '<div class="gdtt-one-dim"><span class="gdtt-field-title-half">Z:</span>';
        $render.= '<input class="gdtt-field-text-half gdtt-field-int" type="text" id="'.$id.'_z_value" name="'.$name.'[z][value]" value="'.$value['z']['value'].'" gdtt-reset="'.$default['z']['value'].'" />';
        $render.= '<span class="gdtt-field-spacer"></span>';
        $render.= '<span class="gdtt-field-title-half gdtt-field-title-unit">'.__("Unit", "gd-taxonomies-tools").':</span>';
        $render.= '<select class="gdtt-field-select-half gdtt-chosen-'.$field['selection'].'" name="'.$name.'[z][unit]" id="'.$id.'_z_unit" gdtt-reset="'.$default['z']['unit'].'">';

        foreach ($units as $f_val => $f_cap) {
            $sel = $f_val == $value['z']['unit'] ? ' selected="selected"' : '';
            $render.= '<option value="'.$f_val.'"'.$sel.'>'.__($f_cap).'</option>';
        }

        $render.= '</select></div>';

        return $render;
    }

    public function get_default($field) {
        return array('x' => array('value' => '0', 'unit' => 'cm'), 'y' => array('value' => '0', 'unit' => 'cm'), 'z' => array('value' => '0', 'unit' => 'cm'));
    }
}

?>