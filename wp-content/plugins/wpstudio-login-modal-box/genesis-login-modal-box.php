<?php
/*
 Plugin Name: Genesis login modal box
 Plugin URI: http://wpstud.io/plugins
 Description: Login modal box
 Version: 1.1.1
 Author: Frank Schrijvers
 Author URI: http://www.wpstud.io
 Text Domain: glmb
 License: GPLv2

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'WPINC' ) or die;

define( 'GLMB_PlUGIN_VERSION', '1' );
define( 'GLMB_RELEASE_DATE', 'february, 2015' );

register_activation_hook( __FILE__, 'wps_glmb_activation_check' );
/**
 * This function runs on plugin activation. It checks to make sure the required
 * minimum Genesis version is installed. If not, it deactivates itself.
 */
function wps_glmb_activation_check() {

	$latest = '2.0';
	$theme_info = wp_get_theme( 'genesis' );

	if ( ! function_exists('genesis_pre') ) {
		deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate plugin
		wp_die( sprintf( __( 'Sorry, you can\'t activate %1$sGenesis Slide-in Widget unless you have installed the %3$sGenesis Framework%4$s. Go back to the %5$sPlugins Page%4$s.', 'genesis-overlay-widget' ), '<em>', '</em>', '<a href="http://www.studiopress.com/themes/genesis" target="_blank">', '</a>', '<a href="javascript:history.back()">' ) );
	}

	if ( version_compare( $theme_info['Version'], $latest, '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate plugin
		wp_die( sprintf( __( 'Sorry, you can\'t activate %1$sGenesis Slide-in Widget unless you have installed the %3$sGenesis %4$s%5$s. Go back to the %6$sPlugins Page%5$s.', 'genesis-overlay-widget' ), '<em>', '</em>', '<a href="http://www.studiopress.com/themes/genesis" target="_blank">', $latest, '</a>', '<a href="javascript:history.back()">' ) );
	}

}

add_action('after_switch_theme', 'wps_glmb_deactivate_check');
function wps_glmb_deactivate_check() {

    if ( ! function_exists('genesis_pre') ) {
		deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate plugin
    }

}

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'wps_glmb_load_scripts', 99);
function wps_glmb_load_scripts() {

	wp_enqueue_script( 'remodal', plugin_dir_url( __FILE__ ) . '/assets/js/remodal.js', array( 'jquery' ) );
	wp_enqueue_style( 'glmb-style', plugin_dir_url( __FILE__ ) . '/assets/css/wpstudio-glmb-style.css', array());
	wp_enqueue_style( 'dashicons' );

}

add_action( 'genesis_admin_init', 'wps_glmb_init');
function wps_glmb_init() {

	require( dirname( __FILE__ )  . '/inc/glmb-admin.php');
	include( dirname( __FILE__ ) . '/inc/glmb-frontend.php');
	new WPSTUDIO_glmb_Settings();

}

//* Add shortcode
add_shortcode( 'wps_login', 'wps_glmb_shortcode' );
function wps_glmb_shortcode( $atts, $content = null ) {

    return '<a href="#login" title="login" class="login">' . $content . '</a>';

}


//* If username or password is wrong redirect
add_action( 'wp_login_failed', 'wps_login_fail' );
function wps_login_fail( $username ) {

     $referrer = $_SERVER['HTTP_REFERER'];

     if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {

     	$referrer = $_SERVER['HTTP_REFERER'];

         wp_redirect(home_url() . '/?#login' );
         //wp_redirect( $referrer . '/?#login' );

          exit;
     }

}