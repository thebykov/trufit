	<?php
	/**
	 * The template for displaying product content within loops.
	 *
	 * Override this template by copying it to yourtheme/woocommerce/content-product.php
	 *
	 * @author 		WooThemes
	 * @package 	WooCommerce/Templates
	 * @version     2.5.0
	 */

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $product, $woocommerce_loop, $no_product_columns, $no_product_rows, $recommend;

	// Store loop count we're currently on
	if ( empty( $woocommerce_loop['loop'] ) )
		$woocommerce_loop['loop'] = 0;

	// Store column count for displaying the grid
	if ( empty( $woocommerce_loop['columns'] ) )
		$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

	// Ensure visibility
	if ( ! $product || ! $product->is_visible() )
		return;

	// Increase loop count
	$woocommerce_loop['loop']++;

	// Extra post classes
	$classes = array();
	if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
		$classes[] = 'first';
	if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
		$classes[] = 'last';
	$classes = 'product';
	?>

	<?php if (!$no_product_columns) : ?>


		<?php if( (!is_shop() && !is_product_category() && !is_product_tag()) && !$recommend ) : ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
		<?php elseif ( $recommend ): ?>
			<div class="col-md-12 col-sm-12 col-xs-12">

		<?php else: ?>
			<div class="col-md-3 col-sm-3 col-xs-6">

		<?php endif; ?>
	<?php endif; ?>

	<!-- div <?php //post_class( $classes ); ?> -->

		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

		<?php echo $recommend ? '<div class="row no-margin"><div class="product-cover col-md-5 col-sm-2 col-xs-2">' : '<div class="product-cover">' ?>
			
				<div class="product-cover-hover">
					<span>
						<a href="<?php the_permalink() ?>"><?php _e('View','sportify') ?></a>
					</span>
				</div>
				
				<?php
					/**
					 * woocommerce_before_shop_loop_item_title hook
					 *
					 * @hooked woocommerce_show_product_loop_sale_flash - 10
					 * @hooked woocommerce_template_loop_product_thumbnail - 10
					 */
					do_action( 'woocommerce_before_shop_loop_item_title' );
				?>
			</div>

			<?php echo $recommend ? '<div class="product-details col-md-7 col-sm-10 col-xs-10">' : '<div class="product-details">' ?>
				
				<h2 class="entry-title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h2>
				<div class="product-price">
					
				<?php
					/**
					 * woocommerce_after_shop_loop_item_title hook
					 *
					 * @hooked woocommerce_template_loop_rating - 5
					 * @hooked woocommerce_template_loop_price - 10
					 */
					do_action( 'woocommerce_after_shop_loop_item_title' );
				?>
				<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
				</div>
			</div>
		<?php echo $recommend ? '</div>' : '' ?>
	
	<?php if (!$no_product_columns): ;?>
		</div>
	<?php endif; ?>

	<?php if( !$no_product_rows ): ?>

		<?php if((!is_shop() && !is_product_category() && !is_product_tag()) && $woocommerce_loop['loop'] % 4 == 0) : ?>

			
		<?php elseif((is_shop() || is_product_category() || is_product_tag()) && $woocommerce_loop['loop'] % 4 == 0) : ?>
			</div>
			<div class="row no-margin">
		<?php endif; ?>
	<?php endif;  ?>
