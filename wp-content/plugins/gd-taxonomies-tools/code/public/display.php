<?php

if (!defined('ABSPATH')) exit;

/**
 * Generate tag cloud. Same as wp_generate_tag_cloud.
 *
 * @param array $tags Tags to render in the cloud.
 * @param array|string $args Optional. Override default arguments.
 * @return string Rendered cloud.
 */
function gdtt_wp_generate_tag_cloud($tags, $args = "") {
    if (empty($tags)) return;

    $defaults = array("topic_count_text_callback" => "default_topic_count_text",
        "smallest" => 8, "largest" => 22, "unit" => "pt", "number" => 0, "mark_current" => 1,
        "format" => "flat", "separator" => "\n", "orderby" => "name", "order" => "ASC",
        "topic_count_scale_callback" => "default_topic_count_scale", "filter" => 1);

    if (!isset( $args["topic_count_text_callback"]) && isset($args["single_text"]) && isset($args["multiple_text"])) {
        $body = "return sprintf (
                _n( " . var_export($args["single_text"], true).", ".var_export($args["multiple_text"], true).", $count ),
                number_format_i18n($count));";
        $args["topic_count_text_callback"] = create_function("$count", $body);
    }

    $args = wp_parse_args($args, $defaults);
    extract($args);

    $current_term = gdtt_get_current_term();

    $orderby = strtolower($orderby);
    $order = strtolower($order);

    $tags_sorted = apply_filters("tag_cloud_sort", $tags, $args);
    if ($tags_sorted != $tags ) {
        $tags = $tags_sorted;
        unset($tags_sorted);
    } else {
        if ("rand" == $orderby) {
            shuffle($tags);
        } else {
            if ("name" == $orderby) {
                uasort($tags, create_function('$a,$b', 'return strnatcasecmp($a->name, $b->name);') );
            } else if ("slug" == $orderby) {
                uasort($tags, create_function('$a,$b', 'return strnatcasecmp($a->slug, $b->slug);'));
            } else {
                uasort($tags, create_function('$a,$b', 'return ($a->count > $b->count);'));
            }

            if ("desc" == $order) {
                $tags = array_reverse($tags, true);
            }
        }
    }

    if ($number > 0) $tags = array_slice($tags, 0, $number);

    $counts = array();
    $real_counts = array();
    foreach ((array) $tags as $key => $tag) {
        $real_counts[$key] = $tag->count;
        $counts[$key] = $topic_count_scale_callback($tag->count);
    }

    $min_count = min($counts);
    $spread = max($counts) - $min_count;
    if ($spread <= 0) $spread = 1;
    $font_spread = $largest - $smallest;
    if ($font_spread < 0) $font_spread = 1;
    $font_step = $font_spread / $spread;

    $a = array();

    foreach ($tags as $key => $tag) {
        $count = $counts[$key];
        $real_count = $real_counts[$key];
        $tag_link = '#' != $tag->link ? esc_url( $tag->link ) : '#';
        $tag_id = isset($tags[$key]->id) ? $tags[$key]->id : $key;
        $tag_name = $tags[$key]->name;
        $class = "tag-link-".$tag_id;
        if ($mark_current == 1 && isset($current_term) && $tag_id == $current_term->term_id) {
            $class.= " current";
        }
        $a[] = "<a href='$tag_link' class='$class' title='"
               .esc_attr(call_user_func($topic_count_text_callback, $real_count))."' style='font-size: "
               .($smallest + (($count - $min_count) * $font_step))."$unit;'>$tag_name</a>";
    }

    switch ($format) :
        case 'array' :
            $return =& $a;
            break;
        case 'list' :
            $return = "<ul class='wp-tag-cloud'>\n\t<li>";
            $return.= join( "</li>\n\t<li>", $a );
            $return.= "</li>\n</ul>\n";
            break;
        default :
            $return = join($separator, $a);
            break;
    endswitch;

    if ($filter) {
        return apply_filters("wp_generate_tag_cloud", $return, $tags, $args);
    } else {
        return $return;
    }
}

/**
 * List of terms for taxonomy assigned to a post with some additional options
 *
 * @param string $taxonomy name of the taxonomy
 * @param string $separator separator, if empty renders as UL/LI
 * @param int $post_id post id
 * @param bool $include_post_type include post_type in the link
 * @return string rendered list
 */
function gdtt_get_taxonomy_list($taxonomy = "category", $separator = "", $post_id = 0, $include_post_type = true) {
    global $post;
    $post_id = (int)$post_id;
    if ($post_id == 0) {
        if (!$post->ID) return false;
        else $post_id = (int)$post->ID;
    }

    $terms = get_the_terms($post_id, $taxonomy);
    $post_type = get_post_type($post_id);

    if (!is_object_in_taxonomy($post_type, $taxonomy) || empty($terms)) {
        return "";
    }

    $thelist = '';
    if ('' == $separator) {
        $thelist .= '<ul class="post-categories">';
        foreach ($terms as $term) {
            $url = get_term_link($term, $taxonomy);
            if ($include_post_type) $url = add_query_arg("post_type", $post_type, $url);
            $thelist.= "\n\t<li>";
            $thelist.= '<a href="'.$url.'" title="'.esc_attr(sprintf(__("View all posts in %s", "gd-taxonomies-tools"), $term->name)).'">'.$term->name.'</a></li>';
        }
        $thelist .= '</ul>';
    } else {
        $i = 0;
        foreach ($terms as $term) {
            if (0 < $i) $thelist .= $separator;
            $url = get_term_link($term, $taxonomy);
            if ($include_post_type) $url = add_query_arg("post_type", $post_type, $url);
            $thelist.= '<a href="'.$url.'" title="'.esc_attr(sprintf(__("View all posts in %s", "gd-taxonomies-tools"), $term->name)).'">'.$term->name.'</a>';
            ++$i;
        }
    }
    return $thelist;
}

/**
 * Display tag cloud. Same as wp_tag_cloud, but expanded for better support for post types.
 *
 * @param array|string $args Optional. Override default arguments.
 * @return array Generated tag cloud, only if no failures and 'array' is set for the 'format' argument.
 */
function gdtt_wp_tag_cloud($args = array()) {
    $defaults = array(
        'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => 45, 
        'post_types' => '', 'order' => 'ASC', 'format' => 'flat', 
        'separator' => "\n", 'orderby' => "name", 'exclude' => '', 'echo' => true, 
        'include' => '', 'link' => 'view', 'taxonomy' => 'post_tag', 'mark_current' => 1);

    $args = wp_parse_args($args, $defaults);
    if (!taxonomy_exists($args['taxonomy'])) return;

    $tags = gdtt_get_terms($args['taxonomy'], $args);

    if (empty($tags)) return;

    foreach ($tags as $key => $tag) {
        $link = '';

        if ('edit' == $args['link']) {
            $link = get_edit_tag_link($tag->term_id, $args['taxonomy']);
        } else {
            if ($args['post_types'] != '') {
                if (gdtt_has_post_type_intersections($args['post_types'])) {
                    $link = gdtt_get_intersection_link($args['post_types'], $tag->taxonomy, $tag);
                } else {
                    $link = get_term_link($tag, $tag->taxonomy);
                    $link = add_query_arg('post_type', $args['post_types'], $link);
                }
            } else {
                $link = get_term_link(intval($tag->term_id), $args['taxonomy']);
            }
        }

        if (is_wp_error($link)) {
            return false;
        }

        $tags[$key]->link = $link;
        $tags[$key]->id = $tag->term_id;
    }

    $return = gdtt_wp_generate_tag_cloud($tags, $args);
    $return = apply_filters('wp_tag_cloud', $return, $args);

    if ('array' == $args['format'] || empty($args['echo'])) {
        return $return;
    } else {
        echo $return;
    }
}

/**
* Display or retrieve the HTML dropdown list of terms for any taxonomy.
*
* @param string|array $args Optional. Override default arguments.
* @return string HTML content only if 'echo' argument is 0.
*/
function gdtt_dropdown_taxonomy_terms($args = array()) {
    $defaults = array(
        'show_option_all' => '', 'show_option_none' => '', 'post_types' => '', 
        'orderby' => 'id', 'order' => 'ASC', 'show_last_update' => 0, 'show_count' => 0,
        'hide_empty' => 1, 'child_of' => 0, 'exclude' => '', 'echo' => 1,
        'selected' => 0, 'hierarchical' => 0, 'name' => 'cat', 'id' => '',
        'class' => 'postform', 'depth' => 0, 'tab_index' => 0, 'taxonomy' => 'category',
        'hide_if_empty' => false, 'mark_current' => 1, 'walker' => null);

    $r = wp_parse_args($args, $defaults);
    if (!taxonomy_exists($args['taxonomy'])) return;


    if (!isset($r['pad_counts']) && $r['show_count'] && $r['hierarchical']) {
        $r['pad_counts'] = true;
    }

    $current_term = gdtt_get_current_term();
    if ($r['mark_current'] == 1 && !is_null($current_term)) {
        $r['selected'] = $current_term->term_id;
    }

    $r['include_last_update_time'] = $r['show_last_update'];
    extract($r);

    $tab_index_attribute = '';
    if ((int)$tab_index > 0) $tab_index_attribute = " tabindex=\"$tab_index\"";

    $terms = gdtt_get_terms($taxonomy, $r);
    $name = esc_attr( $name );
    $class = esc_attr( $class );
    $id = $id ? esc_attr( $id ) : $name;

    if (!$r['hide_if_empty'] || ! empty($terms)) {
        $output = "<select name='$name' id='$id' class='$class' $tab_index_attribute>\n";
    } else {
        $output = '';
    }

    if (empty($terms) && ! $r['hide_if_empty'] && !empty($show_option_none)) {
        $show_option_none = apply_filters('list_cats', $show_option_none);
        $output .= "\t<option value='-1' selected='selected'>$show_option_none</option>\n";
    }

    if (!empty($terms)) {
        if ($show_option_all) {
            $show_option_all = apply_filters('list_cats', $show_option_all);
            $selected = ('0' === strval($r['selected'])) ? " selected='selected'" : '';
            $output.= "\t<option value='0'$selected>$show_option_all</option>\n";
        }

        if ($show_option_none) {
            $show_option_none = apply_filters('list_cats', $show_option_none);
            $selected = ("-1" === strval($r['selected'])) ? " selected='selected'" : '';
            $output.= "\t<option value='-1'$selected>$show_option_none</option>\n";
        }

        $depth = $hierarchical ? $r['depth'] : -1;

        if ($r['walker'] === null) {
            $r['walker'] = new gdttWalker_TermsDropdown();
        }

        $output.= walk_category_dropdown_tree($terms, $depth, $r);
    }

    if (!$r['hide_if_empty'] || ! empty($terms)) $output .= "</select>\n";
    $output = apply_filters('wp_dropdown_terms_'.$taxonomy, $output);

    if ($echo) {
        echo $output;
    }

    return $output;
}

/**
* Display or retrieve the HTML list of terms for any taxonomy.
*
* @param string|array $args Optional. Override default arguments.
* @return string HTML content only if 'echo' argument is 0.
*/
function gdtt_list_taxonomy_terms($args = array()) {
    $defaults = array(
        'show_option_all' => '', 'show_option_none' => __("No categories", "gd-taxonomies-tools"),
        'orderby' => 'name', 'order' => 'ASC', 'post_types' => '', 'show_last_update' => false, 
        'style' => 'list', 'show_count' => false, 'hide_empty' => true, 'use_desc_for_title' => true, 
        'child_of' => 0, 'feed' => '', 'feed_type' => '', 'link_class' => '', 'feed_image' => '', 
        'exclude' => '', 'li_class' => '', 'exclude_tree' => '', 'current_category' => 0,
        'hierarchical' => true, 'title_li' => '', 'echo' => 1, 'depth' => 0, 'taxonomy' => 'category',
        'mark_current' => true, 'current_term_id' => 0);

    $r = wp_parse_args($args, $defaults);
    if (!taxonomy_exists($args['taxonomy'])) return;

    if (!isset( $r['pad_counts']) && $r['show_count'] && $r['hierarchical']) {
        $r['pad_counts'] = true;
    }

    if (isset( $r['show_date'])) {
        $r['include_last_update_time'] = $r['show_date'];
    }

    if (true == $r['hierarchical']) {
        $r['exclude_tree'] = $r['exclude'];
        $r['exclude'] = '';
    }

    if (!isset($r['class'])) {
        $r['class'] = ('category' == $r['taxonomy']) ? 'categories' : $r['taxonomy'];
    }

    $current_term = gdtt_get_current_term();
    if ($r['mark_current'] == 1 && !is_null($current_term)) {
        $r['current_term_id'] = $current_term->term_id;
    }

    extract($r);

    $terms = gdtt_get_terms($taxonomy, $r);

    $output = '';
    if ($title_li && 'list' == $style) {
        $output = '<li class="'.$class . '">'.$title_li.'<ul>';
    }

    if (empty($terms)) {
        if (!empty( $show_option_none)) {
            if ('list' == $style) $output.= "<li>".$show_option_none."</li>";
            else $output.= $show_option_none;
        }
    } else {
        global $wp_query;

        if (!empty( $show_option_all)) {
            if ("list" == $style) {
                $output.= '<li><a href="'.get_bloginfo('url').'">'.$show_option_all.'</a></li>';
            } else {
                $output.= '<a href="'.get_bloginfo('url').'">'.$show_option_all.'</a>';
            }
        }

        $depth = $hierarchical ? $r['depth'] : -1;

        $r['walker'] = new gdttWalker_Terms();
        $output.= walk_category_tree($terms, $depth, $r);
    }

    if ($title_li && 'list' == $style) $output.= "</ul></li>";

    $output = apply_filters('wp_list_terms_'.$taxonomy, $output);

    if ($echo) {
        echo $output;
    }

    return $output;
}

/**
 * Attach image to a taxonomy term.
 *
 * @param string $taxonomy taxonomy to what term belongs to
 * @param object|int $term term object or id
 * @param int $image_id attachement id
 */
function gdtt_term_attach_image($taxonomy, $term, $image_id) {
    global $gdtt;
    $gdtt->tax_term_attach_image($taxonomy, $term, $image_id);
}

/**
 * Dettach image assigned to a taxonomy term
 *
 * @param string $taxonomy taxonomy to what term belongs to
 * @param object|int $term term object or id
 */
function gdtt_term_dettach_image($taxonomy, $term) {
    global $gdtt;
    $gdtt->tax_term_dettach_image($taxonomy, $term);
}

/**
 * Get attached image for a taxonomy term
 *
 * @param string $taxonomy taxonomy to what term belongs to
 * @param object|int $term term object or id
 * @param mixed $size size for the image returned
 * @param string $get what result to return, img for image tag, url for url only, id for attachment id
 * @return string|bool false if no image is attached
 */
function gdtt_get_term_image($taxonomy, $term, $size = 'thumbnail', $get = 'img') {
    global $gdtt;
    return $gdtt->tax_get_term_image($taxonomy, $term, $size, $get);
}

?>