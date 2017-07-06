<?php
/**
* Instagram Widget Setup
* @since 1.9.2
*/
if(!function_exists('tt_instagram_data')){
	/**
	* Get data from instagram by scrapping user's feed
	* @since 1.9.9
	* @return array Image Data & User Stats
	*/
	function tt_instagram_data( $username, $cache_hours, $nr_images ) {
	        
	    $opt_name       = 'tt_insta_' . md5( $username . $nr_images );
	    $instaData      = get_transient( $opt_name );

	    if ( !$instaData ) {
	        $instaData      = array();
	        $insta_url      = 'http://instagram.com/';
	        $user_profile   = $insta_url . $username;
	        $user_options   = compact('username', 'cache_hours', 'nr_images');
	        update_option($opt_name, $user_options);
	        $json           = wp_remote_retrieve_body(
	            wp_remote_get( $user_profile . "/media/", array( 'sslverify' => false, 'timeout'=> 60 ) )
	            );	        	        
	        if ( $json ) {                
	            ( $arr = json_decode( $json, true ) ) && json_last_error() == JSON_ERROR_NONE;

	            if ( !empty($arr['items']) && is_array( $arr['items'] ) ) {

	                foreach( $arr['items'] as $nr => $item ) {
	                    if( $nr >= $nr_images ) 
	                        break;
	                    array_push( $instaData, array(
	                        'id'            => $item['id'],
	                        'user_name'     => $username,
	                        'user_url'      => $user_profile,
	                        'created_time'  => $item['created_time'],
	                        'caption'       => !empty($item['caption']) && !empty($item['caption']['text']) ? $item['caption']['text'] : '',
	                        'image'         => $item['images']['standard_resolution']['url'],
	                        'thumb'         => $item['images']['thumbnail']['url'],
	                        'link'          => $item['link'],
	                        'comments_count'=> $item['comments']['count'],
	                        'likes_count'   => $item['likes']['count'],
	                        'width'         => $item['images']['standard_resolution']['width'],
	                        'height'        => $item['images']['standard_resolution']['height'],
	                    ));
	                
	                }
	            
	            }
	        
	        }
	        
	        if ( $instaData && !empty($cache_hours)) {
	            set_transient( $opt_name, $instaData, $cache_hours * HOUR_IN_SECONDS );
	        }
	    
	    }
	    
	    return $instaData;
	}

} // end !function_exist

if(!function_exists('tt_instagram_generate_output')){
	/**
	* Generate instagram output
	* @since 1.9.2
	*/
	function tt_instagram_generate_output( $username, $cache_hours, $nr_images , $thumbs = true, $callback = '' ){
		if(empty($username)){
			return __("No username inserted in instagram widget","TeslaFramework");
		}
		$images = tt_instagram_data( $username, $cache_hours, $nr_images );
		if(!empty($images)) : 
			if(!empty($callback))
				call_user_func( $callback , $images , $thumbs);
			$output = '<ul class="tt-instagram-feed">';
				foreach ($images as $key => $image) :
					$image_src = $thumbs ? $image['thumb'] : $image['image'];
					$output .= '<li>';
						$output .= '<a target="_blank" href="' . esc_url($image['link']) . '">';
							$output .= '<img class="tt-instagram-img" src="' . esc_attr($image_src) . '" width="' . esc_attr( $image['width'] ) . '" height="' . esc_attr( $image['height'] ) . '" alt="' . esc_attr( $image['caption'] ) . '"/>';
							$output .= '<span class="tt-instagram-caption">' . esc_html($image['caption']) . '</span>';
							$output .= '<span class="tt-instagram-likes">' . esc_html($image['likes_count']) . '</span>';
						$output .= '</a>';
					$output .= '</li>';
				endforeach;
			$output .= '</ul>';
		endif;
		return $output;
	}
}