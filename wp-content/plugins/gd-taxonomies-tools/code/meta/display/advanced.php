<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Field_Display_Color extends gdCPT_Core_Field_Display {
    public $_name = 'color';

    public function render($value, $field, $atts) {
        return '#'.$value;
    }
}

class gdCPT_Field_Display_Editor extends gdCPT_Core_Field_Display {
    public $_name = 'editor';

    function __construct() {
        $this->_ready['column'] = false;
        $this->_ready['filter'] = false;

        parent::__construct();
    }

    public function render($value, $field, $atts) {
        $content = gdr2_entity_decode($value);

        if (!isset($atts['raw']) || !$atts['raw']) {
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
        }

        return $content;
    }
}

class gdCPT_Field_Display_Image extends gdCPT_Core_Field_Display {
    public $_name = 'image';

    function __construct() {
        $this->_ready['bbpress'] = false;
        $this->_ready['filter'] = false;

        parent::__construct();
    }

    public function get_field_display($value, $field) {
        $default = array('url' => '', 'id' => '');

        if (is_numeric($value)) {
            $default['id'] = intval($value);
            $image = wp_get_attachment_image_src($default['id'], 'full');
            $default['url'] = $image[0];
        } else {
            $default['url'] = $value;
        }

        return $default;
    }

    public function get_attributes($field) {
        return array('image' => 'img', 'size' => 'full');
    }

    public function render($value, $field, $atts) {
        $value = $this->get_field_display($value, $field);

        if ($atts['image'] == 'img') {
            if ($atts['size'] != 'full' && $value['id'] != '') {
                $image = wp_get_attachment_image_src($value['id'], $atts['size']);
                $value['url'] = $image[0];
            }

            return '<img src="'.$value['url'].'" alt="" />';
        } else {
            return $value['url'];
        }
    }
}

class gdCPT_Field_Display_Rewrite extends gdCPT_Core_Field_Display {
    public $_name = 'rewrite';

    function __construct() {
        $this->_ready['bbpress'] = false;
        $this->_ready['column'] = false;
        $this->_ready['filter'] = false;

        parent::__construct();
    }

    public function render($value, $field, $atts) {
        return $value;
    }
}

?>