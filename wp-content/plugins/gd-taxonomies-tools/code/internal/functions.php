<?php

if (!defined('ABSPATH')) exit;

global $gdcpt_log;
$gdcpt_log = new gdr2_Log(GDTAXTOOLS_LOG_PATH);

function gdt_dump($val) {
    global $gdcpt_log;
    $gdcpt_log->sdump($val);
}

function gdt_log($val) {
    global $gdcpt_log;
    $gdcpt_log->slog($val);
}

function gdtt_valid_rewrite_post_types() {
    $options = array('public' => true, '_builtin' => false);
    $post_types = get_post_types($options, 'objects');
    $found = array();

    foreach ($post_types as $post_type => $object) {
        if (isset($object->rewrite['slug']) && !empty($object->rewrite['slug'])) {
            $found[$post_type] = $object;
        }
    }

    return $found;
}

function gdtt_is_valid_rewrite_post_type($post_type) {
    if (is_array($post_type)) {
        if (count($post_type) != 1) {
            return false;
        }
        $post_type = $post_type[0];
    }
    $post_type = get_post_type_object($post_type);
    if (!is_null($post_type) &&
        $post_type->_builtin == false &&
        $post_type->public == true &&
        isset($post_type->rewrite['slug']) &&
        !empty($post_type->rewrite['slug']) &&
        $post_type->hierarchical == false ) {
            return $post_type;
    }
    return false;
}

function gdtt_render_taxonomies($tax = '') {
    global $wp_taxonomies;
    foreach ($wp_taxonomies as $taxonomy => $cnt) {
        $current = $tax == $taxonomy ? ' selected="selected"' : '';
        echo "\t<option value='".$taxonomy."'".$current.">".$cnt->label."</option>\r\n";
    }
}

function gdtt_render_post_types($post_type = '') {
    $wp_post_types = gdtt_get_public_post_types(true);

    echo "\t<option value=''>".__("All post types", "gd-taxonomies-tools")."</option>\r\n";
    foreach ($wp_post_types as $t => $cpt) {
        $current = $t == $post_type ? ' selected="selected"' : '';
        echo "\t<option value='".$t."'".$current.">".$cpt->label."</option>\r\n";
    }
}

function gdtt_render_alert($title, $content) {
    ?>
    <div class="ui-widget">
        <div class="ui-state-error ui-corner-all" style="padding: 0pt 0.7em; margin: 10px 0;">
            <p>
                <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>
                <strong><?php echo $title; ?>:</strong> <?php echo $content; ?>
            </p>
        </div>
    </div>
    <?php
}

function gdtt_render_notice($title, $content) {
    ?>
    <div class="ui-widget">
        <div class="ui-state-highlight ui-corner-all" style="padding: 0pt 0.7em; margin: 10px 0;">
            <p>
                <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
                <strong><?php echo $title; ?>:</strong> <?php echo $content; ?>
            </p>
        </div>
    </div>
    <?php
}

function gdtt_generate_custom_posts_options($cpt) {
    $cpt['description'] = !isset($cpt['description']) ? '' : $cpt['description'];
    $cpt['rewrite_slug'] = !isset($cpt['rewrite_slug']) ? '' : $cpt['rewrite_slug'];
    $cpt['query_slug'] = !isset($cpt['query_slug']) ? '' : $cpt['query_slug'];
    $cpt['rewrite_feeds'] = !isset($cpt['rewrite_feeds']) ? 'yes' : $cpt['rewrite_feeds'];
    $cpt['rewrite_pages'] = !isset($cpt['rewrite_pages']) ? 'yes' : $cpt['rewrite_pages'];
    $cpt['show_in_menu'] = !isset($cpt['show_in_menu']) ? 'yes' : $cpt['show_in_menu'];

    $caps = $labels = array();

    $rewrite = $cpt['rewrite'] == 'no' ? false : true;
    if ($rewrite) {
        $rewrite = array('slug' => $cpt['rewrite'] == 'yes_custom' ? $cpt['rewrite_slug'] : $cpt['name'],
                         'with_front' => $cpt['rewrite_front'] == 'yes',
                         'feeds' => $cpt['rewrite_feeds'] == 'yes',
                         'pages' => $cpt['rewrite_pages'] == 'yes');
    }

    $query_var = false;
    if ($cpt['query'] != 'no') {
        $query_var = true;
        if ($cpt['query'] == 'yes_custom' && $cpt['query_slug'] != '') {
            $query_var = $cpt['query_slug'];
        }
    }

    $archive = false;
    if ($cpt['archive'] != 'no') {
        $archive = true;
        if ($cpt['archive'] == 'yes_custom' && $cpt['archive_slug'] != '') {
            $archive = $cpt['archive_slug'];
        }
    }

    if (!isset($cpt['labels'])) {
        $labels = array('name' => $cpt['label'], 'singular_name' => $cpt['label_singular']);
    } else {
        $labels = $cpt['labels'];
    }

    if (isset($cpt['caps'])) {
        $caps = $cpt['caps'];
    }

    $cpt['public'] = $cpt['public'] == 'yes';
    $cpt['menu_position'] = !isset($cpt['menu_position']) ? '__auto__' : $cpt['menu_position'];
    $cpt['ui'] = !isset($cpt['ui']) ? $cpt['public'] : $cpt['ui'] == 'yes';
    $cpt['nav_menus'] = !isset($cpt['nav_menus']) ? $cpt['public'] : $cpt['nav_menus'] == 'yes';
    $cpt['can_export'] = !isset($cpt['can_export']) ? $cpt['public'] : $cpt['can_export'] == 'yes';
    $cpt['publicly_queryable'] = !isset($cpt['publicly_queryable']) ? $cpt['public'] : $cpt['publicly_queryable'] == 'yes';
    $cpt['exclude_from_search'] = !isset($cpt['exclude_from_search']) ? !$cpt['public'] : $cpt['exclude_from_search'] == 'yes';
    $cpt['show_in_admin_bar'] = !isset($cpt['show_in_admin_bar']) ? $cpt['show_in_menu'] == 'yes' : $cpt['show_in_admin_bar'] == 'yes';

    $options = array(
        'labels' => $labels,
        'publicly_queryable' => $cpt['publicly_queryable'],
        'exclude_from_search' => $cpt['exclude_from_search'],
        'capability_type' => $cpt['caps_type'],
        'hierarchical' => $cpt['hierarchy'] == 'yes',
        'public' => $cpt['public'],
        'rewrite' => $rewrite,
        'show_in_menu' => $cpt['show_in_menu'] == 'yes',
        'show_in_admin_bar' => $cpt['show_in_admin_bar'],
        'has_archive' => $archive,
        'query_var' => $query_var,
        'supports' => (array)$cpt['supports'],
        'taxonomies' => (array)$cpt['taxonomies'],
        'show_ui' => $cpt['ui'],
        'can_export' => $cpt['can_export'],
        'show_in_nav_menus' => $cpt['nav_menus'],
        '_edit_link' => $cpt['edit_link']
    );

    if (isset($cpt['menu_icon']) && $cpt['menu_icon'] != '') {
        $options['menu_icon'] = $cpt['menu_icon'];
    }

    if ($cpt['description'] != '') {
        $options['description'] = $cpt['description'];
    }

    if (!in_array($cpt['menu_position'], array('__auto__', '__block__'))) {
        $options['menu_position'] = intval($cpt['menu_position']);
    }

    if ($cpt['capabilites'] != 'type') {
        $options['map_meta_cap'] = true;
        $options['capabilities'] = array_values($caps);
    }

    return $options;
}

function gdtt_generate_custom_taxonomies_options($tax) {
    $rewrite = $query_var = true;

    if ($tax['rewrite'] == 'no') {
        $rewrite = false;
    } else {
        $tax['rewrite_hierarchy'] = !isset($tax['rewrite_hierarchy']) ? 'auto' : $tax['rewrite_hierarchy'];
        $tax['with_front'] = !isset($tax['rewrite_front']) ? 'yes' : $tax['rewrite_front'];

        $rewrite = array('hierarchical' => $tax['rewrite_hierarchy'] != 'no',
                         'with_front' => $tax['with_front'] == 'yes');

        if ($tax['rewrite'] == 'yes_name') {
            $rewrite['slug'] = $tax['name'];
        } else {
            $rewrite['slug'] = $tax['rewrite_custom'];
        }
    }

    if ($tax['query'] == 'no') {
        $query_var = false;
    } else if ($tax['query'] == 'yes_custom') {
        $query_var = $tax['query_custom'];
    }

    $tax['public'] = !isset($tax['public']) ? true : ($tax['public'] == 'yes');
    $tax['ui'] = !isset($tax['ui']) ? $tax['public'] : ($tax['ui'] == 'yes');
    $tax['nav_menus'] = !isset($tax['nav_menus']) ? $tax['public'] : $tax['nav_menus'] == 'yes';
    $tax['cloud'] = !isset($tax['cloud']) ? $tax['public'] : $tax['cloud'] == 'yes';

    $tax['show_admin_column'] = isset($tax['show_admin_column']) ? $tax['show_admin_column'] == 'yes' : false;

    if (isset($tax['special']) && is_array($tax['special']) && in_array('edit_column', $tax['special'])) {
        $tax['show_admin_column'] = true;
    }

    if (!isset($tax['labels'])) {
        $labels = array('name' => $tax['label'], 'singular_name' => $tax['label_singular']);
    } else {
        $labels = $tax['labels'];
        $labels['parent_item_colon'] = $labels['parent_item'].':';
    }

    if (!isset($tax['caps'])) {
        $caps = array();
    } else {
        $caps = $tax['caps'];
    }

    $options = array(
        'hierarchical' => $tax['hierarchy'] == 'yes',
        'rewrite' => $rewrite,
        'query_var' => $query_var,
        'public' => $tax['public'],
        'show_ui' => $tax['ui'],
        'show_tagcloud' => $tax['cloud'],
        'show_admin_column' => $tax['show_admin_column'],
        'labels' => $labels,
        'capabilities' => $caps,
        'show_in_nav_menus' => $tax['nav_menus']
    );

    if (isset($tax['sort']) && $tax['sort'] == 'yes') {
        $options['sort'] = true;
    }

    return $options;
}

function gdtt_custom_post_templates() {
    if (GDTAXTOOLS_WPV < 34) {
        $themes = get_themes();
        $theme = get_current_theme();
        $templates = $themes[$theme]['Template Files'];
    } else {
        $theme = wp_get_theme();
        $templates = $theme->get_files('php', 1, true);
    }

    $post_templates = array('__default__' => __("Default", "gd-taxonomies-tools"));

    $base = array(trailingslashit(get_template_directory()), trailingslashit(get_stylesheet_directory()));

    foreach ((array)$templates as $template) {
        $template = WP_CONTENT_DIR.str_replace(WP_CONTENT_DIR, '', $template);
        $basename = str_replace($base, '', $template);

        if (false !== strpos($basename, '/')) continue;

        $template_data = implode('', file( $template ));

        $name = '';
        if (preg_match( '|Post Template:(.*)$|mi', $template_data, $name)) {
            $name = _cleanup_header_comment($name[1]);
        }

        if (!empty($name)) {
            if(basename($template) != basename(__FILE__)) {
                $post_templates[$basename] = trim($name);
            }
        }
    }

    return $post_templates;
}

function gdtt_get_override_post_type($cpt_name, $post_type) {
    $cpt = array('active' => 'no', 'name' => $cpt_name, 'taxonomies' => $post_type->taxonomies,
        'caps' => (array)$post_type->cap, 'labels' => (array)$post_type->labels,
        'id' => -1, 'publicly_queryable' => $post_type->publicly_queryable ? 'yes' : 'no',
        'exclude_from_search' => $post_type->exclude_from_search ? 'yes' : 'no',
        'description' => isset($post_type->description) ? $post_type->description : '', 
        'caps_type' => $post_type->capability_type, 'capabilites' => 'type', 
        'hierarchy' => $post_type->hierarchical ? 'yes' : 'no', 'label' => $post_type->label,
        'public' => $post_type->public ? 'yes' : 'no', 'ui' => $post_type->show_ui ? 'yes' : 'no', 
        'rewrite' => 'no', 'rewrite_slug' => '', 'yourls_active_link' => 'no',
        'can_export' => $post_type->can_export ? 'yes' : 'no', 'edit_link' => $post_type->_edit_link, 
        'query' => '', 'query_slug' => '', 'archive' => 'yes', 'archive_slug' => '',
        'nav_menus' => $post_type->show_in_nav_menus ? 'yes' : 'no', 'source' => '',
        'supports' => gdtt_get_post_type_features($cpt_name), 'special' => array(),
        'intersections' => 'no', 'intersections_structure' => '', 'intersections_partial' => 'no',
        'intersections_baseless' => '', 'permalinks_active' => 'no', 'permalinks_structure' => '',
        'date_archives' => 'no', 'search_rewrite' => 'no', 'show_in_menu' => $post_type->show_in_menu,
        'show_in_admin_bar' => $post_type->show_in_admin_bar,
        'menu_icon' => is_null($post_type->menu_icon) ? '' : $post_type->menu_icon,
        'menu_position' => is_null($post_type->menu_position) ? '__auto__' : $post_type->menu_position
    );

    if ($post_type->rewrite !== false) {
        $cpt['rewrite'] = 'yes';

        $cpt['rewrite_front'] = $post_type->rewrite['with_front'];
        $cpt['rewrite_feeds'] = $post_type->rewrite['feeds'];
        $cpt['rewrite_pages'] = $post_type->rewrite['pages'];
        $cpt['rewrite_slug'] = $post_type->rewrite['slug'];
    }

    if ($post_type->query_var === true) {
        $cpt['query'] = 'yes';
    } else if ($post_type->query_var === false) {
        $cpt['query'] = 'yes';
    } else {
        $cpt['query'] = 'yes_custom';
        $cpt['query_slug'] = $post_type->query_var;
    }
    
    if ($post_type->has_archive === false) {
        $cpt['archive'] = 'no';
    } else if ($post_type->has_archive !== true) {
        $cpt['archive_slug'] = $post_type->has_archive;
    }

    return apply_filters('gdcpt_update_post_type', $cpt, $post_type);
}

function gdtt_get_override_taxonomy($tax_name, $tax_type) {
    $tax = array('hierarchy' => $tax_type->hierarchical ? 'yes' : 'no',
        'description' => isset($tax_type->description) ? $tax_type->description : '', 
        'rewrite_front' => 'yes', 'caps_type' => 'categories', 'caps' => (array)$tax_type->cap, 
        'show_admin_column' => isset($tax_type->show_admin_column) && $tax_type->show_admin_column ? 'yes' : 'no', 
        'labels' => (array)$tax_type->labels, 'domain' => $tax_type->object_type, 
        'label' => $tax_type->label, 'rewrite_hierarchy' => 'auto',
        'active' => 'no', 'nav_menus' => $tax_type->show_in_nav_menus ? 'yes' : 'no',
        'public' => $tax_type->public ? 'yes' : 'no', 'rewrite' => 'yes',
        'sort' => isset($tax_type->sort) && $tax_type->sort === true ? 'yes' : 'no',
        'ui' => $tax_type->show_ui ? 'yes' : 'no', 'rewrite_custom' => '',
        'cloud' => $tax_type->show_tagcloud ? 'yes' : 'no', 'id' => -1,
        'query' => 'yes', 'query_custom' => '', 'name' => $tax_name, 
        'special' => array(), 'metabox' => 'auto', 'index_normal' => 'no',
        'index_intersect' => 'no', 'source' => ''
    );

    if (is_bool($tax_type->query_var)) {
        $tax['query'] = $tax_type->query_var ? 'yes' : 'no';
    } else {
        $tax['query'] = 'yes_custom';
        $tax['query_custom'] = $tax_type->query_var;
    }

    if (is_bool($tax_type->rewrite)) {
        $tax['rewrite'] = $tax_type->rewrite ? 'yes' : 'no';
    } else {
        $tax['rewrite'] = 'yes_custom';
        $tax['rewrite_custom'] = $tax_type->rewrite['slug'];
    }

    return apply_filters('gdcpt_update_taxonomy', $tax, $tax_type);
}

function gdtt_update_custom_fields($assoc_update = true) {
    global $gdtt;

    foreach ($gdtt->m['fields'] as $key => &$property) {
        if ($property['type'] == 'radio') {
            $property['type'] = 'select';
            $property['selection'] = 'radio';
        }

        if ($property['type'] == 'checkbox') {
            $property['type'] = 'select';
            $property['selection'] = 'checkbox';
        }

        if ($property['type'] == 'select' && $assoc_update) {
            $values = array();

            foreach ($property['assoc_values'] as $key => $value) {
                $values[] = $key.'|'.$value;
            }

            $property['assoc_values'] = $values;

            if ($property['values'] == '') {
                $property['values'] = array();
            } else if (is_string($property['values'])) {
                $property['values'] = gdr2_split_textarea($property['values']);
            }
        }
    }
}

function gdtt_insert_post_type($cpt) {
    global $gdtt;

    $return_id = null;

    if ($cpt['id'] == 0) {
        $gdtt->o['cpt_internal'] = (int)$gdtt->o['cpt_internal'] + 1;
        $cpt['id'] = $gdtt->o['cpt_internal'];
        $gdtt->p[] = $cpt;

        update_option('gd-taxonomy-tools-cpt', $gdtt->p);

        $gdtt->o['cpt_reorder'][] = $cpt['id'];
        $return_id = $cpt['id'];
    } else if ($cpt['id'] == -1) {
        $gdtt->nn_p['full'][$cpt['name']] = $cpt;
        $gdtt->nn_p['status'][$cpt['name']] = $cpt['active'];
        $gdtt->nn_p['simple'][$cpt['name']] = array('active' => $cpt['active'],
            'name' => $cpt['name'], 'special' => $cpt['special'],
            'taxonomies' => $cpt['taxonomies'], 'supports' => $cpt['supports']
        );

        update_option('gd-taxonomy-tools-nn-cpt', $gdtt->nn_p);
    } else {
        $id = gdtt_find_custompost_pos($cpt['id']);

        if ($id > -1) {
            $gdtt->p[$id] = $cpt;

            update_option('gd-taxonomy-tools-cpt', $gdtt->p);

            $return_id = $cpt['id'];
        }
    }

    $gdtt->o['force_rules_flush'] = 1;
    update_option('gd-taxonomy-tools', $gdtt->o);

    return $return_id;
}

function gdtt_insert_taxonomy($tax) {
    global $gdtt;

    $return_id = null;

    if ($tax['id'] == 0) {
        $gdtt->o['tax_internal'] = (int)$gdtt->o['tax_internal'] + 1;
        $tax['id'] = $gdtt->o['tax_internal'];
        $gdtt->t[] = $tax;
        gdt_dump($tax);
        update_option('gd-taxonomy-tools-tax', $gdtt->t);

        $gdtt->o['tax_reorder'][] = $tax['id'];
        $return_id = $tax['id'];
    } else if ($tax['id'] == -1) {
        $gdtt->nn_t['full'][$tax['name']] = $tax;
        $gdtt->nn_t['status'][$tax['name']] = $tax['active'];
        $gdtt->nn_t['simple'][$tax['name']] = array(
            'active' => $tax['active'], 'metabox' => $tax['metabox'],
            'name' => $tax['name'], 'special' => $tax['special'], 
            'domain' => $tax['domain']);

        update_option('gd-taxonomy-tools-nn-tax', $gdtt->nn_t);
    } else {
        $id = gdtt_find_taxonomy_pos($tax['id']);

        if ($id > -1) {
            $gdtt->t[$id] = $tax;

            update_option('gd-taxonomy-tools-tax', $gdtt->t);

            $return_id = $tax['id'];
        }
    }

    $gdtt->o['force_rules_flush'] = 1;
    update_option('gd-taxonomy-tools', $gdtt->o);

    return $return_id;
}

function gdtt_is_registered_post_type($name) {
    global $gdtt;

    foreach ($gdtt->p as $cpt) {
        if ($cpt['name'] == $name) {
            return true;
        }
    }

    return false;
}

function gdtt_is_registered_taxonomy($name) {
    global $gdtt;

    foreach ($gdtt->t as $tax) {
        if ($tax['name'] == $name) {
            return true;
        }
    }

    return false;
}

function gdtt_find_custompost_pos($id) {
    global $gdtt;
    $found = -1;

    for ($i = 0; $i < count($gdtt->p); $i++) {
        if (intval($gdtt->p[$i]['id']) == $id) {
            $found = $i;
            break;
        }
    }

    return $found;
}

function gdtt_find_taxonomy_pos($id) {
    global $gdtt;
    $found = -1;

    for ($i = 0; $i < count($gdtt->t); $i++) {
        if (intval($gdtt->t[$i]['id']) == $id) {
            $found = $i;
            break;
        }
    }

    return $found;
}

?>