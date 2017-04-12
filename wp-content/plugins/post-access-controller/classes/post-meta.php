<?php

    class postaccesscontroller_post_meta{

        public function __construct($pac_db, $pac_ui){
            $this->db = $pac_db;
            $this->ui = $pac_ui;
            add_action( 'add_meta_boxes'                            , array( $this, 'meta_boxes')    , 10, 2 );
            add_action( 'wp_ajax_post-access-controller--meta-user' , array( $this, 'user_select' )  , 10 );
            add_action( 'wp_ajax_post-access-controller--meta-group', array( $this, 'group_select' ) , 10 );
            add_action( 'wp_ajax_post-access-controller--meta-role' , array( $this, 'role_select' )  , 10 );
            add_action( 'admin_enqueue_scripts'                     , array( $this, 'admin_imports' ), 10, 1 );
            add_action( 'save_post'                                 , array( $this, 'save_post' ) );

        }

        public function admin_imports( $hook ){

            // global $post;

            if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
                wp_enqueue_style(  'postaccesscontroller-admin-general', plugins_url().'/post-access-controller/css/admin-general.css' );
                wp_enqueue_script( 'postaccesscontroller-meta-box-script', plugins_url().'/post-access-controller/js/meta-box.js' );
            }

        }

        public function meta_boxes( $post_type, $post ) {

            //check to see if $post_type is one that is configured to have this done
            $post_types    = get_option('post_types');

            //add the box
            if( is_array( $post_types ) && in_array( $post_type, $post_types ) ):

                //get the post type object for label, etc.
                $post_type_obj = get_post_type_object( $post_type );

                //add the box
                add_meta_box(
                    'postaccesscontroller-meta-box',                                //id
                    __( $post_type_obj->labels->singular_name . ' Access' ),        //label
                    array( $this, 'meta_box' ),                                     //function
                    $post_type,                                                     //type
                    get_option('meta_box_location'),                                //placement
                    get_option('meta_box_priority')                                 //priority
                );

            endif;

        }// meta_boxes

        public function meta_box( $post ){

            $ctrl_type                  = $this->ui->generate_form_table_line( array( 'field_label' => 'Control Method'
                                                                                    , 'field_type' => 'DROP_DOWN'
                                                                                    , 'current_value' => get_post_meta( $post->ID, 'postaccesscontroller_ctrl_type', true )
                                                                                    , 'name'          => 'postaccesscontroller_ctrl_type'
                                                                                    , 'id'            => 'postaccesscontroller_ctrl_type'
                                                                                    , 'values'        => array( 'none'  => 'Public'
                                                                                                               ,'user'  => 'By Individual'
                                                                                                               ,'group' => 'By Group'
                                                                                                               ,'role'  => 'By Role' ) ) );

            $msg_type                   = $this->ui->generate_form_table_line( array( 'field_label' => 'No Access Message'
                                                                                    , 'field_type' => 'DROP_DOWN'
                                                                                    , 'current_value' => get_post_meta( $post->ID, 'postaccesscontroller_noacs_msg_type', true )
                                                                                    , 'name'          => 'postaccesscontroller_noacs_msg_type'
                                                                                    , 'id'            => 'postaccesscontroller_noacs_msg_type'
                                                                                    , 'values'         => array( 'std'    => 'Default'
                                                                                                               ,'custom' => 'Custom' ) ) );

            if( get_post_meta( $post->ID, 'postaccesscontroller_noacs_msg_type', true ) == 'custom' ){
                $custom_msg_class = '';
                $std_msg_class    = 'hide';
            }else if( get_post_meta( $post->ID, 'postaccesscontroller_noacs_msg_type', true ) == 'std' ){
                $custom_msg_class = 'hide';
                $std_msg_class    = '';
            }else{
                $custom_msg_class = 'hide';
                $std_msg_class    = 'hide';
            }

            $data['postaccesscontroller_noacs_custom_msg'] = get_post_meta( $post->ID, 'postaccesscontroller_noacs_custom_msg', true );

            //external files
            wp_enqueue_style( 'pca-meta-styles', plugins_url().'/post-access-controller/css/meta-box.css' );

            //call the view
            include_once plugin_dir_path( __FILE__ ) . '../views/post_meta/box.php';

        }

        public function meta_options( $data ){

            $data['options']            = $this->db->meta_options_lkup( $data );

            if( $data['type'] == 'user' ):

                $data['list_label']     = 'Select Users';
                $data['label_field']    = 'display_name';

            elseif( $data['type'] == 'group' ):

                $data['list_label']     = 'Select Groups';
                $data['label_field']    = 'post_title';

            elseif( $data['type'] == 'role' ):

                $data['list_label']     = 'Select Roles';
                $data['label_field']    = 'role_name'; 

            endif;

            //get the current meta data and put it into an array
            $data['current']            = get_post_meta( $_POST['post_id'], 'postaccesscontroller_meta_'.$data['type'] );

            //call the view
            require_once plugin_dir_path( __FILE__ ) . '../views/post_meta/options.php';

            //need this so the wp_ajax call returns properly
            die();

        }

        public function user_select(){
            $this->meta_options( array( 'type' => 'user' ) );
        }

        public function group_select(){
            $this->meta_options( array( 'type' => 'group' ) );
        }

        public function role_select(){
            $this->meta_options( array( 'type' => 'role' ) );
        }

        public function save_post( $post_id ) {

          // Check if our nonce is set.
          if ( ! isset( $_POST['postaccesscontroller_sec_field_nonce'] ) )
            return $post_id;

          $nonce = $_POST['postaccesscontroller_sec_field_nonce'];

          // Verify that the nonce is valid.
          if ( ! wp_verify_nonce( $nonce, 'postaccesscontroller_sec_field' ) )
              return $post_id;

          // If this is an autosave, our form has not been submitted, so we don't want to do anything.
          if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
              return $post_id;

          // Check the user's permissions.
          if ( 'page' == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) )
                return $post_id;

          } else {

            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
          }

          /* OK, its safe for us to save the data now. */

          // Sanitize user input.
          $pac_ctrl_type = sanitize_text_field( $_POST['postaccesscontroller_ctrl_type'] );
          $postaccesscontroller_noacs_custom_msg = sanitize_text_field( $_POST['postaccesscontroller_noacs_custom_msg'] );

          // Update the meta field in the database.
          update_post_meta( $post_id, 'postaccesscontroller_ctrl_type', $pac_ctrl_type );

            //we want multiple records for the meta user so we delete all of them and re-write the currently indicated ones
            delete_post_meta( $post_id, 'postaccesscontroller_meta_user' );
            if( array_key_exists( 'postaccesscontroller_meta_user', $_POST ) ):
                foreach( $_POST['postaccesscontroller_meta_user'] as $pac_meta_user ):
                    add_post_meta( $post_id, 'postaccesscontroller_meta_user', $pac_meta_user );
                endforeach;
            endif;

            //and we want multiple records for the groups so we delete all of them and re-write the currently indicated ones
            delete_post_meta( $post_id, 'postaccesscontroller_meta_group' );
            if( array_key_exists( 'postaccesscontroller_meta_group', $_POST ) ):
                foreach( $_POST['postaccesscontroller_meta_group'] as $pac_meta_group ):
                    add_post_meta( $post_id, 'postaccesscontroller_meta_group', $pac_meta_group );
                endforeach;
            endif;

            //we want multiple records for the meta role so we delete all of them and re-write the currently indicated ones
            delete_post_meta( $post_id, 'postaccesscontroller_meta_role' );
            if( array_key_exists( 'postaccesscontroller_meta_role', $_POST ) ):
                foreach( $_POST['postaccesscontroller_meta_role'] as $pac_meta_role ):
                    add_post_meta( $post_id, 'postaccesscontroller_meta_role', $pac_meta_role );
                endforeach;
            endif;

          update_post_meta( $post_id, 'postaccesscontroller_noacs_msg_type', $_POST['postaccesscontroller_noacs_msg_type'] );
          update_post_meta( $post_id, 'postaccesscontroller_noacs_custom_msg', $postaccesscontroller_noacs_custom_msg );


        }        

	}// postaccesscontroller_post_meta

/* End of file */
/* Location: ./post-access-controller/classes/post-meta.php */