<table class="gdsw-table">
    <tr>
        <td class="tdleft"><?php _e("Maximum terms to show", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright"><input class="widefat gdsw-input-number" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $instance["number"]; ?>" /></td>
    </tr>
    <tr>
        <td class="tdleft"><?php _e("Taxonomy", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright">
        <select class="widefat gdsw-input-text" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
            <?php gdtt_render_taxonomies($instance['taxonomy']); ?>
        </select>
        </td>
    </tr>
    <tr>
        <td class="tdleft"><?php _e("Sort by", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright">
        <select class="widefat gdsw-input-text" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
            <option value="name"<?php echo $instance['orderby'] == 'name' ? ' selected="selected"' : ''; ?>><?php _e("Term name", "gd-taxonomies-tools"); ?></option>
            <option value="slug"<?php echo $instance['orderby'] == 'slug' ? ' selected="selected"' : ''; ?>><?php _e("Term slug", "gd-taxonomies-tools"); ?></option>
            <option value="count"<?php echo $instance['orderby'] == 'count' ? ' selected="selected"' : ''; ?>><?php _e("Posts count", "gd-taxonomies-tools"); ?></option>
            <option value="rand"<?php echo $instance['orderby'] == 'rand' ? ' selected="selected"' : ''; ?>><?php _e("Random", "gd-taxonomies-tools"); ?></option>
        </select>
        </td>
    </tr>
    <tr>
        <td class="tdleft"><?php _e("Order", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright">
        <select class="widefat gdsw-input-text" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
            <option value="asc"<?php echo $instance['order'] == 'asc' ? ' selected="selected"' : ''; ?>><?php _e("Ascending", "gd-taxonomies-tools"); ?></option>
            <option value="desc"<?php echo $instance['order'] == 'desc' ? ' selected="selected"' : ''; ?>><?php _e("Descending", "gd-taxonomies-tools"); ?></option>
        </select>
        </td>
    </tr>
    <tr>
        <td class="tdleft"><?php _e("Post Type", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright">
        <select class="widefat gdsw-input-text" id="<?php echo $this->get_field_id('post_types'); ?>" name="<?php echo $this->get_field_name('post_types'); ?>">
            <?php gdtt_render_post_types($instance['post_types']); ?>
        </select>
        </td>
    </tr>
    <tr>
        <td class="tdleft"><?php _e("Exclude", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright">
            <input class="widefat gdsw-input-text" id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" type="text" value="<?php echo $instance["exclude"]; ?>" />
            <br/><em><?php _e("Comma or space separated list of term ID's.", "gd-taxonomies-tools"); ?></em>
        </td>
    </tr>
    <tr>
        <td class="tdleft"><?php _e("Hide empty", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright">
            <input <?php echo $instance['hide_empty'] == 1 ? 'checked="checked"' : ''; ?> type="checkbox" id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>" />
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
        <td class="tdleft"><?php _e("Font size unit", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright"><input class="widefat gdsw-input-number" id="<?php echo $this->get_field_id('unit'); ?>" name="<?php echo $this->get_field_name('unit'); ?>" type="text" value="<?php echo $instance["unit"]; ?>" /></td>
    </tr>
    <tr>
        <td class="tdleft"><?php _e("Smallest term size", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright"><input class="widefat gdsw-input-number" id="<?php echo $this->get_field_id('smallest'); ?>" name="<?php echo $this->get_field_name('smallest'); ?>" type="text" value="<?php echo $instance["smallest"]; ?>" /></td>
    </tr>
    <tr>
        <td class="tdleft"><?php _e("Largest term size", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright"><input class="widefat gdsw-input-number" id="<?php echo $this->get_field_id('largest'); ?>" name="<?php echo $this->get_field_name('largest'); ?>" type="text" value="<?php echo $instance["largest"]; ?>" /></td>
    </tr>
    <tr>
        <td class="tdleft"><?php _e("Mark current term", "gd-taxonomies-tools"); ?>:</td>
        <td class="tdright">
            <input <?php echo $instance['mark_current'] == 1 ? 'checked="checked"' : ''; ?> type="checkbox" id="<?php echo $this->get_field_id('mark_current'); ?>" name="<?php echo $this->get_field_name('mark_current'); ?>" />
        </td>
    </tr>
</table>
