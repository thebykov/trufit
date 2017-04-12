<?php

if (!defined('ABSPATH')) exit;

class gdttTermsList extends gdr2_Widget {
    var $widget_base = 'gdtttermslist';
    var $folder_name = 'gdtt-terms-list';
    var $defaults = array(
        'title' => 'Terms List',
        '_display' => 'all',
        '_cached' => 0,
        'post_types' => '',
        'taxonomy' => 'post_tag',
        'number' => 10,
        'orderby' => 'name',
        'order' => 'asc',
        'hide_empty' => 1,
        'mark_current' => 1,
        'exclude' => '',
        'display_render' => 'list',
        'display_hierarchy' => 1,
        'show_option_none' => 'Select Term',
        'display_count' => 1,
        'display_css' => ''
    );

    function __construct($id_base = false, $name = '', $widget_options = array(), $control_options = array()) {
        $this->widget_description = __("Display list with taxonomy terms.", "gd-taxonomies-tools");
        $this->widget_name = "gd CPT Tools: ".__("Terms List", "gd-taxonomies-tools");
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
        $instance['orderby'] = strip_tags(stripslashes($new_instance['orderby']));
        $instance['order'] = strip_tags(stripslashes($new_instance['order']));
        $instance['exclude'] = strip_tags(stripslashes($new_instance['exclude']));
        $instance['hide_empty'] = isset($new_instance['hide_empty']) ? 1 : 0;
        $instance['mark_current'] = isset($new_instance['mark_current']) ? 1 : 0;
        $instance['display_render'] = strip_tags(stripslashes($new_instance['display_render']));
        $instance['show_option_none'] = strip_tags(stripslashes($new_instance['show_option_none']));
        $instance['display_count'] = isset($new_instance['display_count']) ? 1 : 0;
        $instance['display_hierarchy'] = isset($new_instance['display_hierarchy']) ? 1 : 0;
        $instance['display_css'] = strip_tags(stripslashes($new_instance['display_css']));

        return $instance;
    }

    function results($instance) {
        $instance['echo'] = 0;
        $args = $instance;

        unset($args['title']);
        unset($args['display_count']);
        unset($args['display_render']);
        unset($args['display_css']);

        $args['show_count'] = $instance['display_count'];
        $args['hierarchical'] = $instance['display_hierarchy'] == 1;
        $args['selected'] = is_category() ? get_query_var('cat') : 0;
        $args['mark_current'] = !isset($instance['mark_current']) ? 1 : $instance['mark_current'];

        if ($instance['display_render'] == 'drop') {
            $args['name'] = 'gdtt-drop-'.$this->widget_id;
            return gdtt_dropdown_taxonomy_terms($args);
        } else {
            $args['link_class'] = 'gdtt-url';
            return gdtt_list_taxonomy_terms($args);
        }
    }

    function add_js_code($id, $js_var) {
        $x = '<script type="text/javascript">'.GDR2_EOL;
        $x.= '/* <![CDATA[ */'.GDR2_EOL;
        $x.= 'var '.$js_var.' = document.getElementById("'.$id.'");'.GDR2_EOL;
        $x.= 'function onChange_'.$js_var.'() {'.GDR2_EOL;
        $x.= 'if ( '.$js_var.'.options['.$js_var.'.selectedIndex].value != "" ) {'.GDR2_EOL;
        $x.= 'location.href = '.$js_var.'.options['.$js_var.'.selectedIndex].value;'.GDR2_EOL;
        $x.= '}'.GDR2_EOL;
        $x.= '}'.GDR2_EOL;
        $x.= $js_var.'.onchange = onChange_'.$js_var.';'.GDR2_EOL;
        $x.= '/* ]]> */'.GDR2_EOL;
        $x.= '</script>'.GDR2_EOL;
        return $x;
    }

    function render($results, $instance) {
        if (is_wp_error($results)) {
            return '';
        }

        $render = '';
        if ($instance['display_render'] == 'drop') {
            $render.= '<div class="gdtt-widget gdtt-terms-dropdown '.$instance["display_css"].'">';
            $render.= $results;
            $render.= '</div>';
            $render.= $this->add_js_code("gdtt-drop-".$this->widget_id, "gdtt_drop_".$this->widget_id);
        } else {
            $render.= '<div class="gdtt-widget gdtt-terms-list '.$instance["display_css"].'"><ul>';
            $render.= $results;
            $render.= '</ul></div>';
        }

        return $render;
    }
}

function gdtt_widget_terms_list($args = array()) {
    $gdtt = new gdttTermsList();
    $args = wp_parse_args((array)$args, $gdtt->defaults);
    $resl = $gdtt->results($args);

    return $gdtt->render($resl, $args);
}

?>