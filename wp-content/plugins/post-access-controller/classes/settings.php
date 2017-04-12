<?php

    class postaccesscontroller_settings{

        public function __construct($pac_db, $pac_ui){
            $this->db = $pac_db;
            $this->ui = $pac_ui;
            add_action( 'admin_init'                  , array( $this, 'admin_init_hooks' ) );
            add_action( 'show_user_profile'           , array( $this, 'user_settings' ) );
            add_action( 'edit_user_profile'           , array( $this, 'user_settings' ) );
            add_action( 'personal_options_update'     , array( $this, 'save_user_settings' ) );
            add_action( 'edit_user_profile_update'    , array( $this, 'save_user_settings' ) );

        }

        public function admin_init_hooks(){
            register_setting( 'postaccesscontroller-settings-group', 'meta_box_location');
            register_setting( 'postaccesscontroller-settings-group', 'meta_box_priority');
            register_setting( 'postaccesscontroller-settings-group', 'post_types');
            register_setting( 'postaccesscontroller-settings-group', 'access_denied_message');
            register_setting( 'postaccesscontroller-settings-group', 'enable_post_visibility');
        }

        public function user_settings(){

            // echo 'settings9';

            //external files
            wp_enqueue_style( 'pca-styles', plugins_url().'/post-access-controller/css/admin-general.css' );

            if( $_GET['user_id'] ):
                $user_id = $_GET['user_id'];
            else:
                $user_id = get_current_user_id();
            endif;

            $data['groups']             = $this->db->user_groups_lkup(array( 'user_id' => $user_id ) );

            // print_r( $data['groups'] );

            $data['group_well']         = $this->ui->generate_checkbox_well( array( 'name' => 'post_id'
                                                                                   ,'options' => $data['groups'] ) );

            //call the view
            require_once plugin_dir_path( __FILE__ ) . '../views/user-profile.php';
        }

        public function save_user_settings( $user_id ){

            if ( !current_user_can( 'edit_user', $user_id ) )
                return FALSE;

            $form_result_data           = $this->db->pac_user_form_process( $_POST );

            // if( array_key_exists( 'ERROR', $form_result_data ) ):
            //     echo '<div id="message" class="error"><ul>';
            //     foreach( $form_result_data['ERROR'] as $groupName => $error ):
            //         echo $groupName.': '.$error;
            //     endforeach;
            //     echo '</ul></div>';
            // endif;

            // if( array_key_exists( 'SUCCESS', $form_result_data ) ):
            //     echo '<div id="message" class="updated"><ul>';
            //     foreach( $form_result_data['SUCCESS'] as $groupName => $msg ):
            //         echo $groupName.': '.$msg;
            //     endforeach;
            //     echo '</ul></div>';
            // endif;


            // die();         

        }


	}//postaccesscontroller_settings

/* End of file */
/* Location: ./post-access-controller/classes/settings.php */