<div style="display:none;">
    <div id="gdcpt-build-shortcode" title="<?php _e("Build Shortcode", "gd-taxonomies-tools"); ?>" class="gdcpt-editor-popup-dialog">
        <div class="contentbox">
            <div class="gdtt-popup-panel">
                <div id="gdcpt-shortcode" class="gdtt-shortcode-built">
                    [cpt_field name=""]
                </div>
                <div class="gdtt-shortcode-elements">
                    ...
                </div>
            </div>
        </div>
        <div class="submitbox">
            <div class="gdcpt-button-cancel">
                <a class="submitdelete gdtt-close-shortcoder-dialog" href="#"><?php _e("Cancel", "gd-taxonomies-tools"); ?></a>
            </div>
            <div class="gdcpt-button-update">
                <div class="gdtt-loader"></div>
                <?php submit_button(__("Insert", "gd-taxonomies-tools"), 'primary', 'gdtt-shortcoder-insert', false, array('tabindex' => 110)); ?>
            </div>
        </div>
    </div>
</div>
