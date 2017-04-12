<?php
/**
 * Review order form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php if ( ! is_ajax() ) : ?><?php endif; ?>

	<ul class="shop_table clean-list shop-list ovh">
			<li class="full-width shop-list-title grey-border-block">
				<ul class="clean-list">
					<li class="product-name head-title"><?php _e( 'Product', 'woocommerce' ); ?></li>
					<li class="product-total head-title"><?php _e( 'Total', 'woocommerce' ); ?></li>
				</ul>
			</li>
			


			<li class="full-width order-cart-block grey-border-block">
				<ul class="clean-list">
			<?php
				do_action( 'woocommerce_review_order_before_cart_contents' );

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						?>
						
							<li class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> product-name">
								<?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ); ?>
								<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
								<?php echo WC()->cart->get_item_data( $cart_item ); ?>
							</li>
							<li class="product-total">
								<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
							</li>
						
						<?php
					}
				}

				do_action( 'woocommerce_review_order_after_cart_contents' );
			?>
				</ul>
			</li>
			<li class="full-width  order-total-block grey-border-block">
				<ul class="clean-list">
				
					<li class="cart-subtotal"><?php _e( 'Cart Subtotal', 'woocommerce' ); ?></li>
					<li><?php wc_cart_totals_subtotal_html(); ?></li>
					

					<?php foreach ( WC()->cart->get_coupons( 'cart' ) as $code => $coupon ) : ?>
						<li class="cart-discount coupon-<?php echo esc_attr( $code ); ?>"><?php wc_cart_totals_coupon_label( $coupon ); ?></li>
						<li><?php wc_cart_totals_coupon_html( $coupon ); ?></li>
					<?php endforeach; ?>

					<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
						<li class="full-width">
						<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

						<?php wc_cart_totals_shipping_html(); ?>

						<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
						</li>

					<?php endif; ?>

					<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
						<li class="fee"><?php echo esc_html( $fee->name ); ?></li>
						<li><?php wc_cart_totals_fee_html( $fee ); ?></li>
					<?php endforeach; ?>

					<?php if ( WC()->cart->tax_display_cart === 'excl' ) : ?>
						<?php if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) : ?>
							<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
								
								<li class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>"><?php echo esc_html( $tax->label ); ?></li>
								<li><?php echo wp_kses_post( $tax->formatted_amount ); ?></li>
								
							<?php endforeach; ?>
						<?php else : ?>
							<li class="tax-total"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></li>
							<li><?php echo wc_price( WC()->cart->get_taxes_total() ); ?></li>
						<?php endif; ?>
					<?php endif; ?>

					<?php foreach ( WC()->cart->get_coupons( 'order' ) as $code => $coupon ) : ?>
						
						<li class="order-discount coupon-<?php echo esc_attr( $code ); ?>"><?php wc_cart_totals_coupon_label( $coupon ); ?></li>
						<li><?php wc_cart_totals_coupon_html( $coupon ); ?></li>
						
					<?php endforeach; ?>

				<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

			
				<li class="order-total"><?php _e( 'Order Total', 'woocommerce' ); ?></li>
				<li><?php wc_cart_totals_order_total_html(); ?></li>
			</ul>
		</li>

			<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

		
	</ul>

<?php if ( ! is_ajax() ) : ?><?php endif; ?>