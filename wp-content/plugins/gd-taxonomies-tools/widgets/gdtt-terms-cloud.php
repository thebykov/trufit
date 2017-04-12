<?php

if (!defined('ABSPATH')) exit;

class gdttTermsCloud extends gdr2_Widget {
    var $widget_base = 'gdtttermscloud';
    var $folder_name = 'gdtt-terms-cloud';
    var $defaults = array(
        'title' => 'Terms Cloud',
        '_display' => 'all',
        '_cached' => 0,
        'post_types' => '',
        'taxonomy' => 'post_tag',
        'number' => 45,
        'unit' => 'pt',
        'smallest' => 8,
        'largest' => 22,
        'orderby' => 'name',
        'order' => 'asc',
        'hide_empty' => 1,
        'mark_current' => 1,
        'exclude' => '',
        'display_css' => ''
    );

    function __construct($id_base = false, $name = '', $widget_options = array(), $control_options = array()) {
        $this->widget_description = __("Display cloud with taxonomy terms.", "gd-taxonomies-tools");
        $this->widget_name = "gd CPT Tools: ".__("Terms Cloud", "gd-taxonomies-tools");
        parent::__construct($this->widget_base, $this->widget_name, array(), array("width" => 400));
    }

    function form($instance) {
        $instance = wp_parse_args((array)$instance, $this->defaults);

        include(GDTAXTOOLS_PATH.'widgets/shared/form.php');
        include(GDTAXTOOLS_PATH.'widgets/'.$this->folder_name.'/form.php');
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
        $instance['_display'] = strip_tags(stripslashes($new_instance['_display']));
        $instance['_cached'] = intval(strip_tags(stripslashes($new_instance['_cached'])));
        $instance['post_types'] = strip_tags(stripslashes($new_instance['post_types']));
        $instance['taxonomy'] = strip_tags(stripslashes($new_instance['taxonomy']));
        $instance['number'] = intval(strip_tags(stripslashes($new_instance['number'])));
        $instance['smallest'] = intval(strip_tags(stripslashes($new_instance['smallest'])));
        $instance['largest'] = intval(strip_tags(stripslashes($new_instance['largest'])));
        $instance['orderby'] = strip_tags(stripslashes($new_instance['orderby']));
        $instance['order'] = strip_tags(stripslashes($new_instance['order']));
        $instance['exclude'] = strip_tags(stripslashes($new_instance['exclude']));
        $instance['hide_empty'] = isset($new_instance['hide_empty']) ? 1 : 0;
        $instance['mark_current'] = isset($new_instance['mark_current']) ? 1 : 0;
        $instance['display_css'] = strip_tags(stripslashes($new_instance['display_css']));

        return $instance;
    }

    function results($instance) {
        $instance['echo'] = false;
        $args = $instance;
        unset($args['title']);
        unset($args['display_css']);
        $args['mark_current'] = !isset($instance['mark_current']) ? 1 : $instance['mark_current'];
        return gdtt_wp_tag_cloud($args);
    }

    function render($results, $instance) {
        $render = '<div class="gdtt-widget gdtt-terms-cloud '.$instance['display_css'].'">';
        $render.= $results;
        $render.= '</div>';
        return $render;
    }
}

function gdtt_widget_terms_cloud($args = array()) {
    $gdtt = new gdttTermsCloud();
    $args = wp_parse_args((array)$args, $gdtt->defaults);
    $resl = $gdtt->results($args);

    return $gdtt->render($resl, $args);
}

?>