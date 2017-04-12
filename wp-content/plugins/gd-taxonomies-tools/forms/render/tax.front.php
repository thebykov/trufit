<tr<?php echo $first ? ' class="first"' : ''; $first = false; ?>>
    <td class="first b"><?php echo $tax->label; ?></td>
    <td class="t">
        <strong><?php echo $tax->name; ?></strong>
        <?php

        if ($editable) {
            echo ' | <a href="admin.php?page=gdtaxtools_taxs&cpt=1&tid='.$indexer["tax"][$tax->name].'&action=edit">'.__("edit", "gd-taxonomies-tools").'</a>';
        } 

        ?>
    </td>
    <td class="b options" style="font-weight: bold;">
        <?php echo wp_count_terms($tax->name); ?>
    </td>
</tr>
