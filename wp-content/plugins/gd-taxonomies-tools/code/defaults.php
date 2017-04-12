<?php

if (!defined('ABSPATH')) exit;

class gdCPTDefaults {
    var $default_options = array(
        'version' => '4.3.9',
        'date' => '2015.12.16.',
        'build' => '4390',
        'status' => 'Stable',
        'product_id' => 'gd-taxonomies-tools',
        'edition' => 'pro',
        'revision' => 1,
        'accessibility_enhancements' => 'on',
        'load_chosen_meta' => 0,
        'load_chosen_bbpress' => 0,
        'transform_chosen_single_meta' => 0,
        'transform_chosen_multi_meta' => 0,
        'cpt_reorder' => array(),
        'tax_reorder' => array(),
        'force_rules_flush' => 0,
        'cache_active' => 1,
        'widget_terms_cloud' => 1,
        'widget_terms_list' => 1,
        'widget_posttypes_list' => 1,
        'rewrite_intersects_active' => 1,
        'rewrite_permalinks_active' => 1,
        'tagger_internal_limit' => 32,
        'tagger_alchemy_api_key' => '',
        'tagger_opencalais_api_key' => '',
        'tagger_zemanta_api_key' => '',
        'tagger_yahoo_api_id' => 'gdCPTTools40Pro',
        'post_edit_tag_delete' => 1,
        'post_edit_tag_yahoo' => 1,
        'post_edit_tag_alchemy' => 0,
        'post_edit_tag_zemanta' => 0,
        'post_edit_tag_opencalais' => 0,
        'post_edit_tag_internal' => 1,
        'special_cpt_home_page' => 1,
        'special_cpt_rss_feed' => 1,
        'special_cpt_favorites' => 1,
        'special_cpt_right_now' => 1,
        'special_cpt_post_template' => 1,
        'special_cpt_disable_quickedit' => 1,
        'special_cpt_gd_star_rating' => 1,
        'special_cpt_menu_drafts' => 1,
        'special_cpt_menu_futures' => 1,
        'special_cpt_menu_archive' => 1,
        'special_cpt_s2_notify' => 1,
        'special_tax_edit_column' => 1,
        'special_tax_edit_filter' => 1,
        'special_tax_term_link' => 1,
        'special_tax_term_image' => 1,
        'special_tax_term_id' => 1,
        'special_tax_metaboxes' => 1,
        'meta_post_type_change' => 1,
        'navmenu_metabox_active' => 1,
        'metabox_clean_title' => 0,
        'metabox_preload_select' => 1,
        'custom_fields_load_datetime' => 1,
        'custom_fields_load_advanced' => 0,
        'custom_fields_load_maps' => 0,
        'custom_fields_load_units' => 0,
        'google_maps_load_admin' => 1,
        'google_maps_load_front' => 1,
        'tax_internal' => 0,
        'cpt_internal' => 0,
        'tpl_expand_intersect' => 1,
        'tpl_expand_single' => 1,
        'tpl_expand_date' => 1,
        'tpl_expand_date_cpt' => 1,
        'tpl_expand_date_cpt_priority' => 1,
        'tpl_expand_archives' => 0,
        'delete_taxonomy_db' => 0,
        'sitemap_expand' => 0,
        'tinymce_auto_create' => 1,
        'tinymce_search_limit' => 5,
        'tinymce_use_shortcode' => 1,
        'bbpress_active' => 0,
        'bbpress_metabox_topic' => '__none__',
        'bbpress_metabox_reply' => '__none__',
        'bbpress_metabox_location_topic' => 'bbp_theme_after_topic_form_tags',
        'bbpress_metabox_location_reply' => 'bbp_theme_after_reply_form_tags',
        'bbpress_embed_js' => 1,
        'bbpress_embed_css' => 1,
        'bbpress_embed_active' => 1,
        'bbpress_embed_anyone' => 1,
        'bbpress_embed_author' => 1,
        'bbpress_embed_roles' => array('administrator')
    );

    var $post_type_caps = array(
        'edit_post' => 'edit_post',
        'read_post' => 'read_post',
        'delete_post' => 'delete_post',
        'edit_posts' => 'edit_posts',
        'edit_others_posts' => 'edit_others_posts',
        'publish_posts' => 'publish_posts',
        'read_private_posts' => 'read_private_posts',
        'read' => 'read',
        'delete_posts' => 'delete_posts',
        'delete_private_posts' => 'delete_private_posts',
        'delete_published_posts' => 'delete_published_posts',
        'delete_others_posts' => 'delete_others_posts',
        'edit_private_posts' => 'edit_private_posts',
        'edit_published_posts' => 'edit_published_posts'
    );

    var $taxonomy_caps = array(
        'manage_terms' => 'manage_categories',
        'edit_terms' => 'manage_categories',
        'delete_terms' => 'manage_categories',
        'assign_terms' => 'manage_categories'
    );

    var $reserved_names = array(
        'post', 'page', 'author', 'category', 'post_tag', 'link', 'nav_menu_items',
        'attachment', 'revision', 'nav_menu', 'link_category', 'year', 'monthnum',
        'hour', 'minute', 'second', 'tag', 'p', 's', 'post_id', 'postname', 'name',
        'category_name', 'feed', 'rdf', 'rss', 'rss2', 'atom', 'nav_menu', 'day',
        'author_name', 'post_format', 'forum', 'topic', 'reply', 'post_status'
    );

    var $post_features = array();
    var $post_features_special = array();
    var $taxonomy_features_special = array();

    function __construct() {
        $this->taxonomy_features_special = array(
            'edit_filter' => array('label' => __("Post edit filter", "gd-taxonomies-tools"), 'info' => __("Add filter for this taonomy above the post edit list", "gd-taxonomies-tools")),
            'term_link' => array('label' => __("Editor term link", "gd-taxonomies-tools"), 'info' => __("Add frontend permalink link for the taxonomy term for quick access.", "gd-taxonomies-tools")),
            'term_id' => array('label' => __("Editor term ID column", "gd-taxonomies-tools"), 'info' => __("Add column with term ID on the terms list for the taxonomy.", "gd-taxonomies-tools")),
            'term_image' => array('label' => __("Attach image to term", "gd-taxonomies-tools"), 'info' => __("Add column for attaching image to the terms.", "gd-taxonomies-tools"))
        );

        $this->post_features_special = array(
            'right_now' => array('label' => __("Dashboard Right Now", "gd-taxonomies-tools"), 'info' => __("Add posts count into the Right Now widget on Dashboard.", "gd-taxonomies-tools")),
            'rss_feed' => array('label' => __("Include in RSS feeds", "gd-taxonomies-tools"), 'info' => __("Inlcude posts in main RSS feed alongside standard posts.", "gd-taxonomies-tools")),
            'home_page' => array('label' => __("Include on home page", "gd-taxonomies-tools"), 'info' => __("Inlcude posts alongside standard posts in the main home page query.", "gd-taxonomies-tools")),
            'post_template' => array('label' => __("Custom Post Template", "gd-taxonomies-tools"), 'info' => __("Additional template for posts similar to templates implemented for pages.", "gd-taxonomies-tools")),
            'disable_quickedit' => array('label' => __("Remove Quick Edit", "gd-taxonomies-tools"), 'info' => __("Remove quick edit option from post s efitor lists.", "gd-taxonomies-tools")),
            'gd_star_rating' => array('label' => __("GD Star Rating", "gd-taxonomies-tools"), 'info' => __("Register post type with GD Star Rating plugin.", "gd-taxonomies-tools")),
            'menu_drafts' => array('label' => __("Drafts in Menu", "gd-taxonomies-tools"), 'info' => __("Adds drafts quick access link in the post type menu.", "gd-taxonomies-tools")),
            'menu_futures' => array('label' => __("Future Posts in Menu", "gd-taxonomies-tools"), 'info' => __("Adds scheduled posts quick access link in the post type menu.", "gd-taxonomies-tools")),
            'menu_archive' => array('label' => __("Archive Link in Menu", "gd-taxonomies-tools"), 'info' => __("Link to the posts archive.", "gd-taxonomies-tools")),
            's2_notify' => array('label' => __("Subscribe2 Notify", "gd-taxonomies-tools"), 'info' => __("Register post type for notifications in Subscribe2 plugin.", "gd-taxonomies-tools"))
        );

        $this->post_features = array(
            'title' => array('label' => __("Title", "gd-taxonomies-tools"), 'info' => __("Post title edit field.", "gd-taxonomies-tools")),
            'editor' => array('label' => __("Editor", "gd-taxonomies-tools"), 'info' => __("Post content editor.", "gd-taxonomies-tools")),
            'author' => array('label' => __("Author", "gd-taxonomies-tools"), 'info' => __("Author selection", "gd-taxonomies-tools")),
            'thumbnail' => array('label' => __("Post Thumbnails", "gd-taxonomies-tools"), 'info' => __("Meta box for featured image.", "gd-taxonomies-tools")),
            'excerpt' => array('label' => __("Excerpts", "gd-taxonomies-tools"), 'info' => __("Post excerpt editor.", "gd-taxonomies-tools")),
            'trackbacks' => array('label' => __("Trackbacks", "gd-taxonomies-tools"), 'info' => __("Allow trackbacks for the post.", "gd-taxonomies-tools")),
            'custom-fields' => array('label' => __("Custom Fields", "gd-taxonomies-tools"), 'info' => __("Allow use and editing of custom fields.", "gd-taxonomies-tools")),
            'comments' => array('label' => __("Comments", "gd-taxonomies-tools"), 'info' => __("Allow comments for the post.", "gd-taxonomies-tools")),
            'revisions' => array('label' => __("Revisions", "gd-taxonomies-tools"), 'info' => __("Save revisions while editing.", "gd-taxonomies-tools")),
            'page-attributes' => array('label' => __("Page Attributes", "gd-taxonomies-tools"), 'info' => __("Meta box with attributes for hierarchical post types.", "gd-taxonomies-tools")),
            'post-formats' => array('label' => __("Post Formats", "gd-taxonomies-tools"), 'info' => __("Selection of post format.", "gd-taxonomies-tools"))
        );
    }
}

?>