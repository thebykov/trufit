<?php
/**
 * Edit address form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $current_user;

$page_title = ( $load_address === 'billing' ) ? __( 'Billing Address', 'woocommerce' ) : __( 'Shipping Address', 'woocommerce' );

?>

<div class="woo-margin-top">
    <div class="white-background padding">
        <div class="entry-content">
            <div class="row">
                <div class="col-md-8">
                   <?php wc_print_notices(); ?>    
                   	 <div class="woo-edit-shipping">

						<?php if ( ! $load_address ) : ?>

							<?php wc_get_template( 'myaccount/my-address.php' ); ?>

						<?php else : 

								do_action( 'woocommerce_before_edit_account_address_form' ); ?>

								<form method="post" class="edit-shipping-address">		
										<header class="">
											<h3 class="entry-title"><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title ); ?></h3>
										</header><hr  />
									<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>
									<div class="padding">
										<?php foreach ( $address as $key => $field ) : ?>

											<?php woocommerce_form_field( $key, $field, ! empty( $_POST[ $key ] ) ? wc_clean( $_POST[ $key ] ) : $field['value'] ); ?>

										<?php endforeach; ?>
										
										<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

										<p>
											<input type="submit" class="button" name="save_address" value="<?php _e( 'Save Address', 'woocommerce' ); ?>" />
											<?php wp_nonce_field( 'woocommerce-edit_address' ); ?>
											<input type="hidden" name="action" value="edit_address" />
										</p>
									</div>
								</form>
						<?php endif; ?>
						<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>