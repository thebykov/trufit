<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product;
$attachment_ids = $product->get_gallery_attachment_ids();
?>

<div class="the-slider product-big-image images ovh" data-tesla-plugin="slider" data-tesla-autoplay="false" data-tesla-item=".slide" data-tesla-next=".product-image-arrows-right" data-tesla-prev=".product-image-arrows-left" data-tesla-container=".slide-wrapper">
	<?php if ( $attachment_ids ) : ?>
		<ul class="product-image-arrows clean-list">
	        <li class="product-image-arrows-left"><i class="icon-517" title="left"></i></li>
	        <li class="product-image-arrows-right"><i class="icon-501" title="right"></i></li>
	    </ul>
	<?php endif; ?>

   <ul class="slide-wrapper clean-list">
	<?php
		if ( $attachment_ids ): ?>
		<ul class="slide-wrapper clean-list ovh">
			<?php $classes = array( 'zoom' );
			foreach ( $attachment_ids as $attachment_id ) {

				$image_link = wp_get_attachment_url( $attachment_id ) ? wp_get_attachment_url( $attachment_id )  : '';
				if ( ! $image_link )
						continue;

				$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
				$image_class = esc_attr( implode( ' ', $classes ) );
				$image_title = esc_attr( get_the_title( $attachment_id ) );

				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<li class="slide %s"><img src="%s" itemprop="image" class="woocommerce-main-image zoom" alt="%s"></li>', $image_class,$image_link, $image_title ), $attachment_id, $post->ID, $image_class );

			} ?>



		<?php elseif ( has_post_thumbnail() ):
			$image_title 		= esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$image_link  		= wp_get_attachment_url( get_post_thumbnail_id() );
			$attachment_count   = count( $product->get_gallery_attachment_ids() );

				if ( $attachment_count > 0 ) {
					$gallery = '[product-gallery]';
				} else {
					$gallery = '';
				}

				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<li class="slide"><img src="%s" itemprop="image" class="woocommerce-main-image zoom" alt="%s"></li>', $image_link, $image_title ), $post->ID ); 
		else:
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', wc_placeholder_img_src() ), $post->ID );
		endif;
		?>
	</ul>
	<?php do_action( 'woocommerce_product_thumbnails' ); ?>

</div>
