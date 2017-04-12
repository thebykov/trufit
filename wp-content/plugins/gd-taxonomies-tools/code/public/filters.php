<?php

if (!defined('ABSPATH')) exit;

/**
 * Standard WP get_terms function expanded with post_types parameter
 *
 * @param string|array $taxonomies one or more taxonomies
 * @param array $args settings
 * @return mixed result or WP_Error
 */
function gdtt_get_terms($taxonomies, $args = '') {
    global $wpdb;
    $empty_array = array();

    $single_taxonomy = false;
    if (!is_array($taxonomies)) {
        $single_taxonomy = true;
        $taxonomies = array($taxonomies);
    }

    foreach ((array)$taxonomies as $taxonomy) {
        if (!taxonomy_exists($taxonomy)) {
            $error = new WP_Error("invalid_taxonomy", __("Invalid Taxonomy", "gd-taxonomies-tools"));
            return $error;
        }
    }

    $in_taxonomies = "'" . implode("', '", $taxonomies) . "'";

    $defaults = array('orderby' => 'name', 'order' => 'ASC', 'post_types' => array(),
        'hide_empty' => true, 'exclude' => array(), 'exclude_tree' => array(), 'include' => array(),
        'number' => '', 'fields' => 'all', 'slug' => '', 'parent' => '',
        'hierarchical' => true, 'child_of' => 0, 'get' => '', 'name__like' => '',
        'pad_counts' => false, 'offset' => '', 'search' => '');
    $args = wp_parse_args($args, $defaults);

    if (!is_array($args['post_types'])) {
        if (!empty($args['post_types'])) {
            $args['post_types'] = (array)$args['post_types'];
        } else {
            $args['post_types'] = array();
        }
    }

    $args['number'] = absint( $args['number'] );
    $args['offset'] = absint( $args['offset'] );
    if (!$single_taxonomy || !is_taxonomy_hierarchical($taxonomies[0]) ||
        '' !== $args['parent'] ) {
        $args['child_of'] = 0;
        $args['hierarchical'] = false;
        $args['pad_counts'] = false;
    }

    if ( 'all' == $args['get'] ) {
        $args['child_of'] = 0;
        $args['hide_empty'] = 0;
        $args['hierarchical'] = false;
        $args['pad_counts'] = false;
    }
    extract($args, EXTR_SKIP);

    $in_post_types = "'" . implode("', '", $post_types) . "'";

    if ( $child_of ) {
        $hierarchy = _get_term_hierarchy($taxonomies[0]);
        if ( !isset($hierarchy[$child_of]) )
            return $empty_array;
    }

    if ( $parent ) {
        $hierarchy = _get_term_hierarchy($taxonomies[0]);
        if ( !isset($hierarchy[$parent]) )
            return $empty_array;
    }

    $filter_key = (has_filter('list_terms_exclusions')) ? serialize($GLOBALS['wp_filter']['list_terms_exclusions']) : '';
    $key = md5(serialize( compact(array_keys($defaults))) . serialize($taxonomies).$filter_key);
    $last_changed = wp_cache_get('last_changed', 'terms');
    if (!$last_changed) {
        $last_changed = time();
        wp_cache_set('last_changed', $last_changed, 'terms');
    }
    $cache_key = "get_terms:$key:$last_changed";
    $cache = wp_cache_get($cache_key, 'terms');
    if ( false !== $cache ) {
        $cache = apply_filters('get_terms', $cache, $taxonomies, $args);
        return $cache;
    }

    $_orderby = strtolower($orderby);
    if ('count' == $_orderby && empty($post_types)) {
        $orderby = 'tt.count';
    }
    if ('count' == $_orderby && !empty($post_types)) {
        $orderby = 'count(*)';
    } else if ('name' == $_orderby) {
        $orderby = 't.name';
    } else if ('slug' == $_orderby) {
        $orderby = 't.slug';
    } else if ('term_group' == $_orderby) {
        $orderby = 't.term_group';
    } else if ('rand' == $_orderby) {
        $orderby = 'rand()';
        $order = '';
    } else if ('none' == $_orderby) {
        $orderby = '';
    } else if (empty($_orderby) || 'id' == $_orderby) {
        $orderby = 't.term_id';
    }

    $orderby = apply_filters('get_terms_orderby', $orderby, $args);

    if (!empty($orderby)) {
        $orderby = "ORDER BY $orderby";
    } else {
        $order = '';
    }

    $where = $group_by = $inclusions = '';
    if (!empty($post_types)) {
        $group_by = ' GROUP BY t.term_id';
    }

    if (!empty($include)) {
        $exclude = $exclude_tree = '';
        $interms = wp_parse_id_list($include);

        foreach ($interms as $interm) {
            if (empty($inclusions)) {
                $inclusions = ' AND (t.term_id = '.intval($interm).' ';
            } else {
                $inclusions.= ' OR t.term_id = '.intval($interm).' ';
            }
        }
    }

    if (!empty($inclusions)) {
        $inclusions.= ')';
    }

    $where.= $inclusions;
    $exclusions = '';
    if (!empty($exclude_tree)) {
        $excluded_trunks = wp_parse_id_list($exclude_tree);

        foreach ($excluded_trunks as $extrunk) {
            $excluded_children = (array)get_terms($taxonomies[0], array('child_of' => intval($extrunk), 'fields' => "ids"));
            $excluded_children[] = $extrunk;

            foreach($excluded_children as $exterm) {
                if (empty($exclusions))
                    $exclusions = ' AND ( t.term_id <> '.intval($exterm).' ';
                else
                    $exclusions.= ' AND t.term_id <> '.intval($exterm).' ';
            }
        }
    }

    if (!empty($exclude)) {
        $exterms = wp_parse_id_list($exclude);
        foreach ($exterms as $exterm) {
            if (empty($exclusions))
                $exclusions = ' AND ( t.term_id <> '.intval($exterm).' ';
            else
                $exclusions.= ' AND t.term_id <> '.intval($exterm).' ';
        }
    }

    if (!empty($exclusions)) {
        $exclusions.= ')';
    }

    $exclusions = apply_filters("list_terms_exclusions", $exclusions, $args );
    $where.= $exclusions;

    if (!empty($slug)) {
        $slug = sanitize_title($slug);
        $where.= " AND t.slug = '$slug'";
    }

    if (!empty($name__like))
        $where.= " AND t.name LIKE '{$name__like}%'";

    if ('' !== $parent) {
        $parent = (int) $parent;
        $where.= " AND tt.parent = '$parent'";
    }

    if ($hide_empty && !$hierarchical) {
        if (empty($post_types)) $where.= ' AND tt.count > 0';
        else $group_by.= ' HAVING count(*) > 0';
    }

    if (!empty($number) && !$hierarchical && empty($child_of) && "" === $parent) {
        if ($offset)
            $limit = 'LIMIT '.$offset.', '.$number;
        else
            $limit = 'LIMIT '.$number;
    } else {
        $limit = '';
    }

    if (!empty($search)) {
        $search = like_escape($search);
        $where.= " AND (t.name LIKE '%$search%')";
    }

    if (!empty($post_types)) {
        $where.= " AND p.post_type in ($in_post_types)";
    }

    $selects = array();
    switch ($fields) {
        case 'all':
            if (empty($post_types)) {
                $selects = array('t.*', 'tt.*');
            } else {
                $selects = array('t.*', 'tt.term_taxonomy_id', 'tt.taxonomy', 'tt.description', 'tt.parent', 'count(*) as count');
            }
            break;
        case 'ids':
        case 'id=>parent':
            if (empty($post_types)) {
                $selects = array('t.term_id', 'tt.parent', 'tt.count');
            } else {
                $selects = array('t.term_id', 'tt.parent', 'count(*) as count');
            }
            break;
        case 'names':
            if (empty($post_types)) {
                $selects = array('t.term_id', 'tt.parent', 'tt.count', 't.name');
            } else {
                $selects = array('t.term_id', 'tt.parent', 'count(*) as count', 't.name');
            }
            break;
        case 'count':
           $orderby = '';
           $order = '';
           $selects = array('COUNT(*)');
    }
    $select_this = implode(', ', apply_filters('get_terms_fields', $selects, $args));

    if (empty($post_types)) {
        $query = "SELECT $select_this FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ($in_taxonomies) $where $orderby $order $limit";
    } else {
        $query = "SELECT $select_this FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id INNER JOIN $wpdb->posts AS p ON p.ID = tr.object_id WHERE tt.taxonomy IN ($in_taxonomies) $where $group_by $orderby $order $limit";
    }

    if ('count' == $fields) {
        $term_count = $wpdb->get_var($query);
        return $term_count;
    }

    $terms = $wpdb->get_results($query);

    if ("all" == $fields) {
        update_term_cache($terms);
    }

    if (empty($terms)) {
        wp_cache_add($cache_key, array(), 'terms');
        $terms = apply_filters('get_terms', array(), $taxonomies, $args);
        return $terms;
    }

    if ($child_of) {
        $children = _get_term_hierarchy($taxonomies[0]);
        if (!empty($children))
            $terms = & _get_term_children($child_of, $terms, $taxonomies[0]);
    }

    if ($pad_counts && 'all' == $fields) {
        _pad_term_counts($terms, $taxonomies[0]);
    }

    if ($hierarchical && $hide_empty && is_array($terms)) {
        foreach ($terms as $k => $term) {
            if (!$term->count) {
                $children = _get_term_children($term->term_id, $terms, $taxonomies[0]);
                if (is_array($children)) {
                    foreach ($children as $child)
                        if ($child->count) continue 2;
                }
                unset($terms[$k]);
            }
        }
    }
    reset($terms);

    $_terms = array();
    if ('id=>parent' == $fields) {
        while ($term = array_shift($terms))
            $_terms[$term->term_id] = $term->parent;
        $terms = $_terms;
    } elseif ('ids' == $fields) {
        while ($term = array_shift($terms))
            $_terms[] = $term->term_id;
        $terms = $_terms;
    } elseif ('names' == $fields) {
        while ($term = array_shift($terms))
            $_terms[] = $term->name;
        $terms = $_terms;
    }

    if (0 < $number && intval(@count($terms)) > $number) {
        $terms = array_slice($terms, $offset, $number);
    }

    wp_cache_add($cache_key, $terms, 'terms');
    $terms = apply_filters('get_terms', $terms, $taxonomies, $args);
    return $terms;
}

/**
 * Get posts using multiple taxonomy filtering.
 *
 * array("tax" => "", "hierarchy" => true, "field" => "term_id", "terms" => array())
 *
 * Version: 1.6.4
 * 
 * @param string|array $args
 * @param array $terms
 * @param string $return what to return, posts or query
 * @return array filtered posts
 */
function gdtt_get_posts($args = null, $terms = array(), $return = 'posts') {
    $default_args = array('post_type' => 'post', 'post_status' => 'publish', 'post__in' => array());
    $default_taxs = array('tax' => 'category', 'hierarchy' => true, 'field' => 'term_id', 'terms' => array());

    $args = wp_parse_args($args, $default_args);
    $post__in = $args['post__in'];
    $post_type = $args['post_type'];
    $post_status = $args['post_status'];
    $posts = array();

    foreach ($terms as $term) {
        $term = wp_parse_args($term, $default_taxs);
        if (is_taxonomy_hierarchical($term['tax']) && $term['hierarchy']) {
            $term['terms'] = gdtt_get_all_child_terms($term['tax'], $term['terms']);
        }
        $t = gdCPTDB::get_posts_for_filter($term['field'], $term['tax'], $term['terms'], $post_type, $post_status);

        if (count($t) == 0) return array();
        if (count($posts) > 0) $posts = array_intersect($posts, $t);
        else $posts = $t;
    }

    $args['post__in'] = array_merge($post__in, $posts);
    $gdtt_query = new WP_Query($args);
    if ($return == 'posts') return $gdtt_query->posts;
    else return $gdtt_query;
}

/**
 * Get terms ID's for all terms bellonging to input terms.
 *
 * @param string $taxonomy taxonomy to process
 * @param array $terms list of term ID's
 * @param string $return ids or terms
 * @return array all descendent terms
 */
function gdtt_get_all_child_terms($taxonomy, $terms = array(), $return = "ids") {
    $terms = (array)$terms;
    $merged = $terms;

    foreach ($terms as $term) {
        $t = get_terms($taxonomy, 'hide_empty=0&child_of='.$term);

        foreach ($t as $r) {
            if (!in_array($r->term_id, $merged)) {
                if ($return == 'ids') {
                    $merged[] = $r->term_id;
                } else {
                    $merged[] = $r;
                }
            }
        }
    }

    return $merged;
}

/**
 * Filter posts using taxonomy terms.
 *
 * @param array $terms terms to search for in different taxonomies
 * @param string what type of result to generate: count, id, post
 * @return array results mixed
 */
function gdtt_posts_term_filter($terms = array(), $result = 'count') {
    $posts = array();

    foreach ($terms as $tax => $term) {
        $t = gdCPTDB::get_posts_for_term_by($term['by'], $tax, $term['terms'], 'ID');

        if (count($t) == 0) return array();
        if (count($posts) > 0) $posts = array_intersect($posts, $t);
        else $posts = $t;
    }

    if ($result == 'count') return count($posts);
    if ($result == 'id') return $posts;
}

?>