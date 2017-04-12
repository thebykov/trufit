<?php

require_once(GDTAXTOOLS_PATH.'gdr2/plugin/gdr2.settings.admin.php');
require_once(GDTAXTOOLS_PATH.'code/internal/render.php');

global $gdtt;

$modules_groups = apply_filters('gdcpt_modules_taxonomies_groups', array(), true);

$r = new gdCPTRender();
$r->base = 'tax';

$p = array(
    new gdr2_Setting_Panel('basics', __("Basics", "gd-taxonomies-tools"), __("core and labels", "gd-taxonomies-tools"), __("Name, description and status for taxonomy.", "gd-taxonomies-tools"), array(
        new gdr2_Setting_Group('info', __("Info", "gd-taxonomies-tools"), "", true, false, false),
        new gdr2_Setting_Group('name', __("Name", "gd-taxonomies-tools"), __("Name for the taxonomy. Restricitions apply.", "gd-taxonomies-tools"), true),
        new gdr2_Setting_Group('status', __("Status", "gd-taxonomies-tools"), __("Activity status for the taxonomy.", "gd-taxonomies-tools"), true)
    )),
    new gdr2_Setting_Panel('features', __("Features", "gd-taxonomies-tools"), __("standard and enhanced", "gd-taxonomies-tools"), __("Standard features, taxonomies, enhanced features for post type.", "gd-taxonomies-tools"), array(
        new gdr2_Setting_Group('meta_box', __("Meta Box Format", "gd-taxonomies-tools"), __("Control of the display of the meta box.", "gd-taxonomies-tools")),
        new gdr2_Setting_Group('visibility', __("Visibility", "gd-taxonomies-tools"), __("Menu intergration and other UI related options.", "gd-taxonomies-tools")),
        new gdr2_Setting_Group('enhanced', __("GD CPT Tools Enhanced", "gd-taxonomies-tools"), __("Enahanced features added by this plugin.", "gd-taxonomies-tools")),
        new gdr2_Setting_Group('post_types', __("Post Types", "gd-taxonomies-tools"), __("Post types to register taxonomies with.", "gd-taxonomies-tools"))
    ))
);

$e = array(
    'basics' => array(
        'info' => array(
            new gdr2_Setting_Element('tax', "[info]", 'basics', 'info', "", __("This is panel for only some settings that can be considered safe to change and override,<br/>and enhanced features implemented by this plugin.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO),
        ),
        'name' => array(
            new gdr2_Setting_Element('tax', '[name]', 'basics', 'name', __("Name", "gd-taxonomies-tools"), __("Names of default taxonomies or custom taxonomies that are not created with this plugin, can't be changed", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $tax["name"], "", "", array("readonly" => true))
        ),
        'status' => array(
            new gdr2_Setting_Element('tax', '[active]', 'basics', 'status', __("Settings Override", "gd-taxonomies-tools"), __("Control scope of the settings to be overriden by this plugin.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $tax["active"], "array", array("no" => __("No", "gd-taxonomies-tools"), "full" => __("Full", "gd-taxonomies-tools"), "simple" => __("Simple", "gd-taxonomies-tools"))),
        ),
    ),
    'features' => array(
        'meta_box' => array(
            new gdr2_Setting_Element('tax', '[metabox]', 'features', 'meta_box', __("Meta box format", "gd-taxonomies-tools"), __("Plugin implements two additional formats that don't allow adding of new terms, they are limited to existing terms only.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $tax["metabox"], "array", array("auto" => __("Automatic / Default", "gd-taxonomies-tools"), "hide" => __("Hidden", "gd-taxonomies-tools"), "limited_single" => __("Limited: Single Terms", "gd-taxonomies-tools"), "limited_multi" => __("Limited: Multi Terms", "gd-taxonomies-tools"))),
        ),
        'visibility' => array(
            new gdr2_Setting_Element('tax', '[show_admin_column]', 'features', 'visibility', __("Show Admin Column", "gd-taxonomies-tools"), __("Add column for this taxonomy to post edit list.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax['show_admin_column'] == 'yes'),
            new gdr2_Setting_Element('tax', '[cloud]', 'features', 'visibility', __("Tag Cloud Widget", "gd-taxonomies-tools"), __("Taxonomy is available for use in the terms cloud.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax['cloud'] == 'yes'),
            new gdr2_Setting_Element('tax', '[nav_menus]', 'features', 'visibility', __("Show In Navigation Menus", "gd-taxonomies-tools"), __("Taxonomy is made available for selecting in the navigation menu designer.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, $tax['nav_menus'] == 'yes')
        )
    )
);

foreach ($taxonomy_features_special as $code => $data) {
    $e['features']['enhanced'][] = new gdr2_Setting_Element("tax", '[enhanced]['.$code.']', 'features', 'enhanced', $data["label"], $data["info"], gdr2_Setting_Type::BOOLEAN, in_array($code, $tax["special"]));
}
foreach ($post_types as $pt) {
    $e['features']['post_types'][] = new gdr2_Setting_Element("tax", '[post_types]['.$pt->name.']', 'features', 'post_types', $pt->label, "", gdr2_Setting_Type::BOOLEAN, in_array($pt->name, $tax["domain"]));
}

if (!empty($modules_groups)) {
    $p[] = new gdr2_Setting_Panel('modules', __("Modules", "gd-taxonomies-tools"), __("extended setup", "gd-taxonomies-tools"), __("Control settings added by the plugin modules.", "gd-taxonomies-tools"), $modules_groups);

    foreach ($modules_groups as $module) {
        $e['modules'][$module->name] = apply_filters('gdcpt_modules_taxonomies_group_settings_'.$module->name, array(), $tax);
    }
}

?>

<script type='text/javascript'>
    gdCPTTools.cookie_name = "wp-gdcpt-settings-tax-simple";
    jQuery(document).ready(function() { gdCPTAdmin.save_tax_simple("<?php echo $tax['name']; ?>", "gdr2dialog_tax_simple"); });
</script>
<div class="gdcpt-settings">
<form action="" id="gdcpt-settings-form" method="post">
    <input name="gdr2_action" type="hidden" value="tax-simple" />
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