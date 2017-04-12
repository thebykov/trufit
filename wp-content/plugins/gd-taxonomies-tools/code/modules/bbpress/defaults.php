<?php

if (!defined('ABSPATH')) exit;

class gdCPTModuleInfo_bbpress extends gdr2_Module {
    public $name = 'bbPress Forums';
    public $subtitle = '';
    public $description = '';

    public $code = 'gd-taxonomies-tools-mod-bbpress';
    public $edition = 'pro';
    public $status = 'stable';
    public $date = '2013.10.12.';
    public $version = '1.5';
    public $build = 4230;
    public $revision = 1;

    public $author_name = 'Milan Petrovic';
    public $author_email = 'support@dev4press.com';
    public $author_web = 'http://www.dev4press.com/';

    function __construct() {
        $this->subtitle = __("integration", "gd-taxonomies-tools");
        $this->description = __("Integrate plugin specific menu into WordPress toolbar.", "gd-taxonomies-tools");
    }
}

class gdCPTModuleDefault_bbpress extends gdr2_Module_Settings {
    public $_product = 'gdCPTModuleInfo_bbpress';
    public $_settings = array(
        'active' => false,
        'metabox_topic' => '__none__',
        'metabox_reply' => '__none__',
        'metabox_location_topic' => 'bbp_theme_after_topic_form_tags',
        'metabox_location_reply' => 'bbp_theme_after_reply_form_tags',
        'metabox_fieldset' => true,
        'embed_active' => true,
        'embed_anyone' => true,
        'embed_author' => true,
        'embed_roles' => array('administrator'),
        'embed_js' => true,
        'embed_css' => true
    );

    public function get_defaults($scope = 'site') {
        $settings = parent::get_defaults($scope);

        global $gdtt;

        $keys = array_keys($settings);
        foreach ($keys as $name) {
            if (!in_array($name, $this->system_keys)) {
                if (isset($gdtt->o['bbpress_'.$name])) {
                    $settings[$name] = $gdtt->o['bbpress_'.$name];
                }
            }
        }

        return $settings;
    }
}

?>