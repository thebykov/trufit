<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Fields {
    public $admin = array();
    public $classes = array();
    public $display = array();
    public $loaded = array();
    public $sources = array();

    private $list = array('plain' => array(), 'grouped' => array());

    function __construct() {
        add_action('plugins_loaded', array(&$this, 'init'), 3);
        add_action('plugins_loaded', array(&$this, 'load'), 4);

        add_action('gdcpt_customfield_editor_elements_block', array(&$this, 'editor_elements_block'));
        add_action('admin_head-gd-cpt-tools_page_gdtaxtools_metas', array(&$this, 'editor_css_block'));
        add_action('admin_footer-gd-cpt-tools_page_gdtaxtools_metas', array(&$this, 'editor_javascript_block'));

        add_action('admin_footer-post-new.php', array(&$this, 'postedit_javascript_block'));
        add_action('admin_footer-post.php', array(&$this, 'postedit_javascript_block'));
    }

    public function register($code, $class_admin, $class_display, $source = '__builtin') {
        $this->loaded[$code] = array('admin' => $class_admin, 'display' => $class_display, 'source' => $source);
    }

    public function init() {
        require_once(GDTAXTOOLS_PATH.'code/meta/display/standard.php');

        $this->register('text', 'gdCPT_Field_Admin_Text', 'gdCPT_Field_Display_Text');
        $this->register('number', 'gdCPT_Field_Admin_Number', 'gdCPT_Field_Display_Number');
        $this->register('boolean', 'gdCPT_Field_Admin_Boolean', 'gdCPT_Field_Display_Boolean');
        $this->register('html', 'gdCPT_Field_Admin_HTML', 'gdCPT_Field_Display_HTML');
        $this->register('listing', 'gdCPT_Field_Admin_Listing', 'gdCPT_Field_Display_Listing');
        $this->register('select', 'gdCPT_Field_Admin_Select', 'gdCPT_Field_Display_Select');
        $this->register('link', 'gdCPT_Field_Admin_Link', 'gdCPT_Field_Display_Link');
        $this->register('email', 'gdCPT_Field_Admin_Email', 'gdCPT_Field_Display_Email');

        do_action('gdcpt_custom_fields_init');

        $this->loaded = apply_filters('gdcpt_loaded_custom_fields', $this->loaded);
    }

    public function load() {
        do_action('gdcpt_custom_fields_load');

        foreach ($this->loaded as $code => $field) {
            if (class_exists($field['display'])) {
                $class_name = $field['display'];

                $this->display[$code] = new $class_name();
                $this->sources[$code] = $field['source'];
                $this->classes[$code] = $this->display[$code]->get_data_object();
            }
        }
    }

    public function load_admin() {
        require_once(GDTAXTOOLS_PATH.'code/meta/admin/standard.php');

        do_action('gdcpt_custom_fields_load_admin');

        foreach ($this->loaded as $code => $field) {
            if (!isset($this->admin[$code]) && class_exists($field['admin'])) {
                $class_name = $field['admin'];
                $this->admin[$code] = new $class_name();
            }
        }
    }

    public function editor_elements_block() {
        foreach ($this->admin as $code => $obj) {
            $obj->embed_html();
        }
    }

    public function editor_css_block() {
        echo '<style type="text/css">';

        foreach ($this->admin as $code => $obj) {
            $obj->embed_css();
        }

        echo '</style>';
    }

    public function editor_javascript_block() {
        echo '<script type="text/javascript">'.GDR2_EOL;
        echo 'jQuery(document).ready(function(){'.GDR2_EOL;

        foreach ($this->admin as $code => $obj) {
            $obj->embed_js();
        }

        echo '});'.GDR2_EOL;
        echo '</script>'.GDR2_EOL;;
    }

    public function postedit_javascript_block() {
        $this->load_admin();

        echo '<script type="text/javascript">'.GDR2_EOL;
        echo 'jQuery(document).ready(function(){'.GDR2_EOL;

        foreach ($this->admin as $code => $obj) {
            $obj->embed_js_postedit();
        }

        echo '});'.GDR2_EOL;
        echo '</script>'.GDR2_EOL;;
    }

    public function is_loaded($code) {
        return isset($this->display[$code]);
    }

    public function is_repeatable($code) {
        if (!isset($this->admin[$code])) {
            return false;
        } else {
            return $this->admin[$code]->is_repeatable();
        }
    }

    public function is_rewritable($code) {
        if (!isset($this->admin[$code])) {
            return false;
        } else {
            return $this->admin[$code]->is_rewritable();
        }
    }

    public function get_shortcode($code) {
        if (!isset($this->admin[$code])) {
            return false;
        } else {
            return $this->admin[$code]->_shortcode;
        }
    }

    public function get_attributes($field) {
        if (!isset($this->display[$field['type']])) {
            return array();
        } else {
            return $this->display[$field['type']]->get_attributes($field);
        }
    }

    public function get_field_shortcode_elements($field_type) {
        if (!isset($this->admin[$field_type])) {
            return array();
        } else {
            $shortcode = array('standard' => array(), 'repeater' => array(), 'advanced' => array());

            $shortcode['standard']['tag'] = array('attr' => '', 'type' => 'input', 'default' => 'div', 'label' => __("Tag", "gd-taxonomies-tools"), 'description' => __("Value is displayed within a tag. If set to empty, value will be displayed on its own.", "gd-taxonomies-tools"));
            $shortcode['standard']['id'] = array('attr' => '', 'type' => 'input', 'default' => '', 'label' => __("ID", "gd-taxonomies-tools"), 'description' => __("ID for the element, only if the tag is defined.", "gd-taxonomies-tools"));
            $shortcode['standard']['class'] = array('attr' => '', 'type' => 'input', 'default' => '', 'label' => __("Class", "gd-taxonomies-tools"), 'description' => __("CSS class for the element, only if the tag is defined.", "gd-taxonomies-tools"));
            $shortcode['standard']['style'] = array('attr' => '', 'type' => 'input', 'default' => '', 'label' => __("Style", "gd-taxonomies-tools"), 'description' => __("CSS style for the element, only if the tag is defined.", "gd-taxonomies-tools"));
            $shortcode['standard']['label'] = array('attr' => '0', 'type' => 'dropdown', 'default' => '0', 'label' => __("Label", "gd-taxonomies-tools"), 'description' => __("Display name for the field with value.", "gd-taxonomies-tools"), 'values' => array('0' => __("No", "gd-taxonomies-tools"), '1' => __("Yes", "gd-taxonomies-tools")));
            $shortcode['standard']['post'] = array('attr' => '0', 'type' => 'number', 'default' => '0', 'label' => __("Post", "gd-taxonomies-tools"), 'description' => __("Post to get value for. Leave 0 for current post.", "gd-taxonomies-tools"));

            if ($this->admin[$field_type]->is_repeatable()) {
                $shortcode['repeater']['multi'] = array('attr' => 'first', 'type' => 'dropdown', 'default' => 'first', 'label' => __("What to show", "gd-taxonomies-tools"), 'description' => __("Value is displayed within a tag. If set to empty, value will be displayed on its own.", "gd-taxonomies-tools"), 'values' => array('all' => __("All values", "gd-taxonomies-tools"), 'first' => __("Only first value", "gd-taxonomies-tools"), 'index' => __("Select by index", "gd-taxonomies-tools")));
                $shortcode['repeater']['midx'] = array('attr' => '0', 'type' => 'number', 'default' => '0', 'label' => __("Value Index", "gd-taxonomies-tools"), 'description' => __("Value is displayed within a tag. If set to empty, value will be displayed on its own.", "gd-taxonomies-tools"));
                $shortcode['repeater']['msep'] = array('attr' => ', ', 'type' => 'input', 'default' => ', ', 'label' => __("Separator", "gd-taxonomies-tools"), 'description' => __("Value is displayed within a tag. If set to empty, value will be displayed on its own.", "gd-taxonomies-tools"));
            } else {
                unset($shortcode['repeater']);
            }

            $shortcode['advanced'] = $this->admin[$field_type]->shortcode_attributes();

            return $shortcode;
        }
    }

    public function get_field_display($value, $field) {
        if (!isset($this->display[$field['type']])) {
            return $value;
        } else {
            return $this->display[$field['type']]->get_field_display($value, $field);
        }
    }

    public function get_field_default($field) {
        if (!isset($this->display[$field['type']])) {
            return '';
        } else {
            return $this->display[$field['type']]->get_defaults($field);
        }
    }

    public function get_field_values($field, $functions_list) {
        if (!isset($this->admin[$field['type']])) {
            return '/';
        } else {
            return $this->admin[$field['type']]->get_values($field, $functions_list);
        }
    }
    
    public function get_field_limit($field) {
        return $field['limit'] == 0 ? __("none", "gd-taxonomies-tools") : $field['limit'];
    }

    public function get_field_type($field) {
        if (isset($this->admin[$field['type']])) {
            return $this->admin[$field['type']]->get_type($field);
        } else {
            return '';
        }
    }

    public function get_group_boxes($group) {
        $meta_box = gdtt_get_meta_box_group($group);

        if (!is_null($meta_box)) {
            global $gdtt;

            $list = array();

            foreach ($meta_box['boxes'] as $f) {
                if (isset($gdtt->m['boxes'][$f])) {
                    $box = (array)$gdtt->m['boxes'][$f];
                    $list[] = $box['name'];
                }
            }

            return join('<br/>', $list);
        } else {
            return '';
        }
    }
    
    public function get_box_fields($box) {
        $meta_box = gdtt_get_meta_box($box);

        if (!is_null($meta_box)) {
            global $gdtt;

            $list = array();

            foreach ($meta_box['fields'] as $f) {
                if (isset($gdtt->m['fields'][$f])) {
                    $field = (array)$gdtt->m['fields'][$f];
                    $list[] = $field['name'].' (<strong>'.$f.'</strong>: '.$this->get_field_type($field).')';
                }
            }

            return join('<br/>', $list);
        } else {
            return '';
        }
    }

    public function get_fields_list($type = 'grouped') {
        if (empty($this->list[$type])) {
            if ($type == 'plain') {
                foreach ($this->admin as $code => $value) {
                    $this->list[$type][$code] = $value->get_label();
                }
            } else {
                $list = array();

                foreach ($this->admin as $code => $value) {
                    $list[$value->get_class()][$code] = $value->get_label();
                }

                foreach ($list as $class => $fields) {
                    $this->list[$type][] = array('title' => $class, 'values' => $fields);
                }
            }
        }

        return apply_filters('gdcpt_custom_fields_list', $this->list[$type], $type);
    }

    public function meta_render($value, $field, $id, $name) {
        if (!isset($this->admin[$field['type']])) {
            return __("Custom Field is not registered", "gd-taxonomies-tools");
        } else {
            $value = $this->admin[$field['type']]->update_value($value, $field);
            return $this->admin[$field['type']]->render($value, $field, $id, $name);
        }
    }

    public function meta_value($field, $values, $atts) {
        $defaults = array_merge(array('format' => '', 'tag' => 'div', 'label' => 'no', 'class' => '', 'style' => '', 'id' => '', 'multi' => 'first', 'midx' => 0, 'msep' => ', '), $this->get_attributes($field));
        $atts = shortcode_atts($defaults, $atts);
        $values = (array)$values;

        $content = array();

        if ($field['type'] == 'select' && ($field['selection'] == 'checkbox' || $field['selection'] == 'multi')) {
            $atts['multi'] = 'list';
        }

        switch ($atts['multi']) {
            default:
            case 'first':
            case 'list':
                $content[] = $this->display[$field['type']]->render($values[0], $field, $atts);
                break;
            case 'all':
                foreach ($values as $value) {
                    $content[] = $this->display[$field['type']]->render($value, $field, $atts);
                }
                break;
            case 'index':
                $idx = isset($values[$atts['midx']]) ? $atts['midx'] : 0;
                $content[] = $this->display[$field['type']]->render($values[$idx], $field, $atts);
                break;
        }

        if (empty($content)) {
            $content = '/';
        } else {
            $content = join($atts['msep'], $content);
        }

        if ($atts['tag'] != '') {
            $actual = $content;
            $content = '<'.$atts['tag'].($atts['class'] != '' ? ' class="'.$atts['class'].'"' : '').($atts['style'] != "" ? ' style="'.$atts["style"].'"' : '').'>';

            if ($atts['label'] == '1' || $atts['label'] == 'true' || $atts['label'] === true) {
                $content.= '<label>'.$field['name'].':</label><div>';
            }

            $content.= $actual;

            if ($atts['label'] == '1' || $atts['label'] == 'true' || $atts['label'] === true) {
                $content.= '</div>';
            }

            $content.= '</'.$atts['tag'].'>';
        }

        $content = apply_filters('gdcpt_cpt_field_content', $content, $values, $field['name'], $atts, $field);

        return $content;
    }

    public function meta_save($post_id, $post) {
        global $gdtt;

        $this->load_admin();

        gdtt_update_custom_fields(false);

        $_id = wp_is_post_revision($post);
        $post_id = $_id === false ? $post->ID : $_id;

        do_action('gdcpt_save_post_metaboxes_start', $post_id, $post);

        $boxes = $_POST['gdtt_box'];
        foreach ($boxes as $meta_code => $data) {
            wp_verify_nonce($data['__nonce__'][0], 'gdcpttools');

            unset($data['__nonce__']);

            $current = $gdtt->meta_box_current_values($post_id, $meta_code);

            $meta = gdtt_get_meta_box($meta_code);

            do_action('gdcpt_save_post_metabox_start_'.$meta_code, $post_id, $meta);

            foreach ($meta['fields'] as $f) {
                $field = $gdtt->m['fields'][$f];
                $active = array();

                if ($field['type'] != 'rewrite' || ($field['type'] == 'rewrite' && $field['rewrite'] == '__none__')) {
                    if (isset($data[$f]) && !empty($data[$f])) {
                        foreach ($data[$f] as $_key => $value) {
                            $new = $this->admin[$field['type']]->clean($value, $field);
                            $set = $this->admin[$field['type']]->check($new, $field);

                            $new = apply_filters('gdcpt_value_custom_field_'.$f, $new, $value, $field, $post_id, $meta);
                            $new = apply_filters('gdcpt_value_custom_field_type_'.$field['type'], $new, $value, $field, $post_id, $meta);

                            if ($set) {
                                $active[] = $new;
                            }
                        }
                    }

                    $this->update_meta_value($post_id, $f, $current[$f], $active);

                    do_action('gdcpt_saved_custom_field', $f, $field, $post_id, $meta, $current[$f], $active);
                    do_action('gdcpt_saved_custom_field_'.$f, $field, $post_id, $meta, $current[$f], $active);
                    do_action('gdcpt_saved_custom_field_type_'.$field['type'], $field, $meta);
                }
            }

            $this->update_rewrite_fields($post_id, $meta);

            do_action('gdcpt_save_post_metabox_end_'.$meta_code, $meta);
        }

        do_action('gdcpt_save_post_metaboxes_end', $post_id, $post);
    }

    public function update_rewrite_fields($post_id, $meta) {
        global $gdtt;

        foreach ($meta['fields'] as $f) {
            $field = $gdtt->m['fields'][$f];

            if ($field['type'] == 'rewrite' && $field['rewrite'] != '__none__') {
                $val = get_post_meta($post_id, $field['rewrite'], true);

                if ($val != '') {
                    $new = gdr2_sanitize_custom($val);

                    update_post_meta($post_id, $f, $new);
                } else {
                    delete_post_meta($post_id, $f);
                }
            }
        }
    }

    public function delete_custom_field_data($field) {
        global $wpdb;

        $sql = "delete from ".$wpdb->postmeta." where meta_key = '".$field."'";

        $wpdb->query($sql);
    }

    public function get_custom_fields_values($fields, $post_id) {
        global $wpdb;

        $fields = array_keys($fields);
        $values = array();
        $sql = "select meta_key, meta_value from ".$wpdb->postmeta." where meta_key in ('".join("', '", $fields)."') and post_id = .$post_id";
        $res = $wpdb->get_results($sql);

        foreach ($fields as $key) {
            $values[$key] = array();
        }

        foreach ($res as $row) {
            $values[$row->meta_key][] = maybe_unserialize($row->meta_value);
        }

        return $values;
    }

    public function count_custom_field_posts($field) {
        global $wpdb;

        $sql = "select count(*) from ".$wpdb->postmeta." where meta_key = '".$field."'";

        return intval($wpdb->get_var($sql));
    }

    public function count_custom_fields_posts($fields) {
        global $wpdb;

        $fields = array_keys($fields);
        $values = array();
        $sql = "select meta_key, count(*) as counter from ".$wpdb->postmeta." where meta_key in ('".join("', '", $fields)."') group by meta_key";
        $res = $wpdb->get_results($sql);

        foreach ($fields as $key) {
            $values[$key] = 0;
        }

        foreach ($res as $row) {
            $values[$row->meta_key] = intval($row->counter);
        }

        return $values;
    }

    private function update_meta_value($post_id, $field, $current, $active) {
        $to_add = $to_del = array();

        foreach ($current as $c) {
            if ($c == '') {
                $to_del[] = $c;
            } else {
                $cv = is_array($c) ? serialize($c) : $c;

                $found = false;
                foreach ($active as $a) {
                    $av = is_array($a) ? serialize($a) : $a;

                    if ($av == $cv) {
                        $found = true;
                    }
                }

                if (!$found) {
                    $to_del[] = $c;
                }
            }
        }

        foreach ($active as $a) {
            $av = is_array($a) ? serialize($a) : $a;

            $found = false;
            foreach ($current as $c) {
                $cv = is_array($c) ? serialize($c) : $c;

                if ($av == $cv) {
                    $found = true;
                }
            }

            if (!$found) {
                $to_add[] = $a;
            }
        }

        foreach ($to_del as $v) {
            delete_post_meta($post_id, $field, $v);
        }

        foreach ($to_add as $v) {
            add_post_meta($post_id, $field, $v);
        }
    }
}

?>