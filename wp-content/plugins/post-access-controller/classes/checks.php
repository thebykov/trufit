<?php

    class postaccesscontroller_checks{

    	public function __construct($pac_core, $pac_db){
    		$this->core = $pac_core;
    		$this->db = $pac_db;
			add_action( 'the_post'     , array( $this, 'post_check' ), 10, 1 );
			add_filter( 'the_content'  , array( $this, 'content_check' ), 10, 1 );
			add_filter( 'posts_join'   , array( $this, 'posts_join') );
			add_filter( 'posts_where'  , array( $this, 'posts_where') );
    	}

		/**
		 * Returns the appropriate "no access" message for the indicated post ID
		 *
		 * @access private
		 * @since 0.9.0
		 * 
		 * @param  number $post_id  The ID of the post that we're showing the message for
		 * @return string           The message to show the user 
		 */
    	private function no_access_msg( $post_id ){
	        $msg_type = get_post_meta( $post_id, 'postaccesscontroller_noacs_msg_type', true);

	        //change the post object to show the message the user setup
	        if( $msg_type == 'std' ):
	        	return get_option('access_denied_message');
	        else:
	            return get_post_meta( $post_id, 'postaccesscontroller_noacs_custom_msg', true);
	        endif;
    	}

		/**
		 * Handles the_content() function and makes sure that even if a user that should *not* have access
		 * gets to the page that shows the content we intercept that function and return the no access message instead
		 *
		 * @access public
		 * @since 0.9.0
		 * 
		 * @param  string $content  The content as it's currently going to be displayed to the user
		 * @return string           Either the content (if the user should see it) or the no access message
		 */
    	public function content_check( $content ){

    		global $post;

    		if( !$this->db->post_access_allow_check( $post ) ){
    			return $this->no_access_msg( $post->ID );
    		}

    		return $content;

    	}

		/**
		 * Handles get_posts() function calls and other calls like it (using the appropriate WP query object protocol) 
		 * and replaces the content with the no access messaging
		 *
		 * @access public
		 * @since 0.9.0
		 * 
		 * @param  object $post_obj  The object containing the post that we're currently displaying
		 * @return object            That same post object that possibly has been modified to show no access messaging
		 */
		public function post_check( $post_obj ){

		    if( !$this->db->post_access_allow_check( $post_obj ) ){
		        $msg_type = get_post_meta( $post_obj->ID, 'postaccesscontroller_noacs_msg_type', true);
		        $post_obj->post_title   = 'Access Denied';
		        $post_obj->post_status  = 'private';
		        // $post_obj->post_type    = 'noaccess';

		        $post_obj->post_content = $this->no_access_msg( $post_obj->ID );
		        $post_obj->post_excerpt = $this->no_access_msg( $post_obj->ID );
 
		        //COMMENTS handling code
					// Kill the comments template. This will deal with themes that don't check comment stati properly!
					add_filter( 'comments_template', array( $this->core, 'comments_template_override' ), 20 );
					// Remove comment-reply script for themes that include it indiscriminately
					wp_deregister_script( 'comment-reply' );

		    }

		    return $post_obj;

		}// post_check

		/**
		 * One half of the code that intercepts the get_posts() query of the database and adds the post access controller logic into that query
		 *
		 * @access public
		 * @since 0.9.0
		 * 
		 * @param  string $clause  The incoming "join" clause that we might be adding to or might be creating if this is the first plugin making this call
		 * @return string          The outgoing "join" clause that has our extra query bits incorporated
		 */
		public function posts_join( $clause = '' ) {
		    global $wpdb;

		    // We join the postmeta table so we can check the value in the WHERE clause.
		    $clause .= " LEFT JOIN $wpdb->postmeta AS postaccesscontroller_ctrl_type ON ($wpdb->posts.ID = postaccesscontroller_ctrl_type.post_id AND postaccesscontroller_ctrl_type.meta_key = 'postaccesscontroller_ctrl_type') ";

		    return $clause;
		}

		/**
		 * The second half of the code that intercepts the get_posts() query of the database and adds the post access controller logic into that query
		 *
		 * @access public
		 * @since 0.9.0
		 * 
		 * @param  string $clause  The incoming "where" clause that we might be adding to or might be creating if this is the first plugin making this call
		 * @return string          The outgoing "where" clause that has our extra query bits incorporated
		 */
		public function posts_where( $clause = '' ) {

		    global $wpdb;

		    // a few weird scenarios handled here where we don't want to monkey with the where clause at all so that it returns the full post object
		    // if the user is not meant to see the particular post object for one of these scenarios then we will still replace the post title and content
		    // with the appropriate no access messaging but the page won't be broken which makes for a better user experience
		    //
		    // 1. if the user is currently in the admin (is_admin function) and the user is a administrator
		    // 2. if the user managed to get to the "single" page for a post
		    // 3. if the user managed to get to a page 
		    if( ( is_admin() && $this->db->check_user_role( 'administrator' ) ) || is_single() || is_page() ){

		    	$clause .= '';

		    }else{

			    // prep
			    $clause .= " AND ( ";

			    //then we'll do each "way" that users could have access to this and wrap each in their own parentheses

			    /*********************************************************
		            NO PAC SET
			     ********************************************************/
					$clause .= "( postaccesscontroller_ctrl_type.meta_value = 'none' )";

			    /*********************************************************
		            PUBLIC / NONE
			     ********************************************************/
					$clause .= " or ";
					$clause .= "( postaccesscontroller_ctrl_type.meta_value is null )";

			    /*********************************************************
		            USER
			     ********************************************************/
				    $clause .= " or ";

				    //put this in a subquery for clarity of what we're doing
				    //this gets all the meta_values (ie user_ids) from each post's metadata
				    $user_subquery = "select meta_value from $wpdb->postmeta where post_id = $wpdb->posts.ID and meta_key = 'postaccesscontroller_meta_user'";

				    //and then if the current post access control type is set to 'user' we check that query for the current user's ID
					$clause .= "( postaccesscontroller_ctrl_type.meta_value = 'user' and ".get_current_user_id()." in ($user_subquery) )";

			    /*********************************************************
		            GROUP
			     ********************************************************/
				    $clause .= " or ";

				    //build this piece by piece so it's more understandable what we're doing
				    //we'll start with getting just the query for all postmeta records for the current post that will tell us what "group" posts to get
				    $group_query = "select meta_value from $wpdb->postmeta where post_id = $wpdb->posts.ID and meta_key = 'postaccesscontroller_meta_group'";

				    //so then we need to get all the "user" meta values from the sum of all of those groups
				    $group_query = "select meta_value from $wpdb->postmeta where post_id in ($group_query) and meta_key = 'postaccesscontroller_group_user'";

				   	//and finally we can use that query to be the inner query here where we need to compare the current user ID to a list of "allowed" IDs
					$clause .= "( postaccesscontroller_ctrl_type.meta_value = 'group' and ".get_current_user_id()." in ($group_query))";

			    /*********************************************************
		            ROLE
			     ********************************************************/
				    $clause .= " or ";

				    // build this piece by piece so it's more understandable what we're doing
				    // first we're going to build a "query" out of the current user's roles
				    $role_query = '';
					foreach( wp_get_current_user()->roles as $idx => $role ):
						$role_query .= "SELECT '".$role."' FROM dual";
						if( $idx + 1 < count( wp_get_current_user()->roles ) ): 
							$role_query .= " UNION ALL ";
						endif;
					endforeach;

				    // this will get us the roles that have been selected to see this post and see if our current user has one of them
				    $role_query  = "select count(1) from $wpdb->postmeta where post_id = $wpdb->posts.ID and meta_key = 'postaccesscontroller_meta_role' and meta_value in ($role_query)";

				   	//and finally we take that and put it into our overall where clause as a subquery
					$clause .= "( postaccesscontroller_ctrl_type.meta_value = 'role' and 0 < ($role_query))";

			    //close
			    $clause .= " ) ";

		    }

		    return $clause;

		}

	}//postaccesscontroller_checks	

/* End of file */
/* Location: ./post-access-controller/classes/checks.php */