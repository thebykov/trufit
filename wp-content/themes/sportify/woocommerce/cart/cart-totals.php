<?php
/**
 * Cart totals
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="cart_totals cart-block <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2><?php _e( 'Cart Totals', 'woocommerce' ); ?></h2>

	<div class="cart-totals-block">

		<ul class="cart-subtotal clean-list row">
			<li class="col-md-4 col-sm-4 col-xs-4"><?php _e( 'Cart Subtotal', 'woocommerce' ); ?></li>
			<li class="col-md-4 col-sm-4 col-xs-4"><?php wc_cart_totals_subtotal_html(); ?></li>
		</ul>

		<?php foreach ( WC()->cart->get_coupons( 'cart' ) as $code => $coupon ) : ?>
			<ul class="cart-discount coupon-<?php echo esc_attr( $code ); ?>">
				<li><?php wc_cart_totals_coupon_label( $coupon ); ?></li>
				<li><?php wc_cart_totals_coupon_html( $coupon ); ?></li>
			</ul>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<ul class="fee">
				<li><?php echo esc_html( $fee->name ); ?></li>
				<li><?php wc_cart_totals_fee_html( $fee ); ?></li>
			</ul>
		<?php endforeach; ?>

		<?php if ( WC()->cart->tax_display_cart == 'excl' ) : ?>
			<?php if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<ul class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<li><?php echo esc_html( $tax->label ); ?></li>
						<li><?php echo wp_kses_post( $tax->formatted_amount ); ?></li>
					</ul>
				<?php endforeach; ?>
			<?php else : ?>
				<ul class="tax-total">
					<li><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></li>
					<li><?php echo wc_cart_totals_taxes_total_html(); ?></li>
				</ul>
			<?php endif; ?>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_coupons( 'order' ) as $code => $coupon ) : ?>
			<ul class="order-discount coupon-<?php echo esc_attr( $code ); ?>">
				<li><?php wc_cart_totals_coupon_label( $coupon ); ?></li>
				<li><?php wc_cart_totals_coupon_html( $coupon ); ?></li>
			</ul>
		<?php endforeach; ?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<ul class="order-total clean-list row">
			<li class="col-md-4 col-sm-4 col-xs-4"><?php _e( 'Order Total', 'woocommerce' ); ?></li>
			<li class="col-md-8 col-sm-8 col-xs-8"><?php wc_cart_totals_order_total_html(); ?></li>
		</ul>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	</div>


	<?php if ( WC()->cart->get_cart_tax() ) : ?>
		<p><small><?php

			$estimated_text = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
				? sprintf( ' ' . __( ' (taxes estimated for %s)', 'woocommerce' ), WC()->countries->estimated_for_prefix() . __( WC()->countries->countries[ WC()->countries->get_base_country() ], 'woocommerce' ) )
				: '';

			printf( __( 'Note: Shipping and taxes are estimated%s and will be updated during checkout based on your billing and shipping information.', 'woocommerce' ), $estimated_text );

		?></small></p>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>
	
</div>