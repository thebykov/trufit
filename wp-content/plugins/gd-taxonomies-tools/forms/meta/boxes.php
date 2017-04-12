<table class="widefat">
    <thead>
        <tr>
            <th scope="col" style=''><?php _e("Code", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style=''><?php _e("Name", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style=''><?php _e("Custom Fields", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style=''><?php _e("Post Types", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style=''><?php _e("Location", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style=''><?php _e("Description", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style="width: 128px; text-align: right;"><?php _e("Options", "gd-taxonomies-tools"); ?></th>
        </tr>
    </thead>
    <tbody id="list-boxes">
        <?php

        if (empty($gdtt_meta['boxes'])) {
            echo '<tr><td colspan="7">'.__("There are no meta boxes right now.", "gd-taxonomies-tools").'</td></tr>';
        } else {
            foreach ($gdtt_meta['boxes'] as $code => $obj) {
                $meta_box = (array)$obj;

                $pts = array();

                if (isset($gdtt_meta['map'][$code])) {
                    foreach ($gdtt_meta['map'][$code] as $pt) {
                        if (isset($post_types_list[$pt])) {
                            $pts[] = $post_types_list[$pt].' (<strong>'.$pt.'</strong>)';
                        } else {
                            $pts[] = '(<strong>'.$pt.'</strong>)';
                        }
                    }
                }

                if (empty($pts)) {
                    $pts[] = '/';
                }

                echo '<tr class="gdtt-mbrow-'.$code.'">';
                    echo '<td><strong>'.$code.'</strong></td>';
                    echo '<td>'.$meta_box['name'].'</td>';
                    echo '<td>'.$gdtt_fields->get_box_fields($code).'</td>';
                    echo '<td class="gdtt-post-types">'.join('<br/>', $pts).'</td>';
                    echo '<td>'.$custom_meta_locations[$meta_box['location']].'</td>';
                    echo '<td>'.$meta_box['description'].'</td>';
                    echo '<td style="width: 128px; text-align: right;">';
                        echo '<a class="ttoption-edit gdtt-mbo-edit" href="#'.$code.'">'.__("edit", "gd-taxonomies-tools").'</a> | ';
                        echo '<a class="ttoption-del gdtt-mbo-delete" href="#'.$code.'">'.__("delete", "gd-taxonomies-tools").'</a><br/>';
                        echo '<a class="ttoption-edit gdtt-mbo-postypes" href="#'.$code.'">'.__("post types", "gd-taxonomies-tools").'</a>';
                    echo '</td>';
                echo '</tr>';
            }
        }

        ?>
    </tbody>
</table>
<a class="pressbutton" id="gdtt-mbe-addnew" href="#" style="margin: 7px 0 0;"><?php _e("Add New Meta Box", "gd-taxonomies-tools"); ?></a>
<div class="gdr2-dialog-blocks">
    <div id="gdttmbdelete" title="<?php _e("Delete Meta Box", "gd-taxonomies-tools"); ?>">
        <h4 class="ui-widget-header"><?php _e("Read this before deleting the meta box", "gd-taxonomies-tools") ?>:</h4>
        <div class="gdtt-element-block">
            <?php _e("Removing meta box definition doesn't affect any data stored in the database associeted with the fields in the meta box.", "gd-taxonomies-tools"); ?> 
        </div>
        <h4 class="ui-widget-header"><?php _e("Confirm deletion", "gd-taxonomies-tools") ?>:</h4>
        <div class="gdtt-element-block">
            <input type="checkbox" id="gdtt-mbe-del-definition" /><label class="gdtt-dialog-check-label" for="gdtt-mbe-del-definition"><?php _e("Delete plugin definition for this meta box.", "gd-taxonomies-tools"); ?></label>
        </div>
    </div>

    <div id="gdttmbptypes" title="<?php _e("Post types to use this Meta Box", "gd-taxonomies-tools"); ?>">
        <div class="gdtt-element-block">
            <?php _e("Select one or more post types you want to use this Meta box.", "gd-taxonomies-tools"); ?>
        </div>
        <h4 class="ui-widget-header"><?php _e("Select post types", "gd-taxonomies-tools") ?>:</h4>
        <div class="gdtt-element-posttypes">
            <ul id="gdtt-ptypesbasic">
                <li class="gdtt-state-default">
                    <?php gdr2_UI::draw_select($post_types_list, '', '', ''); ?>
                    <div class="gdr2-metafield-buttons">
                        <div class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span></div>
                        <div class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-minus"></span></div>
                    </div>
                </li>
            </ul>
            <ul id="gdtt-ptypesfields"></ul>
        </div>
    </div>

    <div id="gdttmbedit" title="<?php _e("Meta Box Editor", "gd-taxonomies-tools"); ?>">
        <table>
            <tbody>
                <tr>
                    <td style="width: 390px; vertical-align: top;">
                        <h4 class="ui-widget-header"><?php _e("Basic settings", "gd-taxonomies-tools") ?>:</h4>
                        <div class="gdtt-element-block">
                            <label><?php _e("Code", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_input_text('', '', 'gdtt-mbe-code'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Required, must be unique. Use letters, numbers and underscore only.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                            <label><?php _e("Name", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_input_text('', '', 'gdtt-mbe-name'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Required, used by the plugin, be descriptive but not too long.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                            <label><?php _e("Location", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_select($custom_meta_locations, '', '', 'gdtt-mbe-location'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Where on the post editor to add meta box by default. If the user moves the meta box, last used location will override this setting.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                            <label><?php _e("Field Repeater", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_select(array('no' => __("No", "gd-taxonomies-tools"), 'yes' => __("Active", "gd-taxonomies-tools")), 'text', '', 'gdtt-mbe-repeater'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("If active, each field (with exception of rich editors) will have option to add multiple instances.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                        </div>
                    </td>
                    <td style="width: 10px;">&nbsp;</td>
                    <td style="width: 390px; vertical-align: top;">
                        <h4 class="ui-widget-header"><?php _e("User Restriction", "gd-taxonomies-tools") ?>:</h4>
                        <div class="gdtt-element-block">
                            <label><?php _e("Type", "gd-taxonomies-tools"); ?>:</label>
                            <?php gdr2_UI::draw_select(array('none' => __("None", "gd-taxonomies-tools"), 'role' => __("User Roles", "gd-taxonomies-tools"), 'caps' => __("User Capabilities", "gd-taxonomies-tools")), 'text', '', 'gdtt-mbe-user_access'); ?>
                            <div class="clear"></div>
                            <label><?php _e("User Roles", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_input_text('', '', 'gdtt-mbe-user_roles'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Comma separated list of user roles that can edit this field value.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                            <label><?php _e("User Caps", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_input_text('', '', 'gdtt-mbe-user_caps'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Comma separated list of user capabilities. User needs one of them to be able to edit this field value.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <h4 class="ui-widget-header"><?php _e("Additional Settings", "gd-taxonomies-tools") ?>:</h4>
        <div class="gdtt-element-block">
            <label><?php _e("Description", "gd-taxonomies-tools"); ?>:</label>
                <?php gdr2_UI::draw_input_text('', '', 'gdtt-mbe-description', '', 'width: 650px;'); ?>
                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Optional, used by the plugin, longer description of the field. This will be displayed in the meta box.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
            <div class="clear"></div>
        </div>            
        <h4 class="ui-widget-header"><?php _e("List of fields", "gd-taxonomies-tools") ?>:</h4>
        <div class="gdtt-element-fields">
            <ul id="gdtt-metabasic">
                <li class="gdtt-state-default">
                    <span class="ui-icon ui-icon-arrowthick-2-n-s gdtt-field-drag"></span>
                    <?php gdr2_UI::draw_select($custom_fields_list, '', '', ''); ?>
                    <div class="gdr2-metafield-buttons">
                        <div class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span></div>
                        <div class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-minus"></span></div>
                    </div>
                </li>
            </ul>
            <ul id="gdtt-metafields"></ul>
        </div>
    </div>
</div>
