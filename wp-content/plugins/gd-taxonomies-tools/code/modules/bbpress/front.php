<?php

if (!defined('ABSPATH')) exit;

class gdCPTCore_bbPress {
    var $meta_values = array();

    function __construct() {
        add_action('init', array(&$this, 'custom_embed_locations'));

        add_action('bbp_theme_before_topic_form_title', array(&$this, 'bbpress_reply_form'));
        add_action('bbp_theme_after_reply_form_content', array(&$this, 'bbpress_reply_form'));
        add_action('bbp_theme_after_reply_form_tags', array(&$this, 'bbpress_reply_form'));
        add_action('bbp_theme_before_reply_form_submit_wrapper', array(&$this, 'bbpress_reply_form'));

        add_action('bbp_theme_before_topic_form_title', array(&$this, 'bbpress_topic_form'));
        add_action('bbp_theme_after_topic_form_title', array(&$this, 'bbpress_topic_form'));
        add_action('bbp_theme_after_topic_form_content', array(&$this, 'bbpress_topic_form'));
        add_action('bbp_theme_after_topic_form_tags', array(&$this, 'bbpress_topic_form'));
        add_action('bbp_theme_before_topic_form_submit_wrapper', array(&$this, 'bbpress_topic_form'));

        add_action('bbp_edit_reply_pre_extras', array(&$this, 'bbpress_reply_save'));
        add_action('bbp_edit_topic_pre_extras', array(&$this, 'bbpress_topic_save'));
        add_action('bbp_new_reply_pre_extras', array(&$this, 'bbpress_reply_save'));
        add_action('bbp_new_topic_pre_extras', array(&$this, 'bbpress_topic_save'));

        add_action('bbp_edit_reply', array(&$this, 'bbpress_reply_insert'));
        add_action('bbp_edit_topic', array(&$this, 'bbpress_topic_insert'));
        add_action('bbp_new_reply', array(&$this, 'bbpress_reply_insert'));
        add_action('bbp_new_topic', array(&$this, 'bbpress_topic_insert'));

        if (gdtt_mod('bbpress', 'embed_active')) {
            add_action('bbp_get_reply_content', array(&$this, 'bbpress_reply_embed'), 10, 2);
            add_action('bbp_get_topic_content', array(&$this, 'bbpress_topic_embed'), 10, 2);
        }

        if (!is_admin()) {
            add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
        }
    }

    private function _bbpress_form($code = 'topic', $id = 0) {
        global $gdtt, $gdtt_fields, $gdr2_units;

        $forum_id = bbp_get_forum_id();
        $filter = current_filter();

        $gdtt_fields->load_admin();

        $override = gdcpt_bbpress_mod_forum_settings($forum_id, true);
        $metabox = $override[$code];
        $location = $override['location_'.$code];

        if ($metabox == '__default__' || $metabox == '__parent__') {
            $metabox = gdtt_mod('bbpress', 'metabox_'.$code);
        }
        if ($location == '__default__' || $location == '__parent__') {
            $location = gdtt_mod('bbpress', 'metabox_location_'.$code);
        }

        if ($metabox != '__none__' && $filter == $location) {
            if (isset($gdtt->m['boxes'][$metabox])) {
                gdtt_update_custom_fields(false);

                $meta = $gdtt->m['boxes'][$metabox];

                $_ID = 'gdtt_box_'.$meta['code'].'_';
                $_NAME = 'gdtt_box['.$meta['code'].'][';
                $_F = $gdtt->m['fields'];

                $fromdb_values = $id > 0 ? $gdtt->meta_box_current_values($id, $meta['code']) : array();
                $posted_values = isset($_POST['gdtt_box'][$meta['code']]) ? $_POST['gdtt_box'][$meta['code']] : $fromdb_values;

                include(GDTAXTOOLS_PATH.'code/modules/bbpress/forms/embed.php');
            }
        }
    }

    private function _bbpress_insert($id, $code = 'topic') {
        if (is_array($this->meta_values) && !empty($this->meta_values)) {
            foreach ($this->meta_values as $key => $value) {
                add_post_meta($id, $key, $value);
            }
        }
    }

    private function _bbpress_save($code = 'topic') {
        global $gdtt, $gdtt_fields;

        $forum_id = bbp_get_forum_id();

        $override = gdcpt_bbpress_mod_forum_settings($forum_id, true);
        $metabox = $override[$code];
        if ($metabox == '__default__') {
            $metabox = gdtt_mod('bbpress', 'metabox_'.$code);
        }

        if ($metabox != '__none__') {
            if (isset($gdtt->m['boxes'][$metabox]) && isset($_POST['gdtt_box'][$metabox])) {
                $gdtt_fields->load_admin();

                $meta = $gdtt->m['boxes'][$metabox];
                $data = $_POST['gdtt_box'][$meta['code']];
                $_ID = 'gdtt_box_'.$meta['code'].'_';

                $done = $values = array();
                foreach ($meta['fields'] as $f) {
                    $field = $gdtt->m['fields'][$f];
                    if (!isset($done[$f])) { $done[$f] = 0; } else { $done[$f]++; }

                    $value = $data[$f][$done[$f]];

                    $new = $gdtt_fields->admin[$field['type']]->clean($value, $field);
                    $ok = $gdtt_fields->admin[$field['type']]->check($new, $field);

                    $this->meta_values[$f] = $new;

                    if ($field['required'] && !$ok) {
                        bbp_add_error($_ID.$field['code'].'_'.$done[$f], '<strong>'.__("ERROR", "gd-taxonomies-tools").'</strong>: '.$field['name']." ".__("cannot be empty.", "gd-taxonomies-tools"));
                    }
                }

                if (!bbp_has_errors()) {
                    $this->meta_values = array();
                }
            }
        }
    }

    private function _bbpress_embed($content, $id, $code = 'topic') {
        global $gdtt, $user_ID;
        $forum_id = bbp_get_forum_id();

        $code = bbp_is_topic($id) ? 'topic' : 'reply';
        $show = gdtt_mod('bbpress', 'embed_anyone');

        if (!$show) {
            $post = get_post($id);
            $show = gdtt_mod('bbpress', 'embed_author') && $post->post_author == $user_ID;

            if (!$show && is_user_logged_in()) {
                global $current_user;

                if (is_array($current_user->roles)) {
                    $value = (array)gdtt_mod('bbpress', 'embed_author');
                    $matched = array_intersect($current_user->roles, $value);
                    $show = !empty($matched);
                }
            }
        }

        if ($show) {
            $override = gdcpt_bbpress_mod_forum_settings($forum_id, true);
            $metabox = $override[$code];

            if ($metabox == '__default__') {
                $metabox = gdtt_mod('bbpress', 'metabox_'.$code);
            }

            if ($metabox != '__none__') {
                if (isset($gdtt->m['boxes'][$metabox])) {
                    $values = $gdtt->meta_box_current_values($id, $metabox);

                    $content.= '<div class="bbp-gdtt-fields">';
                    $content.= apply_filters('gdcpt_bbpress_embed_before', '', $id, $code);

                    foreach ($values as $key => $value) {
                        $atts = array('label' => '1', 'class' => 'gdtt-field gdtt-field-'.$key);
                        $field = isset($gdtt->m['fields'][$key]) ? $gdtt->m['fields'][$key] : array('type' => 'text');

                        $content.= $gdtt->prepare_cpt_field($field, $value, $atts);
                    }

                    $content.= apply_filters('gdcpt_bbpress_embed_before', '', $id, $code);
                    $content.= '</div>';
                }
            }
        }

        return $content;
    }

    public function custom_embed_locations() {
        global $gdtt_bbpress_load;

        $topic = array_keys($gdtt_bbpress_load->embed_locations['topic']);
        foreach ($topic as $t) {
            add_action($t, array(&$this, 'bbpress_topic_form'));
        }

        $reply = array_keys($gdtt_bbpress_load->embed_locations['reply']);
        foreach ($reply as $r) {
            add_action($r, array(&$this, 'bbpress_reply_form'));
        }
    }

    public function enqueue_scripts() {
        if (gdtt_mod('bbpress', 'embed_js')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script('gdcpt-jquery', GDTAXTOOLS_URL.'js/bbpress.js', array('jquery'), false, true);
        }

        if (gdtt_mod('bbpress', 'embed_css')) {
            wp_enqueue_style('gdcpt-css', GDTAXTOOLS_URL.'css/bbpress.css');
        }
    }

    public function bbpress_reply_insert($reply_id) {
        $this->_bbpress_insert($reply_id, 'reply');
    }

    public function bbpress_topic_insert($topic_id) {
        $this->_bbpress_insert($topic_id, 'topic');
    }

    public function bbpress_reply_save() {
        $this->_bbpress_save('reply');
    }

    public function bbpress_topic_save() {
        $this->_bbpress_save('topic');
    }

    public function bbpress_reply_form() {
        $reply_id = bbp_is_reply_edit() ? bbp_get_reply_id() : 0;
        $this->_bbpress_form('reply', $reply_id);
    }

    public function bbpress_topic_form() {
        $topic_id = bbp_is_topic_edit() ? bbp_get_topic_id() : 0;
        $this->_bbpress_form('topic', $topic_id);
    }

    public function bbpress_reply_embed($content, $id) {
        return $this->_bbpress_embed($content, $id, 'reply');
    }

    public function bbpress_topic_embed($content, $id) {
        return $this->_bbpress_embed($content, $id, 'topic');
    }
}

global $gdtt_bbpress;
$gdtt_bbpress = new gdCPTCore_bbPress();

?>