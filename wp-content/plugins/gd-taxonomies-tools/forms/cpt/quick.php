<?php

require_once(GDTAXTOOLS_PATH.'gdr2/plugin/gdr2.settings.admin.php');
require_once(GDTAXTOOLS_PATH.'code/internal/render.php');

global $gdtt_icons;
$all_icons = array_merge(array(''), $gdtt_icons->icons);

$modules_groups = apply_filters('gdcpt_modules_post_types_groups_quick', array(), false, $cpt['name'], $cpt['id']);

$r = new gdCPTRender();
$r->base = 'cpt';

$p = array(
    new gdr2_Setting_Panel('basics', __("Basics", "gd-taxonomies-tools"), __("core and labels", "gd-taxonomies-tools"), __("Name, status, description and labels for post type.", "gd-taxonomies-tools"), array(
        new gdCPT_Setting_Group('name', __("Name", "gd-taxonomies-tools"), __("Name for the post type. Restricitions apply.", "gd-taxonomies-tools"), true, true, true),
        new gdCPT_Setting_Group('icon', __("Icon", "gd-taxonomies-tools"), __("Icon to use on the admin side.", "gd-taxonomies-tools"), true, true, true),
        new gdCPT_Setting_Group('labels', __("Labels", "gd-taxonomies-tools"), __("Main singular and plural labels.", "gd-taxonomies-tools"), true, true, true)
    )),
    new gdr2_Setting_Panel('settings', __("Settings", "gd-taxonomies-tools"), __("hierarchy and taxonomies", "gd-taxonomies-tools"), __("Basic needed settings for post type.", "gd-taxonomies-tools"), array(
        new gdCPT_Setting_Group('hierarchy', __("Hierarchy", "gd-taxonomies-tools"), __("Hierarchy for the post type.", "gd-taxonomies-tools"), false, true, true),
        new gdCPT_Setting_Group('taxonomies', __("Taxonomies", "gd-taxonomies-tools"), __("Taxonomies supported by post type.", "gd-taxonomies-tools"), false, true, true)
    ))
);

$e = array(
    'basics' => array(
        'name' => array(
            new gdr2_Setting_Element('cpt', "[name]", 'basics', "name", __("Name", "gd-taxonomies-tools"), __("Unique name, not used with any other data type, with maximum 20 characters in length. Use only lower case letters with no special characters except for the dash. Name will be checked against list of reserverd words to avoid conflicts. Once set, name can be changed only for custom post types when there are no posts for it.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $cpt["name"])
        ),
        'icon' => array(
            new gdr2_Setting_Element('cpt', '[icon]', 'basics', 'icon', __("Icon", "gd-taxonomies-tools"), __("Use one of the built in icons.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $cpt["icon"], "array", $all_icons)
        ),
        'labels' => array(
            new gdr2_Setting_Element('cpt', "[labels][name]", 'basics', "labels", __("Plural", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["name"]),
            new gdr2_Setting_Element('cpt', "[labels][singular_name]", 'basics', "labels", __("Singular", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $cpt["labels"]["singular_name"]),
        )
    ),
    'settings' => array(
        'hierarchy' => array(
            new gdr2_Setting_Element('cpt', "[hierarchy]", "settings", "hierarchy", __("Hierarchy", "gd-taxonomies-tools"), __("Hierarchical post type is equivalent of the pages in WordPress. Posts can have parents.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $cpt["hierarchy"] == "yes")
        ),
        'taxonomies' => array()
    )
);

$taxonomies = apply_filters('gdcpt_modules_post_types_quick_taxonomies', $wp_taxonomies);
foreach ($taxonomies as $code => $tax) {
    $e['settings']['taxonomies'][] = new gdr2_Setting_Element('cpt', '[taxonomies]['.$code.']', 'settings', 'taxonomies', $tax->label, '', gdr2_Setting_Type::BOOLEAN, in_array($code, $cpt["taxonomies"]));
}

if (!empty($modules_groups)) {
    $p[] = new gdr2_Setting_Panel('modules', __("Modules", "gd-taxonomies-tools"), __("extended setup", "gd-taxonomies-tools"), __("Control settings added by the plugin modules.", "gd-taxonomies-tools"), $modules_groups);

    foreach ($modules_groups as $module) {
        $e['modules'][$module->name] = apply_filters('gdcpt_modules_post_types_group_quick_settings_'.$module->name, array(), $cpt);
    }
}

?>

<script type='text/javascript'>
    jQuery(document).ready(function() {
        gdCPTAdmin.panel.post_types.init();
        gdCPTAdmin.save_cpt_quick();
    });
</script>
<div class="gdcpt-settings">
<form action='' id="gdcpt-settings-form" method="post">
    <input name="gdr2_action" type="hidden" value="cpt-quick" />
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