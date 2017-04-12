<table class="form-table"><tbody>
    <tr><th scope="row"><?php _e("Cache", "gd-taxonomies-tools"); ?></th>
        <td>
            <input type="checkbox" name="cache_active" id="cache_active"<?php if ($options["cache_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="cache_active"><?php _e("Caching of the post types and taxonomies registration data.", "gd-taxonomies-tools"); ?></label>
            <div class="gdsr-table-split"></div>
            <?php _e("If active, registration data will be cached to avoid repeated analysis of the settings and creating registration arrays.", "gd-taxonomies-tools"); ?>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("Custom Rewriting", "gd-taxonomies-tools"); ?></th>
        <td>
            <input type="checkbox" name="rewrite_intersects_active" id="rewrite_intersects_active"<?php if ($options["rewrite_intersects_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="rewrite_intersects_active"><?php _e("Custom post types and custom taxonomies archive intersection.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="rewrite_permalinks_active" id="rewrite_permalinks_active"<?php if ($options["rewrite_permalinks_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="rewrite_permalinks_active"><?php _e("Custom post types custom permalinks rewriting.", "gd-taxonomies-tools"); ?></label>
            <div class="gdsr-table-split"></div>
            <?php _e("These options must be enabled if you want to use expanded rewriting and permalinks.", "gd-taxonomies-tools"); ?>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("Custom Post Types", "gd-taxonomies-tools"); ?></th>
        <td>
            <input type="checkbox" name="special_cpt_home_page" id="special_cpt_home_page"<?php if ($options["special_cpt_home_page"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_cpt_home_page"><?php _e("Add posts from custom post types to home page.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="special_cpt_rss_feed" id="special_cpt_rss_feed"<?php if ($options["special_cpt_rss_feed"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_cpt_rss_feed"><?php _e("Add posts from custom post types to RSS feeds.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="special_cpt_right_now" id="special_cpt_right_now"<?php if ($options["special_cpt_right_now"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_cpt_right_now"><?php _e("Add custom post types into Right Now widget on the admin dashboard.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="special_cpt_post_template" id="special_cpt_post_template"<?php if ($options["special_cpt_post_template"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_cpt_post_template"><?php _e("Add custom templates support, similar to page templates.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="special_cpt_disable_quickedit" id="special_cpt_disable_quickedit"<?php if ($options["special_cpt_disable_quickedit"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_cpt_disable_quickedit"><?php _e("Remove Quick Edit option from post edit list.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="special_cpt_menu_archive" id="special_cpt_menu_archive"<?php if ($options["special_cpt_menu_archive"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_cpt_menu_drafts"><?php _e("Adds posts archives link in the post type menu.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="special_cpt_menu_drafts" id="special_cpt_menu_drafts"<?php if ($options["special_cpt_menu_drafts"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_cpt_menu_drafts"><?php _e("Adds drafts quick access link in the post type menu.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="special_cpt_menu_futures" id="special_cpt_menu_futures"<?php if ($options["special_cpt_menu_futures"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_cpt_menu_futures"><?php _e("Adds scheduled posts quick access link in the post type menu.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="special_cpt_s2_notify" id="special_cpt_s2_notify"<?php if ($options["special_cpt_s2_notify"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_cpt_s2_notify"><?php _e("Adds support for Subscribe2 plugin notify for post types.", "gd-taxonomies-tools"); ?></label>
            <div class="gdsr-table-split"></div>
            <?php _e("Only special features enabled here will be used, regardless of the settings of individual custom post types.", "gd-taxonomies-tools"); ?>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("Custom Taxonomies", "gd-taxonomies-tools"); ?></th>
        <td>
            <input type="checkbox" name="special_tax_edit_filter" id="special_tax_edit_filter"<?php if ($options["special_tax_edit_filter"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_tax_edit_filter"><?php _e("Add custom taxonomy filter to the posts edit grid.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="special_tax_term_link" id="special_tax_term_link"<?php if ($options["special_tax_term_link"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_tax_term_link"><?php _e("Add view term archive links to the terms edit panels.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="special_tax_term_id" id="special_tax_term_id"<?php if ($options["special_tax_term_id"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_tax_term_id"><?php _e("Add ID column on the terms edit panels.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="special_tax_term_image" id="special_tax_term_image"<?php if ($options["special_tax_term_image"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_tax_term_image"><?php _e("Add controls for attaching images to terms.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="special_tax_metaboxes" id="special_tax_metaboxes"<?php if ($options["special_tax_metaboxes"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_tax_metaboxes"><?php _e("Allow customizing meta boxes.", "gd-taxonomies-tools"); ?></label>
            <div class="gdsr-table-split"></div>
            <?php _e("Only special features enabled here will be used, regardless of the settings of individual custom taxonomies.", "gd-taxonomies-tools"); ?>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("Custom Taxonomies: Legacy", "gd-taxonomies-tools"); ?></th>
        <td>
            <input type="checkbox" name="special_tax_edit_column" id="special_tax_edit_column"<?php if ($options["special_tax_edit_column"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="special_tax_edit_column"><?php _e("Add custom taxonomy column to the posts edit grid.", "gd-taxonomies-tools"); ?></label> <strong>WordPress 3.2, 3.3 & 3.4</strong>
        </td>
    </tr>
    <tr class="last-row"><th scope="row"><?php _e("Deleting Taxonomy", "gd-taxonomies-tools"); ?></th>
        <td>
            <input type="checkbox" name="delete_taxonomy_db" id="delete_taxonomy_db"<?php if ($options["delete_taxonomy_db"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="delete_taxonomy_db"><?php _e("Delete terms from database once the taxonomy is deleted.", "gd-taxonomies-tools"); ?></label>
            <div class="gdsr-table-split"></div>
            <?php _e("If this options is not checked, after deleting taxonomy all it's terms will remain in the database.", "gd-taxonomies-tools"); ?>
        </td>
    </tr>
</tbody></table>