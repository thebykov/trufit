<?php
/**
 * Plugin Name: Post Access Controller
 * Plugin URI:  http://arsdehnel.net/plugin/post-access-controller/
 * Description: Allow control of access to individual posts by setting individual users, user groups or roles to have access
 * Version:     1.1.1
 * Author:      Adam Dehnel
 * Author URI:  http://arsdehnel.net/
 * License:     GPLv2 or later
 *
 *
 *
*/

global $postaccesscontroller_statuses;
$postaccesscontroller_statuses = array('publish'=>'Active','trash'=>'Inactive');

define('POSTACCESSCONTROLLER_DIR', dirname(__FILE__));
define('POSTACCESSCONTROLLER_DEBUG', false);

include_once(POSTACCESSCONTROLLER_DIR.'/classes/db.php');
include_once(POSTACCESSCONTROLLER_DIR.'/classes/ui.php');
include_once(POSTACCESSCONTROLLER_DIR.'/classes/core.php');
include_once(POSTACCESSCONTROLLER_DIR.'/classes/checks.php');
include_once(POSTACCESSCONTROLLER_DIR.'/classes/post-meta.php');
include_once(POSTACCESSCONTROLLER_DIR.'/classes/user-groups.php');
include_once(POSTACCESSCONTROLLER_DIR.'/classes/settings.php');

//get all the things rolling
add_action( 'init', 'postaccesscontroller_init' );

function postaccesscontroller_init() {
	
	$pac_db = new postaccesscontroller_db();
    $pac_ui = new postaccesscontroller_ui();
    $pac_core = new postaccesscontroller_core($pac_db, $pac_ui);
	$pac_checks = new postaccesscontroller_checks($pac_core,$pac_db);
    $pac_user_groups = new postaccesscontroller_user_groups($pac_db, $pac_ui);
	$pac_post_meta = new postaccesscontroller_post_meta($pac_db,$pac_ui);
	$pac_settings = new postaccesscontroller_settings($pac_db,$pac_ui);

}

