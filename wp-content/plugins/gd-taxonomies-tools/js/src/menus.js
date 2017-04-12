/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
var gdCPTMenus = {
    init: function() {
        jQuery("#gdtt-cpt-archives-box-submit").click(function(e) {  
            e.preventDefault();

            var post_types = [];
            jQuery("#gdtt-cpt-archives-list li input:checked").each(function() {
                post_types.push(jQuery(this).val());
            });

            if (post_types.length > 0) {
                jQuery("#gdtt-cpt-archives-box .waiting").show();
                jQuery.post(ajaxurl, {
                        action: "gd_cpt_navmenus_post_type_archives",
                        _ajax_nonce: gdCPTMenus_Data.nonce,  
                        list: post_types
                    }, function(html) {
                        jQuery("#menu-to-edit").append(html);
                        jQuery("#gdtt-cpt-archives-box .waiting").hide();
                    }
                );
            }
        });
    }
};

jQuery(document).ready(function() {
    gdCPTMenus.init();
});
