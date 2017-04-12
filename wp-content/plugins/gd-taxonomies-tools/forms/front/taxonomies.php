<div class="postbox gdrgrid frontright">
    <h3 class="hndle"><span><?php _e("Basic Taxonomies Statistics", "gd-taxonomies-tools"); ?></span></h3>
    <div class="inside">
        <p class="sub"><?php _e("Default Taxonomies", "gd-taxonomies-tools"); ?></p>
        <div class="table">
            <table><tbody>
                <?php $first = true; $editable = false; $custom_tx = $third_tx = 0;
                    foreach ($wp_taxonomies as $short => $tax) {
                        if ($tax->_builtin) {
                            include(GDTAXTOOLS_PATH."forms/render/tax.front.php");
                            $first = false;
                        } else $custom_tx++;
                    } ?>
            </tbody></table>
        </div>

        <p class="sub"><?php _e("Custom Taxonomies", "gd-taxonomies-tools"); ?></p>
        <div class="table">
        <?php if ($custom_tx > 0) { $editable = true; ?>
            <table><tbody>
            <?php $first = true;
                foreach ($wp_taxonomies as $short => $tax) {
                    if (!$tax->_builtin) {
                        if (in_array($tax->name, $gdtttax)) {
                            include(GDTAXTOOLS_PATH."forms/render/tax.front.php");
                            $first = false;
                        } else $third_tx++;
                    }
                } ?>
            </tbody></table>
        <?php } else echo '<p>'.__("No custom taxonomies found.", "gd-taxonomies-tools").'</p>'; ?>
        </div>

        <p class="sub"><?php _e("Third party Custom Taxonomies", "gd-taxonomies-tools"); ?></p>
        <div class="table">
        <?php if ($third_tx > 0) { $editable = false; ?>
            <table><tbody>
            <?php $first = true;
                foreach ($wp_taxonomies as $short => $tax) {
                    if (!$tax->_builtin) {
                        if (!in_array($tax->name, $gdtttax)) {
                            include(GDTAXTOOLS_PATH."forms/render/tax.front.php");
                            $first = false;
                        }
                    }
                } ?>
            </tbody></table>
        <?php } else echo '<p>'.__("No third party custom taxonomies found.", "gd-taxonomies-tools").'</p>'; ?>
        </div>

        <div class="link">
            <a class="color-red" href="admin.php?page=gdtaxtools_taxs"><?php _e("Taxonomies", "gd-taxonomies-tools"); ?></a>
             | <a class="color-red" href="admin.php?page=gdtaxtools_taxs&action=addnew"><?php _e("New Taxonomy", "gd-taxonomies-tools"); ?></a>
        </div>
    </div>
</div>
