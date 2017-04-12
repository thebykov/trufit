<table class="widefat">
    <thead>
        <tr>
            <th scope="col" style=""><?php _e("Code", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style=""><?php _e("Name", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style="width:80px;"><?php _e("Type", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style=""><?php _e("Required", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style=""><?php _e("Values", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style=""><?php _e("Limit", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style=""><?php _e("Description", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style="width:64px; text-align: right;"><?php _e("Posts", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style="width:140px; text-align: right;"><?php _e("Options", "gd-taxonomies-tools"); ?></th>
        </tr>
    </thead>
    <tbody id="list-fields">
        <?php

        if (empty($gdtt_meta["fields"])) {
            echo '<tr><td colspan="9">'.__("Nothing here", "gd-taxonomies-tools").'</td></tr>';
        } else {
            $counts = $gdtt_fields->count_custom_fields_posts($gdtt_meta["fields"]);

            foreach ($gdtt_meta["fields"] as $code => $_obj) {
                $obj = (array)$_obj;

                echo '<tr class="gdtt-cfrow-'.$code.'">';
                    echo '<td><strong>'.$code.'</strong></td>';
                    echo '<td>'.$obj['name'].'</td>';
                    echo '<td>'.$gdtt_fields->get_field_type($obj).'</td>';
                    echo '<td>'.($obj['required'] ? __("Yes", "gd-taxonomies-tools") : __("No", "gd-taxonomies-tools")).'</td>';
                    echo '<td>'.$gdtt_fields->get_field_values($obj, $custom_functions_list).'</td>';
                    echo '<td>'.$gdtt_fields->get_field_limit($obj).'</td>';
                    echo '<td>'.$obj['description'].'</td>';

                    echo '<td style="width:64px; text-align: right;">'.$counts[$code].'</td>';
                    echo '<td style="width:128px; text-align: right;">';
                        echo '<a class="ttoption-edit gdtt-cfo-edit" href="#'.$code.'">'.__("edit", "gd-taxonomies-tools").'</a> | ';
                        echo '<a class="ttoption-edit gdtt-cfo-copy" href="#'.$code.'">'.__("copy", "gd-taxonomies-tools").'</a> | ';
                        echo '<a class="ttoption-del gdtt-cfo-delete" href="#'.$code.'">'.__("delete", "gd-taxonomies-tools").'</a>';
                    echo '</td>';
                echo '</tr>';
            }
        }

        ?>
    </tbody>
</table>
<a class="pressbutton" id="gdtt-cfe-addnew" href="#" style="margin: 7px 0 0;"><?php _e("Add New Custom Field", "gd-taxonomies-tools"); ?></a>
<div class="gdr2-dialog-blocks">
    <div id="gdttcfdelete" title="<?php _e("Delete Custom Field", "gd-taxonomies-tools"); ?>">
        <h4 class="ui-widget-header"><?php _e("Read this before deleting the custom field", "gd-taxonomies-tools") ?>:</h4>
        <div class="gdtt-element-block">
            <?php _e("Removing custom field definition only, will not remove the data saved in the posts meta table in the database associated with this custom field.", "gd-taxonomies-tools"); ?> 
            <?php _e("You can remove only saved data, and still leave custom field definition.", "gd-taxonomies-tools"); ?>
        </div>
        <h4 class="ui-widget-header"><?php _e("Confirm what do you want to delete", "gd-taxonomies-tools") ?>:</h4>
        <div class="gdtt-element-block">
            <input type="checkbox" id="gdtt-cfe-del-definition" /><label class="gdtt-dialog-check-label" for="gdtt-cfe-del-definition"><?php _e("Delete plugin definition for this custom field.", "gd-taxonomies-tools"); ?></label>
            <br/>
            <input type="checkbox" id="gdtt-cfe-del-postmeta" /><label class="gdtt-dialog-check-label" for="gdtt-cfe-del-postmeta"><?php _e("Delete all post meta data for this custom field.", "gd-taxonomies-tools"); ?></label>
        </div>
    </div>

    <div id="gdttcfedit" title="<?php _e("Custom Field Editor", "gd-taxonomies-tools"); ?>">
        <table>
            <tbody>
                <tr>
                    <td style="width: 390px; vertical-align: top;">
                        <h4 class="ui-widget-header"><?php _e("Select type of this field", "gd-taxonomies-tools") ?>:</h4>
                        <div class="gdtt-element-block">
                            <label><?php _e("Type", "gd-taxonomies-tools"); ?>:</label>
                            <?php gdr2_UI::draw_select_grouped($custom_fields_values, 'text', '', 'gdtt-cfe-type', 'ms-single gdtt-select-grouped'); ?>
                            <div class="clear"></div>
                        </div>

                        <h4 class="ui-widget-header"><?php _e("Basic settings for the field", "gd-taxonomies-tools") ?>:</h4>
                        <div class="gdtt-element-block">
                            <label><?php _e("Code", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_input_text('', '', 'gdtt-cfe-code'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Required, must be unique. This will be used to store data into the database. Use letters, numbers and underscore only.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                            <label><?php _e("Name", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_input_text('', '', 'gdtt-cfe-name'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Required, used by the plugin, be descriptive but not too long.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                            <label><?php _e("Description", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_input_text('', '', 'gdtt-cfe-description'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Optional, used by the plugin, longer description of the field.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                            <label><?php _e("Required", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_input_checkbox(false, '', 'gdtt-cfe-required'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Optional, if enabled, field must be filled by the user. This is not applied to Boolean fields.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                        </div>
                    </td>
                    <td style="width: 10px;">&nbsp;</td>
                    <td style="width: 390px; vertical-align: top;">
                        <h4 class="ui-widget-header"><?php _e("User Restriction", "gd-taxonomies-tools") ?>:</h4>
                        <div class="gdtt-element-block">
                            <label><?php _e("Type", "gd-taxonomies-tools"); ?>:</label>
                            <?php gdr2_UI::draw_select(array('none' => __("None", "gd-taxonomies-tools"), 'role' => __("User Roles", "gd-taxonomies-tools"), 'caps' => __("User Capabilities", "gd-taxonomies-tools")), 'text', '', 'gdtt-cfe-user_access'); ?>
                            <div class="clear"></div>
                            <label><?php _e("User Roles", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_input_text('', '', 'gdtt-cfe-user_roles'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Comma separated list of user roles that can edit this field value.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                            <label><?php _e("User Caps", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_input_text('', '', 'gdtt-cfe-user_caps'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Comma separated list of user capabilities. User needs one of them to be able to edit this field value.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                        </div>

                        <div class="gdtt-element-field gdtt-element-date">
                            <h4 class="ui-widget-header"><?php _e("Field Settings", "gd-taxonomies-tools") ?>:</h4>
                            <div class="gdtt-element-block">
                                <label><?php _e("Date Format", "gd-taxonomies-tools"); ?>:</label>
                                    <?php gdr2_UI::draw_input_text('', '', 'gdtt-cfe-format'); ?>
                                    <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("PHP string for formating the date for display. Leave empty to use default format.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                                <div class="clear"></div>
                                <label><?php _e("Save Format", "gd-taxonomies-tools"); ?>:</label>
                                    <?php gdr2_UI::draw_select_grouped($custom_date_save_format, '', '', 'gdtt-cfe-datesave', 'gdtt-select-grouped'); ?>
                                    <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Format to use when saving data in the database.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="gdtt-element-field gdtt-element-limit">
                            <h4 class="ui-widget-header"><?php _e("Field Settings", "gd-taxonomies-tools") ?>:</h4>
                            <div class="gdtt-element-block">
                                <label><?php _e("Length Limit", "gd-taxonomies-tools"); ?>:</label>
                                    <?php gdr2_UI::draw_input_text('', '', 'gdtt-cfe-limit'); ?>
                                    <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Maximal number of character for the field. Leave 0 for unlimited field.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="gdtt-element-field gdtt-element-rewrite">
                            <h4 class="ui-widget-header"><?php _e("Mirror Value", "gd-taxonomies-tools") ?>:</h4>
                            <div class="gdtt-element-block">
                                <label><?php _e("Source Field", "gd-taxonomies-tools"); ?>:</label>
                                    <?php gdr2_UI::draw_select($custom_date_rewrite_fields, '', '', 'gdtt-cfe-rewrite'); ?>
                                    <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("If the field is selected on this list, this field will be hidden from the metabox display, but it's value will be auto populated based on the selected field using URL safe cleaned value.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="gdtt-element-field gdtt-element-unit">
                            <h4 class="ui-widget-header"><?php _e("Field Settings", "gd-taxonomies-tools") ?>:</h4>
                            <div class="gdtt-element-block">
                                <label><?php _e("Unit", "gd-taxonomies-tools"); ?>:</label>
                                    <?php gdr2_UI::draw_select($custom_list_of_units, '', '', 'gdtt-cfe-unit'); ?>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="gdtt-element-field gdtt-element-regex">
                            <h4 class="ui-widget-header"><?php _e("Restrictions", "gd-taxonomies-tools") ?>:</h4>
                            <div class="gdtt-element-block">
                                <label><?php _e("Method", "gd-taxonomies-tools"); ?>:</label>
                                    <?php gdr2_UI::draw_select_grouped($custom_restrictions_list, '', '', 'gdtt-cfe-regex', 'gdtt-select-grouped'); ?>
                                <div class="clear"></div>
                                <label><?php _e("Custom Regex", "gd-taxonomies-tools"); ?>:</label>
                                    <?php gdr2_UI::draw_input_text('', '', 'gdtt-cfe-regex_custom'); ?>
                                    <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("This must be JavaScript compatible regular expression.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                                <div class="clear"></div>
                                <label><?php _e("Custom Mask", "gd-taxonomies-tools"); ?>:</label>
                                    <?php gdr2_UI::draw_input_text('', '', 'gdtt-cfe-mask_custom'); ?>
                                    <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("This must be JavaScript compatible regular expression.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="gdtt-element-field gdtt-element-values">
                            <h4 class="ui-widget-header"><?php _e("Field Settings", "gd-taxonomies-tools") ?>:</h4>
                            <div class="gdtt-element-block" style="margin: 5px 0px 0px;">
                                <label><?php _e("Selection", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_select($selection_methods, '', '', 'gdtt-cfe-selection'); ?>
                                <div class="clear"></div>
                                <label><?php _e("Method", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_select($select_methods, '', '', 'gdtt-cfe-selmethod'); ?>
                                <div class="clear"></div>
                            </div>

                            <div class="gdtt-select-list gdtt-select-normal" style="display: none">
                                <h4 class="ui-widget-header"><?php _e("Normal list of values", "gd-taxonomies-tools") ?>:</h4>
                                <div class="gdtt-element-block">
                                    <?php gdr2_UI::draw_input_textarea('', '', 'gdtt-cfe-values'); ?>
                                    <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("One value per line.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                                    <div class="clear"></div>
                                </div>
                            </div>

                            <div class="gdtt-select-list gdtt-select-associative" style="display: none">
                                <h4 class="ui-widget-header"><?php _e("Associative list of values", "gd-taxonomies-tools") ?>:</h4>
                                <div class="gdtt-element-block">
                                    <?php gdr2_UI::draw_input_textarea('', '', 'gdtt-cfe-assoc-values'); ?>
                                    <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("One value per line. Value and title must be separated by pipe character.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                                    <div class="clear"></div>
                                </div>
                            </div>

                            <div class="gdtt-select-list gdtt-select-function" style="display: none">
                                <h4 class="ui-widget-header"><?php _e("Function to get values from", "gd-taxonomies-tools") ?>:</h4>
                                <div class="gdtt-element-block" style="margin: 5px 0px 0px;">
                                    <?php gdr2_UI::draw_select($custom_functions_list, '', '', 'gdtt-cfe-function', '', 'width: 384px'); ?>
                                </div>
                            </div>
                        </div>

                        <?php do_action('gdcpt_customfield_editor_elements_block'); ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
