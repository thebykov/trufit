var postAccessControllerPostMeta = {

    init: function(){
        this.urlVars = this.getUrlVars();
        this.bindUIFunctions();
        this.metaBoxShowDetails(this.urlVars.post);
        if( jQuery('#postaccesscontroller-meta-box .form-table').hasClass('post-visibility--visible') ){
            jQuery('#visibility').css({'display':'block'});
        }
    },

    bindUIFunctions: function(){
        var that = this;
        jQuery('body')
            .on('change','#postaccesscontroller_ctrl_type',function(){
                that.pacTypeChange();
            })
            .on('change','#postaccesscontroller_noacs_msg_type', function(){
                that.pacMsgTypeChange();
            })
            .on('click','.postaccesscontroller-details .height-toggle',function(){
                that.pacDtlHeightToggle(jQuery(this));
            })
            .on('submit','#post',function(){
                that.postSave();
                return false;
            });
    },

    pacTypeChange: function(){
        var dtls = jQuery('.postaccesscontroller-details');
        dtls.removeClass('hide').html('<img src="'+dtls.data('spinner-src')+'">');
        this.metaBoxShowDetails(this.urlVars.post);
    },

    pacMsgTypeChange: function(){
        var dtls = jQuery('.postaccesscontroller-noacs_msg');
        dtls.removeClass('hide').html('<img src="'+dtls.data('spinner-src')+'">');
        this.pac_meta_box_show_msg(this.urlVars.post);
    },

    pacDtlHeightToggle: function(toggleObj){
        var pac_dtls = toggleObj.closest('.postaccesscontroller-details').find('.postaccesscontroller-checkbox-well'),
            crnt_height = pac_dtls.attr('data-height');

        if( crnt_height == 'standard' ){
            pac_dtls.removeClass('height-standard').addClass('height-tall').attr('data-height','tall');
            toggleObj.text('Show Less');
        }else{
            pac_dtls.removeClass('height-tall').addClass('height-standard').attr('data-height','standard');
            toggleObj.text('Show More');
        }
    },

    metaBoxShowDetails: function( postId ){

        var type = jQuery('#postaccesscontroller_ctrl_type').val();
        var dtls = jQuery('.postaccesscontroller-details');

        if( type === 'user' || type === 'group' || type === 'role' ){

            dtls.removeClass('hide');

            var data    = {
                'post_id' : postId,
                'action'  : 'post-access-controller--meta-'+type
            };
            
            jQuery.post(ajaxurl, data, function(response) {
                dtls.html( response );
            });

        }else{

            dtls.addClass('hide');

        }

    },

    pac_meta_box_show_msg: function( postId ){

        var type = jQuery('#postaccesscontroller_noacs_msg_type').val();

        if( type == 'none' ){

            jQuery('.postaccesscontroller-noacs-std-msg').addClass('hide');
            jQuery('.postaccesscontroller-noacs-custom-msg').addClass('hide');

        }else if( type == 'std' ){

            jQuery('.postaccesscontroller-noacs-std-msg').removeClass('hide');
            jQuery('.postaccesscontroller-noacs-custom-msg').addClass('hide');

        }else if( type == 'custom' ){

            jQuery('.postaccesscontroller-noacs-std-msg').addClass('hide');
            jQuery('.postaccesscontroller-noacs-custom-msg').removeClass('hide');

        }

    },

    getUrlVars: function(){
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }

};

jQuery(document).ready(function(){
    postAccessControllerPostMeta.init();
});