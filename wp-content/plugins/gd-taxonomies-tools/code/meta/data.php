<?php

if (!defined('ABSPATH')) exit;

class gdCPT_MetaBox_Data {
    public $post_id;
    public $meta_box;

    public $fields = array();

    function __construct($post_id, $meta_box) {
        global $gdtt, $wpdb, $gdtt_fields;

        $this->post_id = $post_id;
        $this->meta_box = gdr2_clone($meta_box);

        $fields = $meta_box['fields'];
        $this->meta_box['fields'] = array();

        foreach ($fields as $field) {
            if (isset($gdtt->m['fields'][$field])) {
                $f = (array)$gdtt->m['fields'][$field];

                if ($gdtt_fields->is_loaded($f['type'])) {
                    $this->meta_box['fields'][$field] = $f;

                    $new_obj_class = $gdtt_fields->classes[$f['type']];
                    $this->fields[$field] = new $new_obj_class($f);
                }
            }
        }

        $fields = array_keys($this->meta_box['fields']);

        if (!empty($fields)) {
            $sql = "select meta_key, meta_value from ".$wpdb->postmeta." where meta_key in ('".join("', '", $fields)."') and post_id = ".$this->post_id;
            $raw = $wpdb->get_results($sql);

            foreach ($raw as $row) {
                $this->fields[$row->meta_key]->data[] = array(
                    'raw' => maybe_unserialize($row->meta_value), 
                    'value' => null,
                    'rendered' => null);
            }

            foreach ($fields as $field) {
                if (empty($this->fields[$row->meta_key]->data)) {
                    if ($this->meta_box['fields'][$field]['type'] == 'boolean') {
                        $this->fields[$row->meta_key]->data[] = array('raw' => 0, 'value' => null, 'rendered' => null);
                    } else {
                        $this->fields[$row->meta_key]->data[] = array('raw' => '', 'value' => null, 'rendered' => null);
                    }
                }
            }
        }
    }

    function __get($name) {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        } else {
            return null;
        }
    }
    
    public function get_fields($return = 'codes') {
        switch ($return) {
            default:
            case 'codes':
                return array_keys($this->meta_box['fields']);
                break;
            case 'objects':
                return $this->meta_box['fields'];
                break;
            case 'dropdown':
                return wp_list_pluck($this->meta_box['fields'], 'name');
                break;
        }
    }
}

class gdCPT_Meta {
    public $post_id;
    public $meta_box;

    public $data = array();

    function __construct($post_id, $meta_box) {
        global $gdtt, $wpdb;

        $this->post_id = $post_id;
        $this->meta_box = gdr2_clone($meta_box);

        $fields = $meta_box['fields'];
        $this->meta_box['fields'] = array();

        foreach ($fields as $field) {
            if (isset($gdtt->m['fields'][$field])) {
                $this->meta_box['fields'][$field] = (array)$gdtt->m['fields'][$field];
                $this->data[$field] = array();
            }
        }

        $sql = "select meta_key, meta_value from ".$wpdb->postmeta." where meta_key in ('".join("', '", $fields)."') and post_id = ".$this->post_id;
        $raw = $wpdb->get_results($sql);

        foreach ($raw as $row) {
            $this->data[$row->meta_key][] = array(
                'raw' => maybe_unserialize($row->meta_value), 
                'rendered' => null);
        }

        foreach ($fields as $field) {
            if (empty($this->data[$field])) {
                if ($this->meta_box['fields'][$field]['type'] == 'boolean') {
                    $this->data[$field][] = array('raw' => 0, 'rendered' => null);
                } else {
                    $this->data[$field][] = array('raw' => '', 'rendered' => null);
                }
            }
        }
    }

    public function get($field_code, $atts = array(), $single = false, $force = false) {
        if (isset($this->data[$field_code]) && !empty($this->data[$field_code])) {
            global $gdtt;

            if ($single) {
                if (is_null($this->data[$field_code][0]['rendered']) || $force) {
                    $this->data[$field_code][0]['rendered'] = $gdtt->prepare_cpt_field($this->meta_box['fields'][$field_code], $this->data[$field_code][0]['raw'], $atts);
                }

                return $this->data[$field_code][0]['rendered'];
            } else {
                $data = array();

                foreach ($this->data[$field_code] as $d) {
                    if (is_null($d['rendered']) || $force) {
                        $d['rendered'] = $gdtt->prepare_cpt_field($this->meta_box['fields'][$field_code], $d['raw'], $atts);
                    }

                    $data[] = $d['rendered'];
                }

                return $data;
            }
        } else {
            return $single ? null : array();
        }
    }

    public function get_raw($field_code, $single = false) {
        if (isset($this->data[$field_code]) && !empty($this->data[$field_code])) {
            if ($single) {
                return $this->data[$field_code][0]['raw'];
            } else {
                $data = array();

                foreach ($this->data[$field_code] as $d) {
                    $data[] = $d['raw'];
                }

                return $data;
            }
        } else {
            return $single ? null : array();
        }
    }

    public function get_fields($return = 'codes') {
        switch ($return) {
            default:
            case 'codes':
                return array_keys($this->meta_box['fields']);
                break;
            case 'objects':
                return $this->meta_box['fields'];
                break;
        }
    }
}

?>