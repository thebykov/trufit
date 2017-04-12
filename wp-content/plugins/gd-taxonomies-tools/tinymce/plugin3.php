<?php

global $gdtt, $post_type;

$taxonomies = gdtt_get_taxonomies_for_post_types($post_type, "object");
$tax_h = array();
foreach ($taxonomies as $tax) {
    if ($tax->show_ui) {
        $tax_h[] = "'".$tax->name."': ".($tax->hierarchical ? 1 : 0);
    }
}

?>
<div style="display:none;">
    <script language="javascript" type="text/javascript">
        var gdtt_tax_hierarchy = <?php echo "{ ".join(", ", $tax_h)." }"; ?>;
    </script>
    <form id="gdtt-tinymce-plugin" tabindex="-1" class="gdcpt-editor-popup-dialog">
        <div id="gdtt-tinymce-selector">
            <div id="linktax_panel">

                <table class="tbltop" cellpadding="3" cellspacing="0" width="100%">
                    <tr>
                        <td class="gdsrleft"><?php _e("Taxonomy", "gd-taxonomies-tools"); ?>:</td>
                        <td class="gdsrright">
                            <select id="txTaxonomy" name="txTaxonomy">
                                <?php

                                    foreach ($taxonomies as $tax) {
                                        if ($tax->show_ui) {
                                            echo "\t<option value='".$tax->name."'>".$tax->label."</option>\r\n";
                                        }
                                    }

                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="gdsrleft"><?php _e("Term Text", "gd-taxonomies-tools"); ?>:</td>
                        <td class="gdsrright">
                            <input type="text" id="txOriginalText" name="txOriginalText" value="" />
                        </td>
                    </tr>
                </table>
                <input type="hidden" id="taxonomy" value="" />
                <input type="hidden" id="searchid" value="-1" />
                <input type="hidden" id="addsmode" value="check" />
                <div id="ttstatus">
                    <a href="javascript:gdtt_check_taxonomy()"><?php _e("check term", "gd-taxonomies-tools"); ?></a> |
                    <a href="javascript:gdtt_search_taxonomy()"><?php _e("search for term", "gd-taxonomies-tools"); ?></a>
                </div>
                <div id="termnotfound" class="ttresult tthide">
                    <div class="flle"><?php _e("Term not found for selected taxonomy.", "gd-taxonomies-tools") ?></div>
                    <div class="flri"><a href="javascript:gdtt_add_taxonomy('<?php echo GDTAXTOOLS_URL."ajax.php"; ?>')"><?php _e("add term", "gd-taxonomies-tools"); ?></a></div>
                    <div class="clear"></div>
                </div>
                <div id="termfound" class="ttresult tthide">
                    <div id="foundterms"></div>
                    <div class="ttline"></div>
                </div>
                <div id="termok" class="ttresult tthide">
                    <?php _e("Term:", "gd-taxonomies-tools") ?><br/><span class="ttname" id="termname">/</span> (<span class="ttslug" id="termslug">/</span>)<br/>
                    <?php _e("Permalink:", "gd-taxonomies-tools") ?><br/><span class="ttperm" id="termperm">/</span>
                    <div class="ttline"></div>
                </div>
                <div id="termoptions" class="ttresult tthide">
                    <table class="tbltop" cellpadding="3" cellspacing="0" width="100%">
                        <tr>
                            <td class="gdsrleft" style="width: 18px;">
                                <input checked class="tblcheck" type="checkbox" size="5" id="txCntReplace" name="txCntReplace" value="on" />
                            </td>
                            <td class="gdsrleft">
                                <label for="txCntReplace"><?php _e("Replace original selection in the editor.", "gd-taxonomies-tools"); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="gdsrleft" style="width: 18px;">
                                <input checked class="tblcheck" type="checkbox" size="5" id="txCntHref" name="txCntHref" value="on" />
                            </td>
                            <td class="gdsrleft">
                                <label for="txCntHref"><?php _e("Enclose the selection with term permalink.", "gd-taxonomies-tools"); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="gdsrleft" style="width: 18px;">
                                <input checked class="tblcheck" type="checkbox" size="5" id="txAddTerm" name="txAddTerm" value="on" />
                            </td>
                            <td class="gdsrleft">
                                <label for="txAddTerm"><?php _e("Add term for this taxonomy if not already added.", "gd-taxonomies-tools"); ?></label>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
            <p id="gdtt-internal-toggle" class="toggle-arrow"><?php _e("Linking settings and additional information", "gd-taxonomies-tools"); ?></p>
            <div id="setting_panel" style="display: none">

                <table>
                    <tr>
                        <td class="gdsrleft">
                            <?php _e("Limit number of search results", "gd-taxonomies-tools"); ?>:
                        </td>
                        <td class="gdsrright">
                            <input type="text" id="txSearchLimit" name="txSearchLimit" style="width: 40px; text-align: right;" value="<?php echo $gdtt->o["tinymce_search_limit"]; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="gdsrleft">
                            <?php _e("Auto create taxonomy on check", "gd-taxonomies-tools"); ?>:
                        </td>
                        <td class="gdsrright">
                            <input<?php echo $gdtt->o["tinymce_auto_create"] == 1 ? " checked" : ""; ?> class="tblcheck" type="checkbox" size="5" id="txAutoCheck" name="txAutoCheck" value="on" />
                        </td>
                    </tr>
                    <tr>
                        <td class="gdsrleft">
                            <?php _e("Use shortcode instead of direct linking", "gd-taxonomies-tools"); ?>:
                        </td>
                        <td class="gdsrright">
                            <input<?php echo $gdtt->o["tinymce_use_shortcode"] == 1 ? " checked" : ""; ?> class="tblcheck" type="checkbox" size="5" id="txUseShortcode" name="txUseShortcode" value="on" />
                        </td>
                    </tr>
                </table>
                <p><?php _e("If you use shortcodes for linking, you will get more flexibility because links will not be hardcoded. If you change domain, or make permalinks changes links will still be correct.", "gd-taxonomies-tools"); ?> <?php _e("For shortcode to work, you need this plugin active.", "gd-taxonomies-tools"); ?></p>
                <p><?php _e("If you use direct links, links will be hardcoded using current permalink to the term, and you will have to change everyone of them if you make changes that modify the permalinks structure.", "gd-taxonomies-tools"); ?></p>

            </div>
        </div>
        <div class="submitbox">
            <div id="gdtt-tinymce-cancel">
                <a class="submitdelete deletion" href="#"><?php _e("Cancel", "gd-taxonomies-tools"); ?></a>
            </div>
            <div id="gdtt-tinymce-update">
                <?php submit_button(__("Insert", "gd-taxonomies-tools"), 'primary', 'gdtt-tinymce-insert', false, array('tabindex' => 100)); ?>
            </div>
        </div>
    </form>
</div>