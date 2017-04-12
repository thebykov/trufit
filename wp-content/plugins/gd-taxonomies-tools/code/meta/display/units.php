<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Field_Display_Unit extends gdCPT_Core_Field_Display {
    public $_name = 'unit';

    function __construct() {
        $this->_ready['filter'] = false;

        parent::__construct();
    }

    public function get_field_display($value, $field) {
        $default = array('value' => '0', 'unit' => '');

        if (isset($value['value'])) {
            $default['value'] = $value['value'];
        }

        if (isset($value['unit'])) {
            $default['unit'] = $value['unit'];
        }

        return $default;
    }

    public function get_attributes($field) {
        return array('format' => '%value% %unit%', 'decimals' => '');
    }

    public function render($value, $field, $atts) {
        global $gdr2_units;

        $content = $this->get_field_display($value, $field);
        $format = isset($atts['format']) ? $atts['format'] : '%value% %unit%';
        $decimals = isset($atts['decimals']) && $atts['decimals'] != '' ? $atts['decimals'] : '';
        $value = $decimals != '' && is_numeric($decimals) ? number_format($content['value'], intval($decimals)) : $content['value'];

        $parts = array(
            '%value%' => $value, '%unit%' => $content['unit'],
            '%sign%' => $gdr2_units->get_display_value($field['unit'], $content['unit'])
        );

        foreach ($parts as $code => $val) {
            $format = str_replace($code, $val, $format);
        }

        return $format;
    }
}

class gdCPT_Field_Display_Currency extends gdCPT_Core_Field_Display {
    public $_name = 'currency';

    function __construct() {
        $this->_ready['filter'] = false;

        parent::__construct();
    }

    public function get_field_display($value, $field) {
        $default = array('value' => '0', 'currency' => 'USD');

        if (isset($value['value'])) {
            $default['value'] = $value['value'];
        }

        if (isset($value['currency'])) {
            $default['currency'] = $value['currency'];
        }

        return $default;
    }

    public function get_attributes($field) {
        return array('format' => '%value% %unit%', 'decimals' => 2);
    }

    public function render($value, $field, $atts) {
        global $gdr2_units;

        $content = $this->get_field_display($value, $field);
        $format = isset($atts['format']) && $atts['format'] != '' ? $atts['format'] : '%value% %unit%';
        $decimals = isset($atts['decimals']) && $atts['decimals'] != '' ? $atts['decimals'] : 2;
        $value = $decimals != '' && is_numeric($decimals) ? number_format($content['value'], intval($decimals)) : $content['value'];

        $parts = array(
            '%value%' => $value, '%unit%' => $content['currency'],
            '%sign%' => $gdr2_units->get_display_value('currency', $content['currency'])
        );

        foreach ($parts as $code => $val) {
            $format = str_replace($code, $val, $format);
        }

        return $format;
    }
}

class gdCPT_Field_Display_Resolution extends gdCPT_Core_Field_Display {
    public $_name = 'resolution';

    function __construct() {
        $this->_ready['filter'] = false;

        parent::__construct();
    }

    public function get_field_display($value, $field) {
        $default = array('x' => '0', 'y' => '0');

        if (isset($value['x'])) {
            $default['x'] = $value['x'];
        }

        if (isset($value['y'])) {
            $default['y'] = $value['y'];
        }

        return $default;
    }

    public function get_attributes($field) {
        return array('format' => '%x_value% %x_unit% x %y_value% %y_unit% x %z_value% %z_unit%', 'decimals' => 2);
    }

    public function render($value, $field, $atts) {
        $content = $this->get_field_display($value, $field);
        return $content['x'].' x '.$content['y'];
    }
}

class gdCPT_Field_Display_Dimensions extends gdCPT_Core_Field_Display {
    public $_name = 'dimensions';

    function __construct() {
        $this->_ready['filter'] = false;

        parent::__construct();
    }

    public function get_field_display($value, $field) {
        $default = array('x' => array('value' => '0', 'unit' => 'cm'), 'y' => array('value' => '0', 'unit' => 'cm'), 'z' => array('value' => '0', 'unit' => 'cm'));

        if (isset($value['x'])) {
            $default['x'] = $value['x'];
        }

        if (isset($value['y'])) {
            $default['y'] = $value['y'];
        }

        if (isset($value['z'])) {
            $default['z'] = $value['z'];
        }

        return $default;
    }

    public function get_attributes($field) {
        return array('format' => '%x% x %y%');
    }

    public function render($value, $field, $atts) {
        $content = $this->get_field_display($value, $field);
        return $content['x']['value'].' '.$content['x']['unit'].' x '.$content['y']['value'].' '.$content['y']['unit'].' x '.$content['z']['value'].' '.$content['z']['unit'];
    }
}

?>