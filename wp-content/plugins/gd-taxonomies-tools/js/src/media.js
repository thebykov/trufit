/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
jQuery(document).ready(function() {
    window.prepareMediaItem = function(fileObj, serverData) {
        var f = (typeof shortform == 'undefined') ? 1 : 2, item = jQuery('#media-item-' + fileObj.id);

        try {
            if (typeof topWin.tb_remove != 'undefined') {
                topWin.jQuery('#TB_overlay').click(topWin.tb_remove);
            }
        } catch(e){ }

        if (isNaN(serverData) || !serverData) {
            item.append(serverData);
            prepareMediaItemInit(fileObj);
        } else {
            item.load('async-upload.php', {attachment_id:serverData, fetch:f, gdtt_term:'upload'}, function(){prepareMediaItemInit(fileObj);updateMediaForm();});
        }
    };

    jQuery("form#filter").prepend("<input type='hidden' name='gdtt_tax' value='" + gdttMedia.taxonomy + "' />");
    jQuery("form#filter").prepend("<input type='hidden' name='gdtt_term' value='" + gdttMedia.term + "' />");

    jQuery("a").each(function() {
        var href = jQuery(this).attr('href');
        var is_gdtt = href.indexOf('gdtt_term');

        if (is_gdtt === -1 && href !== "#") {
            var qs = href.indexOf('?') === -1 ? "?" : "&";

            href+= qs + 'gdtt_term=' + gdttMedia.term;
            href+= '&gdtt_tax=' + gdttMedia.taxonomy;
            jQuery(this).attr('href', href);
        }
    });

    jQuery(document).on("click", ".gtc-attach", function() {
        var image_id = jQuery(this).attr("rel");

        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            dataType: 'json',
            cache: false,
            data: {'action': 'gd_cpt_attach_image',
                   '_ajax_nonce': gdttMedia.nonce,
                   'image_id': image_id,
                   'term_id': gdttMedia.term,
                   'taxonomy': gdttMedia.taxonomy
            },
            success: function(json) {
                if (json.status === 'ok') {
                    self.parent.gdtt_attach_image(gdttMedia.term, json.image, json.preview);
                }
                self.parent.tb_remove();
            }
        });
    });

    jQuery("td.savesend input.button").remove();
});
