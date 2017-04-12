<?php global $woocommerce ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php wp_title('-', true, 'right'); ?><?php bloginfo('name'); ?></title>
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Responsive helper -->
     <!-- Pingbacks -->
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">


  
    
    <!-- Favicon -->
    <?php if(_go('favicon')): ?>
        <link rel="shortcut icon" href="<?php _eo('favicon') ?>">
    <?php endif; ?>
    <?php if(_go('tracking_code')){_eo('tracking_code');} ?>
    <?php wp_head(); ?>

    <link rel="stylesheet" type="text/css" href="<?php echo Get_template_directory_uri(); ?>-child/scss/mainstyle.css">
</head>

<body <?php body_class(); ?>>
    <div class=" woocommerce-page show-content">
    <!-- ===================================== START HEADER -->
    <!-- All content goes here -->

        <header class="main-header">
                <?php 

    if( current_user_can('editor') || current_user_can('administrator')){

    wp_nav_menu( array( 'theme_location' => 'top-menu', 'container_class' => 'top_menu_class' ) );

    }

    ?>



            <!-- Header Shorcode Area -->



            <div class="nav-bar sticky-bar grey-background">



                <div class="container">



                    <div class="row">



                        <div class="col-md-6 col-sm-12 col-xs-12">



                            <?php if(_go('desktop_logo_image')): ?>



                                <figure class="identity">



                                    <a href="<?php echo home_url() ?>" title="home" rel="home">



                                        <img class="desktop_logo" src="<?php _eo('desktop_logo_image') ?>" alt="TruFitBootcamp">



                    <img class="mobile_logo" src="<?php _eo('mobile_logo_image') ?>" alt="TruFitBootcamp">



                                    </a>



                                </figure>



                            <?php else: ?>



                                <a href="<?php echo home_url() ?>" title="home" class="logo uppercase" rel="home" style="font-family: <?php if( _go('logo_text_font') ){ _eo('logo_text_font'); } else { echo 'Oswald'; } ?>; font-size: <?php if( _go('logo_text_size') ) {_eo('logo_text_size'); } else{ echo '42'; } ?>px; color: <?php if( _go('logo_text_color') ){ _eo('logo_text_color'); } else {echo '#fff'; } ?>">



                                    <?php   if( _go('logo_text') ){



                                                _eo('logo_text');



                                            } else {



                                                echo THEME_PRETTY_NAME;



                                            }



                                    ?>



                                </a>



                            <?php endif; ?>



                        </div>



                        <div class="col-md-6 col-sm-4 col-xs-4 hidden-xs hidden-sm">



                            <div class="row">



                                <div class="col-md-0 col-sm-0 col-xs-0 ">



                                    <?php if (_go('ad_image')) : ?>



                                        <div class="ad">



                                            <?php if (_go('ad_url')) : ?>



                                                <a href="<?php _eo('ad_url'); ?>">



                                                    <img src="<?php _eo('ad_image'); ?>" alt="<?php _e('Banner', 'sportify'); ?>" />



                                                </a>



                                            <?php else: ?>    



                                                <img src="<?php _eo('ad_image'); ?>" alt="<?php _e('Banner', 'sportify'); ?>" />



                                            <?php endif; ?>



                                        </div>



                                    <?php endif; ?>



                                </div>



                                <div class="col-md-12 col-sm-12 ">



                                    <div class="socials">



                                        <ul class="clean-list social-links clearfix">



                                            <?php _esocial_platforms(array('facebook', 'twitter', 'youtube', 'pinterest', 'dribble', 'instagram', 'googleplus', 'linkedin','behance'), 'i-', '') ?>



                                        </ul>



                                    </div>



                                </div>



                            </div>



                        </div>



                    </div>



                </div>



            </div>



            <div class="header-bar">



                <div class="container">



                    <div class="row">



                        <div class="col-md-12">



                            <nav class="main-nav responsive-nav">



                                <ul class="clean-list clearfix">



                                    <?php wp_nav_menu( array( 



                                        'title_li'=>'',



                                        'theme_location' => 'primary',



                                        'container' => false,



                                        'items_wrap' => '%3$s',



                                        'depth'      => 0,



                                        'fallback_cb' => 'wp_list_pages'



                                    ));?>



                                </ul>



                            </nav>



                        </div>



                        <div class="col-xs-12 hidden-md hidden-lg mobile-menu-wrapper">



 <div class="menu-button alignleft"></div>



                           <!-- <div class="search-block header-search wrap">-->



                                    <?php //get_search_form();?>



                               



                            </div>



                        </div>



                    </div>



                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-7">
                            <nav class="main-nav responsive-nav shop-nav">
                                <ul class="clean-list clearfix">
                                    <?php wp_nav_menu( array( 
                                        'title_li'=>'',
                                        'theme_location' => 'shop',
                                        'container' => false,
                                        'items_wrap' => '%3$s',
                                        'depth'      => 0,
                                        'fallback_cb' => 'wp_list_pages'
                                    ));?>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-8 col-sm-8 col-xs-8 no-padding">
                                    <div class="search-block header-search wrap"><!--   search -->
                                            <?php get_search_form();?>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                                    <?php if( tesla_has_woocommerce() ) : ?>
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
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.container -->
        </header>

    <!-- ================================= END HEADER === -->

    <!-- ================================= START CONTENT === -->