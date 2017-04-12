<?php


function migrateOptions() {
	$current_options = get_option('ga_events_options');	
	$new_options = array();
	$new_options['id'] = $current_options['id'];
	$new_options['exclude_snippet'] = $current_options['exclude_snippet'];
	$new_options['universal'] = $current_options['universal'];
	$new_options['events'] = array();
	for ($i=0; $i < sizeof($current_options['divs']); $i++) {
		$current_option = $current_options['divs'][$i];
		$event = new Event('scroll', $current_option[0], $current_option[1], $current_option[2], $current_option[3], $current_option[4]);
		array_push($new_options['events'], $event->getEventArray() );
	}
	
	for ($i=0; $i < sizeof($current_options['click']); $i++) {
		$current_option = $current_options['click'][$i];
		$event = new Event('click', $current_option[0], $current_option[1], $current_option[2], $current_option[3], $current_option[4]);
		array_push($new_options['events'], $event->getEventArray() );
	}

	update_option('ga_events_options', $new_options);
//	print(var_dump($new_options));
}


function isOptionMigrationRequired(){
	$current_options = get_option('ga_events_options');
	if (array_key_exists('divs', $current_options) || array_key_exists('clicks', $current_options)) {
		return true;
	}
	return false;
}

function is_advanced_mode(){
	$ga_events_options =  get_option( 'ga_events_options' );
	return $ga_events_options['advanced'];
}

function is_advanced_type($type){
	return 'advanced' == $type;
}

function createDropdown($name, $id, $options = array(), $selected = 'unknown'){
	$html = '';
	if(!empty($options)){
		$html .= "<select id='$id' name='$name'>";
			
			if(!in_array($selected, $options)){
				// even advanced mode is off, 'avanced' should still be accepted as valid option
				if('advanced' == $selected){
					$options['advanced'] = 'advanced';
				}else{
					$selected = reset($options); // set first element's key to be default
				}					
			}
			
			foreach ($options as $key => $value){				
				$html .= $selected == $key ? "<option selected value='$key' >$value</option>":"<option  value='$key' >$value</option>";
			}
			
		$html .= "</select>";
	}
	return $html;
}

?>
