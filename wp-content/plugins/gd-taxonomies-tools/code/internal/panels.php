<?php

if (!defined('ABSPATH')) exit;

class gdCPTAdmin_Panels {
    static function get_default_cpt_quick() {
        $cpt = array(
            'id' => 0,
            'name' => '',
            'icon' => 'book-open-text-image',
            'hierarchy' => 'no',
            'labels' => array('name' => '', 'singular_name' => ''),
            'taxonomies' => array('category', 'post_tag'),
        );

        return $cpt;
    }

    static function get_default_cpt() {
        global $gdtt;

        $cpt = array(
            'source' => '',
            'id' => 0,
            'name' => '',
            'icon' => 'book-open-text-image',
            'active' => 1,
            'permalinks_active' => 'no',
            'permalinks_structure' => '',
            'date_archives' => 'yes',
            'search_rewrite' => 'yes',
            'intersections' => 'yes',
            'intersections_structure' => '',
            'intersections_partial' => 'no',
            'intersections_baseless' => '',
            'yourls_active_link' => 'yes',
            'description' => '',
            'public' => 'yes',
            'archive' => 'yes',
            'archive_slug' => '',
            'ui' => 'yes',
            'nav_menus' => 'yes',
            'hierarchy' => 'no',
            'rewrite' => 'yes',
            'show_in_menu' => 'yes',
            'show_in_admin_bar' => 'yes',
            'rewrite_slug' => '',
            'rewrite_front' => 'no',
            'rewrite_feeds' => 'yes',
            'rewrite_pages' => 'yes',
            'exclude_from_search' => 'no',
            'publicly_queryable' => 'yes',
            'can_export' => 'yes',
            'query' => 'yes',
            'query_slug' => '',
            'menu_position' => '__auto__',
            'menu_icon' => '',
            'edit_link' => 'post.php?post=%d',
            'meta_columns' => array(),
            'meta_filters' => array(),
            'special' => array(
                'right_now',
                'menu_drafts',
                'menu_futures'),
            'supports' => array(
                'title', 'editor', 'excerpt',
                'trackbacks', 'custom-fields',
                'comments', 'revisions', 
                'author', 'thumbnail'),
            'taxonomies' => array(
                'category', 'post_tag'),
            'labels' => array(
                'name' => '', 'singular_name' => '',
                'add_new' => '', 'add_new_item' => '',
                'edit_item' => '', 'new_item' => '',
                'view_item' => '', 'search_items' => '',
                'not_found' => '', 'not_found_in_trash' => '',
                'parent_item_colon' => '', 'all_items' => '',
                'menu_name' => ''),
            'capabilites' => 'type',
            'caps_type' => 'post',
            'caps' => $gdtt->post_type_caps
        );

        return apply_filters('gdcpt_default_post_type', $cpt);
    }

    static function get_default_tax_quick() {
        $tax = array(
            'id' => 0,
            'name' => '',
            'hierarchy' => 'no',
            'labels' => array('name' => '', 'singular_name' => ''),
            'domain' => array('post')
        );

        return $tax;
    }

    static function get_default_tax() {
        global $gdtt;

        $tax = array(
            'source' => '',
            'id' => 0,
            'name' => '',
            'active' => 1,
            'public' => 'yes',
            'ui' => 'yes',
            'nav_menus' => 'yes',
            'cloud' => 'yes',
            'hierarchy' => 'no',
            'sort' => 'no',
            'metabox' => 'auto',
            'description' => '',
            'show_admin_column' => 'no',
            'rewrite' => 'yes_name',
            'rewrite_custom' => '',
            'rewrite_front' => 'yes',
            'rewrite_hierarchy' => 'auto',
            'query' => 'yes_name',
            'query_custom' => '',
            'index_normal' => 'no',
            'index_intersect' => 'no',
            'special' => array(
                'term_id',
                'term_link'),
            'domain' => array(
                'post'),
            'labels' => array(
                'name' => '', 'singular_name' => '',
                'search_items' => '', 'popular_items' => '',
                'all_items' => '', 'parent_item' => '',
                'parent_item_colon' => '', 'edit_item' => '',
                'view_item' => '', 'update_item' => '', 'add_new_item' => '',
                'new_item_name' => '', 'separate_items_with_commas' => '',
                'add_or_remove_items' => '', 'choose_from_most_used' => '',
                'not_found' => '', 'menu_name' => ''),
            'caps_type' => 'categories',
            'caps' => $gdtt->taxonomy_caps
        );

        return apply_filters('gdcpt_default_taxonomy', $tax);
    }

    static function get_default_cpt_simple($post_type) {
        $cpt = array('active' => 'no', 'name' => $post_type->name,
                     'permalinks_active' => 'no',
                     'permalinks_structure' => '',
                     'date_archives' => 'no',
                     'intersections' => 'no',
                     'intersections_structure' => '',
                     'intersections_partial' => 'no',
                     'intersections_baseless' => '',
                     'search_rewrite' => 'no',
                     'meta_columns' => array(), 'meta_filters' => array(),
                     'special' => array(), 'taxonomies' => $post_type->taxonomies,
                     'supports' => gdtt_get_post_type_features($post_type->name));

        return apply_filters('gdcpt_default_post_type_simple', $cpt, $post_type);
    }

    static function get_default_tax_simple($tax_type) {
        $tax = array('active' => 'no', 'name' => $tax_type->name, 
                     'metabox' => 'auto',
                     'show_admin_column' => 'no',
                     'nav_menus' => 'yes',
                     'cloud' => 'yes',
                     'index_normal' => 'no',
                     'index_intersect' => 'no',
                     'special' => array(), 
                     'domain' => $tax_type->object_type);

        return apply_filters('gdcpt_default_taxonomy_simple', $tax, $tax_type);
    }

    static function get_duplicated_cpt($id) {
        $cpt = gdCPTAdmin_Panels::find_postype($id);
        $cpt = gdCPTAdmin_Panels::update_cpt($cpt);

        $cpt['name'] = '';
        $cpt['source'] = '';
        $cpt['id'] = 0;
        $cpt['permalinks_active'] = 'no';
        $cpt['permalinks_structure'] = '';

        return $cpt;
    }

    static function get_duplicated_tax($id) {
        $tax = gdCPTAdmin_Panels::find_taxonomy($id);
        $tax = gdCPTAdmin_Panels::update_tax($tax);

        $tax['name'] = '';
        $tax['source'] = '';
        $tax['id'] = 0;

        return $tax;
    }
    
    static function update_cpt($cpt) {
        global $gdtt;

        if (!is_array($cpt['supports'])) {
            $cpt['supports'] = array();
        }

        if (!isset($cpt['meta_columns'])) {
            $cpt['meta_columns'] = array();
        }

        if (!isset($cpt['meta_filters'])) {
            $cpt['meta_filters'] = array();
        }

        if (!isset($cpt['source'])) {
            $cpt['source'] = '';
        }

        if (!is_array($cpt['taxonomies'])) {
            $cpt['taxonomies'] = array();
        }

        if (!isset($cpt['description'])) {
            $cpt['description'] = '';
        }

        if (!isset($cpt['rewrite_slug'])) {
            $cpt['rewrite_slug'] = '';
        }

        if (!isset($cpt['menu_position'])) {
            $cpt['menu_position'] = '20';
        }

        if (!isset($cpt['rewrite_front'])) {
            $cpt['rewrite_front'] = 'no';
        }

        if (!isset($cpt['search_rewrite'])) {
            $cpt['search_rewrite'] = 'yes';
        }

        if (!isset($cpt['date_archives'])) {
            $cpt['date_archives'] = 'yes';
        }

        if (!isset($cpt['intersections'])) {
            $cpt['intersections'] = 'yes';
        }

        if (!isset($cpt['intersections_partial'])) {
            $cpt['intersections_partial'] = 'no';
        }

        if (!isset($cpt['intersections_structure'])) {
            $cpt['intersections_structure'] = '';
        }

        if (!isset($cpt['intersections_baseless'])) {
            $cpt['intersections_baseless'] = '';
        }

        if (!isset($cpt['nav_menus'])) {
            $cpt['nav_menus'] = 'yes';
        }

        if (!isset($cpt['show_in_admin_bar'])) {
            $cpt['show_in_admin_bar'] = $cpt['show_in_menu'];
        }

        if (!isset($cpt['exclude_from_search'])) {
            $cpt['exclude_from_search'] = 'no';
        }

        if (!isset($cpt['publicly_queryable'])) {
            $cpt['publicly_queryable'] = 'yes';
        }

        if (!isset($cpt['can_export'])) {
            $cpt['can_export'] = 'yes';
        }

        if (!isset($cpt['active'])) {
            $cpt['active'] = 1;
        }

        if (!isset($cpt['yourls_active'])) {
            $cpt['yourls_active'] = 'no';
        }

        if (!isset($cpt['yourls_active_link'])) {
            $cpt['yourls_active_link'] = 'yes';
        }

        if (!isset($cpt['yourls_active_auto'])) {
            $cpt['yourls_active_auto'] = 'no';
        }

        if (!isset($cpt['yourls_active_tweet'])) {
            $cpt['yourls_active_tweet'] = '%T - %U';
        }

        if (!isset($cpt['labels'])) {
            $cpt['labels'] = array('name' => $cpt['label'],
                'singular_name' => $cpt['label_singular'],
                'add_new' => '', 'add_new_item' => '',
                'edit_item' => '', 'edit' => '',
                'new_item' => '', 'view_item' => '',
                'search_items' => '', 'not_found' => '',
                'not_found_in_trash' => '', 'view' => '',
                'parent_item_colon' => '', 'all_items' => '',
                'menu_name' => $cpt['label']);
        } else {
            if (!isset($cpt['labels']['menu_name'])) {
                $cpt['labels']['menu_name'] = $cpt['labels']['name'];
            }

            if (!isset($cpt['labels']['all_items'])) {
                $cpt['labels']['all_items'] = '';
            }
        }

        if (!isset($cpt['capabilites'])) {
            $cpt['capabilites'] = 'list';
            $cpt['caps_type'] = 'post';
        }

        if (!isset($cpt['special']) || !is_array($cpt['special'])) {
            $cpt['special'] = array(
                'right_now',
                'post_template');
        }

        if (!isset($cpt['caps'])) {
            $cpt['caps'] = $gdtt->post_type_caps;
        } else {
            wp_parse_args($cpt['caps'], $gdtt->post_type_caps);
        }

        return apply_filters('gdcpt_update_post_type', $cpt);
    }

    static function update_tax($tax) {
        global $gdtt;

        $tax['domain'] = explode(',', $tax['domain']);

        if (!isset($tax['special']) || !is_array($tax['special'])) {
            $tax['special'] = array();

            if (isset($tax['columns']) && $tax['columns'] == 1) {
                $tax['special'] = array('term_id', 'term_link');
            }
        }

        if (!isset($tax['source'])) {
            $tax['source'] = '';
        }

        if (!isset($tax['description'])) {
            $tax['description'] = '';
        }

        if (!isset($tax['metabox'])) {
            $tax['metabox'] = 'auto';
        }

        if (!isset($tax['index_normal'])) {
            $tax['index_normal'] = 'no';
        }

        if (!isset($tax['index_intersect'])) {
            $tax['index_intersect'] = 'no';
        }

        if (!isset($tax['sort'])) {
            $tax['sort'] = 'no';
        }

        if (!isset($tax['nav_menus'])) {
            $tax['nav_menus'] = 'yes';
        }

        if (!isset($tax['show_admin_column'])) {
            $tax['show_admin_column'] = in_array('edit_column', $tax['special']) ? 'yes' : 'no';
            $tax['special'] = gdr2_remove_from_array_by_value($tax['special'], 'edit_column');
        }

        if (!isset($tax['caps_type'])) {
            $tax['caps_type'] = 'categories';
        }

        if (!isset($tax['rewrite_front'])) {
            $tax['rewrite_front'] = 'yes';
        }

        if (!isset($tax['rewrite_hierarchy'])) {
            $tax['rewrite_hierarchy'] = 'auto';
        }

        if (!isset($tax['labels'])) {
            $tax['labels'] = array('name' => $tax['label'],
                'singular_name' => $tax['label_singular'],
                'search_items' => '', 'popular_items' => '',
                'all_items' => '', 'parent_item' => '',
                'parent_item_colon' => '', 'edit_item' => '',
                'view_item' => '', 'update_item' => '', 'add_new_item' => '',
                'new_item_name' => '', 'separate_items_with_commas' => '',
                'add_or_remove_items' => '', 'choose_from_most_used' => '',
                'not_found' => '', 'menu_name' => '');
        } else {
            if (!isset($tax['labels']['view_item'])) {
                $tax['labels']['view_item'] = '';
            }

            if (!isset($tax['labels']['not_found'])) {
                $tax['labels']['not_found'] = '';
            }
        }

        if (!isset($tax['caps'])) {
            $tax['caps'] = $gdtt->taxonomy_caps;
        } else {
            wp_parse_args($tax['caps'], $gdtt->taxonomy_caps);
        }

        return apply_filters('gdcpt_update_taxonomy', $tax);
    }

    static function update_cpt_third($cpt, $post_type) {
        if (!isset($cpt['source'])) {
            $cpt['source'] = '';
        }

        return apply_filters('gdcpt_update_post_type', $cpt, $post_type);
    }

    static function update_tax_third($tax, $tax_type) {
        if (!isset($tax['source'])) {
            $tax['source'] = '';
        }

        if (!isset($tax['index_normal'])) {
            $tax['index_normal'] = 'no';
        }

        if (!isset($tax['index_intersect'])) {
            $tax['index_intersect'] = 'no';
        }

        if (!isset($tax['show_admin_column'])) {
            $tax['show_admin_column'] = 'no';
        }

        if (!isset($tax['nav_menus'])) {
            $tax['nav_menus'] = 'no';
        }

        if (!isset($tax['cloud'])) {
            $tax['cloud'] = 'no';
        }

        $tax['domain'] = explode(',', $tax['domain']);

        return apply_filters('gdcpt_update_taxonomy', $tax, $tax_type);
    }

    static function update_cpt_simple($cpt, $post_type) {
        if (!isset($cpt['source'])) {
            $cpt['source'] = '';
        }

        if (!isset($cpt['search_rewrite'])) {
            $cpt['search_rewrite'] = 'no';
        }

        if (!isset($cpt['meta_columns'])) {
            $cpt['meta_columns'] = array();
        }

        if (!isset($cpt['meta_filters'])) {
            $cpt['meta_filters'] = array();
        }

        if (!isset($cpt['permalinks_active'])) {
            $cpt['permalinks_active'] = 'no';
            $cpt['permalinks_structure'] = '';
            $cpt['date_archives'] = 'no';
            $cpt['intersections'] = '';
            $cpt['intersections_structure'] = '';
            $cpt['intersections_partial'] = 'no';
            $cpt['intersections_baseless'] = '';
        }

        return apply_filters('gdcpt_update_post_type_simple', $cpt, $post_type);
    }

    static function update_tax_simple($tax, $tax_type) {
        if (!isset($tax['source'])) {
            $tax['source'] = '';
        }

        if (!isset($tax['index_normal'])) {
            $tax['index_normal'] = 'no';
        }

        if (!isset($tax['index_intersect'])) {
            $tax['index_intersect'] = 'no';
        }

        if (!isset($tax['show_admin_column'])) {
            $tax['show_admin_column'] = 'no';
        }

        if (!isset($tax['nav_menus'])) {
            $tax['nav_menus'] = 'no';
        }

        if (!isset($tax['cloud'])) {
            $tax['cloud'] = 'no';
        }

        $tax['domain'] = explode(',', $tax['domain']);

        return apply_filters('gdcpt_update_taxonomy_simple', $tax, $tax_type);
    }

    static function array_to_string($a, $non_asc = array('supports', 'taxonomies')) {
        $p = array();

        foreach ($a as $name => $value) {
            if (is_array($value)) {
                $in = array();

                if (in_array($name, $non_asc)) {
                    foreach ($value as $vl) {
                        $in[] = is_string($vl) ? "'".str_replace("'", "\'", $vl)."'" : $vl;
                    }
                } else {
                    foreach ($value as $code => $vl) {
                        if (is_bool($vl)) {
                            $v = $vl === false ? 'false' : 'true';
                        } else if (is_null($vl)) {
                            $v = 'null';
                        } else {
                            $v = is_string($vl) ? "'".str_replace("'", "\'", $vl)."'" : $vl;
                        }

                        $in[] = "'$code' => $v";
                    }
                }
                $p[] = sprintf("'%s' => array(%s)", $name, join(', ', $in));
            } else {
                if (is_bool($value)) {
                    $v = $value === false ? 'false' : 'true';
                } else if (is_null($value)) {
                    $v = 'null';
                } else {
                    $v = is_string($value) ? "'".str_replace("'", "\'", $value)."'" : $value;
                }

                $p[] = "'$name' => $v";
            }
        }

        return 'array('.join(', ', $p).')';
    }

    static function find_taxonomy($id) {
        global $gdtt;
        $found = array();

        for ($i = 0; $i < count($gdtt->t); $i++) {
            if ($gdtt->t[$i]['id'] == $id) {
                $found = $gdtt->t[$i];
                break;
            }
        }

        return $found;
    }

    static function find_postype($id) {
        global $gdtt;
        $found = array();

        for ($i = 0; $i < count($gdtt->p); $i++) {
            if ($gdtt->p[$i]['id'] == $id) {
                $found = $gdtt->p[$i];
                break;
            }
        }

        return $found;
    }

    static function admin_front() {
        global $gdtt, $gdtt_admin;

        $_panel_name = 'front';
        $options = $gdtt->o;
        $gdtttax = $gdtt_admin->taxes;
        $gdcpost = $gdtt_admin->posts;
        $indexer = $gdtt_admin->indexer;
        $wpv = GDTAXTOOLS_WPV;

        include(GDTAXTOOLS_PATH.'forms/shared/front.header.php');
        include(GDTAXTOOLS_PATH.'forms/front.php');
        include(GDTAXTOOLS_PATH.'forms/shared/all.footer.php');
    }

    static function admin_about() {
        global $gdtt, $gdtt_admin;

        $_panel_name = 'about';
        $header = array(
            array(__("About", "gd-taxonomies-tools"), 'admin.php?page=gdtaxtools_about'),
            array(__("Modules", "gd-taxonomies-tools"), 'admin.php?page=gdtaxtools_about&subpage=modules'),
            array(__("Custom Fields", "gd-taxonomies-tools"), 'admin.php?page=gdtaxtools_about&subpage=fields'));

        include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');

        if (isset($_GET['subpage']) && $_GET['subpage'] == 'modules') {
            include(GDTAXTOOLS_PATH.'forms/about/modules.php');
        } else if (isset($_GET['subpage']) && $_GET['subpage'] == 'fields') {
            include(GDTAXTOOLS_PATH.'forms/about/fields.php');
        } else {
            include(GDTAXTOOLS_PATH.'forms/about/about.php');
        }

        include(GDTAXTOOLS_PATH.'forms/shared/all.footer.php');
    }

    static function admin_modules() {
        global $gdtt, $gdtt_admin, $wp_roles;

        $_panel_name = 'modules';
        $header = array();

        $header[] = array(__("Modules", "gd-taxonomies-tools"), '#');
        include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
        include(GDTAXTOOLS_PATH.'forms/modules.php');
        include(GDTAXTOOLS_PATH.'forms/shared/all.footer.php');
    }

    static function admin_settings() {
        global $gdtt, $gdtt_admin, $wp_roles;

        $_panel_name = 'settings';
        $header = array();
        $options = $gdtt->o;
        $gdtttax = $gdtt_admin->taxes;
        $meta = $gdtt->m['boxes'];

        $header[] = array(__("Settings", "gd-taxonomies-tools"), '#');
        include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
        include(GDTAXTOOLS_PATH.'forms/settings.php');
        include(GDTAXTOOLS_PATH.'forms/shared/all.footer.php');
    }

    static function admin_tools() {
        global $gdtt;

        $_panel_name = 'tools';
        $header = array();
        $list_cpt = $gdtt->p;
        $list_tax = $gdtt->t;

        $header[] = array(__("Tools", "gd-taxonomies-tools"), 'admin.php?page=gdtaxtools_tools');
        include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
        include(GDTAXTOOLS_PATH.'forms/tools.php');
        include(GDTAXTOOLS_PATH.'forms/shared/all.footer.php');
    }

    static function admin_roles() {
        global $gdtt, $gdtt_admin;

        $_panel_name = 'roles';
        $header = array();
        $options = $gdtt->o;
        $gdtttax = $gdtt_admin->taxes;

        $rcaps = get_option('gd-taxonomy-tools-caps');
        if (!is_array($rcaps)) {
            $rcaps = array('cpt' => array(), 'tax' => array());
            update_option('gd-taxonomy-tools-caps', $rcaps);
        } else {
            $default_cpt = array(
                'administrator' => array('edit_post', 'edit_posts', 'edit_private_posts', 'edit_published_posts', 'edit_others_posts', 'publish_posts', 'read_post', 'read_private_posts', 'delete_post', 'delete_posts', 'delete_published_posts', 'delete_private_posts', 'delete_others_posts'),
                'editor' => array('edit_post', 'edit_posts', 'edit_private_posts', 'edit_published_posts', 'edit_others_posts', 'publish_posts', 'read_post', 'read_private_posts', 'delete_post', 'delete_posts', 'delete_published_posts', 'delete_private_posts', 'delete_others_posts'),
                'author' => array('edit_post', 'edit_posts', 'edit_private_posts', 'edit_published_posts', 'publish_posts', 'read_post', 'delete_post', 'delete_posts', 'delete_published_posts'),
                'contributor' => array('edit_post', 'edit_posts', 'delete_post', 'delete_posts')
            );

            $default_tax = array(
                'administrator' => array('manage_terms', 'edit_terms', 'delete_terms', 'assign_terms'),
                'editor' => array('manage_terms', 'edit_terms', 'delete_terms', 'assign_terms')
            );

            foreach ($rcaps['cpt'] as $key => $data) {
                $rcaps['cpt'][$key]->init($default_cpt);
            }

            foreach ($rcaps['tax'] as $key => $data) {
                $rcaps['tax'][$key]->init($default_tax);
            }

            update_option('gd-taxonomy-tools-caps', $rcaps);
        }

        $header[] = array(__("Roles &amp; Capabilities", "gd-taxonomies-tools"), "admin.php?page=gdtaxtools_roles");
        include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
        include(GDTAXTOOLS_PATH.'forms/caps/editor.php');
        include(GDTAXTOOLS_PATH.'forms/shared/all.footer.php');
    }

    static function admin_metas() {
        global $gdtt, $gdtt_admin, $gdtt_fields;

        $_panel_name = 'meta_boxes';
        $gdtt_fields->load_admin();
        $gdtt->update_meta_boxes_data();
        gdtt_update_custom_fields();

        $header = array();
        $options = $gdtt->o;
        $gdtt_meta = $gdtt->m;
        $gdtttax = $gdtt_admin->taxes;

        $header[] = array(__("Meta Boxes", "gd-taxonomies-tools"), 'admin.php?page=gdtaxtools_metas');
        include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
        include(GDTAXTOOLS_PATH.'forms/meta/editor.php');
        include(GDTAXTOOLS_PATH.'forms/shared/all.footer.php');
    }

    static function admin_postypes() {
        global $wp_taxonomies, $gdtt, $gdtt_admin;

        $_panel_name = 'post_types';
        $post_features_special = $gdtt->post_features_special;
        $post_features = $gdtt->post_features;

        $options = $gdtt->o;
        $wpv = GDTAXTOOLS_WPV;
        $gdcpost = $gdtt_admin->posts;
        $gdcpall = $gdtt->p;
        $gdtt_meta = $gdtt->m;

        $action = isset($_GET['action']) ? $_GET['action'] : 'list';
        $header = array(array(__("Post Types", "gd-taxonomies-tools"), 'admin.php?page=gdtaxtools_postypes'));

        if ($action == 'list') {
            $header[] = array(__("Add Quick", "gd-taxonomies-tools"), admin_url('admin.php?page=gdtaxtools_postypes&action=addquick'));
            $header[] = array(__("Add New", "gd-taxonomies-tools"), admin_url('admin.php?page=gdtaxtools_postypes&action=addnew'));

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/cpt/list.php');
        } else if ($action == 'templates') {
            $header[] = array(__("Theme Templates", "gd-taxonomies-tools"), '#');

            $cpt = gdCPTAdmin_Panels::find_postype($_GET['pid']);

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/cpt/theme.php');
        } else if ($action == 'function') {
            $header[] = array(__("Function", "gd-taxonomies-tools"), '#');

            $cpt = gdCPTAdmin_Panels::find_postype($_GET['pid']);
            $opts = gdtt_generate_custom_posts_options($cpt);
            $arry = gdCPTAdmin_Panels::array_to_string($opts);
            $f = sprintf("register_post_type('%s', %s);", $cpt['name'], $arry);

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/cpt/function.php');
        } else if ($action == 'simple') {
            $header[] = array(__("Post Type: Simple Edit", "gd-taxonomies-tools"), "#");

            $cpt_name = $_GET['pname'];
            $post_type = get_post_type_object($cpt_name);
            $cpt_built_in = $post_type->_builtin;

            if (isset($gdtt->nn_p['simple'][$cpt_name])) {
                $cpt = gdCPTAdmin_Panels::update_cpt_simple($gdtt->nn_p['simple'][$cpt_name], $post_type);
            } else {
                $cpt = gdCPTAdmin_Panels::get_default_cpt_simple($post_type);
            }

            if ($cpt_built_in) {
                $post_features_special = array(
                    'post_template' => array('label' => __("Custom Post Template", "gd-taxonomies-tools"), 'info' => __("Additional template for posts similar to templates implemented for pages.", "gd-taxonomies-tools")),
                    'disable_quickedit' => array('label' => __("Remove Quick Edit", "gd-taxonomies-tools"), 'info' => __("Remove quick edit option from post s efitor lists.", "gd-taxonomies-tools")),
                    'menu_drafts' => array('label' => __("Drafts in Menu", "gd-taxonomies-tools"), 'info' => __("Adds drafts quick access link in the post type menu.", "gd-taxonomies-tools")),
                    'menu_futures' => array('label' => __("Future Posts in Menu", "gd-taxonomies-tools"), 'info' => __("Adds scheduled posts quick access link in the post type menu.", "gd-taxonomies-tools")));

                if ($cpt_name == 'page') {
                    unset($post_features_special['post_template']);
                }
            } else {
                unset($post_features_special['gd_star_rating']);
            }

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/cpt/simple.php');
        } else if ($action == 'addquick') {
            $header[] = array(__("Add new Custom Post Type", "gd-taxonomies-tools"), '#');

            $cpt = gdCPTAdmin_Panels::get_default_cpt_quick();

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/cpt/quick.php');
        } else if ($action == 'addnew') {
            $header[] = array(__("Add new Custom Post Type", "gd-taxonomies-tools"), '#');

            $cpt_built_in = false;
            $cpt = gdCPTAdmin_Panels::get_default_cpt();

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/cpt/editor.php');
        } else if ($action == 'dupecpt') {
            $header[] = array(__("Add new Custom Post Type", "gd-taxonomies-tools"), '#');

            $cpt = gdCPTAdmin_Panels::get_duplicated_cpt($_GET['pid']);

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/cpt/editor.php');
        } else if ($action == 'edit') {
            if ($_GET['cpt'] == 0) {
                $header[] = array(__("Edit Post Type", "gd-taxonomies-tools"), '#');

                $cpt_name = $_GET['pname'];
                $post_type = get_post_type_object($cpt_name);
                $cpt_built_in = $post_type->_builtin;

                if (isset($gdtt->nn_p['full'][$cpt_name])) {
                    $cpt = gdCPTAdmin_Panels::update_cpt_third($gdtt->nn_p['full'][$cpt_name], $post_type);
                } else {
                    $cpt = gdtt_get_override_post_type($cpt_name, $post_type);
                }

                if ($cpt_built_in) {
                    $post_features_special = array(
                            'post_template' => array('label' => __("Custom Post Template", "gd-taxonomies-tools"), 'info' => __("Additional template for posts similar to templates implemented for pages.", "gd-taxonomies-tools")),
                            'disable_quickedit' => array('label' => __("Remove Quick Edit", "gd-taxonomies-tools"), 'info' => __("Remove quick edit option from post s efitor lists.", "gd-taxonomies-tools")),
                            'menu_drafts' => array('label' => __("Drafts in Menu", "gd-taxonomies-tools"), 'info' => __("Adds drafts quick access link in the post type menu.", "gd-taxonomies-tools")),
                            'menu_futures' => array('label' => __("Future Posts in Menu", "gd-taxonomies-tools"), 'info' => __("Adds scheduled posts quick access link in the post type menu.", "gd-taxonomies-tools")));

                    if ($cpt_name == 'page') {
                        unset($post_features_special['post_template']);
                    }
                } else {
                    unset($post_features_special['gd_star_rating']);
                }

                include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
                include(GDTAXTOOLS_PATH.'forms/cpt/editor.php');
            } else {
                $header[] = array(__("Edit Custom Post Type", "gd-taxonomies-tools"), '#');

                $cpt_built_in = false;
                $cpt = gdCPTAdmin_Panels::find_postype($_GET['pid']);
                $cpt = gdCPTAdmin_Panels::update_cpt($cpt);

                include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
                include(GDTAXTOOLS_PATH.'forms/cpt/editor.php');
            }
        }

        include(GDTAXTOOLS_PATH.'forms/shared/all.footer.php');
    }

    static function admin_taxs() {
        global $gdtt, $gdtt_admin;

        $taxonomy_features_special = $gdtt->taxonomy_features_special;

        $_panel_name = 'taxonomies';
        $options = $gdtt->o;
        $wpv = GDTAXTOOLS_WPV;
        $gdtttax = $gdtt_admin->taxes;
        $gdtxall = $gdtt->t;

        $action = isset($_GET['action']) ? $_GET['action'] : 'list';
        $header = array(array(__("Taxonomies", "gd-taxonomies-tools"), 'admin.php?page=gdtaxtools_taxs'));

        $post_types = get_post_types(array(), 'objects');

        if ($action == 'list') {
            $header[] = array(__("Add Quick", "gd-taxonomies-tools"), admin_url('admin.php?page=gdtaxtools_taxs&action=addquick'));
            $header[] = array(__("Add New", "gd-taxonomies-tools"), admin_url('admin.php?page=gdtaxtools_taxs&action=addnew'));

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/tax/list.php');
        } else if ($action == 'templates') {
            $header[] = array(__("Theme Templates", "gd-taxonomies-tools"), '#');

            $tax = gdCPTAdmin_Panels::find_taxonomy($_GET['tid']);

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/tax/theme.php');
        } else if ($action == 'function') {
            $header[] = array(__("Function", "gd-taxonomies-tools"), '#');

            $tax = gdCPTAdmin_Panels::find_taxonomy($_GET['tid']);
            $opts = gdtt_generate_custom_taxonomies_options($tax);
            $arry = gdCPTAdmin_Panels::array_to_string($opts);
            $domains = explode(',', $tax['domain']);
            $f = sprintf("register_taxonomy('%s', array('%s'), %s);", $tax['name'], join("', '", $domains), $arry);

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/tax/function.php');
        } else if ($action == 'simple') {
            $header[] = array(__("Taxonomy: Simple Edit", "gd-taxonomies-tools"), "#");

            $tax_name = $_GET['tname'];
            $tax_type = get_taxonomy($tax_name);
            $tx_built_in = $tax_type->_builtin;

            if (isset($gdtt->nn_t['simple'][$tax_name])) {
                $tax = gdCPTAdmin_Panels::update_tax_simple($gdtt->nn_t['simple'][$tax_name], $tax_type);
            } else {
                $tax = gdCPTAdmin_Panels::get_default_tax_simple($tax_type);
            }

            if ($tx_built_in) {
                unset($taxonomy_features_special['edit_filter']);
            }

            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
            include(GDTAXTOOLS_PATH.'forms/tax/simple.php');
        } else if ($action == 'addnew') {
            $header[] = array(__("Add new Custom Taxonomy", "gd-taxonomies-tools"), "#");
            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');

            $tax = gdCPTAdmin_Panels::get_default_tax();

            include(GDTAXTOOLS_PATH."forms/tax/editor.php");
        } else if ($action == 'addquick') {
            $header[] = array(__("Add new Custom Taxonomy", "gd-taxonomies-tools"), "#");
            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');

            $tax = gdCPTAdmin_Panels::get_default_tax_quick();

            include(GDTAXTOOLS_PATH."forms/tax/quick.php");
        } else if ($action == 'dupecpt') {
            $header[] = array(__("Add new Custom Taxonomy", "gd-taxonomies-tools"), "#");
            include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');

            $tax = gdCPTAdmin_Panels::get_duplicated_tax($_GET['tid']);

            include(GDTAXTOOLS_PATH."forms/tax/editor.php");
        } else if ($action == 'edit') {
            if ($_GET['cpt'] == 0) {
                $header[] = array(__("Edit Taxonomy", "gd-taxonomies-tools"), "#");
                $tax_name = $_GET['tname'];
                $tax_type = get_taxonomy($tax_name);
                $tx_built_in = $tax_type->_builtin;

                if (isset($gdtt->nn_t['full'][$tax_name])) {
                    $tax = gdCPTAdmin_Panels::update_tax_third($gdtt->nn_t['full'][$tax_name], $tax_type);
                } else {
                    $tax = gdtt_get_override_taxonomy($tax_name, $tax_type);
                }

                if ($tx_built_in) {
                    unset($taxonomy_features_special['edit_column']);
                    unset($taxonomy_features_special['edit_filter']);
                }

                include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
                include(GDTAXTOOLS_PATH.'forms/tax/editor.php');
            } else {
                $header[] = array(__("Edit Custom Taxonomy", "gd-taxonomies-tools"), '#');

                $tx_built_in = false;
                $tax = gdCPTAdmin_Panels::find_taxonomy($_GET['tid']);
                $tax = gdCPTAdmin_Panels::update_tax($tax);

                include(GDTAXTOOLS_PATH.'forms/shared/all.header.php');
                include(GDTAXTOOLS_PATH.'forms/tax/editor.php');
            }
        }

        include(GDTAXTOOLS_PATH.'forms/shared/all.footer.php');
    }
}

?>