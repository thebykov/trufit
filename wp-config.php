<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //


/**
*  The name of the database for WordPress
*/
define('REVISR_GIT_PATH', ''); // Added by Revisr
define('DB_NAME', 'n71a3580342437');

/**
*  MySQL database username
*/
define('DB_USER', 'n71a3580342437');

/**
*  MySQL database username
*/
define('DB_PASSWORD', 'Tyt9P3!OtiV');

/**
*  MySQL hostname
*/
define('DB_HOST', 'n71a3580342437.db.3580342.hostedresource.com:3311');

/**
*  Database Charset to use in creating database tables.
*/
define('DB_CHARSET', 'utf8');

/**
*  The Database Collate type. Don't change this if in doubt.
*/
define('DB_COLLATE', '');

/**
*  WordPress Database Table prefix.
*  You can have multiple installations in one database if you give each a unique
*  prefix. Only numbers, letters, and underscores please!
*/
$table_prefix = 'wp_3anz23vaqs_';

/**
*  disallow unfiltered HTML for everyone, including administrators and super administrators. To disallow unfiltered HTML for all users, you can add this to wp-config.php:
*/
define('DISALLOW_UNFILTERED_HTML', false);

/**
*  
*/
define('ALLOW_UNFILTERED_UPLOADS', false);

/**
*  The easiest way to manipulate core updates is with the WP_AUTO_UPDATE_CORE constant
*/
define('WP_AUTO_UPDATE_CORE', true);

/**
*  forces the filesystem method
*/
define('FS_METHOD', 'direct');

/**
*  Authentication Unique Keys and Salts.
*  Change these to different unique phrases!
*  You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
*  You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
*  @since 2.6.0
*/
define('AUTH_KEY', 'vEhgCGaaaEg$GbmgU8p_');
define('SECURE_AUTH_KEY', '7$/T=z&Rv3wcKcAHy)-f');
define('LOGGED_IN_KEY', '&8)kJU31@Y4Nad&_HF*$');
define('NONCE_KEY', ' fL_fjCYgDYn%Y3MD2GH');
define('AUTH_SALT', '#JH UT/ax+&4RfwR6RmB');
define('SECURE_AUTH_SALT', 'JVJx(xT+dKCXCy6xKaD%');
define('LOGGED_IN_SALT', 'FJDt5K+U$qp=+1n7cP A');
define('NONCE_SALT', 'c#2wqbTz+-gbwWXcgkS(');

/**
*  For developers: WordPress debugging mode.
*  Change this to true to enable the display of notices during development.
*  It is strongly recommended that plugin and theme developers use WP_DEBUG
*  in their development environments.
*/
// define('WP_DEBUG', true);
// define('WP_DEBUG_LOG', true);
// define('WP_DEBUG_DISPLAY', false);

if(isset($_COOKIE['SCRT']) && $_COOKIE['SCRT'] == 'alex@alexbykov.com'){
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
}

// define('WP_DEBUG', true);
// define('WP_DEBUG_LOG', true);
// define('WP_DEBUG_DISPLAY', false);

ini_set('log_errors', 'On');
ini_set('error_log', '/home/content/p3nexnas05_data01/42/3580342/html/wp-content/php-errors.log');

/**
*  For developers: WordPress Script Debugging
*  Force Wordpress to use unminified JavaScript files
*/
// define('SCRIPT_DEBUG', false);

/**
*  WordPress Localized Language, defaults to English.
*  Change this to localize WordPress. A corresponding MO file for the chosen
*  language must be installed to wp-content/languages. For example, install
*  de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
*  language support.
*/
define('WPLANG', '');

/**
*  Setup Multi site
*/
define('WP_ALLOW_MULTISITE', false);

/**
*  Max Memory Limit
*/
define('WP_MAX_MEMORY_LIMIT', '1G');

/**
*  Post Autosave Interval
*/
define('AUTOSAVE_INTERVAL', 60);

/**
*  Disable / Enable Post Revisions and specify revisions max count
*/
define('WP_POST_REVISIONS', true);

/**
*  this constant controls the number of days before WordPress permanently deletes 
*  posts, pages, attachments, and comments, from the trash bin
*/
define('EMPTY_TRASH_DAYS', 30);

/**
*  Make sure a cron process cannot run more than once every WP_CRON_LOCK_TIMEOUT seconds
*/
define('WP_CRON_LOCK_TIMEOUT', 60);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
