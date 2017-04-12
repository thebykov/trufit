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
</head>




<body <?php body_class(); ?>>
    <div class="show-content">
    <!-- ===================================== START HEADER -->
    <!-- All content goes here -->
        <header class="main-header">
            <!-- Header Shorcode Area -->
            <div class="nav-bar sticky-bar grey-background">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-4">
                            <?php if(_go('logo_image')): ?>
                                <figure class="identity">
                                    <a href="<?php echo home_url() ?>" title="home" rel="home">
                                        <img src="<?php _eo('logo_image') ?>" alt="<?php echo THEME_PRETTY_NAME; ?><?php _e('theme logo', 'sportify') ?>">
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
                        <div class="col-md-9 col-sm-9 col-xs-8">
                            <div class="row">
                                <div class="col-md-9 col-sm-9 col-xs-12 ">
                                    <?php if (_go('ad_image')) : ?>
                                        <div class="ad">
                                            <?php if (_go('ad_url')) : ?>
                                                <a href="<?php _eo('ad_url'); ?>" target="_blank">
                                                    <img src="<?php _eo('ad_image'); ?>" alt="<?php _e('Banner', 'sportify'); ?>" />
                                                </a>
                                            <?php else: ?>    
                                                <img src="<?php _eo('ad_image'); ?>" alt="<?php _e('Banner', 'sportify'); ?>" />
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 col-sm-3 ">
                                    <div class="socials">
                                        <ul class="clean-list social-links clearfix">
                                            <?php _esocial_platforms(array('twitter', 'facebook', 'youtube', 'pinterest', 'dribble', 'instagram', 'googleplus', 'linkedin'), 'i-', '') ?>
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
                        <div class="col-md-8">
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
                        <div class="col-md-4">
                            <div class="search-block header-search wrap"><!--   search -->
                                    <?php get_search_form();?>
                                <div class="menu-button alignleft"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

    <!-- ================================= END HEADER === -->

    <!-- ================================= START CONTENT === -->