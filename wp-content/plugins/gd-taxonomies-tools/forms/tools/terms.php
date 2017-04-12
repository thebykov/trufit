<form action="" method="post" enctype="multipart/form-data">
    <?php wp_nonce_field("gdcpt-tools"); ?>
    <table class="form-table form-table-margin"><tbody>
        <tr><th scope="row"><?php _e("Import from file", "gd-taxonomies-tools"); ?></th>
            <td>
                <input style="width: 350px;" type="file" name="gdtt_import_file" />
                <div class="gdsr-table-split"></div>
                <?php _e("This must be plain text file (any extension will do). Each line must contain only one term.", "gd-taxonomies-tools"); ?>
                <?php _e("If you want to import hierarhical terms, child items should start with sign *. Hierarchy depth is determined by number of * signs.", "gd-taxonomies-tools"); ?>
                <?php _e("Example files are in the plugin info folder.", "gd-taxonomies-tools"); ?>
            </td>
        </tr>
        <tr class="last-row"><th scope="row"><?php _e("Import settings", "gd-taxonomies-tools"); ?></th>
            <td>
                <table cellpadding="0" cellspacing="0" class="previewtable">
                    <tr>
                        <td width="200"><?php _e("Taxonomy", "gd-taxonomies-tools"); ?>:</td>
                        <td>
                            <select style="width: 200px;" class="widefat" name="gdtt_import_tax">
                                <?php gdtt_render_taxonomies(); ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <div class="gdsr-table-split"></div>
                <?php _e("If you import file with hierarchy into non hierarhical taxonomy, hierarchy will be ignored.", "gd-taxonomies-tools"); ?>
            </td>
        </tr>
    </tbody></table>
    <input type="submit" class="pressbutton" value="<?php _e("Import", "gd-taxonomies-tools"); ?>" name="gdtt_tools_import" />
</form>
<div class="gdsr-table-split"></div>
<script type="text/javascript">
    function gdtt_export_data(url) {
        var tax = jQuery("#gdtt_export_tax").val();
        var hir = jQuery("#gdtt_export_hierarchy").val() == "on" ? "1" : "0";
        window.location = url + "&tax=" + tax + "&hir=" + hir + "&mod=terms";
    }
</script>
<table class="form-table form-table-margin"><tbody>
<tr class="last-row"><th scope="row"><?php _e("Export to file", "gd-taxonomies-tools"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="200"><?php _e("Taxonomy", "gd-taxonomies-tools"); ?>:</td>
                <td><select style="width: 200px;" class="widefat" id="gdtt_export_tax"><?php gdtt_render_taxonomies(); ?></select></td>
            </tr>
            <tr>
                <td width="200" style="height: 25px;"><label for="gdtt_export_hierarchy"><?php _e("Export hierarchy", "gd-taxonomies-tools"); ?></label>:</td>
                <td><input checked type="checkbox" id="gdtt_export_hierarchy" value="on" /></td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <?php _e("If the taxonomy is not hierarchical, hierarchy export option will be ignored.", "gd-taxonomies-tools"); ?>
    </td>
</tr>
</tbody></table>
<a class="pressbutton" href="javascript:gdtt_export_data('<?php echo GDTAXTOOLS_URL; ?>export.php?_ajax_nonce=<?php echo wp_create_nonce("gdcptools-export"); ?>')"><?php _e("Export", "gd-taxonomies-tools"); ?></a>
