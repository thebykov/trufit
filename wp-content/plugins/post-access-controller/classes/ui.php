<?php

    class postaccesscontroller_ui{

        /* ---------------------------------------------------------------------------------
                   FORMS
           --------------------------------------------------------------------------------- */

        public function generate_checkbox_well( $args ){
            return $this->_form_input_checkbox_well( $args );
        }

        public function generate_form_table_line( $args ){

            extract( array_merge( array( 'cell_class' => null ), $args ) );

            $function = '_form_input_'.$field_type;
            $field_code = $this->$function( $args );

            $return  = '<tr>';
            $return .= '<th>'.$field_label.'</th>';
            $return .= '<td class="'.$cell_class.'">'.$field_code.'</td>';
            $return .= '</tr>';
            return $return;
        }

        private function _form_input_text( $args ){
            extract( $args );
            $return = "<input type='text' name='$name' class='$class' value='$current_value' />";
            return $return;
        }

        private function _form_input_textarea( $args ){
            extract( $args );
            $return = "<textarea name='$name' class='$class'>$current_value</textarea>";
            return $return;
        }

        private function _form_input_drop_down( $args ){
            extract( array_merge( array( 'class' => null, 'id' => null ), $args ) );
            $return  = "<select name='$name' class='$class' id='$id'>";
            foreach( $values as $value => $label ):
                $return .= "<option value='$value'";
                if( $value == $current_value ):
                    $return .= ' selected';
                endif;
                $return .= ">$label</option>";
            endforeach;
            $return .= '</select>';

            return $return;
        }

        private function _form_input_checkbox( $args ){

            extract( $args );

            $return = '';

            foreach( $options as $option ):
                $return .= "<div><label for='$name-".$option['value']."'>";
                if( $option['selected'] == 'Y' ):
                    $checked = ' checked';
                else:
                    $checked = '';
                endif;
                $return .= "<input type='checkbox' name='".$name."[]' id='$name-".$option['value']."' value='".$option['value']."' style='width: auto' $checked>";
                $return .= $option['label']."</label></div>";
            endforeach;

            return $return;

        }

        private function _form_input_checkbox_well( $args ){

            extract( $args );

            $return = '<div class="postaccesscontroller-checkbox-well">';

            foreach( $options as $option ):
                $return .= "<label for='$name-".$option['value']."'>";
                if( $option['selected'] == 'Y' ):
                    $checked = ' checked';
                else:
                    $checked = '';
                endif;
                $return .= "<input type='checkbox' name='".$name."[]' id='$name-".$option['value']."' value='".$option['value']."' $checked>";
                $return .= $option['label']."</label>";
            endforeach;

            $return .= '</div>';

            return $return;

        }

        /* ---------------------------------------------------------------------------------
                   UTILITIES
           --------------------------------------------------------------------------------- */

        // public function generate_extra_tablenav( $data ){
        //     if( is_array( $data ) ){
        //         $return = '<ul class="subsubsub">';
        //         $return .= '<li><strong>Filters:</strong></li>';
        //         foreach( $data as $nav ):
        //             $return .= '<li><a href="'.$nav['href'].'">'.$nav['label'].' <span class="count">('.$nav['count'].')</span></a></li>';
        //         endforeach;
        //         $return .= '</ul><!-- /.subsubsub -->';
        //     }
        //     return $return;
        // }

        public function generate_breadcrumbs( $data ){

            $return = '<div class="breadcrumbs"><ul>';

            foreach( $data as $crumb ):
                $return .= '<li>';
                if( empty( $crumb['href'] ) ):
                    $return .= $crumb['label'];
                else:
                    $return .= '<a href="'.$crumb['href'].'">';
                    $return .= $crumb['label'];
                    $return .= '</a>';
                endif;
                $return .= '</li>';
            endforeach;

            $return .= '</ul></div>';
            return $return;

        }

        function register_admin_notice( $type, $msg ) {

            add_user_meta( get_current_user_id(), 'postaccesscontroller_admin_notices', $type.'|'.$msg, true );

        }

        //this won't really be called witin another function, this should just be called from the core class when the admin pages load
        function display_admin_notices(){

            //rather than PHP's session variables we use the wordpress meta tables
            //which should be more reliable (no cookie issues) and persistent (if they log out and then come back)
            $messages = get_user_meta( get_current_user_id(), 'postaccesscontroller_admin_notices' );

            //see if they have any to show
            if( is_array( $messages ) && count( $messages ) > 0 ):

                //if they do we loop through to handle each one as needed
                foreach( $messages as $message ):

                    //we store the "type" as well as the actual message so we'll split them out by pipe
                    $details = explode( '|', $message );

                    ?><div class="<?php echo $details[0]; ?>">
                        <p><?php echo $details[1]; ?></p>
                    </div><?php

                endforeach;

                //delete them as we return them to the display
                delete_user_meta( get_current_user_id(), 'postaccesscontroller_admin_notices', $message );

            endif;
        }

    }//postaccesscontroller_ui

    /*
      There are some warnings around the internet indicating that since this class is marked as private in WP core that it shouldn't be extended
      but there are also lots of discussions about how this is such a common thing to do that it will likely never change or at least not drastically
      enough to break this plugin completely.  So, that all being said, we're using it here because it does the trick nicely and it
      helps the plugin feel like part of WP Core.
    */
    if( ! class_exists( 'WP_List_Table' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    }

    class PAC_List_Table extends WP_List_Table {

        public function __construct( $args ){

            $this->tablenav = $args['tablenav'];
            $this->columns = $args['columns'];
            $this->per_page = ( array_key_exists( 'per_page', $args ) ? $args['per_page'] : 10 );
            $this->table_data = $args['table_data'];
            $this->total_items = count( $this->table_data );
            $this->statuses = array('publish'=>'Active','trash'=>'Inactive');
            parent::__construct();
        }

        function get_columns(){
            $return = array();
            foreach( $this->columns as $column_name => $details ):
                $return[$column_name] = $details['header_text'];
            endforeach;
            return $return;
        }

        function get_sortable_columns(){
            $return = array();
            foreach( $this->columns as $column_name => $details ):
                if( $details['sortable'] ):
                    $return[$column_name] = $details['sortable'];
                endif;
            endforeach;
            return $return;
        }

        function prepare_items() {

            //get the columns, their "code" and the header text
            $columns = $this->get_columns();

            //not sure what this does but it needs to be here for part of the core class to operate properly
            $hidden = array();

            //indicate which columns should be sortable
            $sortable = $this->get_sortable_columns();

            //use all of the above to do headers
            $this->_column_headers = array($columns, $hidden, $sortable);

            //pagination
            $current_page = $this->get_pagenum();       //using one of the core functions that mostly just reads the URL
            $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
            $this->set_pagination_args( array(
                                                'total_items' => $this->total_items,
                                                'per_page'    => $this->per_page
                                            ) );

            //this handles the core functions to make whatever was selected happen
            $this->process_bulk_action();

            //the actual data
            $this->items = $this->table_data;

        }

        //this "extra" tablenav is just always called within the core class to get the code for the navigation at the top or bottom of the table
        function extra_tablenav( $which ) {
            if ( $which == "top" ){
                echo $this->tablenav['top'];
            }
        }

        //this is called within the core class to get the options for the drop down
        function get_bulk_actions() {
            $actions = array(
                            'archive'    => 'Archive'
            );
            return $actions;
        }

        //process whatever action was selected
        function process_bulk_action() {

            //Detect when a bulk action is being triggered...
            if( 'archive' === $this->current_action() ) {

                $result = '<div id="message" class="updated"><p>Groups archived:</p><ul>';

                foreach( $_GET['post_id'] as $post_id ):
                    $results = $this->db->pac_group_archive_process(array('post_id'=>$post_id));
                    $result .= '<li>'.$results['mstr_rslt'].'</li>';
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
          $actions = array(
                    'edit'      => sprintf('<a href="?page=%s&post_id=%s">Edit</a>','post-access-controller--edit',$item->ID),
                );

            if( $item->post_status == 'publish' ):
                $actions['delete'] = sprintf('<a href="?page=%s&post_id=%s">Archive</a>','post-access-controller--archive',$item->ID);
            endif;

          return sprintf('%1$s %2$s', $item->post_title, $this->row_actions($actions) );
        }

        function column_post_status( $item ){
            //return $this->statuses[$item->post_status];
            return $item->post_status;
        }

        function column_user_count( $item ){
            return count( explode( '|', $item->post_content ) );
        }



    }

/* End of file */
/* Location: ./post-access-controller/classes/ui.php */