<?php

    if( ! class_exists( 'WP_List_Table' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    }

    class postaccesscontroller_user_groups{

        public function __construct($pac_db, $pac_ui){
            $this->db = $pac_db;
            $this->ui = $pac_ui;
            $this->statuses = array('publish'=>'Active','trash'=>'Inactive');
            $this->public_inds = array( 'Y' => 'Public -- users can add themselves', 'N' => 'Private -- admin controls access');
            $this->post_defaults = array(
                           'post_type'      => 'pstacsctrlr_grp'
                          ,'post_status'    => array_keys( $this->statuses )
                          ,'orderby'        => 'title'
                          ,'posts_per_page' => -1
                          );            
            add_action( 'admin_menu', array( $this, 'admin_menu_setup' ) );
            add_action( 'init'      , array( $this, 'create_post_type' ) );
        }// __construct

        public function admin_menu_setup(){
            
            add_users_page( 'User Group Maintenance', 'User Groups', 'create_users', 'post-access-controller--groups-listing', array( $this, 'listing' ) );
            add_submenu_page( null, 'Group Maintenance', 'Group Maintenance', 'create_users', 'post-access-controller--group-edit', array( $this, 'edit' ) );
            add_submenu_page( null, 'Group Master Processing', 'Group Master Processing', 'create_users', 'post-access-controller--group-save', array( $this, 'save' ) );
            add_submenu_page( null, 'Group Master Processing', 'Group Master Processing', 'create_users', 'post-access-controller--group-archive', array( $this, 'archive' ) );

        }// admin_menu_setup

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

        public function listing(){

            // echo '5';

            $per_page = 2;

            //header
            $data['header_text']            = 'Post Access Controller';
            $data['add_new']                = ' <a href="' . get_bloginfo('wpurl') . '/wp-admin/users.php?page=post-access-controller--group-edit" class="add-new-h2">Add New</a>';

            //build the list table
            $data['list_table']             = new postaccesscontroller_groups_table();
            $data['list_table']->statuses   = $this->statuses;

            // $data['list_table']->table_data = $this->get_groups( $this->post_defaults );

            $data['list_table']->table_data = $this->db->group_masters_lkup(array( 'page'      => $data['list_table']->get_pagenum()
                                                                                  ,'per_page'  => $per_page
                                                                                  ,'order_by'  => ( array_key_exists( 'orderby', $_GET ) ? $_GET['orderby'] : '' )
                                                                                  ,'order'     => ( array_key_exists( 'order'  , $_GET ) ? $_GET['order']   : '' )
                                                                                  ,'filters'   => ( array_key_exists( 'filters', $_GET ) ? $_GET['filters'] : '' )
                                                                                ));

            //query for each status
            foreach( $this->statuses as $status => $label ):

                $args = array( 'post_type' => 'pstacsctrlr_grp','post_status' => $status );

                $groups = get_posts( array_merge( $this->post_defaults, $args ) );

                $data['list_table']->status_counts[$status] = count($groups);

            endforeach;

            $groups = get_posts( $this->post_defaults );

            $data['list_table']->status_counts['all'] = count($groups);

            $data['list_table']->paginagion_config =  array( 'total_items' => count($data['list_table']->table_data)
                                                            ,'per_page'    => $per_page);


            $data['list_table']->prepare_items();

            // $data['list_table']->search_box('search', 'search_id');

            require_once plugin_dir_path( __FILE__ ) . '../views/group_post_type/list.php';

            die();

        }// listing

        public function get_groups( $data = array() ){

            //filters are | delimited and then the key/value pairs are delimited by ~
            $filters = array();
            if( array_key_exists( 'filters', $data ) && count( $data['filters'] ) > 0 ):
                foreach( explode( '|', $data['filters'] ) as $filter ):
                    $filter_data = explode( '~', $filter );
                    $filters[$filter_data[0]] = $filter_data[1];
                endforeach;
            endif;

            //for things that have to be set but we didn't set the in whatever called this
            //certainly there are other things that can be set if needed
            $defaults = array(
                           'post_type'      => 'pstacsctrlr_grp'
                          ,'post_status'    => array_keys( $this->statuses )
                          ,'orderby'        => 'title'
                          ,'posts_per_page' => -1
                          );

            //merge the defaults with the incoming data but do the data second so it overwrites any defaults
            // print_r( array_merge( $defaults, $data, $filters ) );
            $group_masters    = get_posts( array_merge( $defaults, $data, $filters ) );

            return $group_masters;
        }        

        public function edit(){

            // echo '12';

            //header
            $pac['header_text']         = '<h2>Post Access Controller: Edit Group</h2>';

            //external files
            wp_enqueue_style( 'pca-styles', plugins_url().'/post-access-controller/css/admin-general.css' );

            if( array_key_exists( 'post_id', $_GET ) && $_GET['post_id'] ):
                $post_id = $_GET['post_id'];
            else:
                $post_id = 0;
            endif;

            //data
            $data                       = $this->db->group_master_lkup( array( 'post_id'  => $post_id
                                                                            ,'include_users'    => 'Y' ) );

            // print_r( $data['meta']['postaccesscontroller_public_ind'][0] );

            $pac['group_master']        = $data['group_master'];
            $pac['users']               = $data['users'];

            //breadcrumbs
            $pac['breadcrumbs']         = $this->ui->generate_breadcrumbs(array(
                                                array('label'   => 'User Groups'
                                                     ,'href'    => get_bloginfo('wpurl') . '/wp-admin/users.php?page=post-access-controller--groups-listing'),
                                                array('label'   => 'Group Maintenance'
                                                     ,'href'    => null)
                                          ));

            $data['fields'][]           = $this->ui->generate_form_table_line( array( 'field_label'   => 'Group Name'
                                                                                    , 'field_type'    => 'TEXT'
                                                                                    , 'current_value' => $pac['group_master']->post_title
                                                                                    , 'name'          => 'post_title'
                                                                                    , 'class'         => 'input-medium' ) );
            $data['fields'][]           = $this->ui->generate_form_table_line( array( 'field_label'   => 'Status'
                                                                                    , 'field_type'    => 'DROP_DOWN'
                                                                                    , 'current_value' => $pac['group_master']->post_status
                                                                                    , 'name'          => 'post_status'
                                                                                    , 'class'         => 'input-medium'
                                                                                    , 'values'        => $this->statuses ) );
            $data['fields'][]           = $this->ui->generate_form_table_line( array( 'field_label'   => 'Subscription'
                                                                                    , 'field_type'    => 'DROP_DOWN'
                                                                                    , 'current_value' => $data['meta']['postaccesscontroller_public_ind'][0]
                                                                                    , 'name'          => 'public_ind'
                                                                                    , 'values'        => $this->public_inds ) );
            $data['fields'][]           = $this->ui->generate_form_table_line( array( 'field_label'=> 'Members'
                                                                                    , 'field_type' => 'CHECKBOX_WELL'
                                                                                    , 'name'       => 'post_content'
                                                                                    , 'options'    => $pac['users'] ) );

            //call the view
            include_once plugin_dir_path( __FILE__ ) . '../views/group_post_type/edit.php';

        }

        public function save(){

            $post_data = array(
                'post_title'     => $_POST['post_title'],
                'post_content'   => implode( '|', array_values( array_key_exists( 'post_content', $_POST ) ? $_POST['post_content'] : array() ) ),
                'post_status'    => $_POST['post_status'],
                'post_type'      => 'pstacsctrlr_grp'
            );

            //are we adding a new post?
            if( empty( $_POST['post_id'] ) ):

                //this will return an object with an error if there is a problem
                //or the new ID if it worked
                $return          = wp_insert_post( $post_data, true );

                //check what came back
                if( is_object( $return ) ){

                    //capture the errors
                    foreach( $return->errors as $error ):
                        if( count( $error ) > 0 ):
                            $errors .= implode('<br>',$error);
                        endif;
                    endforeach;

                    //return those errors for display
                    $results = array( 'error'            => $errors
                                     );

                //everything went alright
                }else{

                    //set this so we can just do the "save users" part with the same variable
                    //whether we added a new post or updated an existing one
                    $return_id = $return;

                    $results = array( 'ID'               => $return_id
                                     ,'result'           => 'Group "' . $_POST['post_title'] . '" created successfully'
                                     ,'txn_type'         => 'INS'
                                     );
                }

            //or updating an existing one?
            else:
                $post_data['ID'] = $_POST['post_id'];
                $return_id       = wp_update_post( $post_data );
                if( $return_id == 0 ){
                    $results = array( 'ID'               => $_POST['ID']
                                     ,'error'            => 'Something happened that was not what we wanted (pac_group_form_process)'
                                     );
                }else{
                    $results = array( 'ID'               => $return_id
                                     ,'result'           => 'Group "' . $_POST['post_title'] . '" saved successfully'
                                     ,'txn_type'         => 'UPD'
                                     );
                }
            endif;

            //we want multiple records for the meta user so we delete all of them and re-write the currently indicated ones
            delete_post_meta( $return_id, 'postaccesscontroller_group_user' );

            //now that we've saved the group we need to save each user to post meta for that ID
            foreach( array_values( $_POST['post_content'] ) as $user ):

                add_post_meta( $return_id, 'postaccesscontroller_group_user', $user );

            endforeach;

            // public_ind logic
            update_post_meta($return_id, 'postaccesscontroller_public_ind', $_POST['public_ind']);

            if( array_key_exists( 'error', $results) ):
                echo '<div id="message" class="error"><p>'.$results['error'].'</p></div>';
            else:
                echo '<div id="message" class="updated"><p>'.$results['result'].'</p></div>';
            endif;

            ?>
            <div class="form-control">
                <a href="<?php get_bloginfo('wpurl'); ?>/wp-admin/users.php?page=post-access-controller--groups-listing" class="button button-large button-primary">Back to Group Listing</a>
            </div>
            <?php

        }

        function archive(){

            $form_result_data           = $this->db->pac_group_archive_process( $_GET );

            if( array_key_exists( 'error', $form_result_data) ):
                echo '<div id="message" class="error"><p>'.$form_result_data['error'].'</p></div>';
            else:
                echo '<div id="message" class="updated"><p>'.$form_result_data['result'].'</p></div>';
            endif;

            ?>
            <div class="form-control">
                <a href="<?php get_bloginfo('wpurl'); ?>/wp-admin/users.php?page=post-access-controller--groups-listing" class="button button-large button-primary">Back to Group Listing</a>
            </div>
            <?php

        }


	}//postaccesscontroller_user_groups

    class postaccesscontroller_groups_table extends WP_List_Table {

        public $post_defaults;
        public $statuses;
        public $table_data;
        public $status_counts;
        public $pagination_config;

        public function __construct(){

            parent::__construct();
            
        }

        function get_columns(){

          $columns = array( 'cb'            => '<input type="checkbox" />'
                          , 'post_title'    => 'Group'
                          , 'ID'            => 'ID'
                          , 'post_status'   => 'Status'
                          , 'subscription'  => 'Subscription Public'
                          , 'user_count'    => 'User Count'
                          );

          return $columns;

        }

        function prepare_items() {
            
            // COLUMNS
            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);

            // PAGINATION
            $this->set_pagination_args($this->paginagion_config);
            $current_page = $this->get_pagenum();
            $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;

            $this->process_bulk_action();
            $this->items = $this->table_data;

        }
        
        function extra_tablenav( $which ) {

            if ( $which == "top" ){

                $tablenav                   = array(
                                                    array( 'label' => 'All'
                                                          ,'href'  => get_bloginfo('wpurl') . '/wp-admin/users.php?page=post-access-controller--groups-listing'
                                                          ,'count' => $this->status_counts['all'] ),
                                                    array( 'label' => 'Active'
                                                          ,'href'  => get_bloginfo('wpurl') . '/wp-admin/users.php?page=post-access-controller--groups-listing&filters=post_status~publish'
                                                          ,'count' => $this->status_counts['publish'] ),
                                                    array( 'label' => 'Archived'
                                                          ,'href'  => get_bloginfo('wpurl') . '/wp-admin/users.php?page=post-access-controller--groups-listing&filters=post_status~trash'
                                                          ,'count' => $this->status_counts['trash'] )
                                              );
                if( is_array( $tablenav ) ){
                    $return = '<ul class="subsubsub">';
                    $return .= '<li><strong>Filters:</strong></li>';
                    foreach( $tablenav as $nav ):
                        $return .= '<li><a href="'.$nav['href'].'">'.$nav['label'].' <span class="count">('.$nav['count'].')</span></a></li>';
                    endforeach;
                    $return .= '</ul><!-- /.subsubsub -->';
                }

                echo $return;
            }

        }

        function get_sortable_columns() {

          $sortable_columns = array(
            'ID'           => array('ID',false),
            'post_title'   => array('post_title',false),
            'subscription' => array('subscription',false),
            'post_status'  => array('post_status',false),
            'user_count'   => array('user_count',false)
          );
          return $sortable_columns;

        }

        function get_bulk_actions() {

            $actions = array('archive' => 'Archive');
            return $actions;

        }

        function process_bulk_action() {

            require_once plugin_dir_path( __FILE__ ) . 'db.php';
            $pac_db     = new postaccesscontroller_db();

            $result = '';

            //Detect when a bulk action is being triggered...
            if( 'archive' === $this->current_action() ) {

                $result = '<div id="message" class="updated"><p>Groups archived:</p><ul>';

                foreach( $_GET['ID'] as $post_id ):
                    $results = $pac_db->pac_group_archive_process(array('post_id'=>$post_id));
                    $result .= '<li>'.$results['rslt'].'</li>';
                endforeach;
                $result .= '</ul></div>';

            }

            echo $result;

        }

        /* -------------------------------------------------------------------------------------------------------------
               COLUMNS
           ------------------------------------------------------------------------------------------------------------- */
        
        function column_default( $item, $column_name ) {

          switch( $column_name ) { 
            case 'ID':
            case 'post_title':
            case 'post_status':
            case 'user_count':
              return $item->$column_name;
            default:
              return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
          }

        }

        function column_cb($item) {

            return sprintf(
                '<input type="checkbox" name="%1$s[]" value="%2$s" />', 'ID', $item->ID
            );    

        }

        function column_post_title($item) {

            $title = sprintf('<a href="?page=%s&post_id=%s">%s</a>','post-access-controller--group-edit',$item->ID,$item->post_title);

            $actions['edit'] = sprintf('<a href="?page=%s&post_id=%s">Edit</a>','post-access-controller--group-edit',$item->ID);

            if( $item->post_status == 'publish' ):
                $actions['delete'] = sprintf('<a href="?page=%s&post_id=%s">Archive</a>','post-access-controller--group-archive',$item->ID);
            endif;
        
            return sprintf('%1$s %2$s', $title, $this->row_actions($actions) );

        }

        function column_post_status( $item ){

            return $this->statuses[$item->post_status];

        }

        function column_subscription( $item ){

            // print_r( $item );
            return get_post_meta( $item->ID, 'postaccesscontroller_public_ind', true );
            // return $this->statuses[$item->post_status];

        }

        function column_user_count( $item ){

            return count( explode( '|', $item->post_content ) );

        }
                        
    }// postaccesscontroller_gruops_table


/* End of file */
/* Location: ./post-access-controller/classes/core.php */