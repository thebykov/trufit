<?php

require_once(GDTAXTOOLS_PATH.'gdr2/plugin/gdr2.settings.admin.php');
require_once(GDTAXTOOLS_PATH.'code/internal/render.php');

if (empty($rcaps["tax"])) {
    _e("No custom capabilities rules available.", "gd-taxonomies-tools");
} else {
    $rules = array();
    foreach (array_keys($rcaps["tax"]) as $key) {
        $rules[$key] = $key;
    }
    
?>

<table class="role-editor">
    <tr>
        <td><?php _e("Rule:", "gd-taxonomies-tools"); ?></td>
        <td><?php gdr2_UI::draw_select($rules, "", "", "tax_rule"); ?></td>
        <td>&nbsp;&nbsp;</td>
        <td><?php _e("Role:", "gd-taxonomies-tools"); ?></td>
        <td><select id="tax_role"><?php wp_dropdown_roles(); ?></select></td>
        <td>&nbsp;&nbsp;</td>
        <td><input type="button" id="tax_show" class="pressbutton" value="<?php _e("Show", "gd-taxonomies-tools"); ?>" /></td>
    </tr>
</table>

<div id="editor-tax" style="display: none;">
<form action="" id="gdcpt-caps-form-tax" method="post">
    <input name="mode" type="hidden" value="tax" />
    <input name="tax[info][name]" id="tax_info_name" type="hidden" value="" />
    <input name="tax[info][role]" id="tax_info_role" type="hidden" value="" />
    <div class="gdr2-panel gdr2-panel">
    <?php

    $r = new gdCPTRender();
    $r->base = "tax";

    $g = array(
        new gdr2_Setting_Group("basic", __("Role Status", "gd-taxonomies-tools"), __("Status for the capabilities for the role.", "gd-taxonomies-tools"), true, false),
        new gdr2_Setting_Group("capabilities", __("List of individual Capabilities", "gd-taxonomies-tools"), __("Help icon description shows the generated name for the capability.", "gd-taxonomies-tools"), true, false)
    );

    $e = array(
        "basic" => array(
            new gdr2_Setting_Element("tax", "[info][active]", "basic", "basic", __("Active", "gd-taxonomies-tools"), __("When enabled, capabilities generated for the base rule name will be applied to the role. When disabled, capabilities will be removed from the role.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, false)
        ),
        "capabilities" => array(
            new gdr2_Setting_Element("tax", "[caps][manage_terms]", "advanced", "capabilities", __("Manage terms", "gd-taxonomies-tools"), "manage_terms", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("tax", "[caps][edit_terms]", "advanced", "capabilities", __("Edit terms", "gd-taxonomies-tools"), "edit_terms", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("tax", "[caps][delete_terms]", "advanced", "capabilities", __("Delete terms", "gd-taxonomies-tools"), "delete_terms", gdr2_Setting_Type::BOOLEAN),
            new gdr2_Setting_Element("tax", "[caps][assign_terms]", "advanced", "capabilities", __("Assign terms", "gd-taxonomies-tools"), "assign_terms", gdr2_Setting_Type::BOOLEAN)
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