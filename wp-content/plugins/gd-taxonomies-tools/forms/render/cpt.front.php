<tr<?php echo $first ? ' class="first"' : ''; $first = false; ?>>
    <td class="first b"><?php echo $cpt_data->label; ?></td>
    <td class="t">
        <strong><?php echo $cpt_data->name; ?></strong>
        <?php

        if ($editable) {
            echo ' | <a href="admin.php?page=gdtaxtools_postypes&cpt=1&pid='.$indexer["cpt"][$cpt_data->name].'&action=edit">'.__("edit", "gd-taxonomies-tools").'</a>';
        } 

        ?>
    </td>
    <td class="b options" style="font-weight: bold;">
        <?php echo !isset($post_count[$cpt_data->name]) ? 0 : intval($post_count[$cpt_data->name]); ?>
    </td>
</tr>
