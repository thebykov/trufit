<table class="form-table"><tbody>
    <tr><th scope="row"><?php _e("Main Settings", "gd-taxonomies-tools"); ?></th>
        <td>
            <input type="checkbox" name="metabox_preload_select" id="metabox_preload_select"<?php if ($options["metabox_preload_select"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="metabox_preload_select"><?php _e("Load pre defined set of functions to get data for Select custom field type.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="metabox_clean_title" id="metabox_clean_title"<?php if ($options["metabox_clean_title"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="metabox_clean_title"><?php _e("Display clean title for meta boxes with no plugin name as prefix.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="meta_post_type_change" id="meta_post_type_change"<?php if ($options["meta_post_type_change"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="meta_post_type_change"><?php _e("Add meta box allowing change of post type for posts.", "gd-taxonomies-tools"); ?></label>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("Custom Fields", "gd-taxonomies-tools"); ?></th>
        <td>
            <input type="checkbox" name="custom_fields_load_datetime" id="custom_fields_load_datetime"<?php if ($options["custom_fields_load_datetime"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="custom_fields_load_datetime"><?php _e("Load Date / Time custom fields.", "gd-taxonomies-tools"); echo ' '; _e("Contains", "gd-taxonomies-tools"); echo ': '.__("Date", "gd-taxonomies-tools").', '.__("Month", "gd-taxonomies-tools").', '.__("Datetime", "gd-taxonomies-tools").', '.__("Time", "gd-taxonomies-tools");  ?>.</label>
            <br/>
            <input type="checkbox" name="custom_fields_load_advanced" id="custom_fields_load_advanced"<?php if ($options["custom_fields_load_advanced"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="custom_fields_load_advanced"><?php _e("Load Advanced custom fields.", "gd-taxonomies-tools"); echo ' '; _e("Contains", "gd-taxonomies-tools"); echo ': '.__("Color", "gd-taxonomies-tools").', '.__("Image", "gd-taxonomies-tools").', '.__("Rich Editor", "gd-taxonomies-tools").', '.__("Rewrite", "gd-taxonomies-tools");  ?>.</label>
            <br/>
            <input type="checkbox" name="custom_fields_load_maps" id="custom_fields_load_maps"<?php if ($options["custom_fields_load_maps"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="custom_fields_load_maps"><?php _e("Load Google Maps custom field", "gd-taxonomies-tools"); echo ' '; _e("Contains", "gd-taxonomies-tools"); echo ': '.__("Google Maps", "gd-taxonomies-tools");  ?>.</label>
            <br/>
            <input type="checkbox" name="custom_fields_load_units" id="custom_fields_load_units"<?php if ($options["custom_fields_load_units"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="custom_fields_load_units"><?php _e("Load Units custom fields.", "gd-taxonomies-tools"); echo ' '; _e("Contains", "gd-taxonomies-tools"); echo ': '.__("Dimensions", "gd-taxonomies-tools").', '.__("Resolution", "gd-taxonomies-tools").', '.__("Custom Unit", "gd-taxonomies-tools").', '.__("Currency", "gd-taxonomies-tools");  ?>.</label>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("Chosen Library", "gd-taxonomies-tools"); ?></th>
        <td>
            <input type="checkbox" name="load_chosen_meta" id="load_chosen_meta"<?php if ($options["load_chosen_meta"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="load_chosen_meta"><?php _e("Load chosen.js library on the post editor panels.", "gd-taxonomies-tools"); ?></label>
            <div class="gdsr-table-split"></div>
            <input type="checkbox" name="transform_chosen_single_meta" id="transform_chosen_single_meta"<?php if ($options["transform_chosen_single_meta"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="transform_chosen_single_meta"><?php _e("Apply Chosen transformation for single select control.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="transform_chosen_multi_meta" id="transform_chosen_multi_meta"<?php if ($options["transform_chosen_multi_meta"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="transform_chosen_multi_meta"><?php _e("Apply Chosen transformation for multi select control.", "gd-taxonomies-tools"); ?></label>
        </td>
    </tr>
    <tr class="last-row"><th scope="row"><?php _e("Google Maps", "gd-taxonomies-tools"); ?></th>
        <td>
            <input type="checkbox" name="google_maps_load_admin" id="google_maps_load_admin"<?php if ($options["google_maps_load_admin"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="google_maps_load_admin"><?php _e("Load Google Maps on administration post edit panels.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" name="google_maps_load_front" id="google_maps_load_front"<?php if ($options["google_maps_load_front"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="google_maps_load_front"><?php _e("Load Google Maps on front end pages.", "gd-taxonomies-tools"); ?></label>
            <div class="gdsr-table-split"></div>
            <?php _e("If Google Maps are enabled, plugin will need to load the JavaScript from Google website. But, if you have other means of loading these (some other plugin), you can disable loading from here. For this to work, Google Maps must be loaded in the page header.", "gd-taxonomies-tools"); ?>
        </td>
    </tr>
</tbody></table>