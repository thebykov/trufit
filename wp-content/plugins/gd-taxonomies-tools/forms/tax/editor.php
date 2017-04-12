<?php

require_once(GDTAXTOOLS_PATH.'gdr2/plugin/gdr2.settings.admin.php');
require_once(GDTAXTOOLS_PATH.'code/internal/render.php');

global $gdtt;

$editable = true;

if ($tax['id'] == -1) {
    $editable = false;
} else if ($tax['source'] != '') {
    $editable = false;
} else if (intval($tax['id']) > 0) {
    $editable = gdCPTDB::real_count_taxonomy($tax['name']) == 0;
}

if (!isset($tax["special"]) || !is_array($tax["special"])) {
    $tax["special"] = array();
}

$modules_groups = apply_filters('gdcpt_modules_taxonomies_groups', array(), false, $tax['name'], $tax['id']);

$r = new gdCPTRender();
$r->base = 'tax';

$p = array(
    new gdr2_Setting_Panel('basics', __("Basics", "gd-taxonomies-tools"), __("core and labels", "gd-taxonomies-tools"), __("Name, status, description and labels for post type.", "gd-taxonomies-tools"), array(
        new gdr2_Setting_Group('info', __("Info", "gd-taxonomies-tools"), '', true, false, false),
        new gdr2_Setting_Group('name', __("Name", "gd-taxonomies-tools"), __("Name for the taxonomy. Restricitions apply.", "gd-taxonomies-tools"), true),
        new gdr2_Setting_Group('status', __("Status", "gd-taxonomies-tools"), __("Activity status for the taxonomy.", "gd-taxonomies-tools"), true),
        new gdr2_Setting_Group('labels_basic', __("Standard Labels", "gd-taxonomies-tools"), __("Main singular and plural labels.", "gd-taxonomies-tools"), true),
        new gdr2_Setting_Group('labels_expanded', __("Expanded Labels", "gd-taxonomies-tools"), __("Additional labels used by WordPress interface.", "gd-taxonomies-tools"))
    )),
    new gdr2_Setting_Panel('settings', __("Settings", "gd-taxonomies-tools"), __("rewrite and visibiltiy", "gd-taxonomies-tools"), __("Rewriting, hierarchy, navigation, UI and query for post type.", "gd-taxonomies-tools"), array(
        new gdr2_Setting_Group('hierarchy', __("Hierarchy", "gd-taxonomies-tools"), __("Hierarchy for the taxonomy.", "gd-taxonomies-tools")),
        new gdr2_Setting_Group('query', __("Query", "gd-taxonomies-tools"), __("Query settings for the taxonomy.", "gd-taxonomies-tools")),
        new gdr2_Setting_Group('rewrite', __("Rewriting", "gd-taxonomies-tools"), __("Options for setting the rewriting for terms belonging to the taxonomy.", "gd-taxonomies-tools")),
        new gdr2_Setting_Group('visibility', __("Visibility", "gd-taxonomies-tools"), __("Public visibiltiy, menu intergration and other UI related options.", "gd-taxonomies-tools"))
    )),
    new gdr2_Setting_Panel('features', __("Features", "gd-taxonomies-tools"), __("standard and enhanced", "gd-taxonomies-tools"), __("Standard features, taxonomies, enhanced features for post type.", "gd-taxonomies-tools"), array(
        new gdr2_Setting_Group('meta_box', __("Meta Box Format", "gd-taxonomies-tools"), __("Control of the display of the meta box.", "gd-taxonomies-tools")),
        new gdr2_Setting_Group('enhanced', __("GD CPT Tools Enhanced", "gd-taxonomies-tools"), __("Enahanced features added by this plugin.", "gd-taxonomies-tools")),
        new gdr2_Setting_Group('post_types', __("Post Types", "gd-taxonomies-tools"), __("Post types to register taxonomies with.", "gd-taxonomies-tools"))
    )),
    new gdr2_Setting_Panel('rewriting', __("Rewrite", "gd-taxonomies-tools"), __("archive and extra", "gd-taxonomies-tools"), __("Additional rewriting rules for taxonomy archives.", "gd-taxonomies-tools"), array(
        new gdCPT_Setting_Group('info', __("Info", "gd-taxonomies-tools"), '', true, false, false),
        new gdCPT_Setting_Group('archvlinks', __("Taxonomy Index Rewriting", "gd-taxonomies-tools"), __("Additional rules for taxonomy terms index page.", "gd-taxonomies-tools"), false, true, true)
    )),
    new gdr2_Setting_Panel('advanced', __("Advanced", "gd-taxonomies-tools"), __("capabilities and more", "gd-taxonomies-tools"), __("Capability, capabilities type and list with more post type settings.", "gd-taxonomies-tools"), array(
        new gdr2_Setting_Group('info', __("Info", "gd-taxonomies-tools"), '', true, false, false),
        new gdr2_Setting_Group('caps_basic', __("Basic Capabilities Settings", "gd-taxonomies-tools"), ''),
        new gdr2_Setting_Group('caps_list', __("List of individual Capabilities", "gd-taxonomies-tools"), '')
    ))
);

$e = array(
    'basics' => array(
        'info' => array(),
        'name' => array(
            new gdr2_Setting_Element('tax', "[name]", 'basics', "name", __("Name", "gd-taxonomies-tools"), __("Unique name, not used with any other data type, with maximum 20 characters in length. Use only lower case letters with no special characters except for the dash. Name will be checked against list of reserverd words to avoid conflicts. Once set, name can be changed only for custom post types when there are no posts for it.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $tax["name"], '', '', array("readonly" => !$editable)),
            new gdr2_Setting_Element('tax', "[description]", 'basics', "name", __("Description", "gd-taxonomies-tools"), __("Value is not required but it can be useful as summary on what taxonomy is about.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT_BLOCK, $tax["description"])
        ),
        'status' => array(),
        'labels_basic' => array(
            new gdr2_Setting_Element('tax', "[labels][name]", 'basics', "labels_basic", __("Plural", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["name"]),
            new gdr2_Setting_Element('tax', "[labels][singular_name]", 'basics', "labels_basic", __("Singular", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["singular_name"]),
        ),
        'labels_expanded' => array(
            new gdr2_Setting_Element('tax', "[button][labels]", 'basics', "labels_expanded", '', __("Click this button to automatically fill expanded labes based on standard singular and plural labels.", "gd-taxonomies-tools"), "button", '', '', '', array("href" => "javascript:autofill_taxonomy()", "link" => __("Auto Fill", "gd-taxonomies-tools"))),
            new gdr2_Setting_Element('tax', "[labels][search_items]", 'basics', "labels_expanded", __("Search Items", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["search_items"]),
            new gdr2_Setting_Element('tax', "[labels][popular_items]", 'basics', "labels_expanded", __("Popular Items", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["popular_items"]),
            new gdr2_Setting_Element('tax', "[labels][all_items]", 'basics', "labels_expanded", __("All Items", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["all_items"]),
            new gdr2_Setting_Element('tax', "[labels][parent_item]", 'basics', "labels_expanded", __("Parent Item", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["parent_item"]),
            new gdr2_Setting_Element('tax', "[labels][parent_item_colon]", 'basics', "labels_expanded", __("Parent Item Colon", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["parent_item_colon"]),
            new gdr2_Setting_Element('tax', "[labels][edit_item]", 'basics', "labels_expanded", __("Edit Item", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["edit_item"]),
            new gdr2_Setting_Element('tax', "[labels][view_item]", 'basics', "labels_expanded", __("View Item", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["view_item"]),
            new gdr2_Setting_Element('tax', "[labels][update_item]", 'basics', "labels_expanded", __("Update Item", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["update_item"]),
            new gdr2_Setting_Element('tax', "[labels][add_new_item]", 'basics', "labels_expanded", __("Add New Item", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["add_new_item"]),
            new gdr2_Setting_Element('tax', "[labels][new_item_name]", 'basics', "labels_expanded", __("New Item Name", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["new_item_name"]),
            new gdr2_Setting_Element('tax', "[labels][separate_items_with_commas]", 'basics', "labels_expanded", __("Separate with Commas", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["separate_items_with_commas"]),
            new gdr2_Setting_Element('tax', "[labels][add_or_remove_items]", 'basics', "labels_expanded", __("Add or Remove Items", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["add_or_remove_items"]),
            new gdr2_Setting_Element('tax', "[labels][choose_from_most_used]", 'basics', "labels_expanded", __("Choose from Most Used", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["choose_from_most_used"]),
            new gdr2_Setting_Element('tax', "[labels][not_found]", 'basics', "labels_expanded", __("Not Found", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["not_found"]),
            new gdr2_Setting_Element('tax', "[labels][menu_name]", 'basics', "labels_expanded", __("Menu Name", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["menu_name"])
        )
    ),
    'settings' => array(
        'hierarchy' => array(
            new gdr2_Setting_Element('tax', "[hierarchy]", "settings", "hierarchy", __("Hierarchy", "gd-taxonomies-tools"), __("Hierarchical taxonomy allows for terms to have child terms.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax["hierarchy"] == "yes"),
        ),
        'query' => array(
            new gdr2_Setting_Element('tax', "[query]", "settings", "query", __("Query Variable", "gd-taxonomies-tools"), __("Variable for the taxonomy used for Query objects.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $tax["query"], 'array', array('yes_name' => __("Yes, using name", "gd-taxonomies-tools"), 'yes_custom' => __("Yes, using custom value", "gd-taxonomies-tools"), 'no' => __("No", "gd-taxonomies-tools"))),
            new gdr2_Setting_Element('tax', "[query_custom]", "settings", "query", __("Custom Query Variable Name", "gd-taxonomies-tools"), __("Custom variable for query.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $tax["query_custom"], '', '', array('class' => 'limit-query-safe'))
        ),
        'rewrite' => array(
            new gdr2_Setting_Element('tax', "[rewrite]", "settings", "rewrite", __("Rewrite", "gd-taxonomies-tools"), __("Create rewrite permalinks rules for taxonomy.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $tax["rewrite"], 'array', array('yes_name' => __("Yes, using name", "gd-taxonomies-tools"), 'yes_custom' => __("Yes, using custom value", "gd-taxonomies-tools"), 'no' => __("No", "gd-taxonomies-tools"))),
            new gdr2_Setting_Element('tax', "[rewrite_custom]", "settings", "rewrite", __("Custom Rewrite Slug", "gd-taxonomies-tools"), __("Custom slug for rewrite rules.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $tax["rewrite_custom"], '', '', array('class' => 'limit-url-safe')),
            new gdr2_Setting_Element('tax', "[rewrite_hierarchy]", "settings", "rewrite", __("Hierarchy", "gd-taxonomies-tools"), __("Allow generating hierarchical rewrite rules for hierarchical taxonomies.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax["rewrite_hierarchy"] == "yes"),
            new gdr2_Setting_Element('tax', "[rewrite_front]", "settings", "rewrite", __("With Front", "gd-taxonomies-tools"), __("Permalink rules will be prepended with front base permalink structure.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax["rewrite_front"] == "yes")
        ),
        'visibility' => array(
            new gdr2_Setting_Element('tax', "[public]", "settings", "visibility", __("Public", "gd-taxonomies-tools"), __("Basic setting for other public related variables for UI and manus.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax["public"] == "yes"),
            new gdr2_Setting_Element('tax', "[ui]", "settings", "visibility", __("Show UI", "gd-taxonomies-tools"), __("Generate default UI elements for the post type.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax["ui"] == "yes"),
            new gdr2_Setting_Element('tax', "[show_admin_column]", "settings", "visibility", __("Show Admin Column", "gd-taxonomies-tools"), __("Add column for this taxonomy to post edit list.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax["show_admin_column"] == "yes"),
            new gdr2_Setting_Element('tax', "[cloud]", "settings", "visibility", __("Tag Cloud Widget", "gd-taxonomies-tools"), __("Taxonomy is available for use in the terms cloud.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax["cloud"] == "yes"),
            new gdr2_Setting_Element('tax', "[nav_menus]", "settings", "visibility", __("Show In Navigation Menus", "gd-taxonomies-tools"), __("Taxonomy is made available for selecting in the navigation menu designer.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax["nav_menus"] == "yes"),
            new gdr2_Setting_Element('tax', "[sort]", "settings", "visibility", __("Sort Order", "gd-taxonomies-tools"), __("Remember order of adding terms to objects. Wehn enabled, this can slow down operations for adding terms.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax["sort"] == "yes")
        )
    ),
    'features' => array(
        'meta_box' => array(
            new gdr2_Setting_Element('tax', "[metabox]", "features", "meta_box", __("Meta box format", "gd-taxonomies-tools"), __("Plugin implements two additional formats that don't allow adding of new terms, they are limited to existing terms only.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $tax["metabox"], 'array', array("auto" => __("Automatic / Default", "gd-taxonomies-tools"), "hide" => __("Hidden", "gd-taxonomies-tools"), "limited_single" => __("Limited: Single Terms", "gd-taxonomies-tools"), "limited_multi" => __("Limited: Multi Terms", "gd-taxonomies-tools"))),
        ),
        'enhanced' => array(),
        'post_types' => array()
    ),
    'advanced' => array(
        'info' => array(
            new gdr2_Setting_Element('tax', "[info]", "advanced", "info", '', __("Do not change anything on this page if you are not sure what are you doing. Default values here will make sure that post type created behaves similar to 'post' in terms of usability.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO),
        ),
        'caps_basic' => array(
            new gdr2_Setting_Element('tax', "[caps_type]", "advanced", "caps_basic", __("Capability Base", "gd-taxonomies-tools"), __("Base capability type that will be used to generate all capabilites needed for the taxonomy. Usually, this value is comparable to categories. This can be shared across more than one taxonomy. If you set custom value here, you must use Auto Generate button bellow to fill actual capabilities.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $tax["caps_type"]),
        ),
        'caps_list' => array(
            new gdr2_Setting_Element('tax', "[button][caps_generate]", "advanced", "caps_list", '', __("Click this button to generate capabilities names based on the capability base set above.", "gd-taxonomies-tools"), "button", '', '', '', array("href" => "javascript:capabilities_generate_taxonomy()", "link" => __("Auto Generate", "gd-taxonomies-tools"))),
            new gdr2_Setting_Element('tax', "[button][caps]", "advanced", "caps_list", '', __("Click this button to automatically fill all capabilities with default values, if some of them are empty.", "gd-taxonomies-tools"), "button", '', '', '', array("href" => "javascript:capabilities_taxonomy()", "link" => __("Auto Fill Defaults", "gd-taxonomies-tools"))),
            new gdr2_Setting_Element('tax', "[caps][manage_terms]", "advanced", "caps_list", __("Manage terms", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["caps"]["manage_terms"]),
            new gdr2_Setting_Element('tax', "[caps][edit_terms]", "advanced", "caps_list", __("Edit terms", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["caps"]["edit_terms"]),
            new gdr2_Setting_Element('tax', "[caps][delete_terms]", "advanced", "caps_list", __("Delete terms", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["caps"]["delete_terms"]),
            new gdr2_Setting_Element('tax', "[caps][assign_terms]", "advanced", "caps_list", __("Assign terms", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["caps"]["assign_terms"])
        )
    )
);

if ($tax['id'] == -1) {
    $e['basics']["info"] = array(
            new gdr2_Setting_Element('tax', '[info]', 'basics', "info", '', __("You are editing built in taxonomy. Be careful with this, you can break WordPress installation.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO),
        );
    $e['basics']["status"] = array(
            new gdr2_Setting_Element('tax', '[active]', 'basics', "status", __("Settings Override", "gd-taxonomies-tools"), __("Control scope of the settings to be overriden by this plugin.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $tax["active"], 'array', array("no" => __("No", "gd-taxonomies-tools"), "full" => __("Full", "gd-taxonomies-tools"), "simple" => __("Simple", "gd-taxonomies-tools"))),
        );
} else {
    $e['basics']["status"] = array(
            new gdr2_Setting_Element('tax', '[active]', 'basics', "status", __("Active", "gd-taxonomies-tools"), __("If you deactivate this custom taxonomy, all the terns associated with it will remain intact, but inaccessable while the taxonomy is not active.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax["active"] == 1 || $tax["active"] == "on"),
        );
}
if ($tax['id'] > 0) {
    $e['basics']["info"] = array(
            new gdr2_Setting_Element('tax', "[info]", 'basics', "info", '', __("You are editing custom taxonomy.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO),
        );
}
if ($tax['id'] == 0) {
    $e['basics']["info"] = array(
            new gdr2_Setting_Element('tax', "[info]", 'basics', "info", '', __("Creating new taxonomy, naming convention and restrictions apply.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO),
        );
}

if ($tax['id'] == -1) {
    $e['rewriting']['info'] = array(
        new gdr2_Setting_Element('tax', '[info]', 'rewriting', 'info', '', __("Be careful with rewrite rules, because you may cause conflict with other rewrite rules. Test to make sure that everything is working.", "gd-taxonomies-tools").' '.__("For post types not registered with this plugin there is no guarantee that this will work at all.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO)
    );
} else {
    $e['rewriting']['info'] = array(
        new gdr2_Setting_Element('tax', '[info]', 'rewriting', 'info', '', __("Be careful with rewrite rules, because you may cause conflict with other rewrite rules. Test to make sure that everything is working.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO)
    );
}

$e['rewriting']['archvlinks'] = array(
    new gdr2_Setting_Element('tax', '[index_normal]', 'rewriting', 'archvlinks', __("Normal archives", "gd-taxonomies-tools"), __("Generate terms index for the taxonomy archive.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax['index_normal'] == 'yes'),
    new gdr2_Setting_Element('tax', '[index_intersect]', 'rewriting', 'archvlinks', __("Intersection archives", "gd-taxonomies-tools"), __("Generate terms index for the taxonomy and post type intersections archive.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax['index_intersect'] == 'yes')
);

foreach ($taxonomy_features_special as $code => $data) {
    $e['features']['enhanced'][] = new gdr2_Setting_Element('tax', "[enhanced][".$code."]", "features", "enhanced", $data["label"], $data["info"], gdr2_Setting_Type::BOOLEAN, in_array($code, $tax["special"]));
}
foreach ($post_types as $pt) {
    $e['features']['post_types'][] = new gdr2_Setting_Element('tax', "[post_types][".$pt->name."]", "features", "post_types", $pt->label, '', gdr2_Setting_Type::BOOLEAN, in_array($pt->name, $tax["domain"]));
}

if (!empty($modules_groups)) {
    $p[] = new gdr2_Setting_Panel('modules', __("Modules", "gd-taxonomies-tools"), __("extended setup", "gd-taxonomies-tools"), __("Control settings added by the plugin modules.", "gd-taxonomies-tools"), $modules_groups);

    foreach ($modules_groups as $module) {
        $e['modules'][$module->name] = apply_filters('gdcpt_modules_taxonomies_group_settings_'.$module->name, array(), $tax);
    }
}

$default_caps_tax = json_encode($gdtt->taxonomy_caps);

?>

<script type='text/javascript'>
    function capabilities_generate_taxonomy() {
        var fields = { manage_terms: 'manage_terms', edit_terms: 'edit_terms', delete_terms: 'delete_terms', assign_terms: 'assign_terms' };
        var base_value = jQuery("#tax_caps_type").val().trim();
        jQuery.each(fields, function(idx, vle) {
            vle = vle.replace(/terms/, base_value);
            jQuery("#tax_caps_" + idx).val(vle);
        });
    }

    function capabilities_taxonomy() {
        var fields = <?php echo $default_caps_tax; ?>;
        jQuery.each(fields, function(idx, vle) {
            jQuery("#tax_caps_" + idx).val(vle);
        });
    }

    function autofill_taxonomy() {
        var name = jQuery("#tax_labels_name").val();
        var singular_name = jQuery("#tax_labels_singular_name").val();
        if (name == "" || singular_name == "") {
            alert('<?php _e("Both name and singular name must be filled first.", "gd-taxonomies-tools") ?>');
        } else {
            jQuery("#tax_labels_parent_item").val('<?php _e("Parent", "gd-taxonomies-tools") ?> ' + singular_name);
            jQuery("#tax_labels_search_items").val('<?php _e("Search", "gd-taxonomies-tools") ?> ' + name);
            jQuery("#tax_labels_popular_items").val('<?php _e("Popular", "gd-taxonomies-tools") ?> ' + name);
            jQuery("#tax_labels_all_items").val('<?php _e("All", "gd-taxonomies-tools") ?> ' + name);
            jQuery("#tax_labels_edit_item").val('<?php _e("Edit", "gd-taxonomies-tools") ?> ' + singular_name);
            jQuery("#tax_labels_view_item").val('<?php _e("View", "gd-taxonomies-tools") ?> ' + singular_name);
            jQuery("#tax_labels_update_item").val('<?php _e("Update", "gd-taxonomies-tools") ?> ' + singular_name);
            jQuery("#tax_labels_add_new_item").val('<?php _e("Add New", "gd-taxonomies-tools") ?> ' + singular_name);
            jQuery("#tax_labels_add_or_remove_items").val('<?php _e("Add or remove", "gd-taxonomies-tools") ?> ' + name);
            jQuery("#tax_labels_choose_from_most_used").val('<?php _e("Choose from the most used", "gd-taxonomies-tools") ?> ' + name);
            jQuery("#tax_labels_parent_item_colon").val('<?php _e("Parent", "gd-taxonomies-tools") ?> ' + singular_name + ':');
            jQuery("#tax_labels_new_item_name").val('<?php _e("New", "gd-taxonomies-tools") ?> ' + singular_name + ' <?php _e("Name", "gd-taxonomies-tools") ?>');
            jQuery("#tax_labels_separate_items_with_commas").val('<?php _e("Separate", "gd-taxonomies-tools") ?> ' + name + ' <?php _e("with commas", "gd-taxonomies-tools") ?>');
            jQuery("#tax_labels_not_found").val('<?php _e("No", "gd-taxonomies-tools") ?> ' + name + ' <?php _e("found", "gd-taxonomies-tools") ?>.');

            jQuery("#tax_labels_menu_name").val(name);
        }
    }

    gdCPTTools.cookie_name = "wp-gdcpt-settings-tax-full";
    jQuery(document).ready(function() {
        gdCPTAdmin.panel.taxonomies.init();
        gdCPTAdmin.save_tax_full(<?php echo $tax['id'] > -1 ? 1 : 0; ?>, <?php echo $tax['id']; ?>, "<?php echo $tax['name']; ?>", "gdr2dialog_tax_full");
    });
</script>
<div class="gdcpt-settings">
<form action="" id="gdcpt-settings-form" method="post">
    <input name="gdr2_action" type="hidden" value="tax-full" />
    <input name="gdr2_editable" type="hidden" value="<?php echo $editable ? "yes" : "no"; ?>" />
    <input name="tax[id]" type="hidden" value="<?php echo $tax["id"]; ?>" />
    <input name="tax[source]" type="hidden" value="<?php echo $tax['source']; ?>" />
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