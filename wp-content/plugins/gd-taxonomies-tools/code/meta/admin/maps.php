<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Field_Admin_GoogleMap extends gdCPT_Core_Field_Admin {
    public $_name = 'google_map';
    public $_shortcode = 'cpt_gmap';
    public $_repeater = false;

    function __construct() {
        parent::__construct();

        $this->_class = __("Maps", "gd-taxonomies-tools");
        $this->_label = __("Google Map", "gd-taxonomies-tools");
        $this->_description = __("Google map field", "gd-taxonomies-tools");
    }

    public function clean($value, $field) {
        $new = new gdCPT_GoogleMap($value);
        return $new->to_array();
    }

    public function check($value, $field) {
        return is_array($value) && !empty($value);
    }

    public function shortcode_attributes() {
        $zooms = array();
        for ($i = 1; $i < 23; $i++) {
            $zooms[$i] = $i;
        }

        return array(
            'static' => array('attr' => '0', 'type' => 'dropdown', 'default' => '0', 'label' => __("Static Image", "gd-taxonomies-tools"), 'description' => __("How to represent the image URL.", "gd-taxonomies-tools"), 'values' => array('0' => __("No", "gd-taxonomies-tools"), '1' => __("Yes", "gd-taxonomies-tools"))),
            'streetview' => array('attr' => '0', 'type' => 'dropdown', 'default' => '0', 'label' => __("Streetview", "gd-taxonomies-tools"), 'description' => __("How to represent the image URL.", "gd-taxonomies-tools"), 'values' => array('0' => __("No", "gd-taxonomies-tools"), '1' => __("Yes", "gd-taxonomies-tools"))),
            'maptype' => array('attr' => 'ROADMAP', 'type' => 'dropdown', 'default' => 'ROADMAP', 'label' => __("Map Type", "gd-taxonomies-tools"), 'description' => __("How to represent the image URL.", "gd-taxonomies-tools"), 'values' => array('TERRAIN' => __("Terrain", "gd-taxonomies-tools"), 'ROADMAP' => __("Roadmap", "gd-taxonomies-tools"), 'SATELLITE' => __("Satellite", "gd-taxonomies-tools"), 'HYBRID' => __("Hybrid", "gd-taxonomies-tools"))),
            'zoom' => array('attr' => '10', 'type' => 'dropdown', 'default' => '10', 'label' => __("Zoom Level", "gd-taxonomies-tools"), 'description' => __("How to represent the image URL.", "gd-taxonomies-tools"), 'values' => $zooms),
            'width' => array('attr' => '400', 'type' => 'number', 'default' => '400', 'label' => __("Width", "gd-taxonomies-tools"), 'description' => __("How to represent the image URL.", "gd-taxonomies-tools")),
            'height' => array('attr' => '250', 'type' => 'number', 'default' => '250', 'label' => __("Width", "gd-taxonomies-tools"), 'description' => __("How to represent the image URL.", "gd-taxonomies-tools")),
        );
    }

    public function render($value, $field, $id, $name) {
        $value = new gdCPT_GoogleMap($value);
        include(GDTAXTOOLS_PATH.'forms/shared/meta.gmap.php');
    }

    public function get_default($field) {
        return ' ';
    }
}

?>