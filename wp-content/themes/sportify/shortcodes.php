<?php 
	
/***********************************************************************************************/
/* Shortcodes */
/***********************************************************************************************/
/* Shorcode row (Template structure)
============================================*/
add_shortcode('container', 'container');

function container($atts, $content = null) {
	extract(shortcode_atts(array(
		'addclass' => ''
	), $atts));
	
	return '<div class="container '.$addclass.'">'. do_shortcode(shortcode_unautop($content)) .'</div>';
}

add_shortcode('row', 'row');

function row($atts, $content = null) {
	extract(shortcode_atts(array(
		'addclass' => ''
	), $atts));
	
	return '<div class="row '.$addclass.'">'. shortcode_unautop(do_shortcode($content)) .'</div>';
}

/* Shorcode column (Template structure)
============================================*/

add_shortcode('column', 'column');

function column($atts, $content = null) {
	extract(shortcode_atts(array(
		'size' => '12',
		'addclass' => ''
	), $atts));
	
	return '<div class="col-md-'.$size.' '.$addclass.'">'. do_shortcode(shortcode_unautop($content)) .'</div>';
}
	

function listing($atts, $content = null) {
	extract(shortcode_atts(array(
		'addclass' => ''
	), $atts));
	
	return '<div class="list '.$addclass.'">'. do_shortcode($content) .'</div>';
}

add_shortcode('listing', 'listing');



function quote($atts, $content = null) {
	extract(shortcode_atts(array(
		'addclass' => ''
	), $atts));
	
	return '<blockquote class=" '.$addclass.'">'. do_shortcode($content) .'</blockquote>';
}

add_shortcode('quote', 'quote');

//===================latest posts================================

function sportify_team_box ($atts, $content) {
	extract(shortcode_atts(array(
				'title' => '',
				'description' => ''
			), $atts));

	$args = array(            
			'post_type'         => 'team',
			'post_status'       => 'publish',
			'ignore_sticky_posts' => 1,
			'order'               => 'DESC',
			'orderby'             => 'date',
			
			);

	$members  = new WP_Query( $args );

	if ($members -> have_posts()) :
		$header =  '<section id="team_box" class="box team-box"><!-- Section Trainers-->
						<header class="text-center team-box-header box-header">
							<div class="container">
								<div class="white-border box-header-title-block aligncenter nowrap">
									<h2 class="entry-header">'.$title.'</h2>
								</div>
								<h3 class="entry-description">'.$description.'</h3>
							</div>
						</header>';

		$output         = '';
		while ( $members -> have_posts() ) : $members -> the_post();
			$output = Tesla_slider::get_slider_html('team');
		endwhile;

		return $header.$output;
	endif;
} 

add_shortcode('sportify_team_box', 'sportify_team_box');

function sportify_events_slider ($atts, $content) {
	extract(shortcode_atts(array(
				'title' => '',
				'description' => '',
				'category' => '',
			), $atts));

	$args = array(            
			'post_type'           => 'events',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'order'               => 'DESC',
			'orderby'             => 'date',
			'category_name'		  =>  $category,					
			);

	$events  = new WP_Query( $args );
	if ($events -> have_posts()) :

		$header =  '<div class="events-block block-padding">
						<header class="block-header red-background">
							<h2 class="entry-header"><a href="#">'.$title.'</a></h2>
						</header>';
		$output = '';

		while ( $events -> have_posts() ) : $events -> the_post();
			$output = Tesla_slider::get_slider_html('events');
		endwhile;

		return $header.$output;
	endif;
} 

add_shortcode('sportify_events_slider', 'sportify_events_slider');


function sportify_friends_box ($atts, $content) {
	extract(shortcode_atts(array(
				'title' => '',
				'description' => ''
			), $atts));

	$args = array(            
			'post_type'         => 'partners',
			'post_status'       => 'publish',
			'ignore_sticky_posts' => 1,
			'order'               => 'DESC',
			'orderby'             => 'date',
			
			);

	$partners  = new WP_Query( $args );

	if ($partners -> have_posts()) :
		$header =  '<section id="friends_box" class="box friends-box">
						<header class="text-center friends-box-header box-header">
							<div class="container">
								<div class="white-border box-header-title-block aligncenter nowrap">
									<h2 class="entry-header">'.$title.'</h2>
								</div>
								<h3 class="entry-description">'.$description.'</h3>
							</div>
						</header>';

		$output = '';

		while ( $partners -> have_posts() ) : $partners -> the_post();
			$output = Tesla_slider::get_slider_html('partners');
		endwhile;

		return $header.$output;
	endif;
} 

add_shortcode('sportify_friends_box', 'sportify_friends_box');



function sportify_recent_news ($atts, $content = null) {

	extract(shortcode_atts(array(
				'count' => '',
				'title' => 'Latest News',
				'category' => ''
				), $atts));

	$args = array(            
			//Type & Status Parameters
			'post_type'   => 'post',
			'post_status' => 'publish',
			'posts_per_page'  =>  $count,
			'ignore_sticky_posts' => 1,
			//Order & Orderby Parameters
			'order'               => 'DESC',
			'orderby'             => 'date',
			'category_name'		  =>  $category,
			
		);
	
	$recent  = new WP_Query( $args );

	
	if( $recent->have_posts() ):
		$header = '<div class="news-block light-grey-background block-padding">
						<header class="block-header red-background">
							<h2 class="entry-header"><a href="#">'.do_shortcode($title).'</a></h2>
						</header>
						
						<div id="news_slider" class="news-slider ovh">
							<ul class="clean-list news-list">';
		$footer =   '</ul>
				</div>
			</div>';

		
		$output = '';
		$nr_posts = 4;
		$post_counter = 0;
		$total_posts = $recent->post_count;

		

		while ($recent->have_posts()) : $recent->the_post();
			$post_counter++;

			if ($post_counter == 1 || $post_counter % 5 == 0 ){
				$output .= '<li><div class="row padding">';
			}
				
			$output .= '<div class="col-md-6 col-sm-6 col-xs-6 ">
							<div class="row no-margin">
								<div class="col-md-8 col-sm-6 col-xs-6 no-padding">
									<figure class="">
										<a href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(), 'thumbnail').'</a>
										 
									</figure>
								</div>

								<div class="col-md-4 col-sm-6 col-xs-6 no-padding">
									<time datetime="'.get_the_time("Y-m-d").'" class="light-green-background text-center"><strong>'.get_the_time("d.m").'</strong></time>
								</div>
							</div>

							<h3 class="entry-header inline"><a href="'.get_permalink().'" class="inline">'.get_the_title().'</a></h3>'.apply_filters('the_excerpt', get_the_excerpt()).
						'</div>';
			
			if ($post_counter > 0 && $post_counter % 4 == 0 || $post_counter == $total_posts){
				$output .= '</div></li>';
			}

		endwhile;
	endif;

	return $header.$output.$footer;
}

add_shortcode('sportify_recent_news', 'sportify_recent_news');


function sportify_video_slider ($atts, $content) {
	extract(shortcode_atts(array(
				'title' => '',
				'left_background' => 'http://teslathemes.com/demo/wp/sportify/wp-content/uploads/2014/10/carousel-slide2.jpg',
				'right_background' => 'http://teslathemes.com/demo/wp/sportify/wp-content/uploads/2014/10/carousel-slide3.jpg'

			), $atts));

	$args = array(            
			'post_type'         => 'video_slider',
			'post_status'       => 'publish',
			'ignore_sticky_posts' => 1,
			'order'               => 'DESC',
			'orderby'             => 'date',
			
			);

	$slider  = new WP_Query( $args );

	if ($slider -> have_posts()) :
		$header =  '<section  id="classes_slider_box" class="box classes-slider-box relative margin-top"><!--    Section Videos-->
						<div class="row no-margin underneath">
							<div class="col-md-6 col-sm-6 col-xs-6 no-padding ovh">
								<figure class="blur">
									<img alt="Underneath Photo" class="scale-120" src="'.$left_background.'">
								</figure>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6 no-padding ovh">
								<figure class="blur">
									<img alt="Underneath Photo" class="scale-120" src="'.$right_background.'">
								</figure>
							</div>
						</div>
						<div class="container no-padding">
							<header class="block-header box-header">
								<h2 class="entry-header text-center pre-line">'.$title.'</h2>
							</header>';
		$output = '';

		while ( $slider -> have_posts() ) : $slider -> the_post();
			$output = Tesla_slider::get_slider_html('video_slider');
		endwhile;

		return $header.$output;
	endif;
} 

add_shortcode('sportify_video_slider', 'sportify_video_slider');


function sportify_about_slider ($atts, $content) {
	extract(shortcode_atts(array(
				'title' => ''
			), $atts));

	$args = array(            
			'post_type'         => 'about_us',
			'post_status'       => 'publish',
			'ignore_sticky_posts' => 1,
			'order'               => 'DESC',
			'orderby'             => 'date'
			);


	$slider  = new WP_Query( $args );

	if ($slider -> have_posts()) :
		$output = '';

		while ( $slider -> have_posts() ) : $slider -> the_post();
			$output = Tesla_slider::get_slider_html('about_us');
		endwhile;

		return $output;
	endif;
} 

add_shortcode('sportify_about_slider', 'sportify_about_slider');


function sportify_testimonials ($atts, $content) {
	extract(shortcode_atts(array(
				'title' => 'The Title',
				'description' => 'Description',
			), $atts));

	$args = array(            
			'post_type'         => 'testimonials',
			'post_status'       => 'publish',
			'ignore_sticky_posts' => 1,
			'order'               => 'DESC',
			'orderby'             => 'date'
			);


	$testimonials  = new WP_Query( $args );

	if ($testimonials -> have_posts()) :
		$header =  '<section id="clients_box" class="box clients-box"><!-- Section Trainers-->
						<header class="text-center clients-box-header box-header">
							<div class="container">
								<div class="white-border box-header-title-block aligncenter nowrap">
									<h2 class="entry-header">'.$title.'</h2>
								</div>
								<h3 class="entry-description">'.$description.'</h3>
							</div>
						</header>';
		$output = '';

		while ( $testimonials -> have_posts() ) : $testimonials -> the_post();
			$output = Tesla_slider::get_slider_html('testimonials');
		endwhile;

		return $header.$output;
	endif;
} 

add_shortcode('sportify_testimonials', 'sportify_testimonials');


function sportify_gallery_slider ($atts, $content) {
	extract(shortcode_atts(array(
				'title' => 'The Title',
				'image' => 'http://teslathemes.com/demo/wp/sportify/wp-content/uploads/2014/10/gallery-background.jpg'
			), $atts));

	$args = array(            
			'post_type'         => 'gallery_slider',
			'post_status'       => 'publish',
			'ignore_sticky_posts' => 1,
			'order'               => 'DESC',
			'orderby'             => 'date'
			);


	$gallery  = new WP_Query( $args );
	if ($gallery -> have_posts()) :
		$header =  '<section id="about_gallery_box" class="box gallery-box about-gallery-box" style="background-image: url('.$image.')"><!-- Section Trainers-->
						<header class="text-center gallery-box-header box-header">
							<div class="container">
								<div class="aligncenter">
									<h2 class="entry-header">'.$title.'</h2>
								</div>
							</div>
						</header>';
		$output = '';

		while ( $gallery -> have_posts() ) : $gallery -> the_post();
			$output = Tesla_slider::get_slider_html('gallery_slider');
		endwhile;

		return $header.$output;
	endif;
} 

add_shortcode('sportify_gallery_slider', 'sportify_gallery_slider');


function sportify_timeline ($atts, $content) {
	extract(shortcode_atts(array(
				'title' => ''
			), $atts));

	$args = array(            
			'post_type'         => 'timeline',
			'post_status'       => 'publish',
			'ignore_sticky_posts' => 1,
			'order'               => 'DESC',
			'orderby'             => 'date'
			);


	$timeline  = new WP_Query( $args );

	if ($timeline -> have_posts()):
		
		$output = '';

		while ( $timeline -> have_posts() ) : $timeline -> the_post();
			$tbody = Tesla_slider::get_slider_html('timeline');
		endwhile;

		$output = $tbody;
		return $output;
	endif;
} 

add_shortcode('sportify_timeline', 'sportify_timeline');


function sportify_services ($atts, $content) {
	extract(shortcode_atts(array(
				'title' => '',
				'description' => ''
			), $atts));

	$args = array(            
			'post_type'         => 'services',
			'post_status'       => 'publish',
			'ignore_sticky_posts' => 1,
			'order'               => 'DESC',
			'orderby'             => 'date'
			);


	$services  = new WP_Query( $args );

	if ($services -> have_posts()):
		
		$header =  '<section id="services_box" class="box services-box underneath-light "><!-- Section Services -->
						<header class="text-center services-box-header box-header">
							<div class="container">
								<div class="white-border box-header-title-block aligncenter nowrap">
									<h2 class="entry-header">'.$title.'</h2>
								</div>
								<h3 class="entry-description">'.$description.'</h3>
							</div>
						</header>';

		$output = '';

		while ( $services -> have_posts() ) : $services -> the_post();
			$output = Tesla_slider::get_slider_html('services');

		endwhile;

		return $header.$output;
	endif;
} 

add_shortcode('sportify_services', 'sportify_services');


function sportify_calculator ($atts, $content) {
	extract(shortcode_atts(array(
				'title' => '',
				'data_type' =>'eu'
			), $atts));


	ob_start(); ?>

	<div class="calc-block red-background block-padding" data-type="<?php echo $data_type; ?>">
		<header>
			<h2 class="entry-header"><?php _e('Calculate Your Ideal Body Weight', 'sportify'); ?>:</h2>
		</header>
		<form id="calc_form" class="calc-form" action="#">
			<div class="fieldbox row">
				<div class="col-md-6 col-sm-6">
					<label for="weight"><?php _e('Weight', 'sportify'); ?><?php if ($data_type == 'eu') _e(' (kg)', 'sportify'); else _e(' (pounds)', 'sportify');; ?></label><br>
					<input type="text" id="weight">
				</div>
				<div class="col-md-6 col-sm-6">
					<label for="height"><?php _e('Height', 'sportify'); ?><?php if ($data_type == 'eu') _e(' (cm)', 'sportify'); else _e(' (feets)', 'sportify'); ?></label><br>
					<input type="text" id="height">
				</div>
			</div>
			
			<div class="fieldbox row">
				<div class="col-md-6 col-sm-6">
					<label for="age"><?php _e('Age', 'sportify'); ?></label><br>
					<input type="text" id="age">
				</div>
				<div class="col-md-6 col-sm-6">
					<label for="height"><?php _e('Gender (m/f)', 'sportify'); ?></label><br>
					<input type="text" id="gender">
				</div>
			</div>
			<div class="fieldbox calc-submit" data-trans='<?php echo json_encode(array(__('That you are too thin.','sportify'), __('That you are healthy.','sportify'), __('That you have overweight.','sportify')), JSON_FORCE_OBJECT);?>'>
				<input type="submit" value="Calculate">
			</div>

		</form>
	</div>

	<div class="calc-result-block block-padding">
		<header>
			<h2 class="entry-header"><?php _e('Result', 'sportify'); ?>:</h2>
			<div id="result" class="result">
				<p><?php _e('Fill The Form To Calculate Your BMI', 'sportify'); ?> </p>
			</div>
		</header>
	</div>

	<?php return ob_get_clean();
} 

add_shortcode('sportify_calculator', 'sportify_calculator');




function sportify_new_arrivals( $atts ) {
	global $woocommerce_loop;

	extract( shortcode_atts( array(
		'title'     => 'New Arrivals',
		'products_nr'  => '12',
		'columns'   => '5',
		'orderby'   => 'date',
		'order'     => 'desc'
	), $atts ) );

	$meta_query = WC()->query->get_meta_query();

	$args = array(
		'post_type'             => 'product',
		'post_status'           => 'publish',
		'ignore_sticky_posts'   => 1,
		'posts_per_page'        => $products_nr,
		'orderby'               => $orderby,
		'order'                 => $order,
		'meta_query'            => $meta_query
	);
	
	ob_start();

	$products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

	global $no_product_rows;
	$no_product_rows = true; 
	?>

	<?php if ( $products->have_posts() ) : ?>
		<section id="arrivals_box" class="box arrivals-box">
			<div class="container ">
				<div class="white-background padding">
					<header class="text-center services-box-header light-grey-background">
						<div class="white-border box-header-title-block aligncenter nowrap">
							<h2 class="entry-header">
								<?php echo $title ?>
							</h2>
						</div>
					</header>

					<div class="new-arrivals products-grid">
						<div class="row padding">
							<?php while ( $products->have_posts() ) : $products->the_post(); ?>

								<?php woocommerce_product_loop_start(); ?>
										<?php wc_get_template_part( 'content', 'product' ); ?>
								<?php woocommerce_product_loop_end(); ?>

							<?php endwhile; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php endif;

	wp_reset_postdata();

	return ob_get_clean();
}

add_shortcode('sportify_new_arrivals', 'sportify_new_arrivals');

function sportify_products_block( $atts ){
	global $woocommerce_loop, $no_product_columns;
	
	if ( empty( $atts ) ) return '';

	extract( shortcode_atts( array(
		'columns'   => '6',
		'orderby'   => 'title',
		'order'     => 'asc',
		'title'     => '*You Might Like This*'
	), $atts ) );

	$args = array(
		'post_type'             => 'product',
		'post_status'           => 'publish',
		'ignore_sticky_posts'   => 1,
		'orderby'               => $orderby,
		'order'                 => $order,
		'posts_per_page'        => -1,
		'meta_query'            => array(
			array(
				'key'       => '_visibility',
				'value'     => array('catalog', 'visible'),
				'compare'   => 'IN'
			)
		)
	);


	if ( isset( $atts['skus'] ) ) {
		$skus = explode( ',', $atts['skus'] );
		$skus = array_map( 'trim', $skus );
		$args['meta_query'][] = array(
			'key'       => '_sku',
			'value'     => $skus,
			'compare'   => 'IN'
		);
	}

	if ( isset( $atts['ids'] ) ) {
		$ids = explode( ',', $atts['ids'] );
		$ids = array_map( 'trim', $ids );
		$args['post__in'] = $ids;
	}

	ob_start();

	$products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );
	global $no_product_rows;
	$no_product_rows = true;

	if ( $products->have_posts() ) : ?>
		<section id="top_selling_box" class="box top-selling-box">
			<div class="container">
				<div class="cross-sells white-background products-grid">
					<h2 class="padding text-center"><?php echo $title ?></h2><hr />

					<div class="row padding">
						<?php woocommerce_product_loop_start(); ?>
							<?php while ( $products->have_posts() ) : $products->the_post(); ?>
									<?php wc_get_template_part( 'content', 'product' ); ?>
							<?php endwhile; ?>

						<?php woocommerce_product_loop_end(); ?>
					</div>
				</div>
			</div>
		</section>

	<?php endif;

	wp_reset_postdata();
	return ob_get_clean();
}

add_shortcode('sportify_products_block', 'sportify_products_block');

function tesla_box($atts, $content = null) {
	extract(shortcode_atts(array(
		'addclass'  =>  '',
		'id'        =>  'classes-box',
		'bg'        =>  'black-background'
	), $atts));
	
	return '<div id="'.$id.'" class="box '.$addclass.'"><div class="container ' .$bg. ' no-padding">'. do_shortcode(shortcode_unautop($content)) .'</div></div>';
}

add_shortcode('tesla_box', 'tesla_box');

function about_box($atts, $content = null) {
	extract(shortcode_atts(array(
		'title'  =>  '',
		'subtitle'        =>  'We Are Young But We <br />Have A Lot Of Friends Already',
		'image' => 'http://teslathemes.com/demo/wp/sportify/wp-content/uploads/2014/10/about-slide.jpg',
		'image_small' => 'http://teslathemes.com/demo/wp/sportify/wp-content/uploads/2014/10/about-slide.jpg',
		'second_title'        =>  'Our <br />Story'
	), $atts));
	ob_start();?>
	<section id="about_box" class="box about-box" style="background-image: url('<?php echo $image ?>')">
		<div class='container'>
			<header class="padding-top">
				<h2 class="entry-header"><?php echo $title ?></h2>
				<h3 class="entry-subheader"><?php echo $subtitle ?></h3>

				<div class="header-content">
					<div class="row col-md-offset-3 light-grey-background">
						<div class="col-md-5 col-sm-5 ">
							<div class="header-block prize-icon">
								<h2 class="entry-header"><?php echo $second_title ?></h2>
							</div>
						</div>
						<div class="col-md-7 col-sm-7 padding" style="background-image: url('<?php echo $image_small ?>')">
							<?php echo do_shortcode( $content ); ?>
						</div>
					</div>
				</div>
			</header>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

add_shortcode('about_box', 'about_box');

function tesla_banner($atts, $content = null) {
	extract(shortcode_atts(array(
		'addclass'  =>  '',
		'link'      =>  '',
		'img'       =>  ''
	), $atts));
	
	return '<div class="banner-block '.$addclass.'"><a href="' . esc_attr( $link ) . '" target="_blank"><img src="' . esc_attr($img) . '" /></a></div>';
}

add_shortcode('tesla_banner', 'tesla_banner');

function tesla_banner_2($atts, $content = null) {
	extract(shortcode_atts(array(
		'addclass'  =>  '',
		'link'      =>  '#',
		'img'       =>  ''
	), $atts));
	ob_start();?>
	<section class="banner-block <?php echo $addclass?>">
		<div class="container">
			<header>
				<h2 class="no-margin">
					<a href="<?php echo $link ?>" target="_blank"><img alt="<?php _ex('Banner', 'sportify'); ?>" src="<?php echo $img ?>"></a>
				</h2>
			</header>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

add_shortcode('tesla_banner_2', 'tesla_banner_2');