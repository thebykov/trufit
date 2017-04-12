<?php

require_once(GDTAXTOOLS_PATH.'gdr2/plugin/gdr2.settings.admin.php');
require_once(GDTAXTOOLS_PATH.'code/internal/render.php');

global $gdtt, $gdtt_icons;
$all_icons = array_merge(array(''), $gdtt_icons->icons);

$editable = true;

if ($cpt['id'] == -1) {
    $editable = false;
} else if ($cpt['source'] != '') {
    $editable = false;
} else if ($cpt['id'] > 0) {
    $editable = gdCPTDB::real_count_post_type($cpt['name']) == 0;
}

$modules_groups = apply_filters('gdcpt_modules_post_types_groups', array(), false, $cpt['name'], $cpt['id']);

$r = new gdCPTRender();
$r->base = 'cpt';

$p = array(
    new gdr2_Setting_Panel('basics', __("Basics", "gd-taxonomies-tools"), __("core and labels", "gd-taxonomies-tools"), __("Name, status, description and labels for post type.", "gd-taxonomies-tools"), array(
        new gdCPT_Setting_Group('info', __("Info", "gd-taxonomies-tools"), '', true, false, false),
        new gdCPT_Setting_Group('name', __("Name", "gd-taxonomies-tools"), __("Name for the post type. Restricitions apply.", "gd-taxonomies-tools"), true, true, true),
        new gdCPT_Setting_Group('status', __("Status", "gd-taxonomies-tools"), __("Activity status for the post type.", "gd-taxonomies-tools"), true, true, true),
        new gdCPT_Setting_Group('labels_basic', __("Standard Labels", "gd-taxonomies-tools"), __("Main singular and plural labels.", "gd-taxonomies-tools"), true, true, true),
        new gdCPT_Setting_Group('labels_expanded', __("Expanded Labels", "gd-taxonomies-tools"), __("Additional labels used by WordPress interface.", "gd-taxonomies-tools"), false, true, true),
    )),
    new gdr2_Setting_Panel('settings', __("Settings", "gd-taxonomies-tools"), __("rewrite and visibiltiy", "gd-taxonomies-tools"), __("Rewriting, hierarchy, navigation, UI and query for post type.", "gd-taxonomies-tools"), array(
        new gdCPT_Setting_Group('hierarchy', __("Hierarchy", "gd-taxonomies-tools"), __("Hierarchy for the post type.", "gd-taxonomies-tools"), false, true, true),
        new gdCPT_Setting_Group('query', __("Query", "gd-taxonomies-tools"), __("Query settings for the post type.", "gd-taxonomies-tools"), false, true, true),
        new gdCPT_Setting_Group('rewrite', __("Rewriting", "gd-taxonomies-tools"), __("Options for setting the rewriting for posts belonging to the post type.", "gd-taxonomies-tools"), false, true, true),
        new gdCPT_Setting_Group('visibility', __("Visibility", "gd-taxonomies-tools"), __("Public visibiltiy, menu intergration and other UI related options.", "gd-taxonomies-tools"), false, true, true)
    )),
    new gdr2_Setting_Panel('features', __("Features", "gd-taxonomies-tools"), __("standard and enhanced", "gd-taxonomies-tools"), __("Standard features, taxonomies, enhanced features for post type.", "gd-taxonomies-tools"), array(
        new gdCPT_Setting_Group('enhanced', "GD CPT Tools ".__("Enhanced", "gd-taxonomies-tools"), __("Enahanced features added by this plugin.", "gd-taxonomies-tools"), false, true, true),
        new gdCPT_Setting_Group('wp', __("WordPress Standard", "gd-taxonomies-tools"), __("Features built into WordPress.", "gd-taxonomies-tools"), false, true, true),
        new gdCPT_Setting_Group('taxonomies', __("Taxonomies", "gd-taxonomies-tools"), __("Taxonomies supported by post type.", "gd-taxonomies-tools"), false, true, true),
        new gdCPT_Setting_Group('yourls', "Yourls ".__("Shortlink Integration", "gd-taxonomies-tools"), __("This requires 'YOURLS - WordPress to Twitter' plugin active. Make sure you set it up before using this.", "gd-taxonomies-tools"), false, true, true)
    )),
    new gdr2_Setting_Panel('rewriting', __("Rewrite", "gd-taxonomies-tools"), __("single and archive", "gd-taxonomies-tools"), __("Advanced rewriting rules for single posts and archives.", "gd-taxonomies-tools"), array(
        new gdCPT_Setting_Group('info', __("Info", "gd-taxonomies-tools"), '', true, false, false),
        new gdCPT_Setting_Group('permalinks', __("Single Post Rewriting", "gd-taxonomies-tools"), __("Additional rules for single post permalinks.", "gd-taxonomies-tools"), false, true, true),
        new gdCPT_Setting_Group('archvlinks', __("Archive Rewriting", "gd-taxonomies-tools"), __("To use these options, post type archives rewrite must be enabled on Settings tab.", "gd-taxonomies-tools").'<br/>'.__("Additional rules for archives intersections and permalinks.", "gd-taxonomies-tools"), false, true, true)
    )),
    new gdr2_Setting_Panel('editor', __("Editor", "gd-taxonomies-tools"), __("columns and filters", "gd-taxonomies-tools"), __("Capability, capabilities type and list with more post type settings.", "gd-taxonomies-tools"), array(
        new gdCPT_Setting_Group('columns', __("Posts List Columns", "gd-taxonomies-tools"), __("Columns from meta fields.", "gd-taxonomies-tools"), false, true, true),
        new gdCPT_Setting_Group('filters', __("Posts List Filters", "gd-taxonomies-tools"), __("Filters from meta fields.", "gd-taxonomies-tools"), false, true, true),
    )),
    new gdr2_Setting_Panel('advanced', __("Advanced", "gd-taxonomies-tools"), __("capabilities and more", "gd-taxonomies-tools"), __("Capability, capabilities type and list with more post type settings.", "gd-taxonomies-tools"), array(
        new gdCPT_Setting_Group('info', __("Info", "gd-taxonomies-tools"), '', true, false, false),
        new gdCPT_Setting_Group('caps_basic', __("Basic Capabilities Settings", "gd-taxonomies-tools"), __("Core capabilities settings.", "gd-taxonomies-tools"), false, true, true),
        new gdCPT_Setting_Group('caps_list', __("List of individual Capabilities", "gd-taxonomies-tools"), __("Names for individual capabilities.", "gd-taxonomies-tools"), false, true, true),
        new gdCPT_Setting_Group('miscellaneous', __("Miscellaneous Settings", "gd-taxonomies-tools"), __("Extra settings that don't fit in other groups.", "gd-taxonomies-tools"), false, true, true)
    ))
);

if ($cpt_built_in) {
    unset($p[3]);
    $p = array_values($p);
}

$e = array(
    'basics' => array(
        'info' => array(),
        'name' => array(
            new gdr2_Setting_Element('cpt', "[name]", 'basics', "name", __("Name", "gd-taxonomies-tools"), __("Unique name, not used with any other data type, with maximum 20 characters in length. Use only lower case letters with no special characters except for the dash. Name will be checked against list of reserverd words to avoid conflicts. Once set, name can be changed only for custom post types when there are no posts for it.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $cpt["name"], '', '', array("readonly" => !$editable)),
            new gdr2_Setting_Element('cpt', "[description]", 'basics', "name", __("Description", "gd-taxonomies-tools"), __("Value is not required but it can be useful as summary on what post type is about.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT_BLOCK, $cpt["description"])
        ),
        'status' => array(),
        'labels_basic' => array(
            new gdr2_Setting_Element('cpt', "[labels][name]", 'basics', "labels_basic", __("Plural", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["name"]),
            new gdr2_Setting_Element('cpt', "[labels][singular_name]", 'basics', "labels_basic", __("Singular", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["singular_name"]),
        ),
        'labels_expanded' => array(
            new gdr2_Setting_Element('cpt', "[button][labels]", 'basics', "labels_expanded", '', __("Click this button to automatically fill expanded labes based on standard singular and plural labels.", "gd-taxonomies-tools"), "button", '', '', '', array("href" => "javascript:autofill_posttype()", "link" => __("Auto Fill", "gd-taxonomies-tools"))),
            new gdr2_Setting_Element('cpt', "[labels][add_new]", 'basics', "labels_expanded", __("Add New", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["add_new"]),
            new gdr2_Setting_Element('cpt', "[labels][add_new_item]", 'basics', "labels_expanded", __("Add New Item", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["add_new_item"]),
            new gdr2_Setting_Element('cpt', "[labels][edit_item]", 'basics', "labels_expanded", __("Edit Item", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["edit_item"]),
            new gdr2_Setting_Element('cpt', "[labels][new_item]", 'basics', "labels_expanded", __("New Item", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["new_item"]),
            new gdr2_Setting_Element('cpt', "[labels][view_item]", 'basics', "labels_expanded", __("View Item", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["view_item"]),
            new gdr2_Setting_Element('cpt', "[labels][search_items]", 'basics', "labels_expanded", __("Search Items", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["search_items"]),
            new gdr2_Setting_Element('cpt', "[labels][not_found]", 'basics', "labels_expanded", __("Not Found", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["not_found"]),
            new gdr2_Setting_Element('cpt', "[labels][not_found_in_trash]", 'basics', "labels_expanded", __("Not Found In Trash", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["not_found_in_trash"]),
            new gdr2_Setting_Element('cpt', "[labels][parent_item_colon]", 'basics', "labels_expanded", __("Parent Item Colon", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["parent_item_colon"]),
            new gdr2_Setting_Element('cpt', "[labels][all_items]", 'basics', "labels_expanded", __("All Items", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["all_items"]),
            new gdr2_Setting_Element('cpt', "[labels][menu_name]", 'basics', "labels_expanded", __("Menu Name", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["menu_name"]),
        )
    ),
    'settings' => array(
        'hierarchy' => array(
            new gdr2_Setting_Element('cpt', "[hierarchy]", "settings", "hierarchy", __("Hierarchy", "gd-taxonomies-tools"), __("Hierarchical post type is equivalent of the pages in WordPress. Posts can have parents.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["hierarchy"] == "yes"),
        ),
        'query' => array(
            new gdr2_Setting_Element('cpt', "[query]", "settings", "query", __("Query Variable", "gd-taxonomies-tools"), __("Variable for the post type used for Query objects.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $cpt["query"], 'array', array("yes" => __("Yes, using name", "gd-taxonomies-tools"), "yes_custom" => __("Yes, using custom value", "gd-taxonomies-tools"), "no" => __("No", "gd-taxonomies-tools"))),
            new gdr2_Setting_Element('cpt', "[query_slug]", "settings", "query", __("Custom Query Variable Name", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["query_slug"], '', '', array('class' => 'limit-query-safe')),
            new gdr2_Setting_Element('cpt', "[publicly_queryable]", "settings", "query", __("Publicly Queryable", "gd-taxonomies-tools"), __("Allow for post type queries to be performed from the front end.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["publicly_queryable"] == "yes")
        ),
        'rewrite' => array(
            new gdr2_Setting_Element('cpt', "[rewrite]", "settings", "rewrite", __("Rewrite", "gd-taxonomies-tools"), __("Create rewrite permalinks rules for post type.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $cpt["rewrite"], 'array', array("yes" => __("Yes, using name", "gd-taxonomies-tools"), "yes_custom" => __("Yes, using custom value", "gd-taxonomies-tools"), "no" => __("No", "gd-taxonomies-tools"))),
            new gdr2_Setting_Element('cpt', "[rewrite_slug]", "settings", "rewrite", __("Custom Rewrite Slug", "gd-taxonomies-tools"), __("Custom slug for rewrite rules.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $cpt["rewrite_slug"], '', '', array('class' => 'limit-url-safe')),
            new gdr2_Setting_Element('cpt', "[rewrite_feeds]", "settings", "rewrite", __("Feeds", "gd-taxonomies-tools"), __("Generate permalink rules for feeds.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["rewrite_feeds"] == "yes"),
            new gdr2_Setting_Element('cpt', "[rewrite_pages]", "settings", "rewrite", __("Pages", "gd-taxonomies-tools"), __("Generate permalink rules for pages posts.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["rewrite_pages"] == "yes"),
            new gdr2_Setting_Element('cpt', "[rewrite_front]", "settings", "rewrite", __("With Front", "gd-taxonomies-tools"), __("Permalink rules will be prepended with front base permalink structure.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["rewrite_front"] == "yes"),
            new gdr2_Setting_Element('cpt', "[archive]", "settings", "rewrite", __("Archive", "gd-taxonomies-tools"), __("Enable post type archives. If you want to use advanced archive rewrite rules, this options must be active. If set to NO, you will not have archive pages for this post type.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $cpt["archive"], 'array', array("yes_name" => __("Yes, using name", "gd-taxonomies-tools"), "yes_custom" => __("Yes, using custom value", "gd-taxonomies-tools"), "no" => __("No", "gd-taxonomies-tools"))),
            new gdr2_Setting_Element('cpt', "[archive_slug]", "settings", "rewrite", __("Custom Archive Slug", "gd-taxonomies-tools"), __("Custom slug for archive rewrite rules.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $cpt["archive_slug"], '', '', array('class' => 'limit-url-safe'))
        ),
        'visibility' => array(
            new gdr2_Setting_Element('cpt', "[public]", "settings", "visibility", __("Public", "gd-taxonomies-tools"), __("Basic setting for other public related variables for UI and manus.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["public"] == "yes"),
            new gdr2_Setting_Element('cpt', "[ui]", "settings", "visibility", __("Show UI", "gd-taxonomies-tools"), __("Generate default UI elements for the post type.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["ui"] == "yes"),
            new gdr2_Setting_Element('cpt', "[exclude_from_search]", "settings", "visibility", __("Exclude From Search", "gd-taxonomies-tools"), __("Exclude posts with this post type from search results.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["exclude_from_search"] == "yes"),
            new gdr2_Setting_Element('cpt', "[nav_menus]", "settings", "visibility", __("Show In Navigation Menus", "gd-taxonomies-tools"), __("Post type is made available for selecting in the navigation menu designer.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["nav_menus"] == "yes"),
            new gdr2_Setting_Element('cpt', "[show_in_menu]", "settings", "visibility", __("Show In Admin Menu", "gd-taxonomies-tools"), __("Post type is visible in the main admin menu.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["show_in_menu"] == "yes"),
            new gdr2_Setting_Element('cpt', "[show_in_admin_bar]", "settings", "visibility", __("Show In Admin Bar", "gd-taxonomies-tools"), __("Post type is visible in the toolbar.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["show_in_admin_bar"] == "yes"),
            new gdr2_Setting_Element('cpt', "[menu_position]", "settings", "visibility", __("Admin Menu Position", "gd-taxonomies-tools"), __("Where in the main admin menu to display item for post type.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $cpt["menu_position"], 'array', array(
                "__auto__" => __("Default / Auto", "gd-taxonomies-tools"), "__block__" => __("New menu items block", "gd-taxonomies-tools"), 
                "5" => __("Below Posts", "gd-taxonomies-tools"), "10" => __("Below Media", "gd-taxonomies-tools"), "15" => __("Below Links", "gd-taxonomies-tools"), "20" => __("Below Pages", "gd-taxonomies-tools"),
                "25" => __("Below Comments", "gd-taxonomies-tools"), "60" => __("Below first separator", "gd-taxonomies-tools"), "65" => __("Below Plugins", "gd-taxonomies-tools"), "70" => __("Below Users", "gd-taxonomies-tools"),
                "75" => __("Below Tools", "gd-taxonomies-tools"), "80" => __("Below Settings", "gd-taxonomies-tools"), "100" => __("Below second separator", "gd-taxonomies-tools")
            )),
            new gdr2_Setting_Element('cpt', "[menu_icon]", "settings", "visibility", __("Custom Menu Icon", "gd-taxonomies-tools"), __("Image to use instead of the default icon in the admin menu. It's recommended to use small square image 16x16 px.", "gd-taxonomies-tools"), gdr2_Setting_Type::IMAGE, $cpt["menu_icon"]),
        )
    ),
    'editor' => array(
        'columns' => array(),
        'filters' => array()
    ),
    'features' => array(
        'wp' => array(),
        'enhanced' => array(),
        'taxonomies' => array(),
        'yourls' => array(
            new gdr2_Setting_Element('cpt', "[yourls_active_link]", "features", "yourls", __("Generate Short URL", "gd-taxonomies-tools"), __("After new post is published, Yourls will generate shortlink for it.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["yourls_active_link"] == "yes")
        ),
    ),
    'advanced' => array(
        'info' => array(
            new gdr2_Setting_Element('cpt', '[info]', 'advanced', 'info', '', __("Do not change anything on this page if you are not sure what are you doing.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO),
        ),
        'caps_basic' => array(
            new gdr2_Setting_Element('cpt', '[capabilites]', 'advanced', 'caps_basic', __("Capabilites", "gd-taxonomies-tools"), __("Method to create capabilities for post type.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $cpt["capabilites"], 'array', array("type" => __("Use Capability Base", "gd-taxonomies-tools"), "list" => __("Use Capabilities List", "gd-taxonomies-tools"))),
            new gdr2_Setting_Element('cpt', '[caps_type]', 'advanced', 'caps_basic', __("Capability Base", "gd-taxonomies-tools"), __("Base capability type that will be used to generate all capabilites needed for the post type. Usually, this value is comparable to post type name. This can be shared across more than one post type.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $cpt["caps_type"]),
        ),
        'caps_list' => array(
            new gdr2_Setting_Element('cpt', "[button][caps_generate]", "advanced", "caps_list", '', __("Click this button to generate capabilities names based on the capability base set above.", "gd-taxonomies-tools"), "button", '', '', '', array("href" => "javascript:capabilities_generate_posttype()", "link" => __("Auto Generate", "gd-taxonomies-tools"))),
            new gdr2_Setting_Element('cpt', "[button][caps_auto]", "advanced", "caps_list", '', __("Click this button to automatically fill all capabilities with default values, if some of them are empty. Default values are based on default 'post' post type.", "gd-taxonomies-tools"), "button", '', '', '', array("href" => "javascript:capabilities_posttype()", "link" => __("Auto Fill Defaults", "gd-taxonomies-tools"))),
            new gdr2_Setting_Element('cpt', "[caps][edit_post]", "advanced", "caps_list", __("Edit Post", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["caps"]["edit_post"]),
            new gdr2_Setting_Element('cpt', "[caps][edit_posts]", "advanced", "caps_list", __("Edit Posts", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["caps"]["edit_posts"]),
            new gdr2_Setting_Element('cpt', "[caps][edit_private_posts]", "advanced", "caps_list", __("Edit Private Posts", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["caps"]["edit_private_posts"]),
            new gdr2_Setting_Element('cpt', "[caps][edit_published_posts]", "advanced", "caps_list", __("Edit Published Posts", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["caps"]["edit_published_posts"]),
            new gdr2_Setting_Element('cpt', "[caps][edit_others_posts]", "advanced", "caps_list", __("Edit Others Posts", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["caps"]["edit_others_posts"]),
            new gdr2_Setting_Element('cpt', "[caps][publish_posts]", "advanced", "caps_list", __("Publish Posts", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["caps"]["publish_posts"]),
            new gdr2_Setting_Element('cpt', "[caps][read]", "advanced", "caps_list", __("Read", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["caps"]["read"]),
            new gdr2_Setting_Element('cpt', "[caps][read_post]", "advanced", "caps_list", __("Read Post", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["caps"]["read_post"]),
            new gdr2_Setting_Element('cpt', "[caps][read_private_posts]", "advanced", "caps_list", __("Read Private Posts", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["caps"]["read_private_posts"]),
            new gdr2_Setting_Element('cpt', "[caps][delete_post]", "advanced", "caps_list", __("Delete Post", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["caps"]["delete_post"]),
            new gdr2_Setting_Element('cpt', "[caps][delete_posts]", "advanced", "caps_list", __("Delete Posts", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["caps"]["delete_posts"]),
            new gdr2_Setting_Element('cpt', "[caps][delete_private_posts]", "advanced", "caps_list", __("Delete Private Posts", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["caps"]["delete_private_posts"]),
            new gdr2_Setting_Element('cpt', "[caps][delete_published_posts]", "advanced", "caps_list", __("Delete Published Posts", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["caps"]["delete_published_posts"]),
            new gdr2_Setting_Element('cpt', "[caps][delete_others_posts]", "advanced", "caps_list", __("Delete Others Posts", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["caps"]["delete_others_posts"])
        ),
        'miscellaneous' => array(
            new gdr2_Setting_Element('cpt', '[edit_link]', 'advanced', 'miscellaneous', __("Edit Link", "gd-taxonomies-tools"), __("Post type edit link. Do not change this value unless you are sure you know what this will do. Value for this must be set!", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $cpt["edit_link"]),
            new gdr2_Setting_Element('cpt', '[can_export]', 'advanced', 'miscellaneous', __("Can be Exported", "gd-taxonomies-tools"), __("Posts can be exported.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["can_export"] == "yes"),
        )
    )
);

global $taxonomies_codes, $post_type_name;
$taxonomies_codes = array();
$post_type_name = $cpt['name'];

if ($cpt['id'] == -1) {
    $e['basics']['info'] = array(
            new gdr2_Setting_Element('cpt', '[info]', 'basics', 'info', '', __("You are editing built in post type. Be careful with this, you can break WordPress installation.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO),
        );
    $e['basics']['status'] = array(
            new gdr2_Setting_Element('cpt', '[active]', 'basics', 'status', __("Settings Override", "gd-taxonomies-tools"), __("Control scope of the settings to be overriden by this plugin.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $cpt["active"], 'array', array("no" => __("No", "gd-taxonomies-tools"), "full" => __("Full", "gd-taxonomies-tools"), "simple" => __("Simple", "gd-taxonomies-tools"))),
        );
    $e['advanced']['permalinks'] = array(
            new gdr2_Setting_Element('cpt', '[info]', 'advanced', 'permalinks', '', __("This is available only for custom post types created with this plugin.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO),
        );
} else {
    if ($cpt['permalinks_structure'] == '') {
        $cpt['permalinks_structure'] = $post_type_name.'/%'.$post_type_name.'%/';
    }

    $e['basics']['name'][] = new gdr2_Setting_Element('cpt', '[icon]', 'basics', 'name', __("Icon", "gd-taxonomies-tools"), __("Use one of the built in icons.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $cpt["icon"], 'array', $all_icons);
    $e['basics']['status'] = array(
            new gdr2_Setting_Element('cpt', '[active]', 'basics', 'status', __("Active", "gd-taxonomies-tools"), __("If you deactivate this custom post type, all the posts associated with it will remain intact, but inaccessable while the post type is not active.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["active"] == 1),
        );
}

if ($cpt['id'] == -1) {
    $e['rewriting']['info'] = array(
        new gdr2_Setting_Element('cpt', '[info]', 'rewriting', 'info', '', __("Be careful with rewrite rules, because you may cause conflict with other rewrite rules. Test to make sure that everything is working.", "gd-taxonomies-tools").' '.__("For post types not registered with this plugin there is no guarantee that this will work at all.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO)
    );
} else {
    $e['rewriting']['info'] = array(
        new gdr2_Setting_Element('cpt', '[info]', 'rewriting', 'info', '', __("Be careful with rewrite rules, because you may cause conflict with other rewrite rules. Test to make sure that everything is working.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO)
    );
}

if (!$cpt_built_in) {
    $e['rewriting']['permalinks'] = array(
            new gdr2_Setting_Element('cpt', '[permalinks_active]', 'rewriting', 'permalinks', __("Custom Permalinks", "gd-taxonomies-tools"), __("Activate use of custom structure for the single post permalink. Depending on the rules you made, you may end up with the conflict with some other rules and that will result in 404 page. Test different setups to make sure that there is no conflict.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["permalinks_active"] == "yes"),
            new gdr2_Setting_Element('cpt', '[permalinks_structure]', 'rewriting', 'permalinks', __("Permalinks Structure", "gd-taxonomies-tools"), __("Link structure for single posts for this post type. Use only taxonomies you have set for post type, or they will be replaced by a dash.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $cpt["permalinks_structure"])
        );
    $e['rewriting']['archvlinks'] = array(
            new gdr2_Setting_Element('cpt', "[date_archives]", 'rewriting', 'archvlinks', __("Date based archives", "gd-taxonomies-tools"), __("Generate rewrite structures for post type archives for dates.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["date_archives"] == "yes"),
            new gdr2_Setting_Element('cpt', "[intersections]", 'rewriting', 'archvlinks', __("Archive taxonomy intersection", "gd-taxonomies-tools"), __("Allow for intersection by combining custom post type and taxonomies. Taxonomy name and term will be added to the post type archive to filter posts by term.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $cpt["intersections"], 'array', array("no" => __("No archive intersections", "gd-taxonomies-tools"), "yes" => __("Simple archive intersections", "gd-taxonomies-tools"), "adv" => __("Advanced intersections", "gd-taxonomies-tools"), "max" => __("Simple and Advanced intersections", "gd-taxonomies-tools"))),
            new gdr2_Setting_Element('cpt', "[intersections_structure]", 'rewriting', 'archvlinks', __("Permalinks Structure", "gd-taxonomies-tools"), __("Link structure for archives for this post type. Use only taxonomies you have set for post type, or you will get no results for display. Use only taxonmies separated by slash characters.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $cpt["intersections_structure"]),
            new gdr2_Setting_Element('cpt', "[intersections_partial]", 'rewriting', 'archvlinks', __("Generate partial intersections", "gd-taxonomies-tools"), __("If you set to use 2 or more taxonomies for advanced intersection, with this option plugin will generate rules with partial taxonomies. For 4 taxonomies in intersection, plugin with generate rules for 1, 2 and 3 also. Be careful with this option, it can generate petentially conflicting rules.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["intersections_partial"] == "yes")
        );
}

if ($cpt['id'] > 0) {
    $e['basics']['info'] = array(
            new gdr2_Setting_Element('cpt', "[info]", 'basics', "info", '', __("You are editing custom post type.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO),
        );
}

if ($cpt['id'] == 0) {
    $e['basics']['info'] = array(
            new gdr2_Setting_Element('cpt', "[info]", 'basics', "info", '', __("Creating new post type, naming convention and restrictions apply.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO),
        );
}

foreach ($post_features as $code => $data) {
    $e['features']['wp'][] = new gdr2_Setting_Element('cpt', '[supports]['.$code.']', 'features', 'wp', $data["label"], $data["info"], gdr2_Setting_Type::BOOLEAN, in_array($code, $cpt["supports"]));
}

foreach ($post_features_special as $code => $data) {
    $e['features']['enhanced'][] = new gdr2_Setting_Element('cpt', '[enhanced]['.$code.']', 'features', 'enhanced', $data["label"], $data["info"], gdr2_Setting_Type::BOOLEAN, in_array($code, $cpt["special"]));
}

$taxonomies = apply_filters('gdcpt_modules_post_types_taxonomies', $wp_taxonomies);
foreach ($taxonomies as $code => $tax) {
    $e['features']['taxonomies'][] = new gdr2_Setting_Element('cpt', '[taxonomies]['.$code.']', 'features', 'taxonomies', $tax->label, '', gdr2_Setting_Type::BOOLEAN, in_array($code, $cpt["taxonomies"]));

    if (!in_array($code, array('link_category', 'post_format', 'nav_menu'))) {
        $taxonomies_codes[] = '%'.$code.'%';
    }
}

if (!empty($modules_groups)) {
    $p[] = new gdr2_Setting_Panel('modules', __("Modules", "gd-taxonomies-tools"), __("extended setup", "gd-taxonomies-tools"), __("Control settings added by the plugin modules.", "gd-taxonomies-tools"), $modules_groups);

    foreach ($modules_groups as $module) {
        $e['modules'][$module->name] = apply_filters('gdcpt_modules_post_types_group_settings_'.$module->name, array(), $cpt);
    }
}

$default_caps_cpt = json_encode($gdtt->post_type_caps);

include_once(GDTAXTOOLS_PATH.'forms/cpt/permalinks.php');

?>

<script type='text/javascript'>
    function capabilities_generate_posttype() {
        var fields = <?php echo $default_caps_cpt; ?>;
        var base_value = jQuery("#cpt_caps_type").val().trim();
        jQuery.each(fields, function(idx, vle) {
            vle = vle.replace(/post/, base_value);
            jQuery("#cpt_caps_" + idx).val(vle);
        });
    }

    function capabilities_posttype() {
        var fields = <?php echo $default_caps_cpt; ?>;
        jQuery.each(fields, function(idx, vle) {
            jQuery("#cpt_caps_" + idx).val(vle);
        });
    }

    function autofill_posttype() {
        var name = jQuery("#cpt_labels_name").val();
        var singular_name = jQuery("#cpt_labels_singular_name").val();
        if (name == '' || singular_name == '') {
            alert('<?php _e("Both name and singular name must be filled first.", "gd-taxonomies-tools") ?>');
        } else {
            jQuery("#cpt_labels_add_new").val('<?php _e("Add New", "gd-taxonomies-tools"); ?>');
            jQuery("#cpt_labels_edit").val('<?php _e("Edit", "gd-taxonomies-tools"); ?>');
            jQuery("#cpt_labels_add_new_item").val('<?php _e("Add New", "gd-taxonomies-tools"); ?> ' + singular_name);
            jQuery("#cpt_labels_edit_item").val('<?php _e("Edit", "gd-taxonomies-tools"); ?> ' + singular_name);
            jQuery("#cpt_labels_new_item").val('<?php _e("New", "gd-taxonomies-tools"); ?> ' + singular_name);
            jQuery("#cpt_labels_view_item").val('<?php _e("View", "gd-taxonomies-tools"); ?> ' + singular_name);
            jQuery("#cpt_labels_search_items").val('<?php _e("Search", "gd-taxonomies-tools"); ?> ' + name);
            jQuery("#cpt_labels_not_found").val('<?php _e("No", "gd-taxonomies-tools"); ?> ' + name + ' <?php _e("Found", "gd-taxonomies-tools") ?>');
            jQuery("#cpt_labels_not_found_in_trash").val('<?php _e("No", "gd-taxonomies-tools"); ?> ' + name + ' <?php _e("Found In Trash", "gd-taxonomies-tools") ?>');
            jQuery("#cpt_labels_parent_item_colon").val('<?php _e("Parent", "gd-taxonomies-tools"); ?> ' + name + ':');
            jQuery("#cpt_labels_all_items").val('<?php _e("All", "gd-taxonomies-tools"); ?> ' + name);
            jQuery("#cpt_labels_menu_name").val(name);
        }
    }

    gdCPTTools.cookie_name = "wp-gdcpt-settings-cpt-full";
    jQuery(document).ready(function() {
        gdCPTAdmin.panel.post_types.init();
        gdCPTAdmin.save_cpt_full(<?php echo $cpt["id"] > -1 ? 1 : 0; ?>, <?php echo $cpt["id"]; ?>, "<?php echo $cpt["name"]; ?>", "gdr2dialog_cpt_full");

        jQuery("#gdtt-internal-toggle").click(function(){
            if (jQuery(this).hasClass("toggle-arrow-on")) {
                jQuery(this).removeClass("toggle-arrow-on");
                jQuery(this).next().slideUp();
            } else {
                jQuery(this).addClass("toggle-arrow-on");
                jQuery(this).next().slideDown();
            }
        });

        jQuery("input:radio.tog").change(function(){
            var rule = this.value;
            jQuery("#cpt_permalinks_structure").val(rule);
        });
    });
</script>
<div class="gdcpt-settings">
<form action='' id="gdcpt-settings-form" method="post">
    <input name="gdr2_action" type="hidden" value="cpt-full" />
    <input name="gdr2_editable" type="hidden" value="<?php echo $editable ? 'yes' : 'no'; ?>" />
    <input name="cpt[id]" type="hidden" value="<?php echo $cpt['id']; ?>" />
    <input name="cpt[source]" type="hidden" value="<?php echo $cpt['source']; ?>" />
    <div id="tabs">
        <ul><?php $i = 1;
            foreach ($p as $panel) {
                echo sprintf('<li><a href="#tabs-%s">%s</a><div>%s</div></li>', $i, $panel->title, $panel->subtitle);
                $i++;
            }
        ?></ul>
        <?php
            $i = 1;
            foreach ($p as $panel) {
                echo sprintf('<div id="tabs-%s"><div class="gdr2-panel gdr2-panel-%s">%s', $i, $panel->name, GDR2_EOL);
                foreach ($panel->groups as $group) {
                    $elements = $e[$panel->name][$group->name];
                    $group->base_url = GDTAXTOOLS_URL;
                    $group->render($r, $panel->name, $elements);
                }
                echo '</div></div>'.GDR2_EOL;
                $i++;
            }
        ?>
    </div>
    <input style="margin-top: 10px;" type="submit" value="<?php _e("Save Settings", "gd-taxonomies-tools"); ?>" name="gdsr-save" class="pressbutton" />
</form>
</div>