<?php

    class postaccesscontroller_group_post_type{

    	public function __construct(){
    		add_action('init', array( $this, 'create_post_type' ) );
    	}

     	private function create_post_type(){

		    register_post_type( 'pstacsctrlr_grp',
		        array(
		            'labels' => array(
		                'name'          => __( 'Access Control Groups' ),
		                'singular_name' => __( 'Access Control Group' ),
		                'add_new_item'  => __( 'Create Access Control Group' ),
		                'edit_item'     => __( 'Edit Access Control Group' )
		            ),
		            'description'           => 'Name of the access control group and an array of users',
		            'public'                => false,
		            'exclude_from_search'   => true,
		            'supports'              => array( 'title' )
		        )
		    );
		    flush_rewrite_rules();

       	}

	}//postaccesscontroller_group_post_type

/* End of file */
/* Location: ./post-access-controller/classes/post-types.php */