<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Field_Display_Content_User extends gdCPT_Core_Field_Display {
    public $_name = 'user';

    function __construct() {
        $this->_ready['bbpress'] = false;
        $this->_ready['filter'] = false;

        parent::__construct();
    }

    public function get_attributes($field) {
        return array('display' => 'name');
    }

    public function render($value, $field, $atts) {
        $term = get_term($value, $field['values']);
        $atts['term'] = isset($atts['term']) ? $atts['term'] : 'name';

        switch ($atts['term']) {
            default:
            case 'name':
                return $term->name;
                break;
            case 'id':
                return $value;
                break;
            case 'slug':
                return $term->slug;
                break;
            case 'link':
                return '<a href="'.get_term_link($term).'">'.$term->name.'</a>';
                break;
        }
    }
}

?>