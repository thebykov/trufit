<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Field_Admin_Date extends gdCPT_Core_Field_Admin {
    public $_name = 'date';
    public $_repeater = true;
    public $_rewriter = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Date / Time", "gd-taxonomies-tools");
        $this->_label = __("Date", "gd-taxonomies-tools");
        $this->_description = __("Standard date field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        $value = trim(strip_tags($value));

        if ($field['datesave'] == 'timestamp') {
            return strtotime($value);
        } else {
            $format = $field['datesave'] == 'mysql' ? 'Y-m-d H:i:s' : ($field['datesave'] == 'dashed' ? 'Y-m-d' : 'Y.m.d');

            return date($format, strtotime($value));
        }
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function shortcode_attributes() {
        return array(
            'format' => array('attr' => '', 'type' => 'input', 'default' => '', 'label' => __("Format", "gd-taxonomies-tools"), 'description' => __("PHP date/time format used to display date.", "gd-taxonomies-tools"))
        );
    }

    public function render($value, $field, $id, $name) {
        if ($value != '') {
            $vtime = is_numeric($value) && (int)$value == $value ? (int)$value : strtotime($value);

            if ($vtime === false) {
                $delimiter = strpos($value, '.') === false ? '-' : '.';
                list($y, $m, $d) = explode($delimiter, $value);
                $vtime = mktime(0, 0, 0, intval($m), intval($d), intval($y));
            }

            $value = date('m/d/Y', $vtime);
        } else {
            $value = '';
        }

        $render = '<input class="gdtt-field-text gdtt-field-date" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" gdtt-reset="'.$this->get_default($field).'" />';

        return $render;
    }

    public function get_default($field) {
        return ' ';
    }
}

class gdCPT_Field_Admin_Month extends gdCPT_Core_Field_Admin {
    public $_name = 'month';
    public $_repeater = true;
    public $_rewriter = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Date / Time", "gd-taxonomies-tools");
        $this->_label = __("Month", "gd-taxonomies-tools");
        $this->_description = __("Standard month field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        $value = trim(strip_tags($value));

        if ($field['datesave'] == 'timestamp') {
            return strtotime($value);
        } else {
            $format = $field['datesave'] == 'mysql' ? 'Y-m-d H:i:s' : ($field['datesave'] == 'dashed' ? 'Y-m' : 'Y.m');

            return date($format, strtotime($value));
        }
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function shortcode_attributes() {
        return array(
            'format' => array('attr' => '', 'type' => 'input', 'default' => '', 'label' => __("Format", "gd-taxonomies-tools"), 'description' => __("PHP date/time format used to display date.", "gd-taxonomies-tools"))
        );
    }

    public function render($value, $field, $id, $name) {
        if ($value != '') {
            $vtime = is_numeric($value) && (int)$value == $value ? (int)$value : strtotime($value);

            if ($vtime === false) {
                $delimiter = strpos($value, '.') === false ? '-' : '.';
                list($y, $m) = explode($delimiter, $value);
                $vtime = mktime(0, 0, 0, intval($m), 0, intval($y));
            }

            $value = date('F Y', $vtime);
        } else {
            $value = '';
        }

        $render = '<input class="gdtt-field-text gdtt-field-month" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" gdtt-reset="'.$this->get_default($field).'" />';

        return $render;
    }

    public function get_default($field) {
        return ' ';
    }
}

class gdCPT_Field_Admin_Time extends gdCPT_Core_Field_Admin {
    public $_name = 'time';
    public $_repeater = true;
    public $_rewriter = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Date / Time", "gd-taxonomies-tools");
        $this->_label = __("Time", "gd-taxonomies-tools");
        $this->_description = __("Standard time field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        $value = trim(strip_tags($value));
        $format = $field['datesave'] == 'dashed' ? 'H-i-s' : ($field['datesave'] == 'dotted' ? 'H.i.s' : 'H:i:s');

        return date($format, strtotime($value));
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function shortcode_attributes() {
        return array(
            'format' => array('attr' => '', 'type' => 'input', 'default' => '', 'label' => __("Format", "gd-taxonomies-tools"), 'description' => __("PHP date/time format used to display date.", "gd-taxonomies-tools"))
        );
    }

    public function render($value, $field, $id, $name) {
        if ($value != '') {
            $delimiter = strpos($value, '-') !== false ? '-' : strpos($value, '.') !== false ? '.' : ':';
            list($h, $m, $s) = explode($delimiter, $value);
            $vtime = mktime(intval($h), intval($m), intval($s));

            $value = date('H:i:s', $vtime);
        } else {
            $value = '';
        }

        $render = '<input class="gdtt-field-text gdtt-field-time" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" gdtt-reset="'.$this->get_default($field).'" />';

        return $render;
    }

    public function get_default($field) {
        return ' ';
    }
}

class gdCPT_Field_Admin_Datetime extends gdCPT_Core_Field_Admin {
    public $_name = 'date_time';
    public $_repeater = true;
    public $_rewriter = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Date / Time", "gd-taxonomies-tools");
        $this->_label = __("Date Time", "gd-taxonomies-tools");
        $this->_description = __("Standard date time field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        $value = trim(strip_tags($value));

        if ($field['datesave'] == 'timestamp') {
            return strtotime($value);
        } else {
            return date('Y-m-d H:i:s', strtotime($value));
        }
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function shortcode_attributes() {
        return array(
            'format' => array('attr' => '', 'type' => 'input', 'default' => '', 'label' => __("Format", "gd-taxonomies-tools"), 'description' => __("PHP date/time format used to display date.", "gd-taxonomies-tools"))
        );
    }

    public function render($value, $field, $id, $name) {
        if ($value != '') {
            $vtime = is_numeric($value) && (int)$value == $value ? (int)$value : strtotime($value);

            $value = date('Y-m-d H:i:s', $vtime);
        } else {
            $value = '';
        }

        $render = '<input class="gdtt-field-text gdtt-field-datetime" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" gdtt-reset="'.$this->get_default($field).'" />';

        return $render;
    }

    public function get_default($field) {
        return ' ';
    }
}

class gdCPT_Field_Admin_Period extends gdCPT_Core_Field_Admin {
    public $_name = 'period';
    public $_repeater = true;
    public $_rewriter = true;

    function __construct() {
        parent::__construct();

        $this->_class = __("Date / Time", "gd-taxonomies-tools");
        $this->_label = __("Time Period", "gd-taxonomies-tools");
        $this->_description = __("Standard time period field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        return trim(strip_tags($value));
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function render($value, $field, $id, $name) {
        $render = '<input class="gdtt-field-text gdtt-field-period" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" gdtt-reset="'.$this->get_default($field).'" />';

        return $render;
    }

    public function get_default($field) {
        return '0YR:0MN:0DY:0HR:0MI:0SE';
    }
}

?>