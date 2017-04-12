<?php

$import_list = array(
    "one" => array("skip" => __("Don't import", "gd-taxonomies-tools"), "replace" => __("Replace existing", "gd-taxonomies-tools"), "append" => __("Add to existing", "gd-taxonomies-tools")),
    "two" => array("skip" => __("Don't import", "gd-taxonomies-tools"), "replace" => __("Replace existing", "gd-taxonomies-tools"))
);

?><form action="" method="post" enctype="multipart/form-data">
    <?php wp_nonce_field("gdcpt-tools"); ?>
    <table class="form-table form-table-margin"><tbody>
        <tr><th scope="row"><?php _e("Import from file", "gd-taxonomies-tools"); ?></th>
            <td>
                <input style="width: 350px;" type="file" name="gdtt_settings_file" />
                <div class="gdsr-table-split"></div>
                <?php _e("File must be created by export settings tool from this plugin. File contains serialized data, so do not change this file in any way, or the import will fail.", "gd-taxonomies-tools"); ?>
            </td>
        </tr>
        <tr class="last-row"><th scope="row"><?php _e("Import settings", "gd-taxonomies-tools"); ?></th>
            <td>
                <table cellpadding="0" cellspacing="0" class="previewtable">
                    <tr>
                        <td width="220" style="vertical-align: top;"><label><?php _e("Custom Post Types", "gd-taxonomies-tools"); ?>:</label></td>
                        <td style="vertical-align: top;">
                            <?php gdr2_UI::draw_select($import_list["one"], "", "gdtt_settings_info[cpt]", "", "", "width: 200px;"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="220" style="vertical-align: top;"><label><?php _e("Custom Taxonomies", "gd-taxonomies-tools"); ?>:</label></td>
                        <td style="vertical-align: top;">
                            <?php gdr2_UI::draw_select($import_list["one"], "", "gdtt_settings_info[tax]", "", "", "width: 200px;"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="220" style="vertical-align: top;"><label><?php _e("Objects Overrides", "gd-taxonomies-tools"); ?>:</label></td>
                        <td style="vertical-align: top;">
                            <?php gdr2_UI::draw_select($import_list["two"], "", "gdtt_settings_info[ovr]", "", "", "width: 200px;"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="220" style="vertical-align: top;"><label><?php _e("Custom Capabilities", "gd-taxonomies-tools"); ?>:</label></td>
                        <td style="vertical-align: top;">
                            <?php gdr2_UI::draw_select($import_list["two"], "", "gdtt_settings_info[cap]", "", "", "width: 200px;"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="220" style="vertical-align: top;"><label><?php _e("Meta Fields and Boxes", "gd-taxonomies-tools"); ?>:</label></td>
                        <td style="vertical-align: top;">
                            <?php gdr2_UI::draw_select($import_list["one"], "", "gdtt_settings_info[met]", "", "", "width: 200px;"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="220" style="vertical-align: top;"><label><?php _e("Plugin Settings", "gd-taxonomies-tools"); ?>:</label></td>
                        <td style="vertical-align: top;">
                            <?php gdr2_UI::draw_select($import_list["two"], "", "gdtt_settings_info[set]", "", "", "width: 200px;"); ?>
                        </td>
                    </tr>
                </table>
                <div class="gdsr-table-split"></div>
                <?php _e("If you use append operation, new elements from import file will replace old ones in case of the name conflict.", "gd-taxonomies-tools"); ?>
            </td>
        </tr>
    </tbody></table>
    <input type="submit" class="pressbutton" value="<?php _e("Import", "gd-taxonomies-tools"); ?>" name="gdtt_settings_import" />
</form>
<div class="gdsr-table-split"></div>
<script type="text/javascript">
    function gdtt_export_settings(url) {
        var cpt_list = [];
        var tax_list = [];
        var extra = "";

        var cpt = jQuery("#gdtt_export_obj_cpt").is(":checked") ? "1" : "0";
        var tax = jQuery("#gdtt_export_obj_tax").is(":checked") ? "1" : "0";
        var ovr = jQuery("#gdtt_export_overwrites").is(":checked") ? "1" : "0";
        var met = jQuery("#gdtt_export_meta").is(":checked") ? "1" : "0";
        var set = jQuery("#gdtt_export_settings").is(":checked") ? "1" : "0";
        var cap = jQuery("#gdtt_export_caps").is(":checked") ? "1" : "0";
        
        if (cpt == 1) {
            jQuery(".gdtt_export_cpt:checked").each(function(i){
                cpt_list.push("cl[]=" + this.value);
            });

            if (cpt_list.length == 0) {
                cpt = 0;
            } else {
                extra+= "&" + cpt_list.join("&");
            }
        }

        if (tax == 1) {
            jQuery(".gdtt_export_tax:checked").each(function(i){
                tax_list.push("tl[]=" + this.value);
            });

            if (tax_list.length == 0) {
                tax = 0;
            } else {
                extra+= "&" + tax_list.join("&");
            }
        }

        var full_url = url + extra + "&cpt=" + cpt + "&tax=" + tax + "&ovr=" + ovr + "&met=" + met + "&set=" + set + "&cap=" + cap + "&mod=settings";
        window.location = full_url
    }
</script>
<table class="form-table form-table-margin"><tbody>
<tr class="last-row"><th scope="row"><?php _e("Export to file", "gd-taxonomies-tools"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="24" style="vertical-align: top;"><input checked type="checkbox" id="gdtt_export_obj_cpt" value="on" /></td>
                <td width="196" style="vertical-align: top;"><label for="gdtt_export_obj_cpt"><?php _e("Custom Post Types", "gd-taxonomies-tools"); ?></label>:</td>
                <td style="vertical-align: top;">
                    <table cellpadding="0" cellspacing="0" class="previewtable">
                    <?php foreach ($list_cpt as $cpt) { ?>
                        <tr>
                            <td width="24" style="vertical-align: top;"><input checked type="checkbox" class="gdtt_export_cpt" value="<?php echo $cpt["id"]; ?>" /></td>
                            <td style="vertical-align: top;"><label><?php echo $cpt["labels"]["name"]." (".$cpt["name"].")"; ?></label></td>
                        </tr>
                    <?php } ?>
                    </table>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="24" style="vertical-align: top;"><input checked type="checkbox" id="gdtt_export_obj_tax" value="on" /></td>
                <td width="196" style="vertical-align: top;"><label for="gdtt_export_obj_tax"><?php _e("Custom Taxonomies", "gd-taxonomies-tools"); ?></label>:</td>
                <td style="vertical-align: top;">
                    <table cellpadding="0" cellspacing="0" class="previewtable">
                    <?php foreach ($list_tax as $tax) { ?>
                        <tr>
                            <td width="24" style="vertical-align: top;"><input checked type="checkbox" class="gdtt_export_tax" value="<?php echo $tax["id"]; ?>" /></td>
                            <td style="vertical-align: top;"><label><?php echo $tax["labels"]["name"]." (".$tax["name"].")"; ?></label></td>
                        </tr>
                    <?php } ?>
                    </table>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="24" style="vertical-align: top;"><input checked type="checkbox" id="gdtt_export_overwrites" value="on" /></td>
                <td width="196" style="vertical-align: top;"><label for="gdtt_export_overwrites"><?php _e("Objects Overrides", "gd-taxonomies-tools"); ?></label></td>
            </tr>
            <tr>
                <td width="24"><input checked type="checkbox" id="gdtt_export_caps" value="on" /></td>
                <td width="196" style="vertical-align: top;"><label for="gdtt_export_caps"><?php _e("Custom Capabilities", "gd-taxonomies-tools"); ?></label></td>
            </tr>
            <tr>
                <td width="24"><input checked type="checkbox" id="gdtt_export_meta" value="on" /></td>
                <td width="196" style="vertical-align: top;"><label for="gdtt_export_meta"><?php _e("Meta Fields and Boxes", "gd-taxonomies-tools"); ?></label></td>
            </tr>
            <tr>
                <td width="24"><input checked type="checkbox" id="gdtt_export_settings" value="on" /></td>
                <td width="196" style="vertical-align: top;"><label for="gdtt_export_settings"><?php _e("Plugin Settings", "gd-taxonomies-tools"); ?></label></td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <?php _e("Selected elements will be serialized and exported into the file.", "gd-taxonomies-tools"); ?>
    </td>
</tr>
</tbody></table>
<a class="pressbutton" href="javascript:gdtt_export_settings('<?php echo GDTAXTOOLS_URL; ?>export.php?_ajax_nonce=<?php echo wp_create_nonce("gdcptools-export"); ?>')"><?php _e("Export", "gd-taxonomies-tools"); ?></a>
