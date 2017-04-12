<?php

if (!defined('ABSPATH')) exit;

/**
 * Get meta box object for code.
 *
 * @param string $meta_box_code code for the meta box
 * @return array|null meta box object if found
 */
function gdtt_get_meta_box($meta_box_code) {
    global $gdtt;

    if (isset($gdtt->m['boxes'][$meta_box_code])) {
        return (array)$gdtt->m['boxes'][$meta_box_code];
    } else {
        return null;
    }
}

/**
 * Get meta box group object for code.
 *
 * @param string $group_code code for the meta box group
 * @return array|null meta box group object if found
 */
function gdtt_get_meta_box_group($group_code) {
    global $gdtt;

    if (isset($gdtt->m['groups'][$group_code])) {
        return (array)$gdtt->m['groups'][$group_code];
    } else {
        return null;
    }
}

/**
 * Get values for all fields in meta box for a post.
 *
 * @param string $meta_box_code code for the meta box
 * @param int $post_id post ID
 * @return array|null values for the meta box fields
 */
function gdtt_get_meta_box_values($meta_box_code, $post_id = 0) {
    $box = gdtt_get_meta_box($meta_box_code);

    if (!is_null($box)) {
        if ($post_id == 0) {
            global $post;
            $post_id = $post->ID;
        }

        if ($post_id > 0) {
            global $gdtt_fields;
            return $gdtt_fields->get_custom_fields_values($box->fields, $post_id);
        }
    }

    return null;
}

/**
 * Get the taxonomy on the taxonomy term page.
 *
 * @return object Taxonomy or null if not on taxonomy term page
 */
function gdtt_get_taxonomy() {
    if (!is_tax()) return null;
    return get_taxonomy(get_query_var("taxonomy"));
}

/**
 * Get the term on the taxonomy term page.
 *
 * @return object Term or null if not on taxonomy term page
 */
function gdtt_get_term() {
    if (!is_tax()) return null;
    return get_term_by("slug", get_query_var("term"), get_query_var("taxonomy"));
}

/**
 * Get title for the term.
 *
 * @param bool $with_tax Inlcude taxonomy label as prefix
 * @return string Term title
 */
function gdtt_get_term_title($with_tax = false) {
    if (!is_tax()) return "";
    $term = gdtt_get_term();
    if ($with_tax) {
        $tax = gdtt_get_taxonomy();
        return $tax->label.": ".$term->name;
    } else return $term->name;
}

/**
 * Get description for the term.
 *
 * @return string Term description
 */
function gdtt_get_term_description() {
    if (!is_tax()) return "";
    $term = gdtt_get_term();
    return $term->description;
}

/**
 * Display term title.
 */
function gdtt_term_title() {
    echo gdtt_get_term_title();
}

/**
 * Display term description.
 */
function gdtt_term_description() {
    echo gdtt_get_term_description();
}

/**
 * Check if the current page is post type archive. If the post types names are 
 * specificed, function will check against that list. This function works even 
 * with new intersection archives.
 *
 * @param mixed $post_types post type name or names to check for
 * @return bool true if the archive is for post type, false if it is not
 */
function gdtt_is_post_type_archive($post_types = array()) {
    global $wp_query;

    if (empty($post_types) || !$wp_query->is_post_type_archive) {
        return (bool)$wp_query->is_post_type_archive;
    }

    if ($wp_query->is_post_type_archive) {
        return in_array($wp_query->query['post_type'], (array)$post_types);
    } else {
        return false;
    }
}

/**
 * Check if the current page is taxonomy terms index.
 * 
 * @param string $taxonomy check for specific taxonomy
 * @return boolean true if the page is terms index, fasle if it is not
 */
function gdtt_is_taxonomy_index($taxonomy = '') {
    global $wp_query;

    if ($wp_query->is_cpt_taxonomy_index) {
        return $taxonomy == '' || get_query_var('taxindex') == $taxonomy;
    } else {
        return false;
    }
}

/**
 * Check if the intersections are enabled for specified post type.
 *
 * @param string $post_type custom post type name
 * @return bool true if the intersections are enabled
 */
function gdtt_has_post_type_intersections($post_type) {
    global $gdtt;

    if (is_array($gdtt->sf['cpt'][$post_type])) {
        return in_array('intersections', $gdtt->sf['cpt'][$post_type]);
    } else {
        return false;
    }
}

/**
 * Get date based archive for post type. If you need month archive, year 
 * parameter must be used. If you need day archive, both month and year must be
 * used.
 *
 * @param string $post_type custom post type name
 * @param int $year year for the archive link
 * @param int $month month for the archive link
 * @param int $day day for the archive link
 * @return string|WP_Error HTML link to post type date archive.
 */
function gdtt_get_datearchive_link($post_type, $year = 0, $month = 0, $day = 0) {
    $url = get_post_type_archive_link($post_type);

    if (get_option('permalink_structure')) {
        $url = trailingslashit($url);

        if ($year > 0) {
            $url.= $year.'/';
            if ($month > 0) {
                $url.= $month.'/';
                if ($day > 0) {
                    $url.= $day.'/';
                }
            }
        }
    } else {
        if ($year > 0) {
            $url = add_query_arg('year', $year, $url);
            if ($month > 0) {
                $url = add_query_arg('monthnum', $month, $url);
                if ($day > 0) {
                    $url = add_query_arg('day', $day, $url);
                }
            }
        }
    }

    return $url;
}

/**
 * Get URL for the archive intersection of post type and taxonomy term. Make sure that taxonomy belongs to specified post type.
 *
 * @param string $post_type custom post type name
 * @param string $taxonomy name for the taxonomy term belongs to
 * @param object|int|string $term term name, id or object
 * @return string|WP_Error HTML link to taxonomy term - post type archive intersection.
 */
function gdtt_get_intersection_link($post_type, $taxonomy, $term) {
    if (!is_object($term)) {
        if (is_int($term)) {
            $term = &get_term($term, $taxonomy);
        } else {
            $term = &get_term_by('slug', $term, $taxonomy);
        }
    }

    if (!is_object($term)) {
        $term = new WP_Error('invalid_term', __("Empty Term", "gd-taxonomies-tools"));
    }

    if (is_wp_error($term)) {
        return $term;
    }

    $url = get_post_type_archive_link($post_type);
    if ($url === false || !gdtt_has_post_type_intersections($post_type)) {
        return get_term_link($term, $taxonomy);
    } else {
        if (get_option('permalink_structure')) {
            global $gdtt;

            $slug = '';

            if ($gdtt->get_baseless_taxonomy($post_type) != $taxonomy) {
                $tax = get_taxonomy($taxonomy);
                $slug = isset($tax->rewrite['slug']) ? $tax->rewrite['slug'] : '';
                $slug = end(explode('/', $slug)).'/';
            }

            return trailingslashit($url).$slug.$term->slug.'/';
        } else {
            return add_query_arg($taxonomy, $term->slug, $url);
        }
    }
}

/**
 * Get URL for advanced archive intersection of post type and taxonomy terms. Terms based on the advanced archive permalink setting.
 *
 * @param string $post_type custom post type name
 * @param array $terms terms with name, id or object
 * @return string|WP_Error HTML link to taxonomy term - post type archive intersection.
 */
function gdtt_get_advanced_intersection_link($post_type, $terms) {
    global $gdtt;

    $url = get_post_type_archive_link($post_type);
    $rule = isset($gdtt->sf['intersections'][$post_type]) ? $gdtt->sf['intersections'][$post_type] : '';

    if ($rule == '') {
        return $url;
    }

    $error = false;
    foreach ($terms as $taxonomy => $term) {
        if (is_int($term)) {
            $terms[$taxonomy] = &get_term($term, $taxonomy);
        } else {
            $terms[$taxonomy] = &get_term_by('slug', $term, $taxonomy);
        }

        if (!is_object($terms[$taxonomy])) {
            $terms[$taxonomy] = new WP_Error('invalid_term', __("Empty Term", "gd-taxonomies-tools"));
        }

        if (is_wp_error($terms[$taxonomy])) {
            $error = true;
        }
    }

    if ($error) {
        return $terms;
    }

    $parts = explode('/', trim($rule, '/'));
    $elements = array();

    foreach ($parts as $part) {
        $elements[] = trim($part, '%');
    }

    if (get_option('permalink_structure')) {
        $url = trailingslashit($url);

        foreach ($elements as $tax) {
            $url.= $terms[$tax]->slug.'/';
        }
    } else {
        foreach ($elements as $tax) {
            $url = add_query_arg($tax, $terms[$tax]->slug, $url);
        }
    }

    return $url;
}

/**
 * Retrieve a post's terms as a list with specified format.
 *
 * @param int $id Post ID.
 * @param string $taxonomy Taxonomy name.
 * @param string $post_type Optional. Post type name.
 * @param string $before Optional. Before list.
 * @param string $sep Optional. Separate items using this.
 * @param string $after Optional. After list.
 * @return string
 */
function gdtt_get_the_term_list($id = 0, $taxonomy = 'category', $post_type = "", $before = '', $sep = '', $after = '') {
    $terms = get_the_terms($id, $taxonomy);

    if (is_wp_error($terms)) return $terms;
    if (empty($terms)) return false;

    foreach ($terms as $term) {
        if ($post_type == '') {
            $link = get_term_link($term, $taxonomy);
        } else {
            $link = gdtt_get_intersection_link($post_type, $taxonomy, $term);
        }
        if (is_wp_error($link)) return $link;
        $term_links[] = '<a href="'.$link.'" rel="tag">'.$term->name.'</a>';
    }

    $term_links = apply_filters("term_links-$taxonomy", $term_links);
    return $before.join($sep, $term_links).$after;
}

/**
 * Display the terms in a list for taxonomy with optional post type set.
 *
 * @param int $id Post ID.
 * @param string $taxonomy Taxonomy name.
 * @param string $post_type Optional. Post type name.
 * @param string $before Optional. Before list.
 * @param string $sep Optional. Separate items using this.
 * @param string $after Optional. After list.
 * @return null|bool False on WordPress error. Returns null when displaying.
 */
function gdtt_the_terms($id = 0, $taxonomy = 'category', $post_type = "", $before = "", $sep = ", ", $after = "") {
    $term_list = gdtt_get_the_term_list($id, $taxonomy, $post_type, $before, $sep, $after);

    if (is_wp_error($term_list)) {
        return false;
    }

    echo apply_filters('the_terms', $term_list, $taxonomy, $before, $sep, $after);
}

?>