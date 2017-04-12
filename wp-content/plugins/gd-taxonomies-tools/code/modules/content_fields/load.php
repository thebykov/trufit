<?php

if (!defined('ABSPATH')) exit;

class gdCPTCore_Content_Fields_Load {
    function __construct() {
        add_action('gdcpt_admin_enqueue_meta', array(&$this, 'admin_enqueue'));

        add_action('gdcpt_custom_fields_init', array(&$this, 'custom_fields_init'));
        add_action('gdcpt_custom_fields_load_admin', array(&$this, 'custom_fields_admin_require'));

        add_filter('gdcpt_not_allowed_field_bbpress', array(&$this, 'bbpress_not_allowed'));
    }

    public function admin_enqueue($script_debug) {
        $js_url = $script_debug ? GDTAXTOOLS_URL.'code/modules/content_fields/js/src/cfields.js' : GDTAXTOOLS_URL.'code/modules/content_fields/js/cfields.js';

        wp_enqueue_script('gdcpt-cf-meta', $js_url, array('gdtt-meta'), null, true);
    }

    public function bbpress_not_allowed($fields) {
        return array_merge($fields, array('term', 'post', 'user'));
    }

    public function custom_fields_init() {
        if (gdtt_mod('content_fields', 'terms')) {
            require_once(GDTAXTOOLS_PATH.'code/modules/content_fields/fields/term.display.php');

            gdcpt_register_custom_field('term', 'gdCPT_Field_Admin_Content_Term', 'gdCPT_Field_Display_Content_Term', '__module:content_fields');
        }

        if (gdtt_mod('content_fields', 'posts')) {
            require_once(GDTAXTOOLS_PATH.'code/modules/content_fields/fields/post.display.php');

            gdcpt_register_custom_field('post', 'gdCPT_Field_Admin_Content_Post', 'gdCPT_Field_Display_Content_Post', '__module:content_fields');
        }

        if (gdtt_mod('content_fields', 'users')) {
            require_once(GDTAXTOOLS_PATH.'code/modules/content_fields/fields/user.display.php');

            gdcpt_register_custom_field('user', 'gdCPT_Field_Admin_Content_User', 'gdCPT_Field_Display_Content_User', '__module:content_fields');
        }
    }

    public function custom_fields_admin_require() {
        if (gdtt_mod('content_fields', 'terms')) {
            require_once(GDTAXTOOLS_PATH.'code/modules/content_fields/fields/term.admin.php');
        }

        if (gdtt_mod('content_fields', 'posts')) {
            require_once(GDTAXTOOLS_PATH.'code/modules/content_fields/fields/post.admin.php');
        }

        if (gdtt_mod('content_fields', 'users')) {
            require_once(GDTAXTOOLS_PATH.'code/modules/content_fields/fields/user.admin.php');
        }
    }
}

global $gdtt_content_fields_load;
$gdtt_content_fields_load = new gdCPTCore_Content_Fields_Load();

?>