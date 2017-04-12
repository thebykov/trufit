<table class="form-table"><tbody>
<tr class="last-row"><th scope="row"><?php _e("Plugin Controls", "gd-taxonomies-tools"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Enhancements", "gd-taxonomies-tools"); ?>:</td>
                <td>
                    <select style="width: 200px;" name="accessibility_enhancements">
                        <option<?php if ($options["accessibility_enhancements"] == "on") echo ' selected="selected"'; ?> value="on">Active</option>
                        <option<?php if ($options["accessibility_enhancements"] == "off") echo ' selected="selected"'; ?> value="off">Disabled</option>
                    </select>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <?php _e("Some of plugin controls are enhanced for better visual and usability purposes, but unfortunatly, some of the enhancements can cause accessibility problems. Blind users use screen readers that detects contols on the screen, and some of our enhanced elements can't be detected. These elements are checkboxes, radioboxes and multi select dropdown elements. With this option, set to disabled all these enhancements will be disabled, and normal controls displayed.", "gd-taxonomies-tools"); ?>
    </td>
</tr>
</tbody></table>
