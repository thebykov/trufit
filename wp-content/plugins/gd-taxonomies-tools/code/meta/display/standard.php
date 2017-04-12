<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Field_Display_Text extends gdCPT_Core_Field_Display {
    public $_name = 'text';

    function __construct() {
        $this->_ready['filter'] = false;

        parent::__construct();
    }

    public function render($value, $field, $atts) {
        return $value;
    }
}

class gdCPT_Field_Display_Number extends gdCPT_Core_Field_Display {
    public $_name = 'number';

    function __construct() {
        $this->_ready['filter'] = false;

        parent::__construct();
    }

    public function get_attributes($field) {
        return array('decimals' => 0);
    }

    public function render($value, $field, $atts) {
        $decimals = isset($atts['decimals']) && $atts['decimals'] != '' ? $atts['decimals'] : -1;

        return $decimals != '' && is_numeric($decimals) ? number_format($value, intval($decimals)) : $value;
    }
}

class gdCPT_Field_Display_Boolean extends gdCPT_Core_Field_Display {
    public $_name = 'boolean';

    public function render($value, $field, $atts) {
        return $value == 1 ? __("True", "gd-taxonomies-tools") : __("False", "gd-taxonomies-tools");
    }
}

class gdCPT_Field_Display_HTML extends gdCPT_Core_Field_Display {
    public $_name = 'html';

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

class gdCPT_Field_Display_Listing extends gdCPT_Core_Field_Display {
    public $_name = 'listing';

    function __construct() {
        $this->_ready['filter'] = false;

        parent::__construct();
    }

    public function render($value, $field, $atts) {
        $content = '';

        if (!is_null($value) && !empty($value)) {
            $content.= '<ul><li>';
            $content.= join("</li><li>", (array)$value);
            $content.= '</li></ul>';
        }

        return $content;
    }
}

class gdCPT_Field_Display_Link extends gdCPT_Core_Field_Display {
    public $_name = 'link';

    function __construct() {
        $this->_ready['filter'] = false;

        parent::__construct();
    }

    public function get_attributes($field) {
        return array('format' => 'link');
    }

    public function render($value, $field, $atts) {
        $defaults = array('target' => '', 'rel' => '');
        $atts = wp_parse_args($atts, $defaults);

        $format = isset($atts['format']) ? $atts['format'] : 'link';

        if ($format == 'link') {
            $title = $value;

            if (is_email($value)) {
                $content = 'email:'.$value;

                $atts['target'] = '';
            } else {
                if (substr($value, 0, 7) != 'http://' && substr($value, 0, 8) != 'https://') {
                    $content = 'http://'.$value;
                } else {
                    $content = $value;
                }
            }

            $content = '<a'.($atts['target'] != '' ? ' target="'.$atts['target'].'"' : '').($atts['rel'] != '' ? ' rel="'.$atts['rel'].'"' : '').' href="'.$content.'">'.$title.'</a>';

            return $content;
        } else {
            return $value;
        }
    }
}

class gdCPT_Field_Display_Email extends gdCPT_Core_Field_Display {
    public $_name = 'email';

    function __construct() {
        $this->_ready['filter'] = false;

        parent::__construct();
    }

    public function get_attributes($field) {
        return array('format' => 'link');
    }

    public function render($value, $field, $atts) {
        $defaults = array('target' => '', 'rel' => '');
        $atts = wp_parse_args($atts, $defaults);

        $format = isset($atts['format']) ? $atts['format'] : 'link';

        if ($format == 'link') {
            $title = $value;

            if (is_email($value)) {
                $content = 'email:'.antispambot($value);
                $atts['target'] = '';
            }

            $content = '<a'.($atts['rel'] != '' ? ' rel="'.$atts['rel'].'"' : '').' href="'.$content.'">'.$title.'</a>';

            return $content;
        } else {
            return $value;
        }
    }
}

class gdCPT_Field_Display_Select extends gdCPT_Core_Field_Display {
    public $_name = 'select';

    public function render($value, $field, $atts) {
        $available = $this->get_defaults($field);

        $content = '';

        if ($field['selection'] == 'select' || $field['selection'] == 'radio') {
            if ($field['selmethod'] != 'normal') {
                $content.= isset($available[$value]) ? __($available[$value]) : $value;
            } else {
                $content.= is_null($value) ? '' : __($value);
            }
        } else {
            if (!is_null($value) && !empty($value)) {
                $data = array();

                foreach ((array)$value as $v) {
                    if ($field['selmethod'] != 'normal') {
                        $data[] = isset($available[$v]) ? __($available[$v]) : $v;
                    } else {
                        $data[] = __($v);
                    }
                }

                if (isset($atts['format']) && $atts['format'] == 'comma') {
                    $content.= join(', ', $data);
                } else {
                    $content.= '<ul><li>';
                    $content.= join('</li><li>', $data);
                    $content.= '</li></ul>';
                }
            }
        }

        return $content;
    }

    public function get_defaults($field) {
        $v = array();

        switch ($field['selmethod']) {
            case 'normal':
                if (is_array($field['values'])) {
                    foreach ($field['values'] as $val) {
                        $v[$val] = $val;
                    }
                }
                break;
            case 'associative':
                $v = $field['assoc_values'];
                break;
            case 'function':
                if ($field['fnc_name'] !== '__none__' && !empty($field['fnc_name'])) {
                    $v = call_user_func($field['fnc_name']);
                }
                break;
        }

        return $v;
    }
}

?>