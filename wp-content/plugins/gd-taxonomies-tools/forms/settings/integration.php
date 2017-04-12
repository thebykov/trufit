<?php $tag_text = __("Add %s for terms to all non hierarchical taxonomies meta boxes.", "gd-taxonomies-tools"); ?>
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Theme Templates", "gd-taxonomies-tools"); ?></th>
    <td>
        <input type="checkbox" name="tpl_expand_archives" id="tpl_expand_archives"<?php if ($options["tpl_expand_archives"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="tpl_expand_archives"><?php _e("Add type named custom post type archive templates.", "gd-taxonomies-tools"); ?></label> <strong><?php _e("WordPress 3.1 or newer.", "gd-taxonomies-tools"); ?></strong>
        <br/>
        <input type="checkbox" name="tpl_expand_single" id="tpl_expand_single"<?php if ($options["tpl_expand_single"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="tpl_expand_single"><?php _e("Add extra ID and slug based templates to single posts.", "gd-taxonomies-tools"); ?></label>
        <br/>
        <input type="checkbox" name="tpl_expand_date" id="tpl_expand_date"<?php if ($options["tpl_expand_date"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="tpl_expand_date"><?php _e("Add extra date periods based templates for date archives.", "gd-taxonomies-tools"); ?></label>
        <br/>
        <input type="checkbox" name="tpl_expand_intersect" id="tpl_expand_intersect"<?php if ($options["tpl_expand_intersect"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="tpl_expand_date"><?php _e("Add extra templates for post type and taxonomy intersection archives.", "gd-taxonomies-tools"); ?></label>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="tpl_expand_date_cpt" id="tpl_expand_date_cpt"<?php if ($options["tpl_expand_date_cpt"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="tpl_expand_date_cpt"><?php _e("Add custom post types archive template for date period based templates.", "gd-taxonomies-tools"); ?></label>
        <br/>
        <input type="checkbox" name="tpl_expand_date_cpt_priority" id="tpl_expand_date_cpt_priority"<?php if ($options["tpl_expand_date_cpt_priority"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="tpl_expand_date_cpt_priority"><?php _e("Prioritize custom post type archive template over generic date and archive templates.", "gd-taxonomies-tools"); ?></label>
        <div class="gdsr-table-split"></div>
        <?php _e("Additional templates structure is outlined in the article on Dev4Press", "gd-taxonomies-tools"); ?>: <a href="http://www.dev4press.com/?p=3745"><?php _e("Templates names", "gd-taxonomies-tools"); ?></a>
    </td>
</tr>
<tr><th scope="row"><?php _e("Post Editor", "gd-taxonomies-tools"); ?></th>
    <td>
        <input type="checkbox" name="post_edit_tag_yahoo" id="post_edit_tag_yahoo"<?php if ($options["post_edit_tag_yahoo"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="post_edit_tag_yahoo"><?php echo sprintf($tag_text, "Yahoo API"); ?></label>
        <br/>
        <input type="checkbox" name="post_edit_tag_alchemy" id="post_edit_tag_alchemy"<?php if ($options["post_edit_tag_alchemy"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="post_edit_tag_alchemy"><?php echo sprintf($tag_text, "Alchemy API"); ?></label>
        <br/>
        <input type="checkbox" name="post_edit_tag_opencalais" id="post_edit_tag_opencalais"<?php if ($options["post_edit_tag_opencalais"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="post_edit_tag_alchemy"><?php echo sprintf($tag_text, "OpenCalais API"); ?></label>
        <br/>
        <input type="checkbox" name="post_edit_tag_zemanta" id="post_edit_tag_zemanta"<?php if ($options["post_edit_tag_zemanta"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="post_edit_tag_zemanta"><?php echo sprintf($tag_text, "Zemanta API"); ?></label>
        <br/>
        <input type="checkbox" name="post_edit_tag_internal" id="post_edit_tag_internal"<?php if ($options["post_edit_tag_internal"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="post_edit_tag_internal"><?php echo sprintf($tag_text, "Internal extraction"); ?></label>
        <br/>
        <input type="checkbox" name="post_edit_tag_delete" id="post_edit_tag_delete"<?php if ($options["post_edit_tag_delete"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="post_edit_tag_delete"><?php _e("Add Clear All to all non hierarchical taxonomies meta boxes.", "gd-taxonomies-tools"); ?></label>
        <div class="gdsr-table-split"></div>
        <?php _e("This will add Yahoo Suggest and Clear All buttons to Post Tags and other similar taxonomies meta boxes on post edit panel.", "gd-taxonomies-tools"); ?>
    </td>
</tr>
<tr><th scope="row"><?php _e("TinyMCE Editor", "gd-taxonomies-tools"); ?></th>
    <td>
        <input type="checkbox" name="tinymce_auto_create" id="tinymce_auto_create"<?php if ($options["tinymce_auto_create"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="tinymce_auto_create"><?php _e("Auto create taxonomy on when performing check operation.", "gd-taxonomies-tools"); ?></label>
        <br/>
        <input type="checkbox" name="tinymce_use_shortcode" id="tinymce_use_shortcode"<?php if ($options["tinymce_use_shortcode"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="tinymce_use_shortcode"><?php _e("Use shortcode instead of direct linking when adding code back to the editor.", "gd-taxonomies-tools"); ?></label>
    </td>
</tr>
<tr class="last-row"><th scope="row"><?php _e("Load Widgets", "gd-taxonomies-tools"); ?></th>
    <td>
        <input type="checkbox" name="widget_terms_cloud" id="widget_terms_cloud"<?php if ($options["widget_terms_cloud"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="widget_terms_cloud"><?php _e("Terms Cloud", "gd-taxonomies-tools"); ?></label>
        <br/>
        <input type="checkbox" name="widget_terms_list" id="widget_terms_list"<?php if ($options["widget_terms_list"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="widget_terms_list"><?php _e("Terms List", "gd-taxonomies-tools"); ?></label>
        <br/>
        <input type="checkbox" name="widget_posttypes_list" id="widget_posttypes_list"<?php if ($options["widget_posttypes_list"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="widget_posttypes_list"><?php _e("Post Types List", "gd-taxonomies-tools"); ?></label>
    </td>
</tr>
</tbody></table>
