<?php

if (!defined('ABSPATH')) exit;

/**
 * Get value for the plugin setting.
 *
 * @param string $name setting name
 * @return mixed setting value
 */
function gdtt_get($name) {
    global $gdtt;
    return $gdtt->get($name);
}

/**
 * Get value for the plugin module setting.
 *
 * @param string $module module name
 * @param string $name setting name
 * @return mixed setting value
 */
function gdtt_mod($module, $name) {
    global $gdtt;
    return $gdtt->mod_get($module, $name);
}

/**
 * Get all values for the plugin module settings.
 *
 * @param string $module module name
 * @return mixed setting value
 */
function gdtt_mod_all($module) {
    global $gdtt;
    return $gdtt->mod_get_all($module);
}

/**
 * Check if the special feature is set for post type or taxonomy.
 *
 * @param string $name post type or taxonomy name
 * @param string $feature name of the feature to check
 * @param string $type post type (cpt) or taxonomy (tax)
 * @return bool true if the feature is set, false if it is not
 */
function gdtt_sf($name, $feature, $type = 'cpt') {
    global $gdtt;
    return $gdtt->get_sf($name, $feature, $type);
}

/**
 * Get list of post types or taxonomies that have specified special feature 
 * applied.
 *
 * @param string $feature name of the feature to check
 * @param string $type post type (cpt) or taxonomy (tax)
 * @return array list of post types or taxonomies with specified features
 */
function gdtt_sf_list($feature, $type = 'cpt') {
    global $gdtt;
    return $gdtt->get_sf_list($feature, $type);
}

/**
 * List of custom post types names registered through the plugin.
 *
 * @return array with names of registered post types
 */
function gdtt_registered_post_types() {
    global $gdtt;
    return $gdtt->active['cpt'];
}

/**
 * List of custom taxonomies names registered through the plugin.
 *
 * @return array with names of registered taxonomies
 */
function gdtt_registered_taxonomies() {
    global $gdtt;
    return $gdtt->active['tax'];
}

/**
 * Check if the current page is forum, topic or other bbPress page.
 *
 * @return bool true if the current page is the forum related
 */
function gdtt_is_bbpress() {
    if (function_exists('bbp_get_forum_id')) {
        return bbp_get_forum_id() > 0;
    } else {
        return false;
    }
}

/**
 * Return post type based on post_type parameter in wp_query.
 *
 * @return object post type
 */
function gdtt_post_type_from_wpquery() {
    $post_type = get_query_var('post_type');
    return get_post_type_object($post_type);
}

/**
 * Get feed link for custom post type archive page.
 *
 * @param object $post_type object for the post type
 * @param object $feed replacement feed object
 * @return string feed link
 */
function gdtt_cpt_feed_link($post_type, $feed = '') {
    if (empty($feed)) {
        $feed = get_default_feed();
    }

    $permalink_structure = get_option('permalink_structure');

    if ('' == $permalink_structure) {
        $link = home_url("?feed=$feed&amp;post_type=".$post_type->name);
    } else {
        $link = home_url($post_type->rewrite['slug']);
        $feed_link = ($feed == get_default_feed()) ? 'feed' : "feed/$feed";
        $link = trailingslashit($link).user_trailingslashit($feed_link, 'feed');
    }

    return $link;
}

/**
 * Is the current page archive for custom post type.
 *
 * @param string $post_type post type name
 * @return bool true if page is custom post type archive
 */
function gdtt_is_archive_intersection($post_type = '') {
    global $wp_query;

    if (isset($wp_query->is_cpt_archive_intersection) && $wp_query->is_cpt_archive_intersection) {
        if (empty($post_type) || $post_type == get_query_var('post_type')) {
            return true;
        }
    }

    return false;
}

/**
 * Is the current page archive for custom post type.
 *
 * @param string $post_type post type name
 * @return bool true if page is custom post type archive
 */
function gdtt_is_archive_custom_post_type($post_type = '') {
    global $wp_query;

    if (!isset($wp_query->is_post_type_archive) || !$wp_query->is_post_type_archive) {
        return false;
    }

    if (empty($post_type) || $post_type == get_query_var('post_type')) {
        return true;
    }

    return false;
}

/**
 * Update taxonomies cache for the custom post types for the current query.
 */
function gdtt_custom_post_types_cache() {
    global $wp_query;

    $post_ids = array();
    for ($i = 0; $i < count($wp_query->posts); $i++) {
        $post_ids[] = $wp_query->posts[$i]->ID;
    }

    if (!empty($post_ids)) {
        update_object_term_cache($post_ids, get_query_var('post_type'));
    }
}

/**
 * Return only public custom post types, with or without defaults.
 *
 * @param bool $with_defaults true to include default types, false to exclude them
 * @return array list of custom post types
 */
function gdtt_get_public_post_types($with_defaults = false) {
    $options = array('public' => true);

    if (!$with_defaults) {
        $options['_builtin'] = false;
    }

    return get_post_types($options, 'objects');
}

/**
 * Return only public custom taxonomies, with or without defaults.
 *
 * @param bool $with_defaults true to include default taxonomies, false to exclude them
 * @return array list of custom taxonomies
 */
function gdtt_get_public_taxonomies($with_defaults = false) {
    $options = array('public' => true);

    if (!$with_defaults) {
        $options['_builtin'] = false;
    }

    return get_taxonomies($options, 'objects');
}

/**
 * Get the list of post types registered for a taxonomies listed.
 *
 * @param type $taxonomies one or more taxonomies
 * @param type $return what to return: name, object, label or print
 * @return array list of names or objects 
 */
function gdtt_get_post_types_for_taxonomies($taxonomies = array(), $return = 'name') {
    $post_types = array();

    if (is_string($taxonomies)) {
        $taxonomies = (array)$taxonomies;
    } else if (!is_array($taxonomies) || is_null($taxonomies)) {
        $taxonomies = array();
    }

    foreach ($taxonomies as $taxonmy) {
        $tax = get_taxonomy($taxonmy);
        $ptypes = $tax->object_type;
        
        foreach ($ptypes as $pt) {
            if ($pt != '') {
                $ptype = get_post_type_object($pt);

                if (is_object($ptype)) {
                    switch ($return) {
                        default:
                        case 'name':
                            $post_types[] = $ptype->name;
                            break;
                        case 'label':
                            $post_types[] = $ptype->label;
                            break;
                        case 'object':
                            $post_types[] = $ptype;
                            break;
                        case 'print':
                            $post_types[] = $ptype->label.' ('.$ptype->name.')';
                            break;
                    }
                }
            }
        }
    }

    return $post_types;
}

/**
 * Get list of taxonomies for any post type. You can specify one or more types
 * in array.
 *
 * @param string|array $post_types one or more post types to match
 * @param type $return what to return: name, object, label or print
 * @return array list of names or objects 
 */
function gdtt_get_taxonomies_for_post_types($post_types = array(), $return = 'name') {
    global $wp_taxonomies;
    $taxonomies = array();

    if (is_string($post_types)) {
        $post_types = (array)$post_types;
    } else if (!is_array($post_types) || is_null($post_types)) {
        $post_types = array();
    }

    foreach ((array)$wp_taxonomies as $taxonomy) {
        if (array_intersect($post_types, (array)$taxonomy->object_type)) {
            switch ($return) {
                default:
                case 'name':
                    $taxonomies[] = $taxonomy->name;
                    break;
                case 'label':
                    $taxonomies[] = $taxonomy->label;
                    break;
                case 'object':
                    $taxonomies[] = $taxonomy;
                    break;
                case 'print':
                    $taxonomies[] = $taxonomy->label.' ('.$taxonomy->name.')';
                    break;
            }
        }
    }

    return $taxonomies;
}

/**
 * Get all registered features for post type.
 *
 * @param string $post_type name of post type
 * @return array list of supported features
 */
function gdtt_get_post_type_features($post_type) {
    global $_wp_post_type_features;

    $features = array();

    foreach ($_wp_post_type_features[$post_type] as $f => $active) {
        if ($active) $features[] = $f;
    }

    return $features;
}

/**
 * Get current term object for the query.
 *
 * @return null|object current term object for the archive page 
 */
function gdtt_get_current_term() {
    global $wp_query;

    if (!isset($wp_query)) {
        return null;
    }

    if ($wp_query->is_tax || $wp_query->is_category || $wp_query->is_tag) {
        $queried_object = $wp_query->get_queried_object();

        if (isset($queried_object->term_id)) {
            return $queried_object;
        } else {
            return null;
        }
    } else {
        return null;
    }
}

/** Get current post type for the active query.
 *
 * @return object|null post type or null if post type is not found
 */
function gdtt_get_current_post_type() {
    global $wp_query;

    if (!isset($wp_query)) {
        return null;
    }

    if (is_post_type_archive()) {
        return $wp_query->get_queried_object();
    } else if (is_singular()) {
        $post_obj = $wp_query->get_queried_object();
        return get_post_type_object($post_obj->post_type);
    } else {
        return null;
    }
}

/**
 * Check if the current page is term archive.
 *
 * @param array|string $term term name, term id or term slug to check.
 * @param array|string $taxonomy $taxonomy where the term belongs.
 * @return bool 
 */
function gdtt_is_term($term = '', $taxonomy = '') {
    global $wp_query;

    if (!isset($wp_query)) {
        _doing_it_wrong(__FUNCTION__, __("Conditional query tags do not work before the query is run. Before then, they always return false.", "gd-taxonomies-tools"), '3.1');
        return false;
    }

    global $wp_taxonomies;
    if (!$wp_query->is_tax) return false;

    $queried_object = $wp_query->get_queried_object();
    $taxonomy = (array)$taxonomy;
    $term_array = (array)$term;
    $tax_array = array_intersect(array_keys($wp_taxonomies), $taxonomy);

    $is_tax = empty($taxonomy) ? true : isset($queried_object->taxonomy) && count($tax_array) && in_array($queried_object->taxonomy, $tax_array);
    if ($is_tax) {
        return isset($queried_object->term_id) && count(array_intersect(array($queried_object->term_id, $queried_object->name, $queried_object->slug), $term_array));
    } else {
        return false;
    }
}

/**
 * Check if the post type has archives enabled.
 *
 * @param type $post_type name for the post type
 * @return bool|null true if the post type has archives enabled, null if post type is not valid
 */
function gdtt_has_archives($post_type) {
    $post_type = get_post_type_object($post_type);

    if (is_null($post_type)) {
        return false;
    }

    return $post_type->has_archive === false ? false : true;
}

/**
 * Display the debug info for current page request, matched rule, matched
 * rewrite query and loaded template commented out.
 */
function gdtt_debug_page_request_comment() {
    global $wp, $template;
    echo '<!-- '.__("Request", "gd-taxonomies-tools").': ';
    echo empty($wp->request) ? __("None", "gd-taxonomies-tools") : esc_html($wp->request);
    echo '-->'.GDR2_EOL;
    echo '<!-- '.__("Matched Rewrite Rule", "gd-taxonomies-tools").': ';
    echo empty($wp->matched_rule) ? __("None", "gd-taxonomies-tools") : esc_html($wp->matched_rule);
    echo '-->'.GDR2_EOL;
    echo '<!-- '.__("Matched Rewrite Query", "gd-taxonomies-tools").': ';
    echo empty($wp->matched_query) ? __("None", "gd-taxonomies-tools") : esc_html($wp->matched_query);
    echo '-->'.GDR2_EOL;
    echo '<!-- '.__("Loaded Template", "gd-taxonomies-tools").': ';
    echo basename($template);
    echo '-->'.GDR2_EOL;
}

/**
 * Display the debug info for current page request, matched rule, matched
 * rewrite query and loaded template.
 */
function gdtt_debug_page_request() {
    global $wp, $template;
    echo '<div class="gdtt-debug-page-rquest">';
    echo '<h5>'.__("Request", "gd-taxonomies-tools").'</h5>';
    echo empty($wp->request) ? __("None", "gd-taxonomies-tools") : esc_html($wp->request);
    echo '<h5>'.__("Matched Rewrite Rule", "gd-taxonomies-tools").'</h5>';
    echo empty($wp->matched_rule) ? __("None", "gd-taxonomies-tools") : esc_html($wp->matched_rule);
    echo '<h5>'.__("Matched Rewrite Query", "gd-taxonomies-tools").'</h5>';
    echo empty($wp->matched_query) ? __("None", "gd-taxonomies-tools") : esc_html($wp->matched_query);
    echo '<h5>'.__("Loaded Template", "gd-taxonomies-tools").'</h5>';
    echo basename($template);
    echo '</div>';
}

/**
 * Display list of all reqistered rewrite rules used by WordPress rewrite engine.
 */
function gdtt_debug_rewrite_rules() {
    global $wp_rewrite;
    echo '<div class="gdtt-debug-page-request">';

    if (!empty($wp_rewrite->rules)) {
        echo '<h5>'.__("Rewrite Rules", "gd-taxonomies-tools").'</h5>';
        echo '<table class="widefat"><thead><tr>';
        echo '<td>'.__("Rule", "gd-taxonomies-tools").'</td>';
        echo '<td>'.__("Rewrite", "gd-taxonomies-tools").'</td>';
        echo '</tr></thead><tbody>';

        foreach ($wp_rewrite->rules as $name => $value) {
            echo '<tr><td>'.$name.'</td><td>'.$value.'</td></tr>';
        }

        echo '</tbody></table>';
    } else {
        _e("No rules defined", "gd-taxonomies-tools");
    }

    echo '</div>';
}

?>