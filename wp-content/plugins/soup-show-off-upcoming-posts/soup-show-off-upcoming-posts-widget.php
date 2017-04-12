<?php
/**
 * Plugin Name: SOUP - Show Off Upcoming Posts
 * Plugin URI: https://github.com/theukedge/soup-show-off-upcoming-posts
 * Description: Displays your upcoming posts to tease your readers
 * Version: 2.3
 * Author: Dave Clements
 * Author URI: https://www.theukedge.com
 * License: GPLv2
 * Text domain: soup-show-off-upcoming-posts
 *
 * @package SOUP
 * @author Dave Clements
 * @version 2.3
 */

/*
	Copyright 2016  Dave Clements  (email : https://www.theukedge.com/contact/)

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

/*
 * ---------------------------------- *
 * constants
 * ---------------------------------- *
*/

if ( ! defined( 'SOUP_PLUGIN_DIR' ) ) {
	define( 'SOUP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'SOUP_PLUGIN_URL' ) ) {
	define( 'SOUP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}


/*
 * ---------------------------------- *
 * includes
 * ---------------------------------- *
*/

include( SOUP_PLUGIN_DIR . 'includes/widget.php' );
