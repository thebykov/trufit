/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
function gdtt_attach_image(term_id, image, image_url) {
    jQuery("#gdtt-tid-" + term_id).html(image);
    jQuery("#gdtt-tip-" + term_id).attr("href", image_url);
    jQuery("#gdtt-tia-" + term_id).show();
    jQuery("#gdtt-tip-" + term_id).show();
}

jQuery(document).ready(function() {
    jQuery('.gtc-preview').click(function() {
        var url = jQuery(this).attr("href");
        tb_show(gdttImages.attach_preview, url);
        return false;
    });

    jQuery('.gtc-edit').click(function() {
        var term_id = jQuery(this).attr("href").substr(1);
        var taxonomy = jQuery(this).attr("rel");
        tb_show('', 'media-upload.php?type=image&amp;gdtt_tax=' + taxonomy + '&amp;gdtt_term=' + term_id + '&amp;TB_iframe=true');
        return false;
    });

    jQuery('.gtc-delete').click(function() {
        var term_id = jQuery(this).attr("href").substr(1);
        var taxonomy = jQuery(this).attr("rel");

        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            dataType: 'json',
            cache: false,
            data: {'action': 'gd_cpt_dettach_image',
                   '_ajax_nonce': gdttImages.nonce,
                   'term_id': term_id,
                   'taxonomy': taxonomy
            },
            success: function(json) {
                if (json.status === 'ok') {
                    jQuery("#gdtt-tia-" + term_id).hide();
                    jQuery("#gdtt-tip-" + term_id).hide();
                    jQuery("#gdtt-tid-" + term_id).html("");
                }
            }
        });

        return false;
    });
});
