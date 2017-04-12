<table class="widefat">
    <thead>
        <tr>
            <th scope="col" style=''><?php _e("Code", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style=''><?php _e("Name", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style=''><?php _e("Meta Boxes", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style=''><?php _e("Post Types", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style=''><?php _e("Location", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style="width: 128px; text-align: right;"><?php _e("Options", "gd-taxonomies-tools"); ?></th>
        </tr>
    </thead>
    <tbody id="list-groups">
        <?php

        if (empty($gdtt_meta['groups'])) {
            echo '<tr><td colspan="6">'.__("There are no meta box groups right now.", "gd-taxonomies-tools").'</td></tr>';
        } else {
            foreach ($gdtt_meta['groups'] as $code => $obj) {
                $meta_box = (array)$obj;

                $pts = array();

                if (isset($gdtt_meta['map_groups'][$code])) {
                    foreach ($gdtt_meta['map_groups'][$code] as $pt) {
                        $pts[] = $post_types_list[$pt].' (<strong>'.$pt.'</strong>)';
                    }
                }

                if (empty($pts)) {
                    $pts[] = '/';
                }

                echo '<tr class="gdtt-mbgrow-'.$code.'">';
                    echo '<td><strong>'.$code.'</strong></td>';
                    echo '<td>'.$meta_box['name'].'</td>';
                    echo '<td>'.$gdtt_fields->get_group_boxes($code).'</td>';
                    echo '<td class="gdtt-post-types">'.join('<br/>', $pts).'</td>';
                    echo '<td>'.$custom_meta_locations[$meta_box['location']].'</td>';
                    echo '<td style="width: 128px; text-align: right;">';
                        echo '<a class="ttoption-edit gdtt-mbg-edit" href="#'.$code.'">'.__("edit", "gd-taxonomies-tools").'</a> | ';
                        echo '<a class="ttoption-del gdtt-mbg-delete" href="#'.$code.'">'.__("delete", "gd-taxonomies-tools").'</a><br/>';
                        echo '<a class="ttoption-edit gdtt-mbg-postypes" href="#'.$code.'">'.__("post types", "gd-taxonomies-tools").'</a>';
                    echo '</td>';
                echo '</tr>';
            }
        }

        ?>
    </tbody>
</table>
<a class="pressbutton" id="gdtt-mbg-addnew" href="#" style="margin: 7px 0 0;"><?php _e("Add New Meta Box Group", "gd-taxonomies-tools"); ?></a>
<div class="gdr2-dialog-blocks">
    <div id="gdttmbgdelete" title="<?php _e("Delete Meta Box Group", "gd-taxonomies-tools"); ?>">
        <h4 class="ui-widget-header"><?php _e("Read this before deleting the meta box", "gd-taxonomies-tools") ?>:</h4>
        <div class="gdtt-element-block">
            <?php _e("Removing meta box group definition doesn't affect any data stored in the database associeted with the fields in the meta boxes.", "gd-taxonomies-tools"); ?> 
        </div>
        <h4 class="ui-widget-header"><?php _e("Confirm deletion", "gd-taxonomies-tools") ?>:</h4>
        <div class="gdtt-element-block">
            <input type="checkbox" id="gdtt-mbg-del-definition" /><label class="gdtt-dialog-check-label" for="gdtt-mbg-del-definition"><?php _e("Delete plugin definition for this meta box group.", "gd-taxonomies-tools"); ?></label>
        </div>
    </div>

    <div id="gdttmbgptypes" title="<?php _e("Post types to use this Meta Box Groups", "gd-taxonomies-tools"); ?>">
        <div class="gdtt-element-block">
            <?php _e("Select one or more post types you want to use this Meta box group.", "gd-taxonomies-tools"); ?>
        </div>
        <h4 class="ui-widget-header"><?php _e("Select post types", "gd-taxonomies-tools") ?>:</h4>
        <div class="gdtt-element-posttypes">
            <ul id="gdtt-ptypesbasic-group">
                <li class="gdtt-state-default">
                    <?php gdr2_UI::draw_select($post_types_list, '', '', ''); ?>
                    <div class="gdr2-metafield-buttons">
                        <div class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span></div>
                        <div class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-minus"></span></div>
                    </div>
                </li>
            </ul>
            <ul id="gdtt-ptypesfields-group"></ul>
        </div>
    </div>

    <div id="gdttmbgedit" title="<?php _e("Meta Box Group Editor", "gd-taxonomies-tools"); ?>">
        <table>
            <tbody>
                <tr>
                    <td style="width: 390px; vertical-align: top;">
                        <h4 class="ui-widget-header"><?php _e("Basic settings", "gd-taxonomies-tools") ?>:</h4>
                        <div class="gdtt-element-block">
                            <label><?php _e("Code", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_input_text('', '', 'gdtt-mbg-code'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Required, must be unique. Use letters, numbers and underscore only.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                            <label><?php _e("Name", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_input_text('', '', 'gdtt-mbg-name'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Required, used by the plugin, be descriptive but not too long.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                            <label><?php _e("Location", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_select($custom_meta_locations, '', '', 'gdtt-mbg-location'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Where on the post editor to add meta box by default. If the user moves the meta box, last used location will override this setting.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                        </div>
                    </td>
                    <td style="width: 10px;">&nbsp;</td>
                    <td style="width: 390px; vertical-align: top;">
                        <h4 class="ui-widget-header"><?php _e("User Restriction", "gd-taxonomies-tools") ?>:</h4>
                        <div class="gdtt-element-block">
                            <label><?php _e("Type", "gd-taxonomies-tools"); ?>:</label>
                            <?php gdr2_UI::draw_select(array('none' => __("None", "gd-taxonomies-tools"), 'role' => __("User Roles", "gd-taxonomies-tools"), 'caps' => __("User Capabilities", "gd-taxonomies-tools")), 'text', '', 'gdtt-mbg-user_access'); ?>
                            <div class="clear"></div>
                            <label><?php _e("User Roles", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_input_text('', '', 'gdtt-mbg-user_roles'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Comma separated list of user roles that can edit this field value.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                            <label><?php _e("User Caps", "gd-taxonomies-tools"); ?>:</label>
                                <?php gdr2_UI::draw_input_text('', '', 'gdtt-mbg-user_caps'); ?>
                                <div class="gdr2-description"><div class="ui-state-default ui-corner-all"><span qtip-content="<?php _e("Comma separated list of user capabilities. User needs one of them to be able to edit this field value.", "gd-taxonomies-tools"); ?>" class="ui-icon ui-icon-help gdr2-qtip"></span></div></div>
                            <div class="clear"></div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <h4 class="ui-widget-header"><?php _e("List of meta boxes", "gd-taxonomies-tools") ?>:</h4>
        <div class="gdtt-element-fields">
            <ul id="gdtt-metagroupbasic">
                <li class="gdtt-state-default">
                    <span class="ui-icon ui-icon-arrowthick-2-n-s gdtt-field-drag"></span>
                    <?php gdr2_UI::draw_select($custom_boxes_list, '', '', ''); ?>
                    <div class="gdr2-metafield-group-buttons">
                        <div class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span></div>
                        <div class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-minus"></span></div>
                    </div>
                </li>
            </ul>
            <ul id="gdtt-metaboxes"></ul>
        </div>
    </div>
</div>