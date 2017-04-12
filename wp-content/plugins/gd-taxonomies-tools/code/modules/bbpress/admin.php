<?php

if (!defined('ABSPATH')) exit;

require_once(GDTAXTOOLS_PATH.'code/modules/bbpress/shared.php');

class gdCPTCore_bbPress_Admin {
    function __construct() {
        if (gdtt_mod('bbpress', 'active') && function_exists('bbpress')) {
            add_action('save_post', array(&$this, 'bbpress_save_meta'), 10, 2);
            add_action('admin_menu', array(&$this, 'meta_boxes'));
        }
    }
    public function bbpress_save_meta($post_id, $post) {
        if (isset($_POST['gdtt_bbpress_meta'])) {
            $data = $_POST['gdtt_bbpress_meta'];

            wp_verify_nonce($data['nonce'], 'gdcptbbpress');

            $post_id = $post->ID;
            $_id = wp_is_post_revision($post);
            $post_id = $_id === false ? $post_id : $_id;

            unset($data['nonce']);
            update_post_meta($post_id, '_gdtt_bbpress_forum_settings', $data);
        }
    }

    function meta_boxes() {
        global $gdtt;

        if (!isset($_GET['post'])) return;

        add_meta_box('gdtt_mb_bbpress', __("Topic and Reply Forms", "gd-taxonomies-tools"), array(&$this, 'meta_box_forum'), 'forum', 'side', 'high');

        $post_id = (int)$_GET['post'];
        $post_type = get_post_type($post_id);

        if (isset($post_type) && !is_null($post_type) && !empty($post_type)) {
            if ($post_type == bbp_get_topic_post_type() || $post_type == bbp_get_reply_post_type()) {
                $forum_id = 0;

                if ($post_type == bbp_get_topic_post_type()) {
                    $forum_id = bbp_get_topic_forum_id($post_id);
                }
                if ($post_type == bbp_get_reply_post_type()) {
                    $forum_id = bbp_get_reply_forum_id($post_id);
                }

                $override = gdcpt_bbpress_mod_forum_settings($forum_id, true);
                $metabox = $override[$post_type];
                if ($metabox == '__default__') {
                    $metabox = gdtt_get('bbpress_metabox_'.$post_type);
                }

                if ($metabox != '__none__') {
                    if (isset($gdtt->m['boxes'][$metabox])) {
                        $meta = $gdtt->m['boxes'][$metabox];
                        $location = isset($meta['location']) && $meta['location'] != '' ? $meta['location'] : 'advanced';
                        $title = gdtt_get('metabox_clean_title') == 1 ? '' : 'GD CPT Tools: ';
                        $title.= __($meta['name']);

                        add_meta_box('gdtt_mb_'.$meta['code'], $title, array($this, 'meta_box_custom'), $post_type, $location, 'default', $meta);
                    }
                }
            }
        }
    }

    function meta_box_custom($post, $args) {
        global $gdtt, $gdtt_fields;

        $gdtt_fields->load_admin();

        $meta = $args['args'];
        $values = $gdtt->meta_box_current_values($post->ID, $meta['code']);

        gdtt_update_custom_fields(false);

        $_ID = 'gdtt_box_'.$meta['code'].'_';
        $_NAME = 'gdtt_box['.$meta['code'].'][';
        $_F = $gdtt->m['fields'];

        include(GDTAXTOOLS_PATH.'forms/metaboxes/custom_meta.php');
    }

    function meta_box_forum($post, $args) {
        global $gdtt;

        $meta = $gdtt->m['boxes'];
        $values = gdcpt_bbpress_mod_forum_settings($post->ID);

        include(GDTAXTOOLS_PATH.'code/modules/bbpress/forms/meta.php');
    }
}

global $gdtt_bbpress_admin;
$gdtt_bbpress_admin = new gdCPTCore_bbPress_Admin();

?>