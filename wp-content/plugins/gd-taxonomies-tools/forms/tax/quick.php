<?php

require_once(GDTAXTOOLS_PATH.'gdr2/plugin/gdr2.settings.admin.php');
require_once(GDTAXTOOLS_PATH.'code/internal/render.php');

global $gdtt;

$modules_groups = apply_filters('gdcpt_modules_taxonomies_groups_quick', array(), false, $tax['name'], $tax['id']);

$r = new gdCPTRender();
$r->base = 'tax';

$p = array(
    new gdr2_Setting_Panel('basics', __("Basics", "gd-taxonomies-tools"), __("core and labels", "gd-taxonomies-tools"), __("Name, status, description and labels for post type.", "gd-taxonomies-tools"), array(
        new gdr2_Setting_Group('name', __("Name", "gd-taxonomies-tools"), __("Name for the post type. Restricitions apply.", "gd-taxonomies-tools"), true),
        new gdCPT_Setting_Group('labels', __("Labels", "gd-taxonomies-tools"), __("Main singular and plural labels.", "gd-taxonomies-tools"), true, true, true)
    )),
    new gdr2_Setting_Panel('settings', __("Settings", "gd-taxonomies-tools"), __("hierarachy and post types", "gd-taxonomies-tools"), __("Basic needed settings for taxonomy.", "gd-taxonomies-tools"), array(
        new gdr2_Setting_Group('hierarchy', __("Hierarchy", "gd-taxonomies-tools"), __("Hierarchy for the taxonomy.", "gd-taxonomies-tools")),
        new gdr2_Setting_Group('post_types', __("Post Types", "gd-taxonomies-tools"), __("Post types to register taxonomies with.", "gd-taxonomies-tools"))
    ))
);

$e = array(
    'basics' => array(
        'name' => array(
            new gdr2_Setting_Element('tax', "[name]", 'basics', "name", __("Name", "gd-taxonomies-tools"), __("Unique name, not used with any other data type, with maximum 20 characters in length. Use only lower case letters with no special characters except for the dash. Name will be checked against list of reserverd words to avoid conflicts. Once set, name can be changed only for custom post types when there are no posts for it.", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $tax["name"])
        ),
        'labels' => array(
            new gdr2_Setting_Element('tax', "[labels][name]", 'basics', "labels", __("Plural", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["name"]),
            new gdr2_Setting_Element('tax', "[labels][singular_name]", 'basics', "labels", __("Singular", "gd-taxonomies-tools"), '', gdr2_Setting_Type::TEXT, $tax["labels"]["singular_name"]),
        )
    ),
    'settings' => array(
        'hierarchy' => array(
            new gdr2_Setting_Element('tax', "[hierarchy]", "settings", "hierarchy", __("Hierarchy", "gd-taxonomies-tools"), __("Hierarchical taxonomy allows for terms to have child terms.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax["hierarchy"] == "yes"),
        ),
        'post_types' => array()
    )
);

foreach ($post_types as $pt) {
    $e['settings']['post_types'][] = new gdr2_Setting_Element('tax', "[post_types][".$pt->name."]", "features", "post_types", $pt->label, '', gdr2_Setting_Type::BOOLEAN, in_array($pt->name, $tax["domain"]));
}

if (!empty($modules_groups)) {
    $p[] = new gdr2_Setting_Panel('modules', __("Modules", "gd-taxonomies-tools"), __("extended setup", "gd-taxonomies-tools"), __("Control settings added by the plugin modules.", "gd-taxonomies-tools"), $modules_groups);

    foreach ($modules_groups as $module) {
        $e['modules'][$module->name] = apply_filters('gdcpt_modules_taxonomies_group_quick_settings_'.$module->name, array(), $tax);
    }
}

?>
<script type='text/javascript'>
    jQuery(document).ready(function() {
        gdCPTAdmin.panel.taxonomies.init();
        gdCPTAdmin.save_tax_quick();
    });
</script>
<div class="gdcpt-settings">
<form action="" id="gdcpt-settings-form" method="post">
    <input name="gdr2_action" type="hidden" value="tax-quick" />
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