<?php

if (!defined('ABSPATH')) exit;

class gdCPTDB {
    public static function real_count_taxonomy($name) {
        global $wpdb;
        
        $sql = "select count(*) from ".$wpdb->term_taxonomy." where taxonomy = '".$name."'";
        return intval($wpdb->get_var($sql));
    }

    public static function real_count_post_type($name) {
        global $wpdb;
        
        $sql = "select count(*) from ".$wpdb->posts." where post_type = '".$name."'";
        return intval($wpdb->get_var($sql));
    }

    public static function get_posts_for_filter($by, $tax, $terms, $post_type, $post_status) {
        global $wpdb;
        $terms = (array)$terms;

        $select = "distinct p.ID";
        $from = sprintf("%s tr inner join %s tt on tt.term_taxonomy_id = tr.term_taxonomy_id inner join %s t on t.term_id = tt.term_id inner join %s p on p.ID = tr.object_id",
                        $wpdb->term_relationships, $wpdb->term_taxonomy, $wpdb->terms, $wpdb->posts);
        $where = sprintf("post_type = '%s' and post_status = '%s' and tt.taxonomy = '%s' and t.%s in ('%s')",
                        $post_type, $post_status, $tax, $by, join("', '", $terms));

        $sql = sprintf("select %s from %s where %s", $select, $from, $where);
        $r = $wpdb->get_results($sql);

        $posts = array();
        foreach ($r as $o) $posts[] = $o->ID;
        return $posts;
    }

    public static function get_posts_for_term_by($element, $taxonomy, $term, $result = "post") {
        global $wpdb;

        $select = $result == "post" ? "p.*" : "tr.object_id as ID";
        $join = $result == "post" ? " inner join ".$wpdb->posts." p on p.ID = tr.object_id" : "";
        $terms = is_array($term) ? "'".join("', '", $term)."'" : "'".$term."'";

        $sql = sprintf("select %s from %s tr inner join %s tt on tt.term_taxonomy_id = tr.term_taxonomy_id inner join %s t on t.term_id = tt.term_id where t.%s in (%s) and tt.taxonomy = '%s'",
            $select, $wpdb->term_relationships, $wpdb->term_taxonomy, $wpdb->terms, $element, $terms, $taxonomy);
        $r = $wpdb->get_results($sql);
        if ($result == "post") return $r;
        $posts = array();
        foreach ($r as $o) $posts[] = $o->ID;
        return $posts;
    }

    public static function get_post_types_counts() {
        global $wpdb;

        $sql = sprintf("select post_type, count(*) as items from %sposts where post_status = 'publish' or post_status = 'inherit' group by post_type", $wpdb->prefix);
        $raw = $wpdb->get_results($sql);
        $res = array();
        foreach ($raw as $r) {
            $res[$r->post_type] = $r->items;
        }
        return $res;
    }

    public static function delete_taxonomy_terms($tax) {
        global $wpdb;

        $columns = gdCPTDB::mysql_pre_4_1() ? $wpdb->term_taxonomy.", ".$wpdb->term_relationships : "tt, tr";
        $sql = sprintf("delete %s from %s tt left join %s tr on tt.term_taxonomy_id = tr.term_taxonomy_id where tt.taxonomy = '%s'", 
                        $columns, $wpdb->term_taxonomy, $wpdb->term_relationships, $tax);

        $wpdb->query($sql);
    }

    public static function mysql_pre_4_1() {
        $mysql = str_replace(".", "", substr(mysql_get_server_info(), 0, 3));
        return $mysql < 41;
    }
}

class gdCPTRewrite {
    function __construct() { }

    private function _archive_slug($post_type) {
        $slug_archive = $post_type->has_archive;

        if ($slug_archive === true) {
            $slug_archive = $post_type->name;
        }

        return $slug_archive;
    }

    public function generate_terms_index($taxonomy, $tax, $wp_rewrite) {
        $slug = isset($tax->rewrite['slug']) ? $tax->rewrite['slug'] : '';

        $rules = array();

        if (!empty($slug)) {
            $rules[$slug."/?$"] =                  "index.php?taxindex=".$taxonomy;
            $rules[$slug."/page/([0-9]{1,})/?$"] = "index.php?taxindex=".$taxonomy."&paged=".$wp_rewrite->preg_index(1);
        }

        return $rules;
    }

    public function generate_standard_overrides($cpt, $wp_rewrite) {
        $rules = array();
        $post_type = get_post_type_object($cpt['name']);
        $slug_archive = $this->_archive_slug($post_type);

        if (is_string($slug_archive)) {
            $rules[$slug_archive."/page/([0-9]{1,})/?$"] = "index.php?post_type=".$cpt['name']."&paged=".$wp_rewrite->preg_index(1);
        }

        return $rules;
    }

    public function generate_date_archives($cpt, $wp_rewrite) {
        $rules = array();
        $post_type = get_post_type_object($cpt['name']);
        $slug_archive = $this->_archive_slug($post_type);

        $dates = array(
            array('rule' => "([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})", "vars" => array('year', 'monthnum', 'day')),
            array('rule' => "([0-9]{4})/([0-9]{1,2})", "vars" => array('year', 'monthnum')),
            array('rule' => "([0-9]{4})", "vars" => array('year'))
        );

        foreach ($dates as $data) {
            $query = 'index.php?post_type='.$cpt['name'];
            $rule = $slug_archive."/".$data['rule'];

            $i = 1;
            foreach ($data['vars'] as $var) {
                $query.= '&'.$var.'='.$wp_rewrite->preg_index($i);
                $i++;
            }

            $rules[$rule."/?$"] =                                   $query;
            $rules[$rule."/feed/(feed|rdf|rss|rss2|atom)/?$"] =     $query.'&feed='.$wp_rewrite->preg_index($i);
            $rules[$rule."/(feed|rdf|rss|rss2|atom)/?$"] =          $query.'&feed='.$wp_rewrite->preg_index($i);
            $rules[$rule."/page/([0-9]{1,})/?$"] =                  $query.'&paged='.$wp_rewrite->preg_index($i);
        }

        return $rules;
    }

    public function generate_advanced_intersection($cpt, $wp_rewrite) {
        $rules = $tmp = array();
        $post_type = get_post_type_object($cpt['name']);
        $slug_archive = $this->_archive_slug($post_type);

        if (is_string($slug_archive)) {
            $partial = isset($cpt['intersections_partial']) ? $cpt['intersections_partial'] == 'yes' : false;

            $parts = explode('/', trim($cpt['intersections_structure'], '/'));
            $query = 'index.php?post_type='.$cpt['name'];
            $rule = $slug_archive;

            $i = 1;
            foreach ($parts as $part) {
                $taxonomy = trim($part, '%');
                $tax = get_taxonomy($taxonomy);
                $query_var = $tax->query_var === true ? $tax->name : ($tax->query_var !== false ? $tax->query_var : $tax->name);

                $query.= '&'.$query_var.'='.$wp_rewrite->preg_index($i);
                $rule.= "/([^/]+)";
                $i++;

                if ($partial && count($parts) > 1 && $i <= count($parts)) {
                    $tmp[] = array($rule, $query, $i);
                }
            }

            $tmp[] = array($rule, $query, $i);
            $tmp = array_reverse($tmp);

            foreach ($tmp as $t) {
                $rule = $t[0];
                $query = $t[1];
                $i = $t[2];

                $rules[$rule."/feed/(feed|rdf|rss|rss2|atom)/?$"] =     $query."&feed=".$wp_rewrite->preg_index($i);
                $rules[$rule."/(feed|rdf|rss|rss2|atom)/?$"] =          $query."&feed=".$wp_rewrite->preg_index($i);
                $rules[$rule."/page/([0-9]{1,})/?$"] =                  $query."&paged=".$wp_rewrite->preg_index($i);
                $rules[$rule."/?$"] =                                   $query;
            }
        }

        return $rules;
    }

    public function generate_intersection($cpt, $taxonomies, $wp_rewrite) {
        global $gdtt;

        $rules = array();

        $post_type = get_post_type_object($cpt['name']);
        $slug_archive = $this->_archive_slug($post_type);

        if (is_string($slug_archive)) {
            $slug_archive.= '/';

            $baseless = isset($cpt['intersections_baseless']) && $cpt['intersections_baseless'] != '' ? $cpt['intersections_baseless'] : '';
            
            foreach ($taxonomies as $taxonomy) {
                if (taxonomy_exists($taxonomy)) {
                    $tax = get_taxonomy($taxonomy);

                    $slug = isset($tax->rewrite['slug']) ? $tax->rewrite['slug'] : '';
                    $query_var = $tax->query_var === true ? $tax->name : ($tax->query_var !== false ? $tax->query_var : $tax->name);

                    if (!empty($slug)) {
                        if ($taxonomy == $baseless) {
                            $slug = '';
                        } else {
                            $slug = end(explode('/', $slug)).'/';
                        }

                        if ($taxonomy != $baseless && in_array('intersect', $gdtt->sf['tax'][$taxonomy]['index'])) {
                            $rules[$slug_archive.$slug."?$"] =                                   "index.php?post_type=".$cpt['name']."&taxindex=".$taxonomy;
                            $rules[$slug_archive.$slug."page/([0-9]{1,})/?$"] =                  "index.php?post_type=".$cpt['name']."&taxindex=".$taxonomy."&paged=".$wp_rewrite->preg_index(1);
                        }

                        $rules[$slug_archive.$slug."([^/]+)/?$"] =                               "index.php?post_type=".$cpt['name']."&".$query_var."=".$wp_rewrite->preg_index(1);
                        $rules[$slug_archive.$slug."([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$"] = "index.php?post_type=".$cpt['name']."&feed=".$wp_rewrite->preg_index(2)."&".$query_var."=".$wp_rewrite->preg_index(1);
                        $rules[$slug_archive.$slug."([^/]+)/(feed|rdf|rss|rss2|atom)/?$"] =      "index.php?post_type=".$cpt['name']."&feed=".$wp_rewrite->preg_index(2)."&".$query_var."=".$wp_rewrite->preg_index(1);
                        $rules[$slug_archive.$slug."([^/]+)/page/([0-9]{1,})/?$"] =              "index.php?post_type=".$cpt['name']."&paged=".$wp_rewrite->preg_index(2)."&".$query_var."=".$wp_rewrite->preg_index(1);
                    }
                }
            }
        }

        return $rules;
    }
}

class gdtt_Caps {
    public $mode = 'cpt';
    public $code = '';
    public $active = array();
    public $caps = array();

    function __construct($name, $mode = 'cpt') {
        $this->code = $name;
        $this->mode = $mode;
    }

    function make_caps($list) {
        $caps = array();
        $search = $this->mode == 'cpt' ? 'post' : 'terms';
        foreach ($list as $cap) {
            $caps[] = str_replace($search, $this->code, $cap);
        }
        return $caps;
    }

    function is_active($role) {
        if (isset($this->active[$role])) {
            return $this->active[$role];
        } else {
            return false;
        }
    }

    function get_caps($role, $defaults) {
        if (!isset($this->active[$role]) || !$this->active[$role]) {
            return array();
        } else {
            return $this->make_caps($this->caps[$role]);
        }
    }

    function init($defaults) {
        $roles = get_editable_roles();

        if (!is_array($this->active)) { $this->active = array(); }
        if (!is_array($this->caps)) { $this->caps = array(); }

        foreach ($roles as $role => $data) {
            if (!isset($this->active[$role])) {
                $this->active[$role] = false;
            }
            if (!isset($this->caps[$role])) {
                if (isset($defaults[$role])) {
                    $this->caps[$role] = $defaults[$role];
                } else {
                    $this->caps[$role] = array();
                }
            }
        }
    }
}

class gdtt_CustomPost {
    public $name;
    public $label;
    public $public;
    public $show_ui;
    public $hierarchical;

    function __construct($saved_cpt) {
        $this->name = $saved_cpt['name'];
        $this->label = $saved_cpt['labels']['name'];
        $this->public = $saved_cpt['public'] == 'yes';
        $this->show_ui = !isset($saved_cpt['ui']) ? $saved_cpt['public'] : $saved_cpt['ui'] == 'yes';
        $this->hierarchical = $saved_cpt['hierarchy'] == 'yes';
    }
}

class gdtt_Taxonomy {
    public $name;
    public $label;
    public $object_type;
    public $hierarchical;
    public $rewrite;
    public $query_var;

    function __construct($saved_tax) {
        $this->name = $saved_tax['name'];
        $this->label = $saved_tax['label'];
        $this->object_type = $saved_tax['domain'];
        $this->hierarchical = $saved_tax['hierarchy'];
        $this->rewrite = $saved_tax['rewrite'];
        $this->query_var = $saved_tax['query'];
    }
}

class gdttWalker_CategoryChecklist extends Walker {
    public $tree_type = 'category';
    public $selection = 'multi';
    public $hierarchy = false;
    public $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output.= "$indent<ul class='children'>\n";
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output.= "$indent</ul>\n";
    }

    function start_el(&$output, $object, $depth = 0, $args = array(), $current_object_id = 0) {
        extract($args);

        if (empty($taxonomy)) {
            $taxonomy = 'category';
        }

        $class_item = "";
        $term_value = $object->term_id;
        if ($this->hierarchy) {
            if ($taxonomy == 'category') {
                $name = 'post_category';
            } else {
                $name = 'tax_input['.$taxonomy.']';
            }
        } else {
            $name = '_gdtt_tax_input['.$taxonomy.']';
            $class_item = "gdtt-term-limited gdtt-taxonomy-".$taxonomy;
            $term_value = $object->name;
        }

        $type = $this->selection === 'single' ? 'radio' : 'checkbox';
        $class = in_array($object->term_id, $popular_cats ) ? ' class="popular-category"' : '';

        $output.= "\n<li id='{$taxonomy}-{$object->term_id}'$class>";
        $output.= '<label class="selectit">';
        $output.= '<input class="'.$class_item.'" gdtt-taxonomy="'.$taxonomy.'" value="'.$term_value.'" type="'.$type.'" name="'.$name.'[]" id="in-'.$taxonomy.'-'.$object->term_id.'"'.checked(in_array($object->term_id, $selected_cats), true, false).disabled(empty($args['disabled']), false, false).' /> '.esc_html(apply_filters('the_category', $object->name));
        $output.= '</label>';
    }

    function end_el(&$output, $object, $depth = 0, $args = array()) {
        $output.= "</li>\n";
    }
}

class gdttWalker_Terms extends Walker_Category {
    function start_el(&$output, $term, $depth = 0, $args = array(), $id = 0) {
        extract($args);
        if (is_array($args['post_types'])) {
            $args['post_types'] = $args['post_types'][0];
        }

        if ($term->term_id == $args['current_term_id']) {
            if ('list' == $args['style']) {
                $li_class.= ' current';
                $li_class = trim($li_class);
            } else {
                $link_class.= ' current';
                $link_class = trim($link_class);
            }
        }

        $term_name = esc_attr($term->name);
        $term_name = apply_filters('list_term_name', $term_name, $term);

        $term_url = '';
        if ($args['post_types'] != '') {
            if (gdtt_has_post_type_intersections($args['post_types'])) {
                $term_url = gdtt_get_intersection_link($args['post_types'], $term->taxonomy, $term);
            } else {
                $term_url = get_term_link($term, $term->taxonomy);
                $term_url = add_query_arg('post_type', $args['post_types'], $term_url);
            }
        } else {
            $term_url = get_term_link($term, $term->taxonomy);
        }

        $link = '<a href="'.$term_url.'" ';
        if (!empty($link_class)) {
            $link.= 'class="'.$link_class.'" ';
        }

        if ($use_desc_for_title == 0 || empty($term->description)) {
            $link.= 'title="'.sprintf(__("View all posts filed under %s", "gd-taxonomies-tools"), $term_name).'"';
        } else {
            $link.= 'title="'.esc_attr(strip_tags(apply_filters($taxonomy.'_description', $term->description, $term))).'"';
        }

        $link.= '>';
        $link.= $term_name.'</a>';

        if ((!empty($feed_image)) || (!empty($feed))) {
            $link.= ' ';

            if (empty($feed_image)) $link.= '(';

            $link.= '<a href="'.get_term_feed_link( $term->term_id, $term->taxonomy, $feed_type).'"';

            if (empty($feed)) {
                $alt = ' alt="'.sprintf(__("Feed for all posts filed under %s", "gd-taxonomies-tools"), $term_name).'"';
            } else {
                $title = ' title="'.$feed.'"';
                $alt = ' alt="'.$feed.'"';
                $name = $feed;
                $link.= $title;
            }

            $link.= '>';

            if (empty($feed_image)) $link.= $name;
            else $link.= "<img src='$feed_image'$alt$title".' />';
            $link.= '</a>';
            if (empty($feed_image)) $link.= ')';
        }

        if (isset($show_count) && $show_count) {
            $link.= ' <span class="gdtt-count">('.intval($term->count).')</span>';
        }

        if (isset($show_date) && $show_date) {
            $link.= ' '.gmdate('Y-m-d', $term->last_update_timestamp);
        }

        if ('list' == $args['style']) {
            $output.= "\t<li";
            $class = $taxonomy.'-item '.$taxonomy.'-item-'.$term->term_id.' '.$taxonomy.'-item-'.$term->slug.' '.$li_class;
            $output.=  ' class="'.trim($class).'"';
            $output.= ">$link\n";
        } else {
            $output.= "\t$link<br />\n";
        }
    }
}

class gdttWalker_TaxonomyDropdown extends Walker_CategoryDropdown {
    function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {
        $pad = str_repeat('&nbsp;', $depth * 3);

        $cat_name = apply_filters('list_cats', $category->name, $category);
        $output.= "\t<option class=\"level-$depth\" value=\"".$category->slug."\"";
        if ($category->slug == $args['selected']) {
            $output.= ' selected="selected"';
        }
        $output.= '>';
        $output.= $pad.$cat_name;

        if (isset($args['show_count']) && $args['show_count']) {
                $output.= '&nbsp;&nbsp;('.$category->count.')';
        }

        if (isset($args['show_last_update']) && $args['show_last_update']) {
                $format = 'Y-m-d';
                $output.= '&nbsp;&nbsp;'.gmdate($format, $category->last_update_timestamp);
        }

        $output.= "</option>\n";
    }
}

class gdttWalker_TermsDropdown extends Walker_CategoryDropdown {
    function start_el(&$output, $term, $depth = 0, $args = array(), $id = 0) {
        $pad = str_repeat('&nbsp;', $depth * 3);
        if (is_array($args["post_types"])) {
            $args["post_types"] = $args["post_types"][0];
        }

        $term_name = apply_filters('list_term_name', $term->name, $term);

        $term_url = '';
        if ($args['post_types'] != '') {
            if (gdtt_has_post_type_intersections($args['post_types'])) {
                $term_url = gdtt_get_intersection_link($args['post_types'], $term->taxonomy, $term);
            } else {
                $term_url = get_term_link($term, $term->taxonomy);
                $term_url = add_query_arg('post_type', $args['post_types'], $term_url);
            }
        } else {
            $term_url = get_term_link($term, $term->taxonomy);
        }

        $output.= "\t<option class=\"level-$depth\" value=\"".$term_url."\"";
        if ($term->term_id == $args['selected']) $output.= ' selected="selected"';
        $output.= '>';
        $output.= $pad.$term_name;

        if (isset($args['show_count']) && $args['show_count']) {
            $output.= '&nbsp;&nbsp;('.$term->count.')';
        }

        if (isset($args['show_last_update']) && $args['show_last_update']) {
            $format = 'Y-m-d';
            $output.= '&nbsp;&nbsp;'.gmdate($format, $term->last_update_timestamp);
        }

        $output.= "</option>\n";
    }
}

?>