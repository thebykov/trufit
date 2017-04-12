<?php

require_once(GDTAXTOOLS_PATH.'gdr2/plugin/gdr2.settings.admin.php');
require_once(GDTAXTOOLS_PATH.'code/internal/render.php');

if (empty($rcaps["cpt"])) {
    _e("No custom capabilities rules available.", "gd-taxonomies-tools");
} else {
    $rules = array();
    foreach (array_keys($rcaps["cpt"]) as $key) {
        $rules[$key] = $key;
    }

?>

<table class="role-editor">
    <tr>
        <td><?php _e("Rule:", "gd-taxonomies-tools"); ?></td>
        <td><?php gdr2_UI::draw_select($rules, "", "", "cpt_rule"); ?></td>
        <td>&nbsp;&nbsp;</td>
        <td><?php _e("Role:", "gd-taxonomies-tools"); ?></td>
        <td><select id="cpt_role"><?php wp_dropdown_roles(); ?></select></td>
        <td>&nbsp;&nbsp;</td>
        <td><input type="button" id="cpt_show" class="pressbutton" value="<?php _e("Show", "gd-taxonomies-tools"); ?>" /></td>
    </tr>
</table>

<div id="editor-cpt" style="display: none;">
<form action="" id="gdcpt-caps-form-cpt" method="post">
    <input name="mode" type="hidden" value="cpt" />
    <input name="cpt[info][name]" id="cpt_info_name" type="hidden" value="" />
    <input name="cpt[info][role]" id="cpt_info_role" type="hidden" value="" />
    <div class="gdr2-panel gdr2-panel">
    <?php

    $r = new gdCPTRender();
    $r->base = "cpt";
    
    $g = array(
        new gdr2_Setting_Group("basic", __("Role Status", "gd-taxonomies-tools"), __("Status for the capabilities for the role.", "gd-taxonomies-tools"), true, false),
        new gdr2_Setting_Group("capabilities", __("List of individual Capabilities", "gd-taxonomies-tools"), __("Help icon description shows the generated name for the capability.", "gd-taxonomies-tools"), true, false)
    );

    $e = array(
        "basic" => array(
            new gdr2_Setting_Element("cpt", "[info][active]", "basic", "basic", __("Active", "gd-taxonomies-tools"), __("When enabled, capabilities generated for the base rule name will be applied to the role. When disabled, capabilities will be removed from the role.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, false)
        ),
        "capabilities" => array(
            new gdr2_Setting_Element("cpt", "[caps][edit_post]", "advanced", "capabilities", __("Edit Post", "gd-taxonomies-tools"), "edit_post", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("cpt", "[caps][edit_posts]", "advanced", "capabilities", __("Edit Posts", "gd-taxonomies-tools"), "edit_posts", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("cpt", "[caps][edit_private_posts]", "advanced", "capabilities", __("Edit Private Posts", "gd-taxonomies-tools"), "edit_private_posts", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("cpt", "[caps][edit_published_posts]", "advanced", "capabilities", __("Edit Published Posts", "gd-taxonomies-tools"), "edit_published_posts", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("cpt", "[caps][edit_others_posts]", "advanced", "capabilities", __("Edit Others Posts", "gd-taxonomies-tools"), "edit_others_posts", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("cpt", "[caps][publish_posts]", "advanced", "capabilities", __("Publish Posts", "gd-taxonomies-tools"), "publish_posts", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("cpt", "[caps][read_post]", "advanced", "capabilities", __("Read Post", "gd-taxonomies-tools"), "read_post", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("cpt", "[caps][read_private_posts]", "advanced", "capabilities", __("Read Private Posts", "gd-taxonomies-tools"), "read_private_posts", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("cpt", "[caps][delete_post]", "advanced", "capabilities", __("Delete Post", "gd-taxonomies-tools"), "delete_post", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("cpt", "[caps][delete_posts]", "advanced", "capabilities", __("Delete Posts", "gd-taxonomies-tools"), "delete_posts", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("cpt", "[caps][delete_private_posts]", "advanced", "capabilities", __("Delete Private Posts", "gd-taxonomies-tools"), "delete_private_posts", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("cpt", "[caps][delete_published_posts]", "advanced", "capabilities", __("Delete Published Posts", "gd-taxonomies-tools"), "delete_published_posts", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("cpt", "[caps][delete_others_posts]", "advanced", "capabilities", __("Delete Others Posts", "gd-taxonomies-tools"), "delete_others_posts", gdr2_Setting_Type::BOOLEAN)
        )
    );

    foreach ($g as $group) {
        $elements = $e[$group->name];
        $group->base_url = GDTAXTOOLS_URL;
        $group->render($r, $panel->name, $elements);
    }

    ?>
    </div>
</form>
</div>

<?php } ?>