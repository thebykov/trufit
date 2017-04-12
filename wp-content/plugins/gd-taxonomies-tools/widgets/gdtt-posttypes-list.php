<?php

if (!defined('ABSPATH')) exit;

class gdttPostTypesList extends gdr2_Widget {
    var $widget_base = 'gdttposttypeslist';
    var $folder_name = 'gdtt-posttypes-list';
    var $defaults = array(
        'title' => 'Post Types',
        '_display' => 'all',
        '_cached' => 0,
        'list' => array('post', 'page'),
        'counts' => 1,
        'mark_current' => 1,
        'display_css' => ''
    );

    function __construct($id_base = false, $name = '', $widget_options = array(), $control_options = array()) {
        $this->widget_description = __("Display list of post types.", "gd-taxonomies-tools");
        $this->widget_name = "gd CPT Tools: ".__("Post Types List", "gd-taxonomies-tools");
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
        $instance['counts'] = isset($new_instance['counts']) ? 1 : 0;
        $instance['mark_current'] = isset($new_instance['mark_current']) ? 1 : 0;
        $instance['list'] = (array)$new_instance['list'];
        $instance['display_css'] = strip_tags(stripslashes($new_instance['display_css']));

        return $instance;
    }

    function render($results, $instance) {
        $render = '<div class="gdtt-widget gdtt-posttypes-list '.$instance['display_css'].'"><ul>'.GDR2_EOL;
        $counts = array();
        $current_post_type = gdtt_get_current_post_type();

        if ($instance['counts'] == 1) {
            $counts = gdCPTDB::get_post_types_counts();
        }

        foreach ($instance['list'] as $post_type) {
            $class = 'post-type-'.$post_type;

            if (is_post_type_archive($post_type)) {
                $class.= ' archive';
            }

            if ($instance['mark_current'] == 1 && !is_null($current_post_type) && $current_post_type->name == $post_type) {
                $class.= ' current';
            }

            $pt = get_post_type_object($post_type);
            $url = get_post_type_archive_link($post_type);

            $render.= '<li class="'.$class.'">'.GDR2_EOL.GDR2_TAB.'<a title="'.sprintf(__("View posts for %s", "gd-taxonomies-tools"), $pt->label).'" class="gdtt-url" href="'.$url.'">'.$pt->label.'</a>';

            if ($instance['counts'] == 1) {
                $render.= ' <span class="gdtt-count">('.intval($counts[$post_type]).')</span>';
            }

            $render.= GDR2_EOL."</li>".GDR2_EOL;
        }
        $render.= '</ul></div>';
        return $render;
    }
}

function gdtt_widget_posttypes_list($args = array()) {
    $gdtt = new gdttPostTypesList();
    $args = wp_parse_args((array)$args, $gdtt->defaults);

    return $gdtt->render(null, $args);
}

?>