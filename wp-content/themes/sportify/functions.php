<?php
define('IMAGES', get_template_directory_uri() . '/images/');
/***********************************************************************************************/
/*  Tesla Framework */
/***********************************************************************************************/
require_once(get_template_directory() . '/tesla_framework/tesla.php');

/***********************************************************************************************/
/*  Register Plugins */
/***********************************************************************************************/
if ( is_admin() && current_user_can( 'install_themes' ) ) {
    require_once( get_template_directory() . '/plugins/tgm-plugin-activation/register-plugins.php' );
}

/***********************************************************************************************/
/* Load JS and CSS Files - done with TT_ENQUEUE */
/***********************************************************************************************/

/***********************************************************************************************/
/* Google fonts + Fonts changer */
/***********************************************************************************************/
TT_ENQUEUE::$base_gfonts = array('://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700|Open+Sans:400,700');
TT_ENQUEUE::$gfont_changer = array(
        _go('logo_text_font'),
        _go('main_content_text_font'),
        _go('sidebar_text_font'),
        _go('menu_text_font')
    );
TT_ENQUEUE::add_js(array('http://w.sharethis.com/button/buttons.js'));
/***********************************************************************************************/
/* Custom CSS */
/***********************************************************************************************/
add_action('wp_enqueue_scripts', 'tesla_custom_css', 99);
function tesla_custom_css() {
    $custom_css = _go('custom_css') ? _go('custom_css') : '';
    wp_add_inline_style('tt-main-style', $custom_css);
    if(!is_home())
        wp_dequeue_script( 'tt-jquery.masonry.js' );
    if(!is_single( ))
        wp_dequeue_script( 'tt-buttons.js' );
}

add_action('wp_enqueue_scripts', 'tt_color_changers',99);

function tt_color_changers(){
    $background_color = _go('bg_color') ;
    $background_image = _go('bg_image') ;
    if ( !empty($background_color) ) {
        wp_add_inline_style('tt-main-style', "body{background: $background_color;}");
    }
    if ( !empty($background_image) ) {
        wp_add_inline_style('tt-main-style', "body{background-image: url('$background_image')}");
    }

    $colopickers_css = '';


    if (_go('site_color')) : 
        $colopickers_css .= '.red-background,
                            [class*="toggle-"] > li > input:checked ~ label:before,
                            .main-nav > ul > li > a:hover,
                            .main-nav > ul > li:hover > a,
                            .main-nav > ul > li ul a:hover,
                            .main-nav > ul > li ul,
                            .main-nav > ul > .current-menu-item > a,
                            .main-nav > ul > .current-menu-parent > a,
                            .main-nav > ul:hover .current-menu-item:hover > a,
                            .header-search .search-line,
                            .submit-wrap:before,
                            .clients-box .clients-list li:nth-child(even),
                            .gallery-slider li .gallery-photo-desc > div .zoom-image,
                            .filter-box:before,
                            .filter-box .active-filter a:before,
                            .filter-box ul li a:hover:before,
                            .timetable tbody th,
                            .blog-box .page-numbers a:hover,
                            .blog-box .page-numbers .current,
                            .comments_navigation a:hover,
                            .comments_navigation .current,
                            .post-box .page-numbers li,
                            .post-box .page-numbers a:hover,
                            .post-box .page-numbers .current,
                            .comment-respond small a,
                            .comment-meta .comment-reply a:hover,
                            .main-sidebar .widget h3,
                            .widget_tag_cloud div a:hover,
                            .widget .tab-content ul li:hover time,
                            .widget_recent_new .tabs li:not(.active) > a:hover,
                            .classes-box nav li label:hover:before,
                            .main-footer form input[type="submit"]:hover,
                            .woocommerce-page > header .inside-cart li:hover .remove,
                            .woocommerce-page .search-wrap .search-line,
                            .addto-button:hover,
                            .summary .variations_button button:hover,
                            .summary .variations_button button,
                            .shopping-product-detail:hover li .remove,
                            .products-grid > .row > div:hover .add_to_cart_button,
                            .search-wrap .search-line,
                            .gallery-slider li > div:hover .gallery-photo-desc:before,
                            .woocommerce-page .submit-wrap:before{
                                background-color: ' . _go('site_color') . ';
                            }

                            blockquote:before,
                            .main-slider .entry-header .excuse-test,
                            .archive-box .entry-title span,
                            .search-results  .entry-title span,
                            .blog-box header .entry-header a:hover,
                            .post-box header .entry-header a:hover,
                            .main-sidebar .widget .tt_twitter li:hover,
                            .widget_rss li:hover,
                            .twitter_widget ul li p:hover a,
                            .twitter_widget ul li p:hover,
                            .classes-box ol li a:hover,
                            .calc-block form input[type="submit"],
                            .reviews-content .reviews-title a,
                            .added_to_cart:hover,
                            .woo-checkout .woocommerce-billing-fields label abbr,
                            .required{
                                color: ' . _go('site_color') . ';
                            }

                            .top-border{
                                border-top-color: ' . _go('site_color') . ';
                            }


                            .timetable tbody th,
                            .addto-button:hover,
                            .summary .variations_button button:hover,
                            .summary .variations_button button{
                                border-color: ' . _go('site_color') . ';
                            }

                            .red-black-hover:hover:after{
                                background-color: ' . _go('site_color') . ' !important;
                            }';
            endif;

    if (_go('site_color_2')) :
        $colopickers_css .= 'ins,
                            .about-box header .header-block .entry-header:before,
                            .main-slider .check-programm-block .go-link:hover,
                            .timetable .event:hover,
                            .outlined .light-green-hover,
                            .light-green-hover:hover,
                            .shop-sidebar .widget > header .active,
                            .shop-sidebar .widget > header:hover .toggle,
                            .price_slider_wrapper .price_slider_amount button,
                            .shop-sidebar .widget_product_tag_cloud .tagcloud a:hover,
                            .price_slider_wrapper .price_slider_amount button:hover,
                            .shop-sidebar .widget_layered_nav li small,
                            .shop-sidebar .widget_product_search input[type="submit"],
                            .shop-sidebar .widget_shopping_cart .buttons > a
                            .ui-slider-horizontal .ui-slider-range,
                            .woocommerce-pagination > ul > li a:hover,
                            .woocommerce-pagination > ul > li .current,
                            .light-green-background,
                            .green-background,
                            .clients-box .clients-list li:nth-child(odd),
                            .active header a,
                            .team-item:hover header a,
                            .team-item header a:hover,
                            .widget_recent_new .tab-content time,
                            .woocommerce-page > header .inside-cart .button:hover,
                            .shop-sidebar .widget_product_tag_cloud .tagcloud a,
                            .shop-box .shop-block:before,
                            .shop-items li:hover .content-block,
                            .product-cover-hover > span,
                            .woocommerce > .order-info mark,
                            .order-details-review .shop_table .product-view a,
                            .woo-my-account .shop_table .product-view a,
                            .woo-my-account .shop_table tbody .order-actions a{
                                background-color: ' . _go('site_color_2') . ';
                            }

                            .classes-box nav li label,
                            .woocommerce-page > header .cart-all .ovh a,
                            .woocommerce-page > header .inside-cart > p span,
                            .woocommerce-page .main-nav > ul > .current-menu-item > a,
                            .woocommerce-page .main-nav > ul > .current-menu-parent > a,
                            .woocommerce-page .main-nav > ul:hover .current-menu-item:hover > a,
                            .woocommerce-page .main-nav > ul .sub-menu .current-menu-item > a,
                            .woocommerce-page .main-nav > ul:hover .sub-menu .current-menu-item:hover > a,
                            .shop-sidebar .widget li a:hover,
                            .shop-sidebar .widget_layered_nav_filters li a,
                            .woocommerce .woocommerce-product-rating a:hover,
                            .woocommerce-page .woocommerce-product-rating a:hover,
                            a[href*="mailto:"],
                            .programm-time,
                            .shop-sidebar .widget_layered_nav li a,
                            .woo-my-account .message a,
                            .order-details-review a{
                                color: ' . _go('site_color_2') . ';
                            }

                            .woocommerce-page .main-nav > ul > .current-menu-item > a,
                            .woocommerce-page .main-nav > ul > .current-menu-parent > a,
                            .woocommerce-page .main-nav > ul:hover .current-menu-item:hover > a{
                                border-color: ' . _go('site_color_2') . ';
                            }';
    endif;


    wp_add_inline_style('tt-main-style', $colopickers_css);

    //Custom Fonts Changers
    wp_add_inline_style('tt-main-style', tt_text_css('main_content_text','.content, .content p'));
    wp_add_inline_style('tt-main-style', tt_text_css('sidebar_text','.main-sidebar,.main-sidebar .widget,.main-sidebar a,.main-sidebar p, .main-sidebar .widget h3, .main-sidebar .widget li a, .main-sidebar .widget h3 a, .widget .tab-content .entry-title a'));
    wp_add_inline_style('tt-main-style', tt_text_css('menu_text','.main-nav, .main-nav ul li a'));
}

/***********************************************************************************************/
/* Custom JS */
/***********************************************************************************************/
function tesla_custom_js() {
    ?>
    <script type="text/javascript"><?php echo esc_js(_eo('custom_js')) ?></script>
    <?php
}

add_action('wp_footer', 'tesla_custom_js', 99);

/* Register Contact Form Locations */
/***********************************************************************************************/
TT_Contact_Form_Builder::add_form_locations(array(
    'contact_page'=>'Contact Page',
    'footer'=>'Footer'
));

/***********************************************************************************************/
/* Add Menus */
/***********************************************************************************************/

function tt_register_menus(){
    register_nav_menus(
        array(
            'primary'    => _x('Primary menu', 'dashboard', 'sportify'),
            'secondary'    => _x('Footer menu', 'dashboard', 'sportify'),
            'shop'    => _x('Shop menu', 'dashboard', 'sportify')
        )
    );
}
add_action('init', 'tt_register_menus');


/***********************************************************************************************/
/* Add Shortcodes */
/***********************************************************************************************/

require_once(TT_THEME_DIR . '/shortcodes.php');

/***********************************************************************************************/
/* Add Widgets */
/***********************************************************************************************/

require_once(TT_THEME_DIR . '/widgets/widget-twitter.php');
require_once(TT_THEME_DIR . '/widgets/widget-recent.php');

/* ========================================================================================================================

  Comments

  ======================================================================================================================== */
 
function tt_custom_comments( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ( 'div' == $args['style'] ) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
?>
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
        <?php if ( 'div' != $args['style'] ) : ?>
            <div id="div-comment-<?php comment_ID() ?>" class="tt-comment">
        <?php endif; ?>

        <span class="tt-avatar">
            <?php if ($args['avatar_size'] != 0)
                echo get_avatar( $comment, $args['avatar_size'] ); ?>
        </span>

        <?php if ($comment->comment_approved == '0') : ?>
            <em class="comment-awaiting-moderation">
                <?php _e('Your comment is awaiting moderation.','sportify') ?>
            </em>
            <br />
        <?php endif; ?>

        <div class="comment-block">
            <div class="comment-meta commentmetadata">
                <?php edit_comment_link(__('(Edit)','sportify'),'  ','' );?>

                <span class="comment-reply">
                    <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                </span>
                
                <div class="author-wrap">
                    <?php echo get_comment_author_link() ?>
                    <span class="comment-info"><i>at </i><?php echo get_comment_time('d M Y') ?></span>
                </div>
            </div>

            <div class="comment-text">
                <?php comment_text() ?>
            </div>
          </div>

    <?php if ( 'div' != $args['style'] ) : ?>
    </div>
    <?php endif; 

}

function tt_custom_comments_closed( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ( 'div' == $args['style'] ) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    
    if($comment->comment_type == 'pingback' || $comment->comment_type == 'trackback'):?>
        <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
            
            <?php if ( 'div' != $args['style'] ) : ?>
                <div id="div-comment-<?php comment_ID() ?>" class="tt-comment">
            <?php endif; ?>

            <span class="tt-avatar">
                <?php if ($args['avatar_size'] != 0)
                    echo get_avatar( $comment, $args['avatar_size'] ); ?>
            </span>

            <?php if ($comment->comment_approved == '0') : ?>
                <em class="comment-awaiting-moderation">
                    <?php _e('Your comment is awaiting moderation.','sportify') ?>
                </em>
                <br />
            <?php endif; ?>

            <div class="comment-block">
                <div class="comment-meta commentmetadata">
                    <?php edit_comment_link(__('(Edit)','sportify'),'  ','' );?>

                    <span class="comment-reply">
                        <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                    </span>
                    
                    <div class="author-wrap">
                        <?php echo get_comment_author_link() ?>
                        <span class="comment-info"><i>at </i><?php echo get_comment_time('d M Y') ?></span>
                    </div>
                </div>
                
                <div class="comment-text">
                    <?php comment_text() ?>
                </div>
            </div>
        <?php if ( 'div' != $args['style'] ) : ?>
            </div>
        <?php endif; ?>
    <?php endif; 

}

/***********************************************************************************************/
/* Add Sidebar Support */
/***********************************************************************************************/
function tt_register_sidebars(){
    if (function_exists('register_sidebar')) {
        register_sidebar(
            array(
                'name'           => __('Blog Sidebar', 'sportify'),
                'id'             => 'blog',
                'description'    => __('Blog Sidebar Area', 'sportify'),
                'before_widget'  => '<div class="widget widget-item %2$s">',
                'after_widget'   => '</div>',
                'before_title'   => '<h3>',
                'after_title'    => '</h3>'
            )
        );
        register_sidebar(
            array(
                'name'           => __('Page', 'sportify'),
                'id'             => 'page',
                'description'    => __('Page Sidebar Area', 'sportify'),
                'before_widget'  => '<div class="widget %2$s">',
                'after_widget'   => '</div>',
                'before_title'   => '<h3>',
                'after_title'    => '</h3>'
            )
        );
        register_sidebar(
            array(
                'name'           => __('Shop', 'sportify'),
                'id'             => 'shop',
                'description'    => __('Shop Sidebar Area', 'sportify'),
                'before_widget'  => '<div class="widget %2$s">',
                'after_widget'   => '</div>',
                'before_title'   => '<header><span class="widget-toggle toggle active">-</span><h3 class="widget-title">',
                'after_title'    => '</h3></header>'
            )
        );
        register_sidebar(
            array(
                'name'           => __('Footer Left', 'sportify'),
                'id'             => 'footer_1',
                'description'    => __('Footer left sidebar', 'sportify'),
                'before_widget'  => '<div class="widget widget-item %2$s">',
                'after_widget'   => '</div>',
                'before_title'   => '<h3>',
                'after_title'    => '</h3>'
            )
        );
        register_sidebar(
            array(
                'name'           => __('Footer Right', 'sportify'),
                'id'             => 'footer_2',
                'description'    => __('Footer right sidebar', 'sportify'),
                'before_widget'  => '<div class="widget widget-item %2$s">',
                'after_widget'   => '</div>',
                'before_title'   => '<h3>',
                'after_title'    => '</h3>'
            )
        );
    }
}
add_action('widgets_init','tt_register_sidebars');

//calculates width for each widget in footer area 
function tt_footer_sidebar_params($params) {

    $sidebar_id = $params[0]['id'];

    if ( $sidebar_id == 'footer' ) {
        $total_widgets = wp_get_sidebars_widgets();
        $sidebar_widgets = count($total_widgets[$sidebar_id]);
        $params[0]['before_widget'] = str_replace('class="', 'class="span' . floor(12 / $sidebar_widgets), $params[0]['before_widget']);
    }

    return $params;
}
add_filter('dynamic_sidebar_params','tt_footer_sidebar_params');
// add post-formats to post
//add_theme_support('post-formats', array('quote', 'gallery', 'video', 'audio', 'image'));


function tt_share(){
    $share_this = _go('share_this');
    if(isset($share_this)): ?>
        <div class="share-it">
            <ul class="clean-list socials">
                <?php foreach($share_this as $val): ?>
                    <li>
                        <a href="#"><span class='st_<?php echo $val ?>_large' displayText='<?php echo ucfirst($val) ?>'></span></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clear"></div>
        </div>
    <?php endif;
}

add_image_size('home-blog-thumb',178,178);

/*==== Function Call custom meta boxex ====*/
function tt_video_or_image_featured($echo = false) {
    global $post;
    $embed_code = get_post_meta($post->ID , THEME_NAME . '_video_embed', true);
    $patern = '<div class="entry-cover">%s</div>';

    if($echo){

        if(!empty($embed_code)) {
            return sprintf($patern, $embed_code);
        }else {
            if( has_post_thumbnail() && ! post_password_required() ){
                return sprintf($patern, get_the_post_thumbnail());
            }
        }

    }else{

        if(!empty($embed_code)) {
            printf($patern, $embed_code);
        }else {
            if( has_post_thumbnail() && ! post_password_required() ){
                printf($patern, get_the_post_thumbnail());
            }
        }

    }
}

/*==== Custom form builder ====*/
function tt_custom_form_builder() {
    $fields = _go_repeated('Form builder');
    $output = '';
    $left = array();
    $right = array();
    $full = array();
    $offset = '';
    $before_submit = _go('custom_form_info');
    $button = _go('custom_form_button');
    $field_counter = 0;

    if(!empty($fields)) {
        $output = '<form class="project-form"><div class="row">';
        foreach ($fields as $key => $val) {
            if($val['custom_input_position'] == '1') {
                $left[] = $val;
            }elseif($val['custom_input_position'] == '2') {
                $right[] = $val;                    
            }else{
                $full[] = $val;                    
            }
        }

        $output .= ' <div class="span6">';
        if(!empty($left)) {
            $output .= tt_form_fields($left);
            $counter = count($left);
        }else {
            $counter = 0;
            $offset = 'offset6';
        }
        $output .= ' </div>'; 
        if(!empty($right)) {
            $output .= ' <div class="span6 '.$offset.'">';
            $output .= tt_form_fields($right,$counter);
            $output .= ' </div>';
            $counter = count($left)+count($right);
        }
        if(!empty($full)) {
            $output .= '<div class="span12">';
            $output .= tt_form_fields($full,$counter);
            $output .= ' </div>';
        }
        $output .= '';
        if(!empty($before_submit)) {
            $output .= '<div class="span12">';
            $output .= sprintf('<h5>%s</h5>', $before_submit); 
            $output .= '</div>';               
        }
        if(empty($button)) {
            $button = 'Submit';
        }
        $output .= '<div class="span12">';
        $output .= sprintf('<input type="submit" value="%s" class="project-button">', $button);
        $output .= '</div></div></form>';
    }

    return $output;
}

/*==== Custom form fields ====*/
function tt_form_fields($fields,$i=0) {
    $output     = '';
    $span       = 'span12';

    if(!empty($fields)) {
        foreach ($fields as $key => $val) {
            $i++;
            if(!empty($val['custom_input_type'])) {
                if($val['custom_input_size'] === '12') {
                    $span = 'span12';
                } else if($val['custom_input_size'] === '6') {
                    $span = 'span6';
                }
            }

            $output .= '<div class="row-fluid"><div class="'.$span.'">';
                /*if(!empty($val['custom_input_label'])){
                    $output .= sprintf('<p>%s</p>', $val['custom_input_label']);                    
                }*/
                if(!empty($val['custom_input_type'])) {
                    $type = $val['custom_input_type'];
                    $n = 'field_'. $i;

                    if($type === 'text' || $type === 'email') {
                        $output .= sprintf('<input type="text" name="%1$s" value="%2$s" placeholder="%3$s" class="project-line" />', $n, $val['custom_form_value'], $val['custom_form_placeholder']);
                    }
                    if($type === 'select') {
                        $n = 'field_'. $i;
                        if(!empty($val['custom_form_value'])) {
                            $options    = '';
                            $content    = str_replace(', ', ',', $val['custom_form_value']);
                            $content    = explode(',', $content);
                            $content    = array_filter($content);
                            $j          = 0;

                            if(!empty($val['custom_form_placeholder'])) {
                                $options .= sprintf('<option value="0">%2$s</option>', $j, $val['custom_form_placeholder']);
                            }

                            foreach ($content as $key => $val) {
                                $j++;
                                $options .= sprintf('<option value="%1$s">%2$s</option>', $val, $val);
                            }
                        }
                        $output .= sprintf('<select name="%1$s" class="project-select">%2$s</select>', $n, $options);
                    }
                    if($type === 'textarea') {
                        $output .= sprintf('<textarea name="%1$s" value="%2$s" placeholder="%3$s" class="project-area"></textarea>', $n, $val['custom_form_value'], $val['custom_form_placeholder']);
                    }
                }
            $output .= '</div></div>';
        }

    }
    return $output;
}

function tt_ajax_custom_form () {
    $receiver_mail = _go('custom_form_email');
    $mail_title    = '"'._go('custom_form_title').'" - form was sent';
    $fields        = _go_repeated('Form builder');

    $i = '';

    $message = '<table style="margin: 0 auto; border: 1px solid #dddddd;"><tbody>';
        foreach ($fields as $key => $val) {
            $i++;
            $message .= sprintf('<tr><td style="padding: 10px;border-bottom: 1px solid #ddd;">%1$s</td><td style="padding: 10px;border-bottom: 1px solid #ddd;border-left: 1px solid #ddd;">%2$s</td></tr>', $val['custom_input_label'], $_POST['field_'.$i]);
        }
    $message .= '</tbody></table>';

    $header  = 'MIME-Version: 1.0' . "\r\n";
    $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $header .= 'Submited form from -' . get_bloginfo('name');
    
    if ( mail( $receiver_mail, $mail_title, $message, $header ) )
            $result = __('Message successfully sent.', 'sportify');
        else
            $result = __('Message could not be sent.', 'sportify');

    die($result);
}
add_action('wp_ajax_tt_ajax_custom_form', 'tt_ajax_custom_form');           // for logged in user  
add_action('wp_ajax_nopriv_tt_ajax_custom_form', 'tt_ajax_custom_form');    // if user not logged in

function tt_ajax_contact_form () {
    $receiver_mail = (_go('email_contact')) ? _go('email_contact') : get_bloginfo( 'admin_email' );

    $header = '';
    if (!empty($_POST['name']) && !empty($_POST ['email']) && !empty($_POST ['message'])) {
        $mail_title = (!empty($_POST['website'])) ? $_POST['name'] . ' from ' . $_POST['website'] : ' from ' . get_bloginfo( 'name' ) . ' Contact form';
        $email = $_POST['email'];
        $message = $_POST['message'];
        $message = wordwrap($message, 70, "\r\n");
        $header .= 'From: ' . $_POST['name'] . "\r\n";
        $header .= 'Reply-To: ' . $email;
    
        if ( wp_mail( $receiver_mail, $mail_title, $message, $header ) )
            $result = __('Message successfully sent.', 'sportify');
        else
            $result = __('Message could not be sent.', 'sportify');
    }else
        $result = __('Please fill all the fields','sportify');
    die($result);
}
add_action('wp_ajax_tt_ajax_contact_form', 'tt_ajax_contact_form');           // for logged in user  
add_action('wp_ajax_nopriv_tt_ajax_contact_form', 'tt_ajax_contact_form');    // if user not logged in

//Search page
function cut_shortcodes($content) {
    return preg_replace('@\[.*?\]@', '', $content);
}

// =========================================================================
//                         WOOCOMMERCE ACTIONS
// =========================================================================

add_theme_support( 'woocommerce' );

//Ajaxify cart
// Ensure cart contents update when products are added to the cart via AJAX
add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
 
function woocommerce_header_add_to_cart_fragment( $fragments ) {
    
    global $woocommerce;
    
    ob_start(); ?>

    <div class="cart-all">
        <a href="<?php echo get_permalink( wc_get_page_id( 'cart' ) ); ?>"><i class="icon-cart" title="icon-cart"></i><?php echo $woocommerce->cart->get_cart_subtotal(); ?></a>
        <div class="inside-cart">
            <div class="alignright">
                <a href="<?php echo $woocommerce->cart->get_checkout_url() ?>" class="button">Checkout</a>
            </div>
                
            <?php if (count($woocommerce->cart->get_cart()) > 0) : ?>
                <p><?php _e('Products',' sportify'); ?> <span><?php echo '('.$woocommerce->cart->get_cart_contents_count(); _e(' items',' sportify'); echo ')'; ?></span></p>

                <ul class="clean-list">
                    <?php foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) :
                        $_product = $cart_item['data'];
                        // Only display if allowed
                        if (!apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key) || !$_product->exists() || $cart_item['quantity'] == 0)
                            continue;

                        // Get price
                        $product_price = get_option('woocommerce_tax_display_cart') == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();

                        $product_price = apply_filters('woocommerce_cart_item_price_html', woocommerce_price($product_price), $cart_item, $cart_item_key);
                        ?>
                        <li>
                            <?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove alignright" title="%s">&times;</a>', esc_url($woocommerce->cart->get_remove_url($cart_item_key)), __('Remove this item', 'woocommerce')), $cart_item_key); ?>
                            <div class="inside-cart-image alignleft">
                                <?php echo $_product->get_image(); ?>
                            </div>
                            
                            <div class="ovh">
                                <a href="<?php echo get_permalink($cart_item['product_id']); ?>"><?php echo apply_filters('woocommerce_widget_cart_product_title', $_product->get_title(), $_product); ?></a>
                                <p><?php _e('Q-ty: ',' sportify'); echo '<span>'.$cart_item['quantity'].' x '.$product_price.'</span>'; ?></p>
                            </div>
                        </li>
                    <?php endforeach;?>
                </ul>
                <span class="submount grey-background"><?php _ex('Subtotal', 'cart', 'sportify') ?>: <?php echo $woocommerce->cart->get_cart_subtotal(); ?></span>
            <?php else: ?>
                <p class="empty-cart"><?php _e('No items in cart. <br />Keep shopping.', 'hudson'); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php   
    $fragments['div.cart-all'] = ob_get_clean();
    return $fragments;   
}


// disable Woo styles
add_filter( 'woocommerce_enqueue_styles', '__return_false' );


// Remove the product rating display on product loops
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 5 );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
