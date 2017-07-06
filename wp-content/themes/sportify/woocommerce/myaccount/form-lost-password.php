<?php
/**
 * Lost password form
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
`           <div class="row">
                <div class="col-md-8">
                    <div class="woo-account ">
                        <?php wc_print_notices(); ?>    
                            <form method="post" class="woocommerce-ResetPassword lost_reset_password">

                                <p><?php echo apply_filters( 'woocommerce_lost_password_message', __( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ); ?></p>

                                <p class="woocommerce-FormRow woocommerce-FormRow--first form-row form-row-first">
                                    <label for="user_login"><?php _e( 'Username or email', 'woocommerce' ); ?></label>
                                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" />
                                </p>

                                <div class="clear"></div>

                                <?php do_action( 'woocommerce_lostpassword_form' ); ?>

                                <p class="woocommerce-FormRow form-row">
                                    <input type="hidden" name="wc_reset_password" value="true" />
                                    <input type="submit" class="woocommerce-Button button" value="<?php esc_attr_e( 'Reset Password', 'woocommerce' ); ?>" />
                                </p>

                                <?php wp_nonce_field( 'lost_password' ); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>