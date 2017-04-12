<?php

/**
 * @since 0.9 this class handles all the database interactions for post-access-controller
 */
    class postaccesscontroller_db{

        public function __construct(){
            global $wpdb;
            $this->wpdb = &$wpdb;
            $this->statuses = array('publish'=>'Active','trash'=>'Inactive');
        }

        /*
        * Marks the "group" post as trash
        */
		public function pac_group_archive_process( $data ){

            $post_data = array(
                'ID'             => $data['post_id'],
                'post_status'    => 'trash'
            );
            $return_id       = wp_update_post( $post_data );
            if( $return_id == 0 ){
                $results = array( 'ID'               => $data['ID']
                                 ,'error'            => 'Something happened that was not what we wanted (pac_group_archive_process)'
                                 );
            }else{
                $results = array( 'ID'               => $return_id
                                 ,'result'           => 'Group archived successfully'
                                 ,'txn_type'         => 'UPD'
                                 );
            }

            return $results;

        }

        public function group_master_lkup( $data ){

            //localize
            extract( $data );

            if( $post_id ){
                $return['group_master']     = get_post( $post_id );
            }else{
                $return['group_master']     = new stdClass();
                $return['group_master']->post_title = 'New Group';
                $return['group_master']->post_status = 'publish';
                $return['group_master']->ID = 0;
                $return['group_master']->public_ind = 'Y';
            }

            $return['meta']             = get_post_meta( $post_id );

            if( $include_users == 'Y' ):

                //create and (more importantly) cache the array of current users in the group
                $current_users          = get_post_meta( $post_id, 'postaccesscontroller_group_user' );

                //start by getting ALL users
                $users                  = get_users();

                //go through and create an array of all users with their ID, their display name and a selected indicator
                foreach( $users as $user ):

                    //are they in the current group
                    if( in_array( $user->ID, ( is_array( $current_users ) ? $current_users : array() ) ) ):
                        $selected = 'Y';
                    else:
                        $selected = 'N';
                    endif;

                    $return['users'][] = array( 'value'    => $user->ID
                                               ,'label'    => $user->display_name
                                               ,'selected' => $selected );

                endforeach;

            endif;

            return $return;
        }

        public function meta_options_lkup( $data ){

            if( $data['type'] == 'user' ):

                $options    = get_users();

            elseif( $data['type'] == 'group' ):

                $options    = $this->group_masters_lkup(array('post_status'=>array('publish')));

            elseif( $data['type'] == 'role' ):

                $options    = array();

                foreach( get_editable_roles() as $role_name => $role_attrs ):
                    $option = new stdClass();
                    $option->ID = $role_name;
                    $option->role_name = $role_attrs['name'];
                    array_push( $options, $option);
                endforeach;

            endif;

            return $options;

        }

        public function user_groups_lkup( $data ){

            //localize
            extract( $data );

            $group_masters        = $this->group_masters_lkup(array('post_status'=>array('publish')));

            foreach( $group_masters as $group ):

        		$group_public_ind = get_post_meta( $group->ID, 'postaccesscontroller_public_ind', true );
        		if( $group_public_ind == 'N' ):
        			continue;
        		endif;

                if( in_array( $user_id, get_post_meta( $group->ID, 'postaccesscontroller_group_user' ) ) ):
                    $selected = 'Y';
                else:
                    $selected = 'N';
                endif;

                $return[]         = array( 'value'    => $group->ID
                                          ,'label'    => $group->post_title
                                          ,'selected' => $selected );

                // print_r( $group );

            endforeach;

            return $return;
        }

        public function group_masters_lkup( $data = array() ){

            // echo 'group master lkup data:';
            // print_r( $data );

        	//filters are | delimited and then the key/value pairs are delimited by ~
        	$filters = array();
        	if( array_key_exists( 'filters', $data ) && count( $data['filters'] ) > 0 && strlen( $data['filters'] ) > 0 ):
        		foreach( explode( '|', $data['filters'] ) as $filter ):
        			$filter_data = explode( '~', $filter );
        			$filters[$filter_data[0]] = $filter_data[1];
        		endforeach;
    		endif;

            // print_r( $filters );

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

        /*
        * Saving the post access controller data that is part of the user profile form
        */
        public function pac_user_form_process( $data ){

            // echo 'pac user form process data:';
            // print_r( $data );

            $user_id          = $data['user_id'];
            $requested_groups = $data['post_id'];

            //get all the groups and the "selected" indicator
            $group_masters              = $this->user_groups_lkup(array('user_id'=>$user_id));

            //loop through those
            foreach( $group_masters as $group ):

                //check to see if our current user is already in that array
                if( $group['selected'] == 'Y' ):

                    //if they are then we should check if they should STAY in that array
                    if( !in_array( $group['value'], $requested_groups ) ):

                        //so the user is in a group but now they are not according to the requested group data
                        //so remove them and then re-save that group
                        $results[$group['name']] = $this->pac_grp_single_user_upd( $group['value'], $user_id, 'REMOVE' );

                    endif;

                //if they are NOT in the current group
                else:

                    //check to see if they should be now
                    if( in_array( $group['value'], $requested_groups ) ):

                        //add them
                        $results[$group['name']] = $this->pac_grp_single_user_upd( $group['value'], $user_id, 'ADD' );

                    endif;

                endif;

            endforeach;

            // translating the results array into a new array grouped by result code (error or success)
            foreach( $results as $groupName => $result ):
                $return[$result['rslt_code']][$groupName] = $result['rslt_desc'];
            endforeach;

            return $return;

        }

        private function pac_grp_single_user_upd( $group_id, $user_id, $prcs_type ){

            //get all the "user" meta records
            $users = get_post_meta( $group_id, 'postaccesscontroller_group_user' );

            //are we adding or removing?
            if( $prcs_type == 'ADD' ):

            	//does the user already exist?
                if( in_array( $user_id, $users ) ):

                    $return['rslt_code'] = 'SUCCESS';
                    $return['rslt_desc'] = 'No action needed';

                //guess not so we should add them
                else:

                	//this shouldn't really ever return false because we're not setting the unique parm to true but never hurts to check
                    if( add_post_meta( $group_id, 'postaccesscontroller_group_user', $user_id ) ):
    	                $return['rslt_code'] = 'SUCCESS';
	                    $return['rslt_desc'] = 'User added';
                	else:
    	                $return['rslt_code'] = 'ERROR';
	                    $return['rslt_desc'] = 'User was not able to be added successfully';
            		endif;

                endif;

            elseif( $prcs_type == 'REMOVE' ):

                //try to find the key for this user (ie see if they are already in the group)
                $key = array_search($user_id, $users);

            	//if they aren't then we don't need to do anything
                if( $key === false ):

                    $return['rslt_code'] = 'SUCCESS';
                    $return['rslt_desc'] = 'No action needed';

                //guess they are so we need to remove them
                else:

                    //just delete that user's meta record
                    if( delete_post_meta( $group_id, 'postaccesscontroller_group_user', $user_id ) ):
    	                $return['rslt_code'] = 'SUCCESS';
	                    $return['rslt_desc'] = 'User removed';
                	else:
    	                $return['rslt_code'] = 'ERROR';
	                    $return['rslt_desc'] = 'User was not able to be removed successfully';
            		endif;

                endif;

            endif;

        }

        // public function pac_grp_mstr_sts_cnt_lkup(){

        //     //query for each status
        //     foreach( $this->statuses as $status => $label ):

        //         $args = array( 'post_type' => 'pstacsctrlr_grp','post_status'      => $status );

        //         $groups = $this->group_masters_lkup($args);

        //         $status_counts[$status] = count($groups);

        //     endforeach;

        //     $groups = $this->group_masters_lkup();

        //     $status_counts['all'] = count($groups);

        //     return $status_counts;
        // }

        public function post_access_allow_check( $post_obj ){

            if( get_post_meta( $post_obj->ID, 'postaccesscontroller_ctrl_type', true ) == 'user' ){
                if( is_user_logged_in() ):
                    $users = get_post_meta( $post_obj->ID, 'postaccesscontroller_meta_user' );
                    if( in_array( get_current_user_id(), $users ) ):
                        return TRUE;
                    else:
                        return FALSE;
                    endif;
                else:
                    return FALSE;
                endif;
            }
            if( get_post_meta( $post_obj->ID, 'postaccesscontroller_ctrl_type', true ) == 'group' ){
                if( is_user_logged_in() ):
                    foreach( get_post_meta( $post_obj->ID, 'postaccesscontroller_meta_group' ) as $grp_post_id ):
                        $users = get_post_meta( $grp_post_id, 'postaccesscontroller_group_user' );
                        if( in_array( get_current_user_id(), $users ) ):
                            return TRUE;
                        endif;
                    endforeach;
                    return FALSE;
                else:
                    return FALSE;
                endif;
            }
            if( get_post_meta( $post_obj->ID, 'postaccesscontroller_ctrl_type', true ) == 'role' ){
                if( is_user_logged_in() ):
                    foreach( get_post_meta( $post_obj->ID, 'postaccesscontroller_meta_role' ) as $role ):
                        if( $this->check_user_role( $role ) ):
                            return TRUE;
                        endif;
                    endforeach;
                    return FALSE;
                else:
                    return FALSE;
                endif;
            }
            return TRUE;

        }

        public function check_user_role( $role, $user_id = null ) {

            if ( is_numeric( $user_id ) )
                $user = get_userdata( $user_id );
            else
                $user = wp_get_current_user();

            if ( empty( $user ) )
                return false;

            return in_array( $role, (array) $user->roles );
        }


    }

/* End of file */
/* Location: ./post-access-controller/classes/db.php */