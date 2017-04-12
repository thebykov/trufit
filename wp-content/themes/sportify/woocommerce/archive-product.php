<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>
		<div class="white-background">
			<div class="all-products-details ovh">
				
					
						<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
							<header class="alignleft">
								<h2 class="entry-header"><?php woocommerce_page_title(); ?></h2>
							</header>

						<?php endif; ?>
					
						<div class="catalog-filters ovh">
							<?php
								/**
								 * woocommerce_before_shop_loop hook
								 *
								 * @hooked woocommerce_result_count - 20
								 * @hooked woocommerce_catalog_ordering - 30
								 */
								do_action( 'woocommerce_before_shop_loop' );
							?>
						</div>
					
				
			</div>
			
			<div class="row no-margin">

				<?php do_action( 'woocommerce_archive_description' ); ?>

				<?php if ( have_posts() ) : ?>

					

					<div class="col-md-3">
						<?php
							/**
							 * woocommerce_sidebar hook
							 *
							 * @hooked woocommerce_get_sidebar - 10
							 */
							do_action( 'woocommerce_sidebar' );
						?>
					</div>
					<div class="col-md-9">


						<div class="catalog-block products-grid catalog-grid">
							<div class="row no-margin">
								<?php woocommerce_product_loop_start(); ?>

									<?php woocommerce_product_subcategories(); ?>

									<?php while ( have_posts() ) : the_post(); ?>

										<?php wc_get_template_part( 'content', 'product' ); ?>

									<?php endwhile; // end of the loop. ?>

								<?php woocommerce_product_loop_end(); ?>
							</div>	
						</div>
					</div>

					<?php
						/**
						 * woocommerce_after_shop_loop hook
						 *
						 * @hooked woocommerce_pagination - 10
						 */
						do_action( 'woocommerce_after_shop_loop' );
					?>
					<div class="bottom-catalog-filters catalog-filters">
						<?php do_action( 'woocommerce_before_shop_loop' ); ?>
					</div>


				<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

					<?php wc_get_template( 'loop/no-products-found.php' ); ?>

				<?php endif; ?>

			
			<?php
				/**
				 * woocommerce_after_main_content hook
				 *
				 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
				 */
				do_action( 'woocommerce_after_main_content' );

			?>

		</div>
	</div>
<?php get_footer( 'shop' ); ?>