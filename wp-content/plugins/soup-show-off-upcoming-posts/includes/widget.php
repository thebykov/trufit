<?php
/**
 * SOUP widget
 *
 * Widgets for the SOUP plugin.
 *
 * @package		SOUP
 * @subpackage	Functions/Widgets
 * @copyright	Copyright (c) 2016
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since		1.0
 */

/**
 * Upcoming posts widget
 *
 * Creates a widget that displays upcoming posts.
 *
 * @since 1.0
 */
class SoupWidget extends WP_Widget {

	/**
	 * Sets up the SOUP widget
	 *
	 * @since      1.0
	 * @author     Dave Clements
	 * @return     void
	 */
	function SoupWidget() {
		parent::__construct( false, $name = esc_html__( 'Upcoming Posts', 'soup-show-off-upcoming-posts' ), array( 'description' => esc_html__( 'Displays your upcoming posts to entice your readers', 'soup-show-off-upcoming-posts' ) ) );
	}

	/**
	 * Widget output
	 *
	 * @since      1.0
	 * @author     Dave Clements
	 * @see        WP_Widget::widget
	 * @param      array $args Widget arguments.
	 * @param      array $instance Saved values from the database.
	 * @return     void
	 */
	function widget( $args, $instance ) {
		extract( $args );
		$title			= apply_filters( 'widget_title', $instance['title'] ); // The widget title.
		$soupnumber		= $instance['soup_number']; // The number of posts to show.
		$showdate		= $instance['show_date']; // Whether or not to show the scheduled post date.
		$showrss		= $instance['show_rss']; // Whether or not to show the RSS feed link.
		$postorder		= $instance['post_order']; // Display newest first or random order.
		$shownews		= isset( $instance['show_newsletter'] ) ? $instance['show_newsletter'] : false ; // Whether or not to show the newsletter link.
		$newsletterurl	= $instance['newsletter_url']; // URL of newsletter signup.
		$noresults		= $instance['no_results']; // Message for when there are no posts to display.

		// Before widget.
		echo wp_kses_post( $before_widget );

		// Title of widget.
		if ( $title ) { echo wp_kses_post( $before_title . $title . $after_title ); }

		echo "<u><h4>Released day before at 8pm</h4></u><br>";

		// Widget output.
		$args = array(
			'post_type' => 'wods',
			'posts_per_page'		=> $soupnumber,
			'no_paging'				=> '1',
			'post_status'			=> 'future',
			'order'					=> 'ASC',
			'orderby'				=> $postorder,
			'ignore_sticky_posts'	=> '1',
		);

		$soup_query = new WP_Query( $args );

		if ( $soup_query->have_posts() ) { ?>
			<ul class="no-bullets unstyled list-unstyled">
			<?php while ( $soup_query->have_posts() ) {
				$soup_query->the_post(); ?>
				<li>
					<?php the_title();
					if ( $showdate ) {
						echo esc_html( ' (' . get_the_time( get_option( 'date_format' ) ) . ')' );
					} ?>
				<br><br></li>
			<?php } ?>
			</ul>
			<?php wp_reset_postdata();
		} else {
			echo esc_html( $noresults );
		} ?>

		<?php if ( $showrss ) { ?>
		<p>
			<span class="dashicons dashicons-rss"></span>
			<?php printf(
				__( 'Don\'t miss it - <strong><a href="%1$s" title="%2$s">Subscribe by RSS</a></strong>', 'soup-show-off-upcoming-posts' ),
				esc_url( get_bloginfo( 'rss2_url' ) ),
				sprintf( esc_html__( 'Subscribe to %s', 'soup-show-off-upcoming-posts' ),
					esc_html( get_bloginfo( 'name' ) )
				)
			); ?>
		</p>
		<?php } ?>

		<?php if ( $shownews ) { ?>
		<p>
			<?php printf(
				__( 'Or just <strong><a href="%1$s" title="%2$s">subscribe to the newsletter</a></strong>', 'soup-show-off-upcoming-posts' ),
				esc_url( $newsletterurl ),
				sprintf(
					esc_html__( 'Subscribe to %s newsletter', 'soup-show-off-upcoming-posts' ),
					esc_html( get_bloginfo( 'name' ) )
				)
			); ?>
		</p>
		<?php }

	// After widget.
		echo wp_kses_post( $after_widget );
	}

	/**
	 * Sanitize and save widget control panel settings
	 *
	 * @since      1.0
	 * @author     Dave Clements <dave@theukedge.com>
	 * @see        WP_Widget::update
	 * @param      array $new_instance New settings.
	 * @param      array $old_instance Saved setting from the database.
	 * @return     array Sanitized settings to be saved to database
	 */
	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['soup_number'] = intval( $new_instance['soup_number'] );
		$instance['show_date'] = strip_tags( $new_instance['show_date'] );
		$instance['show_rss'] = strip_tags( $new_instance['show_rss'] );
		$instance['post_order'] = strip_tags( $new_instance['post_order'] );
		$instance['show_newsletter'] = strip_tags( $new_instance['show_newsletter'] );
		$instance['newsletter_url'] = strip_tags( $new_instance['newsletter_url'],'<a>' );
		$instance['no_results'] = strip_tags( $new_instance['no_results'] );
		return $instance;
	}

	/**
	 * The widget control panel form
	 *
	 * @since      1.0
	 * @author     Dave Clements
	 * @see        WP_Widget::form
	 * @param      array $instance The saved settings in the database.
	 * @return     void
	 */
	function form( $instance ) {

		$defaults = array(
			'title'				=> esc_html__( 'Upcoming Posts', 'soup-show-off-upcoming-posts' ),
			'soup_number'		=> 3,
			'show_date'			=> 'off',
			'show_rss'			=> 'off',
			'post_order'		=> 'date',
			'show_newsletter'	=> 'off',
			'newsletter_url'	=> '',
			'no_results'		=> esc_html__( 'Sorry - nothing planned yet!', 'soup-show-off-upcoming-posts' ),
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'soup-show-off-upcoming-posts' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>'" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'soup_number' ) ); ?>"><?php esc_html_e( 'Number of upcoming posts to display', 'soup-show-off-upcoming-posts' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'soup_number' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'soup_number' ) ); ?>" type="text" value="<?php echo intval( $instance['soup_number'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Show post date', 'soup-show-off-upcoming-posts' ); ?>?</label>
			<input <?php checked( $instance['show_date'], 'on' ); ?> id="<?php echo esc_html( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'show_date' ) ); ?>" type="checkbox" />
		</p>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'show_rss' ) ); ?>"><?php esc_html_e( 'Show RSS link', 'soup-show-off-upcoming-posts' ); ?>?</label>
			<input <?php checked( $instance['show_rss'], 'on' ); ?> id="<?php echo esc_html( $this->get_field_id( 'show_rss' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'show_rss' ) ); ?>" type="checkbox" />
		</p>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'post_order' ) ); ?>"><?php esc_html_e( 'Sort order', 'soup-show-off-upcoming-posts' ); ?>:</label>
			<select id="<?php echo esc_html( $this->get_field_id( 'post_order' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'post_order' ) ); ?>" class="widefat" style="width:100%;">
				<option value="date" <?php selected( 'date', $instance['post_order'] ); ?>><?php esc_html_e( 'Next post first', 'soup-show-off-upcoming-posts' ); ?></option>
				<option value="rand" <?php selected( 'rand', $instance['post_order'] ); ?>><?php esc_html_e( 'Random order', 'soup-show-off-upcoming-posts' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'no_results' ) ); ?>"><?php esc_html_e( 'Message to display for no results', 'soup-show-off-upcoming-posts' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'no_results' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'no_results' ) ); ?>" type="text" value="<?php echo esc_html( $instance['no_results'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'show_newsletter' ) ); ?>"><?php esc_html_e( 'Show Newsletter', 'soup-show-off-upcoming-posts' ); ?>?</label>
			<input <?php checked( $instance['show_newsletter'], 'on' ); ?> id="<?php echo esc_html( $this->get_field_id( 'show_newsletter' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'show_newsletter' ) ); ?>" type="checkbox" />
		</p>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'newsletter_url' ) ); ?>"><?php esc_html_e( 'Newsletter URL', 'soup-show-off-upcoming-posts' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'newsletter_url' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'newsletter_url' ) ); ?>" type="text" value="<?php echo esc_url( $instance['newsletter_url'] ); ?>" />
		</p>
	<?php }
}

add_action( 'widgets_init', function() { return
	register_widget( 'SoupWidget' );
} );
