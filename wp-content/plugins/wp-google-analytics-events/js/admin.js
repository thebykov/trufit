jQuery.widget.bridge( 'gaetooltip', jQuery.ui.tooltip );
	
jQuery( '.ga-tooltip' ).gaetooltip({ position: {
	my: "left bottom-10",
    at: "right top",
    collision: "none"
	}
});

jQuery('.btn_upload').on('click',function(e){
    jQuery('.settings_content').slideDown();
    e.preventDefault();
});

jQuery('.btn_close').on('click',function(e){
    jQuery('.settings_content').slideUp();
    e.preventDefault();
});

jQuery('.popup').on('click',function(e){
    jQuery('.popup').slideUp();
    e.preventDefault();
});

if (jQuery('#snippet').is(":checked")) {
    jQuery('#anonymizeip')[0].checked = false;
    jQuery('#anonymizeip').attr("disabled", true);
}

jQuery('#snippet').change(function () {
    if (this.checked) {
        jQuery('#anonymizeip')[0].checked = false;
        jQuery('#anonymizeip').attr("disabled", true);
    } else {
        jQuery('#anonymizeip').removeAttr("disabled");
    }
});

jQuery('#advanced:checkbox').change(function () {
    var checked = jQuery(this).is(':checked');
    if(checked) {
        if(!confirm('Advanced mode allows you to use jQuery selectors for click and scroll events. Enabling this feature and creating advanced events could cause errors on your site if misconfigured. \n\nAre you sure? ')){         
            jQuery(this).removeAttr('checked');
        }
    }
});
