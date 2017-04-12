<?php

if (!defined('ABSPATH')) exit;

class gdCPTCore_bbPress_Load {
    public $embed_locations = array(
        'topic' => array(),
        'reply' => array()
    );

    function __construct() {
        if (gdtt_mod('bbpress', 'active') && function_exists('bbpress') && !is_admin()) {
            require_once(GDTAXTOOLS_PATH.'code/modules/bbpress/shared.php');
            require_once(GDTAXTOOLS_PATH.'code/modules/bbpress/front.php');
        }

        add_action('after_setup_theme', array(&$this, 'form_embed_locations'));
    }

    public function form_embed_locations() {
        $this->embed_locations['topic'] = apply_filters('gdcpt_bbpress_embed_locations_topic', array());
        $this->embed_locations['reply'] = apply_filters('gdcpt_bbpress_embed_locations_reply', array());
    }
}

global $gdtt_bbpress_load;
$gdtt_bbpress_load = new gdCPTCore_bbPress_Load();

?>