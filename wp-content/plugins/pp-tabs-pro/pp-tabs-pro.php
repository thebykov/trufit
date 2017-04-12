<?php
/*
Plugin Name: Ultimate Member - Tabs Pro
Plugin URI: https://plusplugins.com
Description: Use UM forms in custom profile tabs.
Author: PlusPlugins
Version: 1.0.1
Author URI: https://plusplugins.com
 */

define('PP_TABS_PRO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PP_TABS_PRO_VERSION', '1.0.1');
define('PP_TABS_PRO_STORE_URL', 'https://plusplugins.com');
define('PP_TABS_PRO_ITEM_NAME', 'Ultimate Member Tabs Pro');
define('PP_TABS_PRO_LICENSE_KEY', 'pp-tabs-pro-license-key');
define('PP_TABS_PRO_LICENSE_STATUS', 'pp-tabs-pro-license-status');

add_action('um_user_after_updating_profile', function ($to_update) {

	if (isset($_GET['profiletab']) && isset($_GET['um_action'])) {

		if ($_GET['profiletab'] != 'main' && $_GET['um_action'] == 'edit') {

			exit(wp_redirect(remove_query_arg('um_action', $_SERVER['REQUEST_URI'])));
		}
	}

}, 1000, 1);

add_action('admin_init', function () {

	if (get_option('pp-tabs-pro-version') == PP_TABS_PRO_VERSION) {
		return;
	}

	$theme_um_template_dir = get_stylesheet_directory() . '/ultimate-member/templates/';

	wp_mkdir_p($theme_um_template_dir);

	$templates = array_diff(scandir(PP_TABS_PRO_PLUGIN_DIR . 'templates'), array('.', '..'));

	foreach ($templates as $template) {
		copy(PP_TABS_PRO_PLUGIN_DIR . 'templates/' . $template, $theme_um_template_dir . $template);
	}

	update_option('pp-tabs-pro-version', PP_TABS_PRO_VERSION);

}, 1000);

register_deactivation_hook(__FILE__, function () {

	delete_option('pp-tabs-pro-version');

});

if (!class_exists('EDD_SL_Plugin_Updater')) {
	include PP_TABS_PRO_PLUGIN_DIR . 'EDD_SL_Plugin_Updater.php';
}

add_action('admin_init', function () {

	if (!function_exists('um_get_option')) {
		return;
	}

	$license_key = trim(um_get_option(PP_TABS_PRO_LICENSE_KEY));

	$edd_updater = new EDD_SL_Plugin_Updater(PP_TABS_PRO_STORE_URL, __FILE__, array(
		'version'   => PP_TABS_PRO_VERSION,
		'license'   => $license_key,
		'item_name' => PP_TABS_PRO_ITEM_NAME,
		'author'    => 'PlusPlugins',
		'url'       => home_url(),
	));

});

add_filter('um_licensed_products_settings', function ($array) {

	if (!function_exists('um_get_option')) {
		return;
	}

	$array[] = array(
		'id'       => PP_TABS_PRO_LICENSE_KEY,
		'type'     => 'text',
		'title'    => 'Tabs Pro License Key',
		'compiler' => true,
	);

	return $array;

});

add_filter('redux/options/um_options/compiler', function ($options, $css, $changed_values) {

	if (!function_exists('um_get_option')) {
		return;
	}

	if (isset($options[PP_TABS_PRO_LICENSE_KEY]) && isset($changed_values[PP_TABS_PRO_LICENSE_KEY]) && $options[PP_TABS_PRO_LICENSE_KEY] != $changed_values[PP_TABS_PRO_LICENSE_KEY]) {

		if ($options[PP_TABS_PRO_LICENSE_KEY] == '') {

			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $changed_values[PP_TABS_PRO_LICENSE_KEY],
				'item_name'  => urlencode(PP_TABS_PRO_ITEM_NAME), // the name of our product in EDD
				'url'        => home_url(),
			);

			$response = wp_remote_get(
				add_query_arg($api_params, PP_TABS_PRO_STORE_URL),
				array('timeout' => 30, 'sslverify' => false)
			);

			if (is_wp_error($response)) {
				return false;
			}

			$license_data = json_decode(wp_remote_retrieve_body($response));

			delete_option(PP_TABS_PRO_LICENSE_STATUS);

		} else {

			$license = trim($options[PP_TABS_PRO_LICENSE_KEY]);

			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $license,
				'item_name'  => urlencode(PP_TABS_PRO_ITEM_NAME), // the name of our product in EDD
				'url'        => home_url(),
			);

			$response = wp_remote_get(
				add_query_arg($api_params, PP_TABS_PRO_STORE_URL),
				array('timeout' => 30, 'sslverify' => false)
			);

			if (is_wp_error($response)) {
				return false;
			}

			$license_data = json_decode(wp_remote_retrieve_body($response));

			update_option(PP_TABS_PRO_LICENSE_STATUS, $license_data->license);

		}

	}

}, 10, 3);

?>
