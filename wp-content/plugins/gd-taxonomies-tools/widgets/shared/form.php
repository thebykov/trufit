<table class="gdsw-table">
    <tr>
        <td class="tdleft"><?php _e("Title", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright"><input class="widefat gdsw-input-text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" /></td>
    </tr>
    <tr>
        <td class="tdleft"><?php _e("Display To", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright">
            <?php 

                global $wp_roles;
                $list = array('all' => __("Everyone", "gd-taxonomies-tools"), 'visitor' => __("Only Visitors", "gd-taxonomies-tools"), 'user' => __("All Users", "gd-taxonomies-tools"));

                foreach ($wp_roles->role_names as $role => $title) {
                    $list['role:'.$role] = __("Role", "gd-taxonomies-tools").': '.$title;
                }

                $this->display_select_options($list, $instance['_display'], $this->get_field_name('_display'), $this->get_field_id('_display'), "widefat gdsw-input-text");

            ?>
        </td>
    </tr>
    <tr>
        <td class="tdleft"><?php _e("Results Cache", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright">
            <input class="widefat gdsw-input-number" id="<?php echo $this->get_field_id('_cached'); ?>" name="<?php echo $this->get_field_name('_cached'); ?>" type="text" value="<?php echo $instance['_cached']; ?>" />
            <br/><em class="gdsw-description" style="display: inline-block;"><?php _e("To use cache and speed up the widget, enter number of hours for cached results to be kept. Leave 0 to disable cache.", "gd-taxonomies-tools"); ?></em>
        </td>
    </tr>
</table>
<div class="gdsw-table-split"></div>
