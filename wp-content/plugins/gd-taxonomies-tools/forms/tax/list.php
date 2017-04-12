<?php

global $wp_taxonomies, $gdtt;

$tax_default = array_slice($wp_taxonomies, 0, $gdtt->get_defaults_count());
$tax_custom = array_slice($wp_taxonomies, $gdtt->get_defaults_count());

$tax_inactive = $gdtt->prepare_inactive_tax();
$tax_all = $gdtt->list_names_tax();
$inactive_item = false;

?>
<script type='text/javascript'>
    jQuery(document).ready(function() {
        gdCPTAdmin.panel.taxonomies.list();
    });
</script>
<div id="tabs" style="width: 99.4%;">
    <ul>
        <li style="width: 240px;"><a href="#tabs-cpt"><?php _e("Custom Taxonomies", "gd-taxonomies-tools"); ?></a><div><?php _e("created with this plugin", "gd-taxonomies-tools"); ?></div></li>
        <li style="width: 240px;"><a href="#tabs-third"><?php _e("Custom Taxonomies", "gd-taxonomies-tools"); ?></a><div><?php _e("by third party plugin or theme", "gd-taxonomies-tools"); ?></div></li>
        <li style="width: 240px;"><a href="#tabs-default"><?php _e("Default Taxonomies", "gd-taxonomies-tools"); ?></a><div><?php _e("built into WordPress", "gd-taxonomies-tools"); ?></div></li>
    </ul>
    <div id="tabs-cpt">
        <?php

        $cpt_made = true;
        include(GDTAXTOOLS_PATH."forms/render/tax.header.php");
        $count = 0;
        foreach ($tax_custom as $tax_name => $tax_data) {
            $default = false;

            if (in_array($tax_data->name, $tax_all)) {
                include(GDTAXTOOLS_PATH."forms/render/tax.item.php");
                $count++;
            }
        }

        if ($count == 0) {
            echo '<tr><td colspan="8">'.__("Nothing here.", "gd-taxonomies-tools").'</td></tr>';
        }

        include(GDTAXTOOLS_PATH."forms/render/shared.footer.php");

        $inactive_item = true;
        if (count($tax_inactive) > 0) {
            echo '<h3>'.__("Inactive Custom Taxonomies:", "gd-taxonomies-tools").'</h3>';
            include(GDTAXTOOLS_PATH."forms/render/tax.header.php");

            foreach ($tax_inactive as $tax_name => $tax_data) {
                $default = false;
                $tax_data->object_type = array();
                include(GDTAXTOOLS_PATH."forms/render/tax.item.php");
            }

            include(GDTAXTOOLS_PATH."forms/render/shared.footer.php");
        }

        $inactive_item = false;

        if (count($gdtxall) > 0) {
            $notice = $options["delete_taxonomy_db"] == 1 ?
                __("Deleting the taxonomy will also delete the relationship entries in the database. Backup your database before proceeding with any deletion operation.", "gd-taxonomies-tools") :
                __("After deleting taxonomy, terms relationships in the database will remain.", "gd-taxonomies-tools");
            $notice.= " ".__("You can change that on the settings panel.", "gd-taxonomies-tools");
            gdtt_render_notice("Warning", $notice);
        }

        ?>
    </div>
    <div id="tabs-third">
        <?php

        $cpt_made = false;
        include(GDTAXTOOLS_PATH."forms/render/tax.header.php");
        $count = 0;

        foreach ($tax_custom as $tax_name => $tax_data) {
            $default = false;

            if (!in_array($tax_data->name, $tax_all)) {
                include(GDTAXTOOLS_PATH."forms/render/tax.item.php");
                $count++;
            }
        }

        if ($count == 0) {
            echo '<tr><td colspan="8">'.__("Nothing here.", "gd-taxonomies-tools").'</td></tr>';
        }

        include(GDTAXTOOLS_PATH."forms/render/shared.footer.php");

        ?>
    </div>
    <div id="tabs-default">
        <?php

        include(GDTAXTOOLS_PATH."forms/render/tax.header.php");

        foreach ($tax_default as $tax_name => $tax_data) {
            $default = true;

            include(GDTAXTOOLS_PATH."forms/render/tax.item.php");
        }

        include(GDTAXTOOLS_PATH."forms/render/shared.footer.php");

        ?>
    </div>
</div>

<a class="pressbutton" style="margin-top: 10px;" href="admin.php?page=gdtaxtools_taxs&action=addnew"><?php _e("Add New Custom Taxonomy", "gd-taxonomies-tools"); ?></a>
