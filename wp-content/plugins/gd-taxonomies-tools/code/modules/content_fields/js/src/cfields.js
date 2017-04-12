/*jslint regexp: true, nomen: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
var gdCPT_CF_Admin = {
    post: {
        get_results: function() {
            jQuery("#gdcpt-mod-cf-post .gdtt-loader").show();
            jQuery("#gdtt-mod-cf-term-insert").attr("disabled", "disabled");

            jQuery.ajax({
                success: function(html) {
                    jQuery("#gdcpt-mod-cf-post-results").html(html);
                    jQuery("#gdcpt-mod-cf-post .gdtt-loader").hide();
                },
                dataType: "html", data: {q: jQuery("#gdcpt-mod-cf-post-search").val(), p: jQuery("#gdcpt-mod-cf-post-posttype").val()},
                type: "POST", url: "admin-ajax.php?action=gdcpt_mod_cf_get_posts&_ajax_nonce=" + gdttMetas.nonce
            });
        },
        init: function() {
            jQuery(document).on("click", "#gdcpt-mod-cf-post-results li", function(){
                jQuery("#gdcpt-mod-cf-post-results ul li").removeClass('gdtt-selected');
                jQuery(this).addClass('gdtt-selected');
                jQuery("#gdtt-mod-cf-post-insert").removeAttr("disabled");
            });

            jQuery(document).on("click", ".gdtt-close-post-dialog", function(e){
                e.preventDefault();

                jQuery("#gdcpt-mod-cf-post").wpdialog("close");
            });

            jQuery(document).on("click", "#gdtt-mod-cf-post-insert", function(e){
                e.preventDefault();

                var post = jQuery("#gdcpt-mod-cf-post-results ul li.gdtt-selected").get(0);

                if (post) {
                    gdCPTMeta.tmp['cf_post_source_is'].find("input.gdtt-field-text").val(jQuery(post).attr("gdtt-id"));
                    gdCPTMeta.tmp['cf_post_source_is'].find("span.gdtt-text-block").html(jQuery(post).find("span").html());
                }

                jQuery("#gdcpt-mod-cf-post").wpdialog("close");
            });

            jQuery(document).on("click", ".gdtt-cf-type-post .gdtt-ui-button span.ui-icon-note", function(){
                jQuery("#gdcpt-mod-cf-post-title").html('<span>' + jQuery(this).attr("title").replace(": ", "</span><br/>"));
                jQuery("#gdcpt-mod-cf-post-posttype").val(jQuery(this).attr("gdtt-cpt"));

                gdCPTMeta.tmp['cf_post_source_is'] = jQuery(this).parent().parent().parent();

                jQuery("#gdcpt-mod-cf-post").wpdialog({
                    width: 620,
                    height: 400,
                    modal: true,
                    dialogClass: 'wp-dialog',
                    zIndex: 300000
                });

                gdCPT_CF_Admin.post.get_results();
            });

            jQuery("#gdcpt-mod-cf-post-search").keyup(gdCPT_CF_Admin.post.get_results);
        }
    },
    term: {
        get_results: function() {
            jQuery("#gdcpt-mod-cf-term .gdtt-loader").show();
            jQuery("#gdtt-mod-cf-term-insert").attr("disabled", "disabled");

            jQuery.ajax({
                success: function(html) {
                    jQuery("#gdcpt-mod-cf-term-results").html(html);
                    jQuery("#gdcpt-mod-cf-term .gdtt-loader").hide();
                },
                dataType: "html", data: {q: jQuery("#gdcpt-mod-cf-term-search").val(), p: jQuery("#gdcpt-mod-cf-term-taxonomy").val()},
                type: "POST", url: "admin-ajax.php?action=gdcpt_mod_cf_get_terms&_ajax_nonce=" + gdttMetas.nonce
            });
        },
        init: function() {
            jQuery("#gdcpt-mod-cf-term-results li").live("click", function(){
                jQuery("#gdcpt-mod-cf-term-results ul li").removeClass('gdtt-selected');
                jQuery(this).addClass('gdtt-selected');
                jQuery("#gdtt-mod-cf-term-insert").removeAttr("disabled");
            });

            jQuery(document).on("click", ".gdtt-close-term-dialog", function(e){
                e.preventDefault();

                jQuery("#gdcpt-mod-cf-term").wpdialog("close");
            });

            jQuery(document).on("click", "#gdtt-mod-cf-term-insert", function(e){
                e.preventDefault();

                var term = jQuery("#gdcpt-mod-cf-term-results ul li.gdtt-selected").get(0);

                if (term) {
                    gdCPTMeta.tmp['cf_term_source_is'].find("input.gdtt-field-text").val(jQuery(term).attr("gdtt-id"));
                    gdCPTMeta.tmp['cf_term_source_is'].find("span.gdtt-text-block").html(jQuery(term).html());
                }

                jQuery("#gdcpt-mod-cf-term").wpdialog("close");
            });

            jQuery(document).on("click", ".gdtt-cf-type-term .gdtt-ui-button span.ui-icon-tag", function(){
                jQuery("#gdcpt-mod-cf-term-title").html('<span>' + jQuery(this).attr("title").replace(": ", "</span><br/>"));
                jQuery("#gdcpt-mod-cf-term-taxonomy").val(jQuery(this).attr("gdtt-tax"));

                gdCPTMeta.tmp['cf_term_source_is'] = jQuery(this).parent().parent().parent();

                jQuery("#gdcpt-mod-cf-term").wpdialog({
                    width: 620,
                    height: 400,
                    modal: true,
                    dialogClass: 'wp-dialog',
                    zIndex: 300000
                });

                gdCPT_CF_Admin.term.get_results();
            });

            jQuery("#gdcpt-mod-cf-term-search").keyup(gdCPT_CF_Admin.term.get_results);
        }
    },
    user: {
        get_results: function() {
            jQuery("#gdcpt-mod-cf-user .gdtt-loader").show();
            jQuery("#gdtt-mod-cf-user-insert").attr("disabled", "disabled");

            jQuery.ajax({
                success: function(html) {
                    jQuery("#gdcpt-mod-cf-user-results").html(html);
                    jQuery("#gdcpt-mod-cf-user .gdtt-loader").hide();
                },
                dataType: "html", data: {q: jQuery("#gdcpt-mod-cf-user-search").val(), p: jQuery("#gdcpt-mod-cf-user-class").val()},
                type: "POST", url: "admin-ajax.php?action=gdcpt_mod_cf_get_users&_ajax_nonce=" + gdttMetas.nonce
            });
        },
        init: function() {
            jQuery(document).on("click", "#gdcpt-mod-cf-user-results li", function(){
                jQuery("#gdcpt-mod-cf-user-results ul li").removeClass('gdtt-selected');
                jQuery(this).addClass('gdtt-selected');
                jQuery("#gdtt-mod-cf-user-insert").removeAttr("disabled");
            });

            jQuery(document).on("click", ".gdtt-close-user-dialog", function(e){
                e.preventDefault();

                jQuery("#gdcpt-mod-cf-user").wpdialog("close");
            });

            jQuery(document).on("click", "#gdtt-mod-cf-user-insert", function(e){
                e.preventDefault();

                var user = jQuery("#gdcpt-mod-cf-user-results ul li.gdtt-selected").get(0);

                if (user) {
                    gdCPTMeta.tmp['cf_user_source_is'].find("input.gdtt-field-text").val(jQuery(user).attr("gdtt-id"));
                    gdCPTMeta.tmp['cf_user_source_is'].find("span.gdtt-text-block").html(jQuery(user).find("span").html());
                }

                jQuery("#gdcpt-mod-cf-user").wpdialog("close");
            });

            jQuery(document).on("click", ".gdtt-cf-type-user .gdtt-ui-button span.ui-icon-contact", function(){
                jQuery("#gdcpt-mod-cf-user-title").html('<span>' + jQuery(this).attr("title").replace(": ", "</span><br/>"));
                jQuery("#gdcpt-mod-cf-user-class").val(jQuery(this).attr("gdtt-usr"));

                gdCPTMeta.tmp['cf_user_source_is'] = jQuery(this).parent().parent().parent();

                jQuery("#gdcpt-mod-cf-user").wpdialog({
                    width: 620,
                    height: 400,
                    modal: true,
                    dialogClass: 'wp-dialog',
                    zIndex: 300000
                });

                gdCPT_CF_Admin.user.get_results();
            });

            jQuery("#gdcpt-mod-cf-user-search").keyup(gdCPT_CF_Admin.user.get_results);
        }
    }
};
