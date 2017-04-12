<?php

if (!defined('ABSPATH')) exit;

abstract class gdCPT_Core_Field_Admin {
    public $_class = '';
    public $_name = '';
    public $_label = '';
    public $_description = '';
    public $_repeater = false;
    public $_rewriter = false;
    public $_js_hooks = array();
    public $_shortcode = 'cpt_field';

    function __construct() { }

    public function shortcode_attributes() {
        return array();
    }

    public function get_class() {
        return $this->_class;
    }

    public function get_name() {
        return $this->_name;
    }

    public function get_label() {
        return $this->_label;
    }

    public function get_description() {
        return $this->_description;
    }

    public function is_rewritable() {
        return $this->_rewriter;
    }
    
    public function is_repeatable() {
        return $this->_repeater;
    }

    public function get_type($field) {
        return $this->_label;
    }

    public function get_values($field, $functions_list = array()) {
        return '/';
    }

    public function update_value($value, $field) {
        if ($value == '' || (is_array($value) && empty($value))) {
            return $this->get_default($field);
        } else {
            return $value;
        }
    }

    public function embed_html() { }

    public function embed_css() { }

    public function embed_js() {
        if (!empty($this->_js_hooks)) {
            $hooks = array();
            foreach ($this->_js_hooks as $hook => $fnc) {
                $hooks[] = "'".$hook."': ".$fnc;
            }

            echo "gdCPTAdmin.meta['".$this->_name."'] = {".join(", ", $hooks)."};".GDR2_EOL;
        }
    }

    public function embed_js_postedit() { }

    abstract public function clean($value, $field);
    abstract public function check($value, $field);
    abstract public function render($value, $field, $id, $name);

    abstract public function get_default($field);
}

abstract class gdCPT_Core_Field_Display {
    public $_name = '';
    public $_object = 'gdCPT_Field_Object_Shared';
    public $_ready = array(
        'bbpress' => true,
        'column' => true,
        'filter' => true
    );

    function __construct() { }

    public function get_name() {
        return $this->_name;
    }

    public function get_id($field) {
        return 'gdcp_field_'.$field['type'].'_'.$field['code'];
    }

    public function get_values($value, $field) {
        return $value;
    }

    public function get_defaults($field) {
        return null;
    }

    public function get_attributes($field) {
        return array();
    }

    public function get_data_object() {
        return $this->_object;
    }
    
    abstract public function render($value, $field, $atts);
}

abstract class gdCPT_Core_Field_Object {
    public $field;
    public $data;

    function __construct($field) {
        $this->field = $field;
        $this->data = array();
    }

    public function count() {
        return count($this->data);
    }

    public function get($atts = array(), $single = false, $force = false, $key = 'rendered') {
        global $gdtt;

        $id = is_numeric($single) ? intval($single) : ($single === true ? 0 : false);

        if ($id === false) {
            $data = array();

            for ($i = 0; $i < count($this->data); $i++) {
                if (is_null($this->data[$i][$key]) || $force) {
                    $this->data[$i][$key] = $gdtt->prepare_cpt_field($this->field, $this->data[$i]['raw'], $atts);
                }

                $data[] = $this->data[$i][$key];
            }

            return $data;
        } else {
            if (is_null($this->data[$id][$key]) || $force) {
                $this->data[$id][$key] = $gdtt->prepare_cpt_field($this->field, $this->data[$id]['raw'], $atts);
            }

            return $this->data[$id][$key];
        }
    }

    public function val($single = true, $force = false) {
        $atts = array('tag' => '');
        return $this->get($atts, $single, $force, 'value');
    }

    public function raw($single = false) {
        if (is_numeric($single)) {
            return $this->data[intval($single)]['raw'];
        } else if ($single === true) {
            return $this->data[0]['raw'];
        } else {
            $data = array();

            foreach ($this->data as $d) {
                $data[] = $d['raw'];
            }

            return $data;
        }
    }
}

class gdCPT_Field_Object_Shared extends gdCPT_Core_Field_Object { }

?>