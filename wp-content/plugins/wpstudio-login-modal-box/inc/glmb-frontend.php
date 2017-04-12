<?php

defined( 'WPINC' ) or die;


//* Add login form
add_filter('genesis_after_footer', 'wpstudio_glmb_add_login_form', 100);
function wpstudio_glmb_add_login_form() {

	if ( genesis_get_option( 'glmb_loginurl', 'glmb-settings' ) ) {

        $login_url 		=  genesis_get_option( 'glmb_loginurl', 'glmb-settings' );

    }

    else {

        $login_url 		= get_the_permalink();

    }

     $login_title       =  genesis_get_option( 'glmb_title', 'glmb-settings' );


    echo '<div class="remodal" data-remodal-id="login">';
    echo '<p class="signin-title">' . $login_title . '</p>';
    echo '<button data-remodal-action="close" class="remodal-close ion-close" aria-label="Close"></button>';

    $referrer = $_SERVER['HTTP_REFERER'];

    if ( basename( $_SERVER['REQUEST_URI'] ) == '?' ) {

        echo '<div id="login-error">';
        echo 'Login failed: You have entered an incorrect Username or Password, please try again.';
        echo '</div>';

    }

    echo '<div class="login">';

    $args = array(
        'echo'           => true,
        'redirect'       => $login_url,
        'form_id'        => 'login',
        'label_username' => __( 'Username' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in'   => __( 'Log In' ),
        'id_username'    => 'log',
        'id_password'    => 'pwd',
        'id_remember'    => 'rememberme',
        'id_submit'      => 'wp-submit',
        'remember'       => true,
        'value_username' => '',
        'value_remember' => false
    );
    wp_login_form( $args );
    echo '</div>';
    echo '</div>';

}

//* Redirect logout to homepage
add_action('wp_logout','wpstudio_glmb_logout_url');
function wpstudio_glmb_logout_url(){

    if ( genesis_get_option( 'glmb_logouturl', 'glmb-settings' ) ) {
        $logout_url      =  genesis_get_option( 'glmb_logouturl', 'glmb-settings' );
    }
    else {
        $logout_url      = get_the_permalink();
    }

    wp_redirect( $logout_url );

  exit();

}

//* Add login logout button
add_filter( 'genesis_nav_items', 'wpstudio_add_login', 10, 2 );
add_filter( 'wp_nav_menu_items', 'wpstudio_add_login', 10, 2 );
function wpstudio_add_login($menu, $args) {

    $login_location =  genesis_get_option( 'glmb_position', 'glmb-settings' );

    $args = (array)$args;
    if ( $login_location !== $args['theme_location']  )
        return $menu;
    $logout = '<li class="menu-item logout"><a href="'.wp_logout_url( home_url() ).'" title="Logout">Log out</a></li>';
    $login  = '<li class="menu-item login"><a href="' . get_the_permalink() . '/#login" title="Login">Log in</a></li>';

    $logout = '<li class="menu-item logout"><a href="'.wp_logout_url( home_url() ).'" title="Logout">Log out</a></li>';
    $login  = '<li class="menu-item login"><a href="' . get_the_permalink() . '/#login" title="Login">Log in</a></li>';

    if ( has_filter('wpstudio_add_logout_filter' ) ) {
        $logout = apply_filters( 'wpstudio_add_logout_filter', $logout );
    }

    if ( has_filter('wpstudio_add_login_filter' ) ) {
        $login = apply_filters( 'wpstudio_add_login_filter', $login );
    }

    if ( is_user_logged_in())
        return $menu . $logout;

    else {
        return $menu . $login;
    }

}