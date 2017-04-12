<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;



do_action( 'woocommerce_before_cart' ); ?>

<div class="white-background padding woo-container woo-margin-top shopping-cart">

	<?php wc_print_notices(); ?>

		<header class="entry-header">
			<h2 class="entry-title"><?php _e('SHOPPING CART','sportify'); ?></h2>
		</header>
		

	<div class="entry-content">
		<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<div class="woocommerce row">
			<div class="col-md-8">
				<div class="shopping-cart-wrap">
					<div class="shopping-cart-products lightest-grey-background ovh">
						<ul class="shopping-product-detail clean-list ovh">
				            <li class="product-remove shopping-1"></li><li class="product-thumbnail shopping-2"></li><li class="product-name shopping-3"><?php _e('Product', 'sportify'); ?></li><li class="product-price shopping-4"><?php _e('Price', 'sportify'); ?></li><li class="product-quantity shopping-5"><?php _e('Quantity', 'sportify'); ?></li><li class="product-subtotal shopping-6"><?php _e('Total', 'sportify'); ?></li>
				        </ul>

					
						<?php do_action( 'woocommerce_before_cart_contents' ); ?>

						<?php
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								?>
								<ul class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> shopping-product-detail cart-product-item clean-list ovh"><li class="product-remove shopping-1">
										<?php
											echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" title="%s">&times;</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
										?>
									</li><li class="product-thumbnail shopping-2">
										<?php
											$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

											if ( ! $_product->is_visible() )
												echo $thumbnail;
											else
												printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
										?>
									</li><li class="product-name shopping-3">
										<?php
											if ( ! $_product->is_visible() )
												echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
											else
												echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $cart_item, $cart_item_key );

											// Meta data
											echo WC()->cart->get_item_data( $cart_item );

				               				// Backorder notification
				               				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
				               					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
										?>
									</li><li class="product-price shopping-4">
										<?php
											echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
										?>
									</li><li class="product-quantity shopping-5">
										<?php
											if ( $_product->is_sold_individually() ) {
												$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
											} else {
												$product_quantity = woocommerce_quantity_input( array(
													'input_name'  => "cart[{$cart_item_key}][qty]",
													'input_value' => $cart_item['quantity'],
													'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
													'min_value'   => '0'
												), $_product, false );
											}

											echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
										?>
									</li><li class="product-subtotal shopping-6">
										<?php
											echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
										?>
									</li>
								</ul>
								<?php
							}
						}

						do_action( 'woocommerce_cart_contents' ); ?>

						<?php do_action( 'woocommerce_after_cart_contents' ); ?>

						<?php do_action( 'woocommerce_after_cart_table' ); ?>	
					</div>
					<div class=" ovh">
						<div class="coupon-code-block lightest-grey-background padding">
								<div class="alignright update-cart">
									<?php //do_action( 'woocommerce_proceed_to_checkout' ); ?>
									<input type="submit" class="button" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" /> 
								</div>


								<?php wp_nonce_field( 'woocommerce-cart' ); ?>


								<?php if ( WC()->cart->coupons_enabled() ) { ?>
									<div class="coupon">
										<input type="text" name="coupon_code" class="input-text coupon-field" id="coupon_code" value="" placeholder="<?php _e( 'Coupon code', 'woocommerce' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'woocommerce' ); ?>" />
										<?php do_action('woocommerce_cart_coupon'); ?>
									</div>
								<?php } ?>
						</div>
						<div class="alignright padding">
							<input type="submit" class="checkout-button button alt wc-forward" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" />
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="cart-collaterals">
					<?php woocommerce_cart_totals(); ?>
					<?php woocommerce_shipping_calculator(); ?>
				</div>
			</div>
		</div>

		</form>


	</div>	
</div>


<?php //do_action( 'woocommerce_cart_collaterals' ); ?>

<?php do_action( 'woocommerce_after_cart' ); ?>
