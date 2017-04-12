<table class="gdsw-table">
    <tr>
        <td class="tdleft"><?php _e("Post Types", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright">
            <?php

            $wp_post_types = gdtt_get_public_post_types(true);
            foreach ($wp_post_types as $pt => $val) {
                $on = empty($instance["list"]);
                if (!$on) $on = in_array($pt, $instance["list"]);
                echo sprintf('<label style="margin-right: 5px;">%s</label><input type="checkbox" name="%s[]" value="%s"%s /><br/>',
                        $val->label, $this->get_field_name('list'), $pt, $on ? 'checked="checked"' : '');
            }

            ?>
        </td>
    </tr>
</table>
<div class="gdsw-table-split"></div>
<table class="gdsw-table">
    <tr>
        <td class="tdleft"><?php _e("Additional CSS class", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright"><input class="widefat gdsw-input-text" id="<?php echo $this->get_field_id('display_css'); ?>" name="<?php echo $this->get_field_name('display_css'); ?>" type="text" value="<?php echo $instance["display_css"]; ?>" /></td>
    </tr>
    <tr>
        <td class="tdleft"><?php _e("Show post counter", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright">
            <input <?php echo $instance['counts'] == 1 ? 'checked="checked"' : ''; ?> type="checkbox" id="<?php echo $this->get_field_id('counts'); ?>" name="<?php echo $this->get_field_name('counts'); ?>" />
        </td>
    </tr>
    <tr>
        <td class="tdleft"><?php _e("Mark current post type", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright">
            <input <?php echo $instance['mark_current'] == 1 ? 'checked="checked"' : ''; ?> type="checkbox" id="<?php echo $this->get_field_id('mark_current'); ?>" name="<?php echo $this->get_field_name('mark_current'); ?>" />
        </td>
    </tr>
</table>
