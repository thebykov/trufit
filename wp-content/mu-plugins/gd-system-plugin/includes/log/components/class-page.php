<?php

namespace WPaaS\Log\Components;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

final class Page extends Post {

	/**
	 * Run on load.
	 */
	protected function load() {

		foreach ( get_post_types( [ 'hierarchical' => false ] ) as $post_type ) {

			$this->excluded_post_types[] = $post_type;

		}

	}

	/**
	 * Include extra log meta on certain kinds of pages.
	 *
	 * @param WP_Post $post
	 * @param string  $old_status (optional)
	 *
	 * @return array
	 */
	protected function get_log_meta( $post, $old_status = null ) {

		$meta = parent::get_log_meta( $post, $old_status );

		// Only add WPEM meta if the site used WPEM
		if ( \WPaaS\Plugin::has_used_wpem() ) {

			// Whether the event occurred on a page that originated from WPEM
			// The post meta key for WPEM pages is `wpnux_page`
			$meta['wpem_id'] = ( $wpem_id = get_post_meta( $post->ID, 'wpnux_page', true ) ) ? $wpem_id : false;

		}

		// Only add Page Builder meta if the site used WPEM and the plugin is active
		if ( \WPaaS\Plugin::has_used_wpem() && class_exists( 'FLBuilder' ) ) {

			// Whether the page event was initiated by Page Builder
			$meta['pagebuilder'] = is_a( BeaverBuilder::get_post(), 'WP_Post' );

		}

		return $meta;

	}

}
