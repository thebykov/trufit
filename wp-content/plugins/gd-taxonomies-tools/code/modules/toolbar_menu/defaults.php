<?php

if (!defined('ABSPATH')) exit;

class gdCPTModuleInfo_toolbar_menu extends gdr2_Module {
    public $name = 'Toolbar Menu';
    public $subtitle = '';
    public $description = '';

    public $code = 'gd-taxonomies-tools-mod-toolbar-menu';
    public $edition = 'pro';
    public $status = 'stable';
    public $date = '2012.12.11.';
    public $version = '1.2';
    public $build = 4000;
    public $revision = 1;

    public $author_name = 'Milan Petrovic';
    public $author_email = 'support@dev4press.com';
    public $author_web = 'http://www.dev4press.com/';

    function __construct() {
        $this->subtitle = __("integration", "gd-taxonomies-tools");
        $this->description = __("Integrate plugin specific menu into WordPress toolbar.", "gd-taxonomies-tools");
    }
}

class gdCPTModuleDefault_toolbar_menu extends gdr2_Module_Settings {
    public $_product = 'gdCPTModuleInfo_toolbar_menu';
    public $_settings = array(
        'active' => true,
        'icon' => true,
        'create_new' => true
    );
}

?>