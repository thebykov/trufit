<?php

define( 'GD_VIP', '166.62.107.121' );
define( 'GD_RESELLER', 1 );
define( 'GD_ASAP_KEY', '173bcc34830373ce4f5ae43eaa6afe5b' );
define( 'GD_STAGING_SITE', false );
define( 'GD_EASY_MODE', false );
define( 'GD_SITE_CREATED', 1473507344 );

// Newrelic tracking
if ( function_exists( 'newrelic_set_appname' ) ) {
	newrelic_set_appname( '33f78c19-33f3-414b-a383-3aeabf96dd82;' . ini_get( 'newrelic.appname' ) );
}

/**
 * Is this is a mobile client?  Can be used by batcache.
 * @return array
 */
function is_mobile_user_agent() {
	return array(
	       "mobile_browser"             => !in_array( $_SERVER['HTTP_X_UA_DEVICE'], array( 'bot', 'pc' ) ),
	       "mobile_browser_tablet"      => false !== strpos( $_SERVER['HTTP_X_UA_DEVICE'], 'tablet-' ),
	       "mobile_browser_smartphones" => in_array( $_SERVER['HTTP_X_UA_DEVICE'], array( 'mobile-iphone', 'mobile-smartphone', 'mobile-firefoxos', 'mobile-generic' ) ),
	       "mobile_browser_android"     => false !== strpos( $_SERVER['HTTP_X_UA_DEVICE'], 'android' )
	);
}
define( 'REDIS_SOCKET', '/var/run/RedisProxy/mwprp.sock' );
