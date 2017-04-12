<div class="postbox gdrgrid frontright">
    <h3 class="hndle"><span><?php _e("Basic Post Types Statistics", "gd-taxonomies-tools"); ?></span></h3>
    <div class="inside">
        <p class="sub"><?php _e("Default Posts Types", "gd-taxonomies-tools"); ?></p>
        <div class="table">
            <table><tbody>
            <?php $first = true; $editable = false; $custom_pt = $third_pt = 0;
                foreach ($post_types as $cpt_data) {
                    if ($cpt_data->_builtin) {
                        include(GDTAXTOOLS_PATH."forms/render/cpt.front.php");
                        $first = false;
                    } else $custom_pt++;
                } ?>
            </tbody></table>
        </div>

        <p class="sub"><?php _e("Custom Posts Types", "gd-taxonomies-tools"); ?></p>
        <div class="table">
        <?php if ($custom_pt > 0) { $editable = true; ?>
            <table><tbody>
            <?php $first = true;
                foreach ($post_types as $cpt_data) {
                    if (!$cpt_data->_builtin) {
                        if (in_array($cpt_data->name, $gdcpost)) {
                            include(GDTAXTOOLS_PATH."forms/render/cpt.front.php");
                            $first = false;
                        } else $third_pt++;
                    }
                } ?>
            </tbody></table>
        <?php } else echo '<p>'.__("No custom post types found.", "gd-taxonomies-tools").'</p>'; ?>
        </div>

        <p class="sub"><?php _e("Third party Custom Posts Types", "gd-taxonomies-tools"); ?></p>
        <div class="table">
        <?php if ($third_pt > 0) { $editable = false; ?>
            <table><tbody>
            <?php $first = true;
                foreach ($post_types as $cpt_data) {
                    if (!$cpt_data->_builtin) {
                        if (!in_array($cpt_data->name, $gdcpost)) {
                            include(GDTAXTOOLS_PATH."forms/render/cpt.front.php");
                            $first = false;
                        }
                    }
                } ?>
            </tbody></table>
        <?php } else echo '<p>'.__("No third party custom post types found.", "gd-taxonomies-tools").'</p>'; ?>
        </div>

        <div class="link">
            <a class="color-red" href="admin.php?page=gdtaxtools_postypes"><?php _e("Post Types", "gd-taxonomies-tools"); ?></a>
             | <a class="color-red" href="admin.php?page=gdtaxtools_postypes&action=addnew"><?php _e("New Post Type", "gd-taxonomies-tools"); ?></a>
             | <a class="color-red" href="admin.php?page=gdtaxtools_metas"><?php _e("Meta Boxes", "gd-taxonomies-tools"); ?></a>
        </div>
    </div>
</div>
