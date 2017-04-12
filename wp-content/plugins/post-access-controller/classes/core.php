<?php

    class postaccesscontroller_core{

        public function __construct($pac_db, $pac_ui){
            $this->db = $pac_db;
            $this->ui = $pac_ui;
            add_action( 'admin_menu', array( $this, 'admin_menu_setup' ) );
        }// __construct

        public function admin_menu_setup(){
            
            // this adds the page (Settings->Access Controls) to maintain the PAC options for the plugin itself
            add_options_page( 'Access Controls', 'Access Controls', 'edit_plugins', 'post-access-controller--options', array( $this, 'options' ) );

        }// admin_menu_setup

        public function options(){

            //header
            $data['header_text']        = '<h1>Post Access Controller: Options</h1>';

            $post_types                 = get_post_types( array( 'public' => true ), 'object' );
            $pac_post_types             = is_array( get_option('post_types') ) ? get_option('post_types') : array();

            foreach( $post_types as $post_type => $post_type_obj ):
                if( in_array( $post_type, $pac_post_types ) ):
                    $selected = 'Y';
                else:
                    $selected = 'N';
                endif;
                $well_data[]            = array( 'value'    => $post_type
                                                ,'label'    => $post_type_obj->labels->name
                                                ,'selected' => $selected );
            endforeach;


            $overall['fields'][]        = $this->ui->generate_form_table_line( array( 'field_label'   => 'Post Types'
                                                                                    , 'field_type'    => 'CHECKBOX'
                                                                                    , 'name'          => 'post_types'
                                                                                    , 'options'       => $well_data ) );

            $overall['fields'][]        = $this->ui->generate_form_table_line( array( 'field_label'   => 'Access Denied Message'
                                                                                    , 'field_type'    => 'TEXTAREA'
                                                                                    , 'name'          => 'access_denied_message'
                                                                                    , 'current_value' => get_option('access_denied_message')
                                                                                    , 'class'         => 'input-large input-textareaheight-medium' ) );

            $post_maint['fields'][]     = $this->ui->generate_form_table_line( array( 'field_label'   => 'Location'
                                                                                    , 'field_type'    => 'DROP_DOWN'
                                                                                    , 'current_value' => get_option('meta_box_location')
                                                                                    , 'name'          => 'meta_box_location'
                                                                                    , 'values'        => array('normal'    => 'Below post editor field'
                                                                                                              ,'advanced'  => 'Only as option enabled in the "Screen Options" panel'
                                                                                                              ,'side'      => 'Along right side') ) );

            $post_maint['fields'][]     = $this->ui->generate_form_table_line( array( 'field_label'   => 'Priority'
                                                                                    , 'field_type'    => 'DROP_DOWN'
                                                                                    , 'current_value' => get_option('meta_box_priority')
                                                                                    ,'name'           => 'meta_box_priority'
                                                                                    ,'values'         => array('high'      => 'High'
                                                                                                              ,'core'      => 'Core'
                                                                                                              ,'default'   => 'Default'
                                                                                                              ,'low'       => 'Low') ) );

            $post_maint['fields'][]     = $this->ui->generate_form_table_line( array( 'field_label'   => 'Visibility'
                                                                                    , 'field_type'    => 'DROP_DOWN'
                                                                                    , 'current_value' => get_option('enable_post_visibility')
                                                                                    , 'name'          => 'enable_post_visibility'
                                                                                    , 'values'        => array('hidden'     => 'Disable'
                                                                                                              ,'visible'    => 'Enable') ) );

            //external files
            wp_enqueue_style( 'pca-styles', plugins_url().'/post-access-controller/css/admin-general.css' );

            //call the view
            require_once(plugin_dir_path( __FILE__ ) . '../views/settings.php');

        }// options

        public function comments_template_override(){
            return dirname( __FILE__ ) . '../views/comments-template.php';
        }

	}//postaccesscontroller_core

/* End of file */
/* Location: ./post-access-controller/classes/core.php */