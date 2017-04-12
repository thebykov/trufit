<?php

if (!defined('ABSPATH')) exit;

class gdCPTModuleInfo_content_fields extends gdr2_Module {
    public $name = 'Content Custom Fields';
    public $subtitle = '';
    public $description = '';

    public $code = 'gd-taxonomies-tools-mod-content_fields';
    public $edition = 'pro';
    public $status = 'stable';
    public $date = '2013.10.12.';
    public $version = '1.2';
    public $build = 4230;
    public $revision = 1;

    public $author_name = 'Milan Petrovic';
    public $author_email = 'support@dev4press.com';
    public $author_web = 'http://www.dev4press.com/';

    function __construct() {
        $this->subtitle = __("expansion", "gd-taxonomies-tools");
        $this->description = __("Custom fields based on the WordPress content.", "gd-taxonomies-tools");
    }
}

class gdCPTModuleDefault_content_fields extends gdr2_Module_Settings {
    public $_product = 'gdCPTModuleInfo_content_fields';
    public $auto_load = true;
    public $_settings = array(
        'users' => true,
        'posts' => true,
        'terms' => true
    );
}

?>