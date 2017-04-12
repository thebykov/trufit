<form action="" method="post">
    <?php wp_nonce_field("gdcpt-tools"); ?>
    <table class="form-table form-table-margin"><tbody>
        <tr class="last-row"><th scope="row"><?php _e("Data to Reset", "gd-taxonomies-tools"); ?></th>
            <td>
                <table cellpadding="0" cellspacing="0" class="previewtable">
                    <tr>
                        <td width="250" style="height: 25px;"><label for="gdtt_reset_ccpt"><?php _e("Custom Post Types", "gd-taxonomies-tools"); ?></label>:</td>
                        <td><input type="checkbox" name="gdtt_reset[ccpt]" id="gdtt_reset_ccpt" value="on" /></td>
                    </tr>
                    <tr>
                        <td width="250" style="height: 25px;"><label for="gdtt_reset_ctax"><?php _e("Custom Taxonomies", "gd-taxonomies-tools"); ?></label>:</td>
                        <td><input type="checkbox" name="gdtt_reset[ctax]" id="gdtt_reset_ctax" value="on" /></td>
                    </tr>
                    <tr>
                        <td width="250" style="height: 25px;"><label for="gdtt_reset_ocpt"><?php _e("Post Types Overrides", "gd-taxonomies-tools"); ?></label>:</td>
                        <td><input type="checkbox" name="gdtt_reset[ocpt]" id="gdtt_reset_ocpt" value="on" /></td>
                    </tr>
                    <tr>
                        <td width="250" style="height: 25px;"><label for="gdtt_reset_otax"><?php _e("Taxonomies Overrides", "gd-taxonomies-tools"); ?></label>:</td>
                        <td><input type="checkbox" name="gdtt_reset[otax]" id="gdtt_reset_otax" value="on" /></td>
                    </tr>
                    <tr>
                        <td width="250" style="height: 25px;"><label for="gdtt_reset_meta"><?php _e("Meta fields and boxes", "gd-taxonomies-tools"); ?></label>:</td>
                        <td><input type="checkbox" name="gdtt_reset[meta]" id="gdtt_reset_meta" value="on" /></td>
                    </tr>
                    <tr>
                        <td width="250" style="height: 25px;"><label for="gdtt_reset_ocpt"><?php _e("Post Types Order", "gd-taxonomies-tools"); ?></label>:</td>
                        <td><input type="checkbox" name="gdtt_reset[ocpt]" id="gdtt_reset_ocpt" value="on" /></td>
                    </tr>
                    <tr>
                        <td width="250" style="height: 25px;"><label for="gdtt_reset_otax"><?php _e("Taxonomies Order", "gd-taxonomies-tools"); ?></label>:</td>
                        <td><input type="checkbox" name="gdtt_reset[otax]" id="gdtt_reset_otax" value="on" /></td>
                    </tr>
                    <tr>
                        <td width="250" style="height: 25px;"><label for="gdtt_reset_settings"><?php _e("Plugins Settings", "gd-taxonomies-tools"); ?></label>:</td>
                        <td><input type="checkbox" name="gdtt_reset[settings]" id="gdtt_reset_settings" value="on" /></td>
                    </tr>
                </table>
                <div class="gdsr-table-split"></div>
                <?php _e("Selected elements will be deleted. Make sure that you make a backup before proceeding because operation is not reversible.", "gd-taxonomies-tools"); ?>
            </td>
        </tr>
    </tbody></table>
    <input type="submit" class="pressbutton" value="<?php _e("Reset", "gd-taxonomies-tools"); ?>" name="gdtt_reset_data" />
</form>
<div class="gdsr-table-split"></div>
<form action="" method="post">
    <?php wp_nonce_field("gdcpt-tools"); ?>
    <table class="form-table form-table-margin"><tbody>
        <tr class="last-row"><th scope="row"><?php _e("Cache and Rules<br/>to Reset", "gd-taxonomies-tools"); ?></th>
            <td>
                <table cellpadding="0" cellspacing="0" class="previewtable">
                    <tr>
                        <td width="250" style="height: 25px;"><label for="gdtt_cache_plugin"><?php _e("Plugin registration cache", "gd-taxonomies-tools"); ?></label>:</td>
                        <td><input type="checkbox" name="gdtt_cache[plugin]" id="gdtt_cache_plugin" value="on" /></td>
                    </tr>
                    <tr>
                        <td width="250" style="height: 25px;"><label for="gdtt_cache_rewrite"><?php _e("WordPress rewrite rules", "gd-taxonomies-tools"); ?></label>:</td>
                        <td><input type="checkbox" name="gdtt_cache[rewrite]" id="gdtt_cache_rewrite" value="on" /></td>
                    </tr>
                </table>
                <div class="gdsr-table-split"></div>
                <?php _e("Removing this is considered safe, it will be regenerated automatically.", "gd-taxonomies-tools"); ?>
            </td>
        </tr>
    </tbody></table>
    <input type="submit" class="pressbutton" value="<?php _e("Reset", "gd-taxonomies-tools"); ?>" name="gdtt_reset_cache" />
</form>
