<?php
/**
 * Product attributes
 *
 * Used by list_attributes() in the products class
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$has_row    = false;
$alt        = 1;
$attributes = $product->get_attributes();

ob_start();
?>
<ul class="shop_attributes shop-attributes">

	<?php if ( $product->enable_dimensions_display() ) : ?>

		<?php if ( $product->has_weight() ) : $has_row = true; ?>
			<li class="<?php if ( ( $alt = $alt * -1 ) == 1 ) echo 'alt'; ?> row">
				<div class="col-md-4 col-sm-4 col-xs-4 attr-name"><?php _e( 'Weight', 'woocommerce' ) ?></div>
				<div class="col-md-8 col-sm-8 col-xs-8 product_weight attr-value"><?php echo $product->get_weight() . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) ); ?></div>
			</li>
		<?php endif; ?>

		<?php if ( $product->has_dimensions() ) : $has_row = true; ?>
			<li class="<?php if ( ( $alt = $alt * -1 ) == 1 ) echo 'alt'; ?> row">
				<div class="col-md-4 col-sm-4 col-xs-4 attr-name"><?php _e( 'Dimensions', 'woocommerce' ) ?></div>
				<div class="col-md-8 col-sm-8 col-xs-8 product_dimensions attr-value"><?php echo $product->get_dimensions(); ?></div>
			</li>
		<?php endif; ?>

	<?php endif; ?>

	<?php foreach ( $attributes as $attribute ) :
		if ( empty( $attribute['is_visible'] ) || ( $attribute['is_taxonomy'] && ! taxonomy_exists( $attribute['name'] ) ) ) {
			continue;
		} else {
			$has_row = true;
		}
		?>
		<li class="<?php if ( ( $alt = $alt * -1 ) == 1 ) echo 'alt'; ?> row">
			<div class="col-md-4 col-sm-4 col-xs-4 attr-name"><?php echo wc_attribute_label( $attribute['name'] ); ?></div>
			<div class="col-md-8 col-sm-8 col-xs-8 attr-value"><?php
				if ( $attribute['is_taxonomy'] ) {

					$values = wc_get_product_terms( $product->id, $attribute['name'], array( 'fields' => 'names' ) );
					echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );

				} else {

					// Convert pipes to commas and display values
					$values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
					echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );

				}
			?></div>
		</li>
	<?php endforeach; ?>
	
</ul>
<?php
if ( $has_row ) {
	echo ob_get_clean();
} else {
	ob_end_clean();
}