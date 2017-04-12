<?php

require_once(GDTAXTOOLS_PATH.'gdr2/plugin/gdr2.settings.admin.php');
require_once(GDTAXTOOLS_PATH.'code/internal/render.php');

global $gdtt;

$modules_groups = apply_filters('gdcpt_modules_post_types_groups', array(), true);

$r = new gdCPTRender();
$r->base = 'cpt';

$p = array(
    new gdr2_Setting_Panel('basics', __("Basics", "gd-taxonomies-tools"), __("core and labels", "gd-taxonomies-tools"), __("Name, description and status for post type.", "gd-taxonomies-tools"), array(
        new gdr2_Setting_Group('info', __("Info", "gd-taxonomies-tools"), '', true, false, false),
        new gdr2_Setting_Group('name', __("Name", "gd-taxonomies-tools"), __("Name for the post type. Restricitions apply.", "gd-taxonomies-tools"), true),
        new gdr2_Setting_Group('status', __("Status", "gd-taxonomies-tools"), __("Activity status for the post type.", "gd-taxonomies-tools"), true)
    )),
    new gdr2_Setting_Panel('features', __("Features", "gd-taxonomies-tools"), __("standard and enhanced", "gd-taxonomies-tools"), __("Standard features, taxonomies, enhanced features for post type.", "gd-taxonomies-tools"), array(
        new gdr2_Setting_Group('wp', __("WordPress Standard", "gd-taxonomies-tools"), __("Features built into WordPress.", "gd-taxonomies-tools")),
        new gdr2_Setting_Group('enhanced', __("GD CPT Tools Enhanced", "gd-taxonomies-tools"), __("Enahanced features added by this plugin.", "gd-taxonomies-tools")),
        new gdr2_Setting_Group('taxonomies', __("Taxonomies", "gd-taxonomies-tools"), __("Taxonomies supported by post type.", "gd-taxonomies-tools"))
    ))
);

if (!$cpt_built_in) {
    $p[] = new gdr2_Setting_Panel('rewriting', __("Rewrite", "gd-taxonomies-tools"), __("single and archive", "gd-taxonomies-tools"), __("Advanced rewriting rules for single posts and archives.", "gd-taxonomies-tools"), array(
                new gdCPT_Setting_Group('info', __("Info", "gd-taxonomies-tools"), '', true, false, false),
                new gdCPT_Setting_Group('permalinks', __("Single Post Rewriting", "gd-taxonomies-tools"), __("Additional rules for single post permalinks.", "gd-taxonomies-tools"), false, true, true),
                new gdCPT_Setting_Group('archvlinks', __("Archive Rewriting", "gd-taxonomies-tools"), __("To use these options, post type archives rewrite must be enabled on Settings tab.", "gd-taxonomies-tools")."<br/>".__("Additional rules for archives intersections and permalinks.", "gd-taxonomies-tools"), false, true, true)
           ));
}

$e = array(
    'basics' => array(
        'info' => array(
            new gdr2_Setting_Element('cpt', '[info]', 'basics', 'info', '', __("This is panel for only some settings that can be considered safe to change and override,<br/>and enhanced features implemented by this plugin.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO),
        ),
        'name' => array(
            new gdr2_Setting_Element('cpt', '[name]', 'basics', 'name', __("Name", "gd-taxonomies-tools"), __("Names of default post types or custom post types that are not created with this plugin, can't be changed", "gd-taxonomies-tools"), gdr2_Setting_Type::TEXT, $cpt['name'], '', '', array('readonly' => true))
        ),
        'status' => array(
            new gdr2_Setting_Element('cpt', '[active]', 'basics', 'status', __("Settings Override", "gd-taxonomies-tools"), __("Control scope of the settings to be overriden by this plugin.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT, $cpt['active'], 'array', array('no' => __("No", "gd-taxonomies-tools"), 'full' => __("Full", "gd-taxonomies-tools"), 'simple' => __("Simple", "gd-taxonomies-tools"))),
        ),
    ),
    'rewriting' => array(
        'info' => array(
            new gdr2_Setting_Element('cpt', '[info]', 'rewriting', 'info', '', __("Be careful with rewrite rules, because you may cause conflict with other rewrite rules. Test to make sure that everything is working.", "gd-taxonomies-tools").' '.__("For post types not registered with this plugin there is no guarantee that this will work at all.", "gd-taxonomies-tools"), gdr2_Setting_Type::INFO)
        )
    )
);

global $taxonomies_codes, $post_type_name;
$taxonomies_codes = array();
$post_type_name = $cpt['name'];

foreach ($post_features as $code => $data) {
    $e['features']['wp'][] = new gdr2_Setting_Element('cpt', '[supports]['.$code."]", 'features', 'wp', $data['label'], $data['info'], gdr2_Setting_Type::BOOLEAN, in_array($code, $cpt['supports']));
}
foreach ($post_features_special as $code => $data) {
    $e['features']['enhanced'][] = new gdr2_Setting_Element('cpt', '[enhanced]['.$code."]", 'features', 'enhanced', $data['label'], $data['info'], gdr2_Setting_Type::BOOLEAN, in_array($code, $cpt['special']));
}
foreach ($wp_taxonomies as $code => $tax) {
    $e['features']['taxonomies'][] = new gdr2_Setting_Element('cpt', '[taxonomies]['.$code.']', 'features', 'taxonomies', $tax->label, '', gdr2_Setting_Type::BOOLEAN, in_array($code, $cpt['taxonomies']));

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

include_once(GDTAXTOOLS_PATH.'forms/cpt/permalinks.php');

?>

<script type='text/javascript'>
    gdCPTTools.cookie_name = "wp-gdcpt-settings-cpt-simple";
    jQuery(document).ready(function() {
        gdCPTAdmin.save_cpt_simple("<?php echo $cpt['name']; ?>", "gdr2dialog_cpt_simple");

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
<form action="" id="gdcpt-settings-form" method="post">
    <input name="gdr2_action" type="hidden" value="cpt-simple" />
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