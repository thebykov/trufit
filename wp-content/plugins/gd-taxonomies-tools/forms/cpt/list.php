<?php

global $gdtt;

$post_types = get_post_types(array(), "objects");
$post_count = gdCPTDB::get_post_types_counts();
$post_inactive = $gdtt->prepare_inactive_cpt();
$post_all = $gdtt->list_names_cpt();

?>
<script type='text/javascript'>
    jQuery(document).ready(function() {
        gdCPTAdmin.panel.post_types.list();
    });
</script>
<div id="tabs" style="width: 99.4%;">
    <ul>
        <li style="width: 240px;"><a href="#tabs-cpt"><?php _e("Custom Post Types", "gd-taxonomies-tools"); ?></a><div><?php _e("created with this plugin", "gd-taxonomies-tools"); ?></div></li>
        <li style="width: 240px;"><a href="#tabs-third"><?php _e("Custom Post Types", "gd-taxonomies-tools"); ?></a><div><?php _e("by third party plugin or theme", "gd-taxonomies-tools"); ?></div></li>
        <li style="width: 240px;"><a href="#tabs-default"><?php _e("Default Post Types", "gd-taxonomies-tools"); ?></a><div><?php _e("built into WordPress", "gd-taxonomies-tools"); ?></div></li>
    </ul>
    <div id="tabs-cpt">
        <?php

        $default = false;
        $cpt_made = true;

        include(GDTAXTOOLS_PATH.'forms/render/cpt.header.php');
        $count = 0;
        foreach ($post_types as $cpt_data) {
            $cpt_name = $cpt_data->name;
            if (!$cpt_data->_builtin && in_array($cpt_data->name, $post_all, true)) {
                include(GDTAXTOOLS_PATH."forms/render/cpt.item.php");
                $count++;
            }
        }

        if ($count == 0) {
            echo '<tr><td colspan="8">'.__("Nothing here.", "gd-taxonomies-tools").'</td></tr>';
        }

        include(GDTAXTOOLS_PATH.'forms/render/shared.footer.php');

        if (count($post_inactive) > 0) {
            echo '<h3>'.__("Inactive Custom Post Types:", "gd-taxonomies-tools").'</h3>';
            include(GDTAXTOOLS_PATH."forms/render/cpt.header.php");

            foreach ($post_inactive as $cpt_data) {
                $cpt_name = $cpt_data->name;
                include(GDTAXTOOLS_PATH."forms/render/cpt.item.php");
            }

            include(GDTAXTOOLS_PATH."forms/render/shared.footer.php");
        }

        ?>
    </div>
    <div id="tabs-third">
        <?php

        $default = true;
        $cpt_made = false;
        $count = 0;

        include(GDTAXTOOLS_PATH."forms/render/cpt.header.php");

        foreach ($post_types as $cpt_data) {
            $cpt_name = $cpt_data->name;
            if (!$cpt_data->_builtin && !in_array($cpt_data->name, $post_all, true)) {
                include(GDTAXTOOLS_PATH."forms/render/cpt.item.php");
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

        include(GDTAXTOOLS_PATH."forms/render/cpt.header.php");

        foreach ($post_types as $cpt_data) {
            $cpt_name = $cpt_data->name;
            if ($cpt_data->_builtin) {
                include(GDTAXTOOLS_PATH."forms/render/cpt.item.php");
            }
        }

        include(GDTAXTOOLS_PATH."forms/render/shared.footer.php");

        ?>
    </div>
</div>
<a class="pressbutton" style="margin-top: 10px;" href="admin.php?page=gdtaxtools_postypes&action=addnew"><?php _e("Add New Custom Post Type", "gd-taxonomies-tools"); ?></a>
