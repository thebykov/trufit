<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Core {
    function __construct() {
        $this->_actions_wpany();
    }

    // WordPress ANY //
    private function _actions_wpany() {
        add_filter('wp_setup_nav_menu_item', array(&$this, 'setup_nav_menu_item'));
        add_filter('wp_nav_menu_objects', array(&$this, 'nav_menu_objects'));

        add_action('wp', array(&$this, 'expand_wp_query'), 1000);
        add_filter('template_include', array(&$this, 'intercept_taxonomy_index'));

        if (gdtt_get('tpl_expand_single') == 1) {
            add_filter('single_template', array(&$this, 'expand_single_template'));
        } else {
            add_filter('single_template', array(&$this, 'post_single_template'));
        }

        if (gdtt_get('metabox_preload_select') == 1) {
            add_filter('setup_theme', array(&$this, 'preload_select'));
        }

        if (gdtt_get('tpl_expand_intersect') == 1) {
            add_filter('taxonomy_template', array(&$this, 'expand_intersect_template'));
            add_filter('category_template', array(&$this, 'expand_intersect_template'));
            add_filter('tag_template', array(&$this, 'expand_intersect_template'));

            if (GDTAXTOOLS_WPV > 36) {
                add_filter('archive_template', array(&$this, 'expand_intersect_template'));
            }
        }

        if (gdtt_get('tpl_expand_date') == 1) {
            add_filter('date_template', array(&$this, 'expand_date_template'));
        }

        if (gdtt_get('special_cpt_home_page') == 1) {
            add_filter('pre_get_posts', array(&$this, 'expand_home_page'));
        }

        if (gdtt_get('special_cpt_rss_feed') == 1) {
            add_filter('pre_get_posts', array(&$this, 'expand_feed_results'));
        }

        if (gdtt_get('special_cpt_s2_notify') == 1) {
            add_filter('s2_post_types', array(&$this, 'expand_s2_post_types'));
        }

        if (gdtt_get('tpl_expand_archives') == 1) {
            add_filter('archive_template', array(&$this, 'expand_archives_template'));
        }

        if (gdtt_get('special_cpt_gd_star_rating') == 1) {
            add_action('gdsr_register_ratings', array(&$this, 'expand_rating_types'));
        }

        do_action('gdcpt_core_wpany');
    }

    private function _expand_query($query, $cpt_enhanced, $filter_key, $default = 'post') {
        if (empty($cpt_enhanced)) return $query;

        $additional_post_types = isset($query->query_vars['post_type']) ? (array)$query->query_vars['post_type'] : array();
        $push_post = empty($additional_post_types);
        $additional_post_types = array_merge($additional_post_types, $cpt_enhanced);

        if ($push_post) {
            array_push($additional_post_types, $default);
        }

        $additional_post_types = apply_filters('gdcpt_expand_'.$filter_key.'_query_post_types', $additional_post_types);
        $query->set('post_type', $additional_post_types);
        return $query;
    }

    public function setup_nav_menu_item($menu_item) {
        if ($menu_item->type == 'gdtt_cpt_archive') {
            $menu_item->url = get_post_type_archive_link($menu_item->object);
        }

        return $menu_item;
    }

    public function nav_menu_objects($menu_items) {
        foreach ($menu_items as $item) {
            if ($item->type == 'gdtt_cpt_archive') {
                $post_type = $item->object;

                if (is_post_type_archive($post_type) || is_singular($post_type)) {
                    $item->current = true;
                    $item->classes[] = 'current-menu-item';
                }
            }
        }

        return $menu_items;
    }

    public function intercept_taxonomy_index($template) {
        global $wp_query;

        $new_template = '';

        if ($wp_query->is_cpt_taxonomy_index) {
            $post_type = get_query_var('post_type');

            $templates = array();

            if ($post_type) {
                $templates[] = 'taxindex-'.get_query_var('taxindex').'-'.$post_type.'.php';
            }

            $templates[] = 'taxindex-'.get_query_var('taxindex').'.php';
            $templates[] = 'taxindex.php';

            $new_template = locate_template($templates);
        }

        if (!empty($new_template)) {
            $template = $new_template;
        }

        return apply_filters('gdcpt_intercept_taxonomy_index_template', $template);
    }
    
    public function expand_wp_query() {
        global $wp_query;

        $wp_query->is_cpt_taxonomy_index = false;
        $wp_query->is_cpt_archive_intersection = false;

        if (get_query_var('taxindex') != '') {
            $wp_query->is_cpt_taxonomy_index = true;
            $wp_query->is_home = false;
        }

        if (is_tax()) {
            $cpt = get_query_var('post_type');

            if ($cpt != '') {
                $wp_query->is_cpt_archive_intersection = true;

                $wp_query->cpt_intersection = array(
                    'post_type' => $cpt,
                    'taxonomies' => array()
                );

                foreach ($wp_query->tax_query->queries as $tax) {
                    $wp_query->cpt_intersection['taxonomies'][] = $tax['taxonomy'];
                }
            }
        }
    }

    public function preload_select() {
        require_once(GDTAXTOOLS_PATH.'code/internal/data.php');
    }

    public function expand_home_page($query) {
        if ((is_home()) && (!isset($query->query_vars['suppress_filters']) || false == $query->query_vars['suppress_filters'])) {
            $cpts = gdtt_sf_list('home_page');
            $query = $this->_expand_query($query, $cpts, 'home');
        }

        return $query;
    }

    public function expand_feed_results($query) {
        if ($query->is_feed && !isset($query->query['post_type'])) {
            $cpts = gdtt_sf_list('rss_feed');
            $query = $this->_expand_query($query, $cpts, 'feed');
        }
        return $query;
    }

    public function expand_intersect_template($template) {
        if (gdtt_is_archive_intersection()) {
            global $wp_query;

            $base_intersect = 'intersection-'.$wp_query->cpt_intersection['post_type'];
            $base_archive = 'archive-'.$wp_query->cpt_intersection['post_type'];
            $line_intersect = $base_intersect;
            $line_archive = $base_archive;

            $templates = array();

            foreach ($wp_query->cpt_intersection['taxonomies'] as $tax) {
                $line_archive.= '-'.$tax;
                $line_intersect.= '-'.$tax;

                $templates[] = $line_archive.'.php';
                $templates[] = $line_intersect.'.php';
            }

            $templates = array_merge(array_reverse($templates), array(
                $base_intersect.'.php',
                'intersection.php',
                $base_archive.'.php',
                'taxonomy-'.$wp_query->cpt_intersection['taxonomies'][0],
                'taxonomy.php',
                'archive.php',
                'index.php')
            );

            return locate_template($templates);
        } else {
            return $template;
        }
    }

    public function expand_single_template($template) {
        global $wp_query;
        $object = $wp_query->get_queried_object();

        $templates = array(
            'single-'.$object->post_type.'-'.$object->ID.'.php',
            'single-'.$object->post_type.'-'.$object->post_name.'.php',
            'single-'.$object->ID.'.php',
            'single-'.$object->post_name.'.php',
            'single-'.$object->post_type.'.php',
            'single.php',
            'index.php');

        if (gdtt_sf($object->post_type, 'post_template')) {
            $tpl = get_post_meta($object->ID, '_wp_post_template', true);

            $templates = array_merge(array($tpl), $templates);
        }

        return locate_template($templates);
    }

    public function expand_date_template($template) {
        global $wp_query, $wp_rewrite;

        $rewrite_active = $wp_rewrite->using_permalinks();
        $post_type = isset($wp_query->query['post_type']) ? $wp_query->query['post_type'] : '';
        $prefix = $post_type != '' ? 'archive-'.$post_type : '';

        $templates = array();
        if (is_year()) {
            $year = isset($wp_query->query['year']) ? $wp_query->query['year'] : substr($wp_query->query['m'], 0, 4);

            if ($post_type != '' && gdtt_get('tpl_expand_date_cpt') == 1) {
                $templates = array(
                    $prefix.'-year-'.$year.'.php',
                    $prefix.'-'.$year.'.php',
                    $prefix.'-year.php');
            }

            $templates = array_merge($templates, array(
                'date-year-'.$year.'.php',
                'date-'.$year.'.php',
                'date-year.php'));
        }

        if (is_month()) {
            if ($rewrite_active) {
                $month = $wp_query->query['monthnum'];
                $year = $wp_query->query['year'];
            } else {
                $month = substr($wp_query->query['m'], 4, 2);
                $year = substr($wp_query->query['m'], 0, 4);
            }

            if ($post_type != '' && gdtt_get('tpl_expand_date_cpt') == 1) {
                $templates = array(
                    $prefix.'-month-'.$year.'-'.$month.'.php',
                    $prefix.'-month-'.$month.'.php',
                    $prefix.'-'.$year.'-'.$month.'.php',
                    $prefix.'-'.$month.'.php',
                    $prefix.'-month.php');
            }

            $templates = array_merge($templates, array(
                'date-month-'.$year.'-'.$month.'.php',
                'date-month-'.$month.'.php',
                'date-'.$year.'-'.$month.'.php',
                'date-'.$month.'.php',
                'date-month.php'));
        }

        if (is_day()) {
            if ($rewrite_active) {
                $day = $wp_query->query['day'];
                $month = $wp_query->query['monthnum'];
                $year = $wp_query->query['year'];
            } else {
                $day = substr($wp_query->query['m'], 6, 2);
                $month = substr($wp_query->query['m'], 4, 2);
                $year = substr($wp_query->query['m'], 0, 4);
            }

            if ($post_type != '' && gdtt_get('tpl_expand_date_cpt') == 1) {
                $templates = array(
                    $prefix.'-day-'.$year.'-'.$month.'-'.$day.'.php',
                    $prefix.'-day-'.$day.'.php',
                    $prefix.'-'.$year.'-'.$month.'-'.$day.'.php',
                    $prefix.'-day.php');
            }

        $templates = array_merge($templates, array(
            'date-day-'.$year.'-'.$month.'-'.$day.'.php',
            'date-day-'.$day.'.php',
            'date-'.$year.'-'.$month.'-'.$day.'.php',
            'date-day.php'));
        }

        if ($post_type != '' && gdtt_get('tpl_expand_date_cpt_priority') == 1) {
            $templates[] = $prefix.'.php';
        }

        array_push($templates, 'date.php', 'archive.php', 'index.php');

        return locate_template($templates);
    }

    public function expand_s2_post_types($post_types) {
        $cpts = gdtt_sf_list('s2_notify');

        if (!empty($cpts)) {
            if (is_array($post_types) && !empty($post_types)) {
                $post_types = array_merge($post_types, $cpts);
            } else {
                $post_types = $cpts;
            }
        }

        return $post_types;
    }

    public function post_single_template($template) {
        global $post;
        $cpt = $post->post_type;

        if (gdtt_sf($cpt, 'post_template')) {
            $tpl = get_post_meta($post->ID, '_wp_post_template', true);

            if (!empty($tpl) && file_exists(TEMPLATEPATH.'/'.$tpl)) {
                $template = TEMPLATEPATH.'/'.$tpl;
            }
        }

        return $template;
    }

    public function expand_archives_template($template) {
        if (gdtt_is_archive_intersection() && gdtt_get('tpl_expand_intersect') == 1) {
            return $this->expand_intersect_template($template);
        } else {
            $post_type = get_query_var('post_type');

            if ($post_type) {
                $templates = array('type-'.$post_type.'.php', 
                              'archive-'.$post_type.'.php',
                              'type.php',
                              'archive.php');

                $templates = array_merge($templates, (array)$template);
            }

            return locate_template($templates);
        }
    }

    public function expand_rating_types() {
        $cpts = gdtt_sf_list('gd_star_rating');

        foreach ($cpts as $cpt) {
            $post_type = get_post_type_object($cpt);
            gdsr_register_rating_object($cpt, array(
                'method' => 'inherit',
                'inherit' => 'post',
                'override' => array('name' => 'cpt_'.$cpt, 'post_type' => $cpt),
                'extend' => array('title' => __("Post Type", "gd-taxonomies-tools").': '.$post_type->labels->singular_name)
            ));
        }
    }
}

?>