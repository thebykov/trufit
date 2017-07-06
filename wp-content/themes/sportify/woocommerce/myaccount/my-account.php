<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>


<div class="woo-margin-top">
    <div class="white-background padding">
        <div class="entry-content">
            <div class="row">
                <div class="col-md-8">
                   <?php wc_print_notices(); ?>    
                   	 <div class="woo-account woo-my-account">

                   	 	<header>
							<h2 class="entry-title padding"><?php _e( 'My Account', 'woocommerce' ); ?></h2>
						</header>

						<p class="myaccount_user message padding">
							<?php
							printf(
								__( '<strong>Hello %1$s</strong> (not %1$s? <a href="%2$s">Sign out</a>).', 'woocommerce' ) . ' ',
								$current_user->display_name,
								wp_logout_url( get_permalink( wc_get_page_id( 'myaccount' ) ) )
							);

							printf( __( 'From your account dashboard you can view your recent orders, manage your shipping and billing addresses and <a href="%s">edit your password and account details</a>.', 'woocommerce' ),
								wc_customer_edit_account_url()
							);
							?>
						</p>

						<?php do_action( 'woocommerce_before_my_account' ); ?>

						<?php wc_get_template( 'myaccount/my-downloads.php' ); ?>

						<?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>

						<div class="account-my-address margin-top-20 ovh">
							<?php wc_get_template( 'myaccount/my-address.php' ); ?>
						</div>

						<?php do_action( 'woocommerce_after_my_account' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>