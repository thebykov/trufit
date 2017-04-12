<?php

if (!defined('ABSPATH')) exit;

class gdptDebug_Panel_gdCPTTools extends gdptDebug_Panel {
    public $code = "gdcpttools";
    public $columns = "two";

    function __construct() {
        $this->title = __("GD CPT Tools", "gd-taxonomies-tools");
        $this->description = __("GD CPT Tools Plugin.", "gd-taxonomies-tools");
    }

    public function display_column_one() {
        global $gdtt;

        $this->t(__("Special Features", "gd-taxonomies-tools"));
        $this->list_array($gdtt->sf);

        $this->t(__("Settings", "gd-taxonomies-tools"));
        $this->list_array($gdtt->o);
    }

    public function display_column_two() {
        global $gdtt;

        $this->t(__("Custom Post Types", "gd-taxonomies-tools"));
        $this->list_array($gdtt->p);

        $this->t(__("Custom Taxonomies", "gd-taxonomies-tools"));
        $this->list_array($gdtt->t);

        $this->t(__("Meta Boxes", "gd-taxonomies-tools"));
        $this->list_array($gdtt->m);

        $this->t(__("Override Post Types", "gd-taxonomies-tools"));
        $this->list_array($gdtt->nn_p);

        $this->t(__("Override Taxonomies", "gd-taxonomies-tools"));
        $this->list_array($gdtt->nn_t);
    }
}

?>