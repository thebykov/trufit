<div style="display:none;">
    <div id="gdcpt-mod-cf-term" title="<?php _e("Select Term", "gd-taxonomies-tools"); ?>" class="gdcpt-editor-popup-dialog">
        <div class="contentbox">
            <div class="gdtt-popup-panel">
                <table class="gdtt-popup-table">
                    <tbody>
                        <tr>
                            <td class="gdtt-td-left">
                                <h3 id="gdcpt-mod-cf-term-title">Posts</h3>
                                <label><?php _e("Search for term", "gd-taxonomies-tools"); ?>:</label>
                                <input id="gdcpt-mod-cf-term-taxonomy" type="hidden" />
                                <input id="gdcpt-mod-cf-term-search" class="gdcpt-mod-cf-post-input" type="text" />
                                <div class="gdtt-loader"><?php _e("Working. Please wait...", "gd-taxonomies-tools"); ?></div>
                            </td>
                            <td class="gdtt-td-right">
                                <div id="gdcpt-mod-cf-term-results" class="gdcpt-mod-panel-results">
                                    
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="submitbox">
            <div class="gdcpt-button-cancel">
                <a class="submitdelete gdtt-close-term-dialog" href="#"><?php _e("Cancel", "gd-taxonomies-tools"); ?></a>
            </div>
            <div class="gdcpt-button-update">
                <?php submit_button(__("Save", "gd-taxonomies-tools"), 'primary', 'gdtt-mod-cf-term-insert', false, array('tabindex' => 100, 'disabled' => 'disabled')); ?>
            </div>
        </div>
    </div>
</div>
<script language="javascript" type="text/javascript">
    jQuery(document).ready(function() {
        gdCPT_CF_Admin.term.init();
    });
</script>