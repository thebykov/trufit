<?php

    if( ! class_exists( 'WP_List_Table' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    }
    
    class postaccesscontroller_list_table extends WP_List_Table {
        
        public function __construct( $args ){

            // print_r( $args );
            $this->tablenav_top = array( 'top'    => $args['tablenav_top']
                                        ,'bottom' => array_key_exists( 'tablenav_bottom', $args ) ? $args['tablenav_bottom'] : null );

            $this->table_data = array_key_exists( 'table_data', $args ) ? $args['table_data'] : '';
            $this->status_counts = $args['status_counts'];

            global $postaccesscontroller_statuses;
            $this->statuses = $postaccesscontroller_statuses;
            parent::__construct();
            
        }

        function get_columns(){
          $columns = array( 'cb'            => '<input type="checkbox" />'
                          , 'post_title'    => 'Group'
                          , 'ID'            => 'ID'
                          , 'post_status'   => 'Status'
                          , 'user_count'    => 'User Count'
                          );
          return $columns;
        }

        public function import_table_data( $data ){
            $this->table_data = $data;
        }

        function prepare_items() {
            
            print_r( $this->table_data );
            echo 'this thing11';

            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns(); //array();
            $this->_column_headers = array($columns, $hidden, $sortable);

            // $current_page = $this->get_pagenum();
            // $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
            // $total_items = $this->status_counts['all'];
            // $this->data = $this->table_data;

            $rec = new stdClass();
            $rec->ID = 'hooray';
            $rec->post_status = $this->column_post_status( 'something' );
            $rec->user_count = 'what';
            $rec->post_content = '0|4|13|';
            $rec->post_title = $this->column_user_count( $rec );


            //merge the defaults with the incoming data but do the data second so it overwrites any defaults
            // print_r( array_merge( $defaults, $data, $filters ) );
            // $group_masters    = get_posts( array_merge( $defaults, $data, $filters ) );
            // $this->items = get_posts( $this->data_defaults );
            $this->items = $this->table_data;


            // $this->items = array($rec);
        }
        
        public function pre3333pare_items( $data ) {

            $per_page = $data['per_page'];

            $this->process_bulk_action();
            // $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
            // $total_items = $data['total_items'];
            $this->set_pagination_args( array(
                                                'total_items' => $total_items,
                                                'per_page'    => $per_page
                                            ) );
            $this->items = $data['table_data'];
        
        }
        
        function extra_tablenav( $which ) {

            if ( $which == "top" ){
                echo $this->tablenav_top;
            }
            // if ( $which == "bottom" ){
            //     //The code that goes after the table is there
            //     //echo"Hi, I'm after the table";
            // }
        }

        function get_sortable_columns() {
          $sortable_columns = array(
            'ID'  => array('ID',false),
            'post_title' => array('post_title',false),
            'post_status' => array('post_status',false),
            'user_count'   => array('user_count',false)
          );
          return $sortable_columns;
        }

        function get_bulk_actions() {
            $actions = array(
                            'archive'    => 'Archive'
            );
            return $actions;
        }
        function process_bulk_action() {

            require_once plugin_dir_path( __FILE__ ) . 'db.php';
            $pac_db     = new postaccesscontroller_db();

            $result = '';

            //Detect when a bulk action is being triggered...
            if( 'archive' === $this->current_action() ) {

                $result = '<div id="message" class="updated"><p>Groups archived:</p><ul>';

                foreach( $_GET['post_id'] as $post_id ):
                    $results = $pac_db->pac_group_archive_process(array('post_id'=>$post_id));
                    $result .= '<li>'.$results['mstr_rslt'].'</li>';
                endforeach;
                $result .= '</ul></div>';

            }

            // echo $result;

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
          $actions = array(
                    'edit'      => sprintf('<a href="?page=%s&post_id=%s">Edit</a>','post-access-controller--edit',$item->ID),
                );

            if( $item->post_status == 'publish' ):
                $actions['delete'] = sprintf('<a href="?page=%s&post_id=%s">Archive</a>','post-access-controller--archive',$item->ID);
            endif;
        
          return sprintf('%1$s %2$s', $item->post_title, $this->row_actions($actions) );
        }

        function column_post_status( $item ){
            return 'What';
            // return $this->statuses[$item->post_status];
        }

        function column_user_count( $item ){
            return count( explode( '|', $item->post_content ) );
        }


                        
    }// postaccesscontroller_list_table

/* End of file */
/* Location: ./post-access-controller/classes/list-table.php */