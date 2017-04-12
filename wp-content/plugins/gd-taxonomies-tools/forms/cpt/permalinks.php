<?php

if (!$cpt_built_in) {
    $list_of_taxonomies = array('' => __("No baseless taxonomy", "gd-taxonomies-tools"));
    $raw_taxonomies = gdtt_get_public_taxonomies(true);

    foreach ($raw_taxonomies as $tax => $obj) {
        $list_of_taxonomies[$tax] = $obj->labels->name;
    }

    $e['rewriting']['permalinks'] = array(
            new gdr2_Setting_Element('cpt', '[permalinks_active]', 'rewriting', 'permalinks', __("Custom Permalinks", "gd-taxonomies-tools"), __("Activate use of custom structure for the single post permalink. Depending on the rules you made, you may end up with the conflict with some other rules and that will result in 404 page. Test different setups to make sure that there is no conflict.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt['permalinks_active'] == 'yes'),
            new gdr2_Setting_Element('cpt', '[permalinks_structure]', 'rewriting', 'permalinks', __("Permalinks Structure", "gd-taxonomies-tools"), __("Link structure for single posts for this post type. Use only taxonomies you have set for post type, or they will be replaced by a dash.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $cpt['permalinks_structure'])
        );
    $e['rewriting']['archvlinks'] = array(
            new gdr2_Setting_Element('cpt', "[date_archives]", "rewriting", "archvlinks", __("Date based archives", "gd-taxonomies-tools"), __("Generate rewrite structures for post type archives for dates.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["date_archives"] == "yes"),
            new gdr2_Setting_Element('cpt', "[intersections]", "rewriting", "archvlinks", __("Archive taxonomy intersection", "gd-taxonomies-tools"), __("Allow for intersection by combining custom post type and taxonomies. Taxonomy name and term will be added to the post type archive to filter posts by term.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $cpt['intersections'], 'array', array('no' => __("No intersections", "gd-taxonomies-tools"), 'yes' => __("Simple intersections", "gd-taxonomies-tools"), 'adv' => __("Advanced intersections", "gd-taxonomies-tools"), "max" => __("Simple and Advanced intersections", "gd-taxonomies-tools"))),
            new gdr2_Setting_Element('cpt', "[intersections_structure]", "rewriting", "archvlinks", __("Permalinks Structure", "gd-taxonomies-tools"), __("Link structure for archives for this post type. Use only taxonomies you have set for post type, or you will get no results for display. Use only taxonmies separated by slash characters.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $cpt["intersections_structure"]),
            new gdr2_Setting_Element('cpt', "[intersections_partial]", "rewriting", "archvlinks", __("Generate partial intersections", "gd-taxonomies-tools"), __("If you set to use 2 or more taxonomies for advanced intersection, with this option plugin will generate rules with partial taxonomies. For 4 taxonomies in intersection, plugin with generate rules for 1, 2 and 3 also. Be careful with this option, it can generate petentially conflicting rules.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt['intersections_partial'] == 'yes'),
            new gdr2_Setting_Element('cpt', "[intersections_baseless]", "rewriting", "archvlinks", __("Taxonomy for baseless intersection", "gd-taxonomies-tools"), __("Simple intersection will use taxonomy slug to form the archive link. But, for one taxonomy (and only one), you can leave out the taxonomy slug from URL. Also, rewrite slug for archive and single posts for this post type must be different!", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $cpt['intersections_baseless'], 'array', $list_of_taxonomies)
        );
}

function archvlinks_structure_info() {
    global $taxonomies_codes, $post_type_name;
    echo '<div class="gdr2-structure-info">';
    if ($post_type_name == '') {
        _e("Save post type before you can set up this field.", "gd-taxonomies-tools"); 
    } else {
        echo '<u>';
        _e("You can use following URL elements", "gd-taxonomies-tools");
        echo ':</u><br/><span>'.join(", ", $taxonomies_codes).'</span>';
    }
    echo '</div>';
}

function permalinks_structure_info_header() {
    global $post_type_name;

    if ($post_type_name != '') {
        $list = array(
            __("Default", "gd-taxonomies-tools") => array(
                $post_type_name.'/%'.$post_type_name.'%/', 
                site_url($post_type_name.'/sample-post/'),
            ),
            __("Year and Name", "gd-taxonomies-tools") => array(
                $post_type_name.'/%year%/%'.$post_type_name.'%/', 
                site_url($post_type_name.'/2012/sample-post/'),
            ),
            __("Month and Name", "gd-taxonomies-tools") => array(
                $post_type_name.'/%year%/%monthnum%/%'.$post_type_name.'%/', 
                site_url($post_type_name.'/2012/04/sample-post/'),
            ),
            __("Numeric", "gd-taxonomies-tools") => array(
                $post_type_name.'/%'.$post_type_name.'_id%', 
                site_url($post_type_name.'/123'),
            ),
            __("Numeric and Name", "gd-taxonomies-tools") => array(
                $post_type_name.'/%'.$post_type_name.'_id%_%'.$post_type_name.'%/', 
                site_url($post_type_name.'/123_sample-post/'),
            )
        );
        echo '<div class="gdr2-permalinks-examples">';
        echo '<p id="gdtt-internal-toggle" class="toggle-arrow">'.__("List of permalinks examples", "gd-taxonomies-tools").'</p>';
        echo '<div style="display: none">';
        echo '<table class="form-table" style="width: 515px;">';
        echo '<tbody>';

        foreach ($list as $title => $data) {
            echo '<tr><td style="padding-right: 6px;">';
            echo '<label><input type="radio" class="tog" value="'.$data[0].'" name="permalink_examples"> '.$title.'</label>';
            echo '</td><td><code>'.$data[1].'</code>';
            echo '</td></tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div></div>';
    }
}

function permalinks_structure_info() {
    global $taxonomies_codes, $post_type_name, $gdtt;

    echo '<div class="gdr2-structure-info">';
    if ($post_type_name == '') {
        _e("Save post type before you can set up this field.", "gd-taxonomies-tools"); 
    } else {
        $cf = array();
        $pt = array('%'.$post_type_name.'%', '%'.$post_type_name.'_id%');

        foreach ($gdtt->m['fields'] as $field => $obj) {
            $_field = (array)$obj;
            if ($_field['type'] == 'rewrite') {
                $cf[] = '%cf_'.$field.'%';
            }
        }

        echo '<u>'.__("You can use following URL elements", "gd-taxonomies-tools").':</u><br/><span>';
        echo '<strong>'.__("Post Type", "gd-taxonomies-tools").':</strong> '.join(', ', $pt).'<br/>';
        echo '<strong>'.__("Date", "gd-taxonomies-tools").':</strong> %year%, %monthnum%, %day%<br/>';
        echo '<strong>'.__("Time", "gd-taxonomies-tools").':</strong> %hour%, %minute%, %second%<br/>';
        echo '<strong>'.__("Taxonomies", "gd-taxonomies-tools").':</strong> '.join(', ', $taxonomies_codes).'<br/>';
        echo '<strong>'.__("Other Elements", "gd-taxonomies-tools").':</strong> %author%';

        if (!empty($cf)) {
            echo '<br/><strong>'.__("Custom Fields", "gd-taxonomies-tools").':</strong> '.join(', ', $cf);
        }

        echo '</span>';
    }
    echo '</div>';
}

add_action('gdr2_settings_render_header_rewriting_permalinks_cpt_permalinks_structure', 'permalinks_structure_info_header');
add_action('gdr2_settings_render_footer_rewriting_permalinks_cpt_permalinks_structure', 'permalinks_structure_info');
add_action('gdr2_settings_render_footer_rewriting_archvlinks_cpt_intersections_structure', 'archvlinks_structure_info');

?>