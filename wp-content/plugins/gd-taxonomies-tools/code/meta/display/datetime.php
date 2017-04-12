<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Field_Object_Date extends gdCPT_Core_Field_Object { }

class gdCPT_Field_Display_Date extends gdCPT_Core_Field_Display {
    public $_name = 'date';
    public $_object = 'gdCPT_Field_Object_Date';

    public function get_attributes($field) {
        return array('format' => null);
    }

    public function render($value, $field, $atts) {
        if ($value != '') {
            $vtime = is_numeric($value) && (int)$value == $value ? (int)$value : strtotime($value);

            if ($vtime === false) {
                $delimiter = strpos($value, '.') === false ? '-' : '.';
                list($y, $m, $d) = explode($delimiter, $value);
                $vtime = mktime(0, 0, 0, $m, $d, $y);
            }

            $format = is_null($atts['format']) ? get_option('date_format') : $atts['format'];
            return date_i18n($format, $vtime);
        } else {
            return '';
        }
    }
}

class gdCPT_Field_Display_Month extends gdCPT_Core_Field_Display {
    public $_name = 'month';
    public $_object = 'gdCPT_Field_Object_Date';

    public function get_attributes($field) {
        return array('format' => null);
    }

    public function render($value, $field, $atts) {
        if ($value != '') {
            $vtime = is_numeric($value) && (int)$value == $value ? (int)$value : strtotime($value);

            if ($vtime === false) {
                $delimiter = strpos($value, '.') === false ? '-' : '.';
                list($y, $m) = explode($delimiter, $value);
                $vtime = mktime(0, 0, 0, $m, 0, $y);
            }

            $format = is_null($atts['format']) ? 'F Y' : $atts['format'];
            return date_i18n($format, $vtime);
        } else {
            return '';
        }
    }
}

class gdCPT_Field_Display_Time extends gdCPT_Core_Field_Display {
    public $_name = 'time';
    public $_object = 'gdCPT_Field_Object_Date';

    public function get_attributes($field) {
        return array('format' => null);
    }

    public function render($value, $field, $atts) {
        if ($value != '') {
            $delimiter = strpos($value, '.') === false ? '-' : '.';
            list($h, $m, $s) = explode($delimiter, $value);
            $vtime = mktime($h, $m, $s);

            $format = is_null($atts['format']) ? get_option('time_format') : $atts['format'];
            return date_i18n($format, $vtime);
        } else {
            return '';
        }
    }
}

class gdCPT_Field_Display_Datetime extends gdCPT_Core_Field_Display {
    public $_name = 'date_time';
    public $_object = 'gdCPT_Field_Object_Date';

    public function get_attributes($field) {
        return array('format' => null);
    }

    public function render($value, $field, $atts) {
        if ($value != '') {
            $vtime = is_numeric($value) && (int)$value == $value ? (int)$value : strtotime($value);

            $format = is_null($atts['format']) ? get_option('date_format').' '.get_option('time_format') : $atts['format'];
            return date_i18n($format, $vtime);
        } else {
            return '';
        }
    }
}

class gdCPT_Field_Display_Period extends gdCPT_Core_Field_Display {
    public $_name = 'period';
    private $_translate = array();

    public function __construct() {
        $this->_translate = array(
            'YR' => array(__("Year", "gd-taxonomies-tools"), __("Years", "gd-taxonomies-tools")),
            'MN' => array(__("Month", "gd-taxonomies-tools"), __("Months", "gd-taxonomies-tools")),
            'DY' => array(__("Day", "gd-taxonomies-tools"), __("Days", "gd-taxonomies-tools")),
            'HR' => array(__("Hour", "gd-taxonomies-tools"), __("Hours", "gd-taxonomies-tools")),
            'MI' => array(__("Minute", "gd-taxonomies-tools"), __("Minutes", "gd-taxonomies-tools")),
            'SE' => array(__("Second", "gd-taxonomies-tools"), __("Seconds", "gd-taxonomies-tools")),
        );
    }

    public function render($value, $field, $atts) {
        if ($value != '') {
            $result = '';
            $value = explode(':', $value);

            foreach ($value as $set) {
                $value = intval(substr($set, 0, strlen($set) - 2));

                if ($value != 0) {
                    $unit = substr($set, strlen($set) - 2, 2);

                    if (isset($this->_translate[$unit])) {
                        $result[] = $value.' '._n($this->_translate[$unit][0], $this->_translate[$unit][1], $value, "gd-taxonomies-tools");
                    }
                }
            }

            return join(' ', $result);
        } else {
            
        }
    }
}

?>