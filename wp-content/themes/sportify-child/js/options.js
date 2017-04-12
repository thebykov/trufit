var teslaThemes = {



  settings: {

    scripts: [

            'js/plugins/queryloader.js',

            'js/plugins/simple-slider.js',

            'js/plugins/jquery.masonry.js',
			
            'js/plugins/imagesloaded.js',

            'js/plugins/swipebox.js',

            'js/plugins/plugins.js',

            'js/plugins/placeholder.js',

            'js/plugins/enquire.js',

            'js/plugins/jquery.finger.js'

    ]},



    init: function () {

       "use strict";

        this.loadScripts();    

    },



    module: function () {

        "use strict";

        this.queryLoader();

        this.portfolioFilter();

        this.simpleSlider();

        this.menu();

        this.gallerySlider();

        this.homeSlider();

        this.pageSlider();

        this.masonryBlog();

        this.zoomImage();

        this.calcWeight();

        this.eventRegister();

    },



  loadScripts: function () {

    "use strict";

    teslaThemes.module();

    

  },



  queryLoader: function () {

    "use strict";

    jQuery(document).ready(function () {

        showContent();



      function showContent() {

        jQuery('#home').addClass('show-content');

      }

    });

  },



  sticky: function () {

    "use strict";

    if ( jQuery('.sticky-bar').length ) {

      jQuery(".sticky-bar").sticky({topSpacing:0});       

    }

  },



  fitvids: function () {

    "use strict";

    var video = jQuery('noscript').text();



    if(video.trim().search('iframe') === 1) {

      jQuery('noscript').parent().append(video);      

    }





    jQuery("#home").fitVids({ customSelector: "iframe[src^='//player.vimeo.com'], iframe[src^='//www.youtube.com']"});

  },



  scrolld: function () {

    "use strict";

    jQuery(".main-nav > ul > li > a, .intro-button").stop(true).on('click', function (e) {

      jQuery(".main-nav > ul > li").removeClass('current-menu-item');



      if(jQuery(this).hasClass('intro-button')) {

        jQuery(".main-nav > ul > li").eq(0).addClass('current-menu-item'); 

      } else {

        jQuery(this).parent().addClass('current-menu-item');        

      }





      e.preventDefault();

      jQuery(this).scrolld({

        scrolldEasing: 'easeOutBack'

      });

    });

  },



  menu: function () {

    "use strict";

    var menu = jQuery('.responsive-nav');

    var bodyPosition;

    var menuButtonHTML = '<a href="#" class="mobile-menu-button"><i class="icon-62"></i></a>';

    var menuButtonHolder = jQuery('.menu-button');

    var menuMarkup = menu.clone();

        //menuMarkup.prepend(menuButtonHTML);



        jQuery(document).on('click', '.mobile-menu-button', function(e) {

          e.preventDefault();



          if(menuMarkup.hasClass('active-menu')) {

            menuMarkup.removeClass('active-menu');

          }else {

            menuMarkup.toggleClass('active-menu');            

          }



          if(jQuery('body').hasClass('active-menu')) {

             jQuery('body').removeClass('active-menu');

          }else {            

            jQuery('body').toggleClass('menu-effect');

          }

        });



        jQuery(document).on('drag', 'body .responsive-nav', function(e) {

            bodyPosition = -(e.adx - 200);



            if(e.adx < 100) {

              jQuery('body.menu-effect .boxed-view').css({

                '-webkit-transform': 'translate3d('+bodyPosition+'px,0,0)'

              });

            }



            if(e.end === true) {

                jQuery('body.menu-effect .boxed-view').removeAttr("style");

              if(e.adx > 100) {

                jQuery('body ').removeClass('menu-effect');

                jQuery('body > .responsive-nav').removeClass('active-menu');

              }

            }

            //jQuery('body .main-nav').removeClass('active-menu');

            //jQuery('body ').removeClass('menu-effect');              

        });



        enquire.register("screen and (max-width:992px)", {



            // OPTIONAL

            // If supplied, triggered when a media query matches.

            match : function() {

              menu.hide();

              jQuery('body').prepend(menuMarkup);

              menuButtonHolder.append(menuButtonHTML);

            },



                                        

            // OPTIONAL

            // If supplied, triggered when the media query transitions 

            // *from a matched state to an unmatched state*.

            unmatch : function() {

             menuMarkup.remove();

             menu.show();

             jQuery('.logo .mobile-menu-button').remove();

            },    

            

            // OPTIONAL

            // If supplied, triggered once, when the handler is registered.

            setup : function() {},    

                                        

            // OPTIONAL, defaults to false

            // If set to true, defers execution of the setup function 

            // until the first time the media query is matched

            deferSetup : true,

                                        

            // OPTIONAL

            // If supplied, triggered when handler is unregistered. 

            // Place cleanup code here

            destroy : function() {}

              

        });



  },



  homeSlider: function(){

    "use strict";

    jQuery(document).ready(function(){

        jQuery( '#home_slider' ).sudoSlider({

           numeric: false,

           auto: false,

           responsive: true,

           vertical: false,

           autoHeight: true,

           moveCount: 1,

           prevhtml:          ' <a href="#" class="prev-nav"><i class=""></i></a> ',

           nexthtml:          ' <a href="#" class="next-nav"><i class=""></i></a> ',

           controlsattr:      'id="gallery_controls" class="gallery-controls"',

           numericattr:       'class="slider-nav"',

           slideCount: 1,

           continuous: true,

           animationZIndex: 10

        });

    });

  },



  pageSlider: function(){

    "use strict";

    jQuery(document).ready(function(){

        jQuery( '#page_slider' ).sudoSlider({

           numeric: false,

           responsive: true,

           vertical: false,

           autoHeight: true,

           moveCount: 1,

           prevhtml:          ' <a href="#" class="prev-nav"><i class=""></i></a> ',

           nexthtml:          ' <a href="#" class="next-nav"><i class=""></i></a> ',

           controlsattr:      'id="gallery_controls" class="slider-controls"',

           numericattr:       'class="slider-nav"',

           slideCount: 1,

           continuous: false,

           animationZIndex: 10

        });

    });

  },



  gallerySlider: function(){

    "use strict";

    jQuery(document).ready(function(){

        jQuery( '#gallery_slider' ).sudoSlider({

           numeric: false,

           responsive: true,

           vertical: false,

           autoHeight: true,

           moveCount: 1,

           prevhtml:          ' <a href="#" class="prev-nav"><i class=""></i></a> ',

           nexthtml:          ' <a href="#" class="next-nav"><i class=""></i></a> ',

           controlsattr:      'id="gallery_controls" class="gallery-controls"',

           numericattr:       'class="slider-nav"',

           slideCount: 3,

           speed: 1000,

           continuous: false,

           animationZIndex: 10

        });

    });

  },



  masonryBlog: function() {

        "use strict";

        //var box = jQuery('.blog-items');//BYKOV
		var box = jQuery('.blog-items-box');//BYKOV
		

        jQuery(document).ready(function(){

          if (box.length) {

            box.masonry({

                isResizeBound:false,

                itemSelector: box.children("div"),

                isFitWidth: true,

                isAnimated: false,

                animationOptions: {

                duration: 750,

                  easing: 'linear',

                  queue: false

                }

            });

        }

        });

        

  },

  simpleSlider: function () {

    "use strict";

    jQuery( '#events_slider' ).sudoSlider({

       numeric: false,

       responsive: true,

       vertical: true,

       moveCount: 1,

       speed: 1000,

       autoHeight: false,

       prevhtml:          ' <a href="#" class="prev-nav"><i class=""></i></a> ',

       nexthtml:          ' <a href="#" class="next-nav"><i class=""></i></a> ',

       controlsattr:      'id="events_controls"',

       numericattr:       'class="slider-nav"',

       continuous: false,

       updateBefore: true,

       animationZIndex: 10,

    });



    jQuery( '#news_slider' ).sudoSlider({

       numeric: false,

       responsive: true,

       vertical: false,

       autoHeight: false,

       moveCount: 1,

       speed: 800,

       prevhtml:          ' <a href="#" class="prev-nav"><i class=""></i></a> ',

       nexthtml:          ' <a href="#" class="next-nav"><i class=""></i></a> ',

       controlsattr:      'id="news_controls"',

       numericattr:       'class="slider-nav"',

       continuous: true,

       updateBefore: true,

       animationZIndex: 10,

    });



    jQuery( '#testimonials-slider' ).sudoSlider({

       numeric: false,

       responsive: true,

       moveCount: 1,

       speed: 1000,

       updateBefore: true,

       vertical: true,

       continuous: true,

       auto: true,

       prevhtml:          ' <a href="#" class="prev-nav"><i class="icon-503"></i></a> ',

       nexthtml:          ' <a href="#" class="next-nav"><i class="icon-515"></i></a> ',

       controlsattr:      'id="controls-testimonials"'

    });



    jQuery( '.blog-slider' ).sudoSlider({

       numeric: false,

       responsive: true,

       moveCount: 1,

       speed: 1000,

       auto: false,

       continuous: true,

       updateBefore: true,

       prevhtml:          ' <a href="#" class="prev-nav"><i class="icon-517"></i></a> ',

       nexthtml:          ' <a href="#" class="next-nav"><i class="icon-501"></i></a> ',

       controlsattr:      'id="controls"',

       numericattr:       'class="slider-nav"', 

    });





    // sliders.each(function() {

    //   var imgSrc = jQuery(this).find('img').attr('src');

    //   jQuery('.portfolio-thumbs').append('<li><img src="' + imgSrc + '" height="28"></li>');

    // });



  },



  zoomImage: function () {

    "use strict";

    jQuery( '.zoom-image' ).swipebox();

  },





  calcWeight: function(){

    "use strict";

    jQuery(document).ready(function () {

      jQuery('#calc_form').submit(function (e) {

        e.preventDefault();

        var weight = jQuery('#weight').val();

        var height = jQuery('#height').val();

        var result = jQuery('#result p');



        if (weight > 0 && height > 0) {

            var finalBmi = weight / ((height / 100) * (height / 100));

          if (finalBmi < 18.5) {

              result.html("That you are too thin.");

          }

          if (finalBmi > 18.5 && finalBmi < 25) {

              result.html("That you are healthy.");

          }

          if (finalBmi > 25) {

              result.html("That you have overweight.");

          }

        } else {

            alert("Please Fill in everything correctly");

        }

      });

    });

  },



  eventRegister: function(){

    "use strict";

    jQuery(function() {

      

      if( jQuery('#shop_sidebar').length ){

          jQuery('#shop_sidebar').click( function(evt){

              var elm = jQuery(evt.target);

              if ( elm.closest('header').length ){

                  elm.closest('header').siblings().slideToggle();

                  elm.closest('header').find('.toggle').toggleClass('active');

              }

          });

      }



      if ( jQuery('#classes-box').length ){

        jQuery('#classes-box .classes input[type="radio"]').first().prop('checked', true);

      }



      if ( jQuery('#is_classes'.length) ){

        // show first block content

        jQuery('#classes_box .classes input[type="radio"]').first().prop('checked', true);

        jQuery('#classes_box .classes-content-block').first().fadeIn();





        // register events

        jQuery('#classes_box .classes input[type="radio"]').change( function(evt){

            var elm_id = jQuery(evt.target).attr('id').replace('toggle-', '');

            

            jQuery('.classes-content-block').hide();

            jQuery('#classes_content_' + elm_id).fadeIn('slow');

        });

      }

    });

  },



  portfolioFilter: function () {

    "use strict";

      jQuery('.filter-tags > li a').click(function (e) {

          e.preventDefault();

          var tag = jQuery(this).attr('data-cat-slug');

          var filters = jQuery(this).parent();



          jQuery('.filter-tags > li').removeClass('active-filter');

          filters.addClass('active-filter');



          

          jQuery.each( jQuery('.timetable > tbody td'), function( i, cell ) {

            jQuery(this).removeClass('event');

        



            if (tag.toLowerCase() !== 'all') {

              if ( jQuery(this).hasClass(tag.toLowerCase()) === true ) {

                jQuery(this).addClass('event');

              } 

            } else {

              jQuery.each( jQuery('.filter-tags > li a'), function( key, elm ) {

                  var tag = jQuery(elm).attr('data-cat-slug');



                  if ( jQuery(cell).hasClass(tag.toLowerCase()) === true ) {

                    jQuery(cell).addClass('event');

                  }

              });

            }

          });



      });

  }

};



teslaThemes.init();