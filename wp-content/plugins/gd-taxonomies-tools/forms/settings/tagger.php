<table class="form-table form-table-margin"><tbody>
    <tr><th scope="row"><?php _e("Internal API", "gd-taxonomies-tools"); ?></th>
        <td>
            <table cellpadding="0" cellspacing="0" class="previewtable">
                <tr>
                    <td width="150"><?php _e("Tags Limit", "gd-taxonomies-tools"); ?>:</td>
                    <td><input type="text" name="tagger_internal_limit" id="tagger_internal_limit" value="<?php echo $options["tagger_internal_limit"]; ?>" style="width: 100px;" /></td>
                </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <?php _e("Internal API can return hundred of results, this will limit that number.", "gd-taxonomies-tools"); ?>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("Yahoo API", "gd-taxonomies-tools"); ?></th>
        <td>
            <table cellpadding="0" cellspacing="0" class="previewtable">
                <tr>
                    <td width="150"><?php _e("Application ID", "gd-taxonomies-tools"); ?>:</td>
                    <td><input type="text" name="tagger_yahoo_api_id" id="tagger_yahoo_api_id" value="<?php echo $options["tagger_yahoo_api_id"]; ?>" style="width: 400px;" /></td>
                </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <?php _e("This can be anything, it's used internally by Yahoo.", "gd-taxonomies-tools"); ?>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("OpenCalais API", "gd-taxonomies-tools"); ?></th>
        <td>
            <table cellpadding="0" cellspacing="0" class="previewtable">
                <tr>
                    <td width="150"><?php _e("API Key", "gd-taxonomies-tools"); ?>:</td>
                    <td><input type="text" name="tagger_opencalais_api_key" id="tagger_opencalais_api_key" value="<?php echo $options["tagger_opencalais_api_key"]; ?>" style="width: 400px;" /></td>
                </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <?php _e("To get API Key, you must register on the OpenCalais website", "gd-taxonomies-tools"); ?>:<br/>
            <a href="http://www.opencalais.com/APIkey" target="_blank">http://www.opencalais.com/APIkey</a>
        </td>
    </tr>
    <tr><th scope="row"><?php _e("Alchemy API", "gd-taxonomies-tools"); ?></th>
        <td>
            <table cellpadding="0" cellspacing="0" class="previewtable">
                <tr>
                    <td width="150"><?php _e("API Key", "gd-taxonomies-tools"); ?>:</td>
                    <td><input type="text" name="tagger_alchemy_api_key" id="tagger_alchemy_api_key" value="<?php echo $options["tagger_alchemy_api_key"]; ?>" style="width: 400px;" /></td>
                </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <?php _e("To get API Key, you must register on the Alchemy website", "gd-taxonomies-tools"); ?>:<br/>
            <a href="http://www.alchemyapi.com/api/register.html" target="_blank">http://www.alchemyapi.com/api/register.html</a>
        </td>
    </tr>
    <tr class="last-row"><th scope="row"><?php _e("Zemanta API", "gd-taxonomies-tools"); ?></th>
        <td>
            <table cellpadding="0" cellspacing="0" class="previewtable">
                <tr>
                    <td width="150"><?php _e("API Key", "gd-taxonomies-tools"); ?>:</td>
                    <td><input type="text" name="tagger_zemanta_api_key" id="tagger_zemanta_api_key" value="<?php echo $options["tagger_zemanta_api_key"]; ?>" style="width: 400px;" /></td>
                </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <?php _e("To get API Key, you must register on the Zemanta website", "gd-taxonomies-tools"); ?>:<br/>
            <a href="http://developer.zemanta.com/apps/register/" target="_blank">http://developer.zemanta.com/apps/register/</a>
        </td>
    </tr>
</tbody></table>
