<?php

/*
 * Plugin Admin Settings
 */


add_action( 'admin_menu', 'ga_events_menu');

function ga_events_menu() {
	add_menu_page('WP Google Analytics Settings','WP GA Events','manage_options', 'wp-google-analytics-events', 'ga_events_settings_page', plugins_url( 'images/icon.png', dirname(__FILE__)));
	add_submenu_page('wp-google-analytics-events','General Settings','General Settings', 'manage_options', 'wp-google-analytics-events' , 'ga_events_settings_page' );
	add_submenu_page('wp-google-analytics-events','Click Tracking','Click Tracking', 'manage_options', 'wp-google-analytics-events-click' , 'ga_events_settings_page' );
	add_submenu_page('wp-google-analytics-events','Scroll Tracking','Scroll Tracking', 'manage_options', 'wp-google-analytics-events-scroll' , 'ga_events_settings_page' );
	add_submenu_page('wp-google-analytics-events','Getting Started Guide','Getting Started Guide', 'manage_options', 'wp-google-analytics-events-started' , 'ga_events_settings_page' );
	add_submenu_page('wp-google-analytics-events','Upgrade','Upgrade Now', 'manage_options', 'wp-google-analytics-events-upgrade', 'ga_events_settings_page' );
}

function ga_events_settings_page() {

	?>
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<div id="ga_main" class="wrap">
		<?php screen_icon( 'plugins' ); ?>
	<h2>GA Scroll Events Plugin</h2>

			<?php
			$active_page = isset( $_GET[ 'page' ] ) ? $_GET[ 'page' ] : 'wp-google-analytics-events';
	  ?>
			<h2 class="nav-tab-wrapper">
			<a href="?page=wp-google-analytics-events" class="nav-tab <?php echo $active_page == 'wp-google-analytics-events' ? 'nav-tab-active' : ''; ?>">General Settings</a>
			<a href="?page=wp-google-analytics-events-click" class="nav-tab <?php echo $active_page == 'wp-google-analytics-events-click' ? 'nav-tab-active' : ''; ?>">Click Tracking</a>
			<a href="?page=wp-google-analytics-events-scroll" class="nav-tab <?php echo $active_page == 'wp-google-analytics-events-scroll' ? 'nav-tab-active' : ''; ?>">Scroll Tracking</a>
			<a href="?page=wp-google-analytics-events-started" class="nav-tab <?php echo $active_page == 'wp-google-analytics-events-started' ? 'nav-tab-active' : ''; ?>"><i class="fa fa-question-circle ga-events-help"></i> Getting Started Guide</a>
		</h2>
		<?php
			if ($active_page == 'wp-google-analytics-events-started') {
			do_settings_sections('ga_events_started');
		} else {
		?>

		<form id="ga-events-settings-form" method="post" action='options.php'>
			<?php settings_fields('ga_events_options'); ?>
			<?php
				$show_sidebar = true;
				if ($active_page == 'wp-google-analytics-events-click') {
					do_settings_sections('ga_events_click');
				} else if ($active_page == 'wp-google-analytics-events-scroll') {
					do_settings_sections('ga_events_scroll');
				}else {
					do_settings_sections('ga_events');
				}
			?>

		<input class="button-primary" type="submit" name="submit" value="Save Changes" />

		</form>
		<div class="settings_content">
			<form action="" method="post" enctype="multipart/form-data">
				<a href="#" class="btn_close"><img src="<?=plugins_url( 'images/close.png', dirname(__FILE__))?>"></a>
				<input type="file" name="settings">
				<input type="submit" name="set_settings">
			</form>
		</div>
	</div>
   <?php
		if ($show_sidebar) {
		?>
		 <div class="wrap ga_events_sidebar">
			<table class="form-table widefat" >
				<thead>
					<th>Need More Features?</th>
				</thead>
				<tbody>
					<tr class="features">
						<td>
							<ul>
								<li><i class="fa fa-check-square-o fa-lg"></i><strong>Link Tracking</strong></li>
								<li title="Dynamic Event Data"><i  class="fa fa-check-square-o fa-lg"></i><strong>Placeholders</strong></li>
								<li><i class="fa fa-check-square-o fa-lg"></i><strong>YouTube Video Tracking</strong></li>
								<li><i class="fa fa-check-square-o fa-lg"></i><strong>Vimeo Video support</strong></li>
								<li><i class="fa fa-check-square-o fa-lg"></i><strong>Set Value for Events</strong></li>
								<li><i class="fa fa-check-square-o fa-lg"></i><strong>HTML Tag support</strong></li>
								<li><i class="fa fa-check-square-o fa-lg"></i><strong>Pro Support</strong></li>
							</ul>
						</td>
					</tr>
					<tr class="tfoot">
						<td>
							<div class="wpcta">
								<a class="button-primary button-large" target="_blank" href="https://wpflow.com/upgrade?utm_source=wpadm&utm_medium=banner&utm_content=side&utm_campaign=wpadmin">
									<span class="btn-title ">
										Upgrade Now
									</span>
								</a>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

		</div>
		<?php }} ?>


<?php

	echo "<script>
			jQuery('.remove').click(function (event) {
				event.preventDefault();
				jQuery(this).closest('tr').remove();
			});
			jQuery('.add').click(function (event) {
				event.preventDefault();
			});
		  </script>
	";
}

function load_custom_wp_admin_style() {
	wp_register_style( 'custom_wp_admin_css', plugins_url('css/style.css', dirname(__FILE__)));
	wp_enqueue_style( 'custom_wp_admin_css' );
		wp_enqueue_script( 'admin-init', plugins_url('js/admin.js', dirname(__FILE__)) , array('jquery','jquery-ui-tooltip'), null, true );

}

add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

add_action('admin_init', 'ga_events_admin_init');

function ga_events_admin_init() {
		if(isset($_GET['download']) && isset($_GET['page'])){
			if ($_GET['page'] == 'wp-google-analytics-events') {
				ga_events_file();
			}
		}

		if(isset($_POST['set_settings'])){
			ga_events_upload_settings($_FILES);
		}


	register_setting('ga_events_options','ga_events_options','ga_events_validate');
	add_settings_section('ga_events_main','WP Google Analytics Events Settings', 'ga_events_section_text','ga_events');
	add_settings_section('ga_events_click_section','', 'ga_events_section_text','ga_events_click');
	add_settings_section('ga_events_scroll_section','', 'ga_events_section_text','ga_events_scroll');
	add_settings_section('ga_events_started_section','Getting Started Guide', 'ga_events_section_text','ga_events_started');
	add_settings_field('ga_events_id', '','ga_events_setting_input','ga_events','ga_events_main');
	add_settings_field('ga_events_exclude_snippet', '','ga_events_setting_snippet_input','ga_events','ga_events_main');
	add_settings_field('ga_events_universal', '','ga_events_setting_uni_input','ga_events','ga_events_main');
	add_settings_field('ga_events_anonymizeip', '','ga_events_setting_anon_input','ga_events','ga_events_main');
	add_settings_field('ga_events_advanced', '','ga_events_setting_adv_input','ga_events','ga_events_main');
	add_settings_field('ga_events_divs', '','ga_events_setting_divs_input','ga_events_scroll','ga_events_scroll_section');
	add_settings_field('ga_events_started', '','ga_events_setting_started','ga_events_started','ga_events_started_section');
	add_settings_field('ga_events_click', '','ga_events_setting_click_input','ga_events_click','ga_events_click_section');
	add_settings_field('ga_events_sidebar', '','ga_events_setting_sidebar','ga_events','ga_events_main');
	add_settings_field('ga_events_download_settings', '','ga_events_settings_download','ga_events','ga_events_main');
	add_settings_field('ga_events_upload_settings', '','ga_events_settings_upload','ga_events','ga_events_main');


}

function ga_events_section_text() {
	echo "<br><a style='margin-left:8px;' href='http://wpflow.com/documentation' target='_blank'>Plugin Documentation</a>";
}

function ga_events_setting_started() {
	echo '
		  <h2>Getting Started Guide</h2>
		 <form action="https://www.getdrip.com/forms/4588171/submissions" method="post" data-drip-embedded-form="4588171">
						  <div style="background:white; line-height:20px; padding: 5px 15px 15px 15px;
 font-size: 15px; max-width:400px;">

			 <h3 style="margin-top: 10px;" data-drip-attribute="headline">Want to learn more about event tracking?</h3>
			 <div data-drip-attribute="description">Now that you installed the plugin, we want to help you get everything up and running.&nbsp;<br />
				 Join our short email course and get started with event tracking.</div>
			 <div>
				 <label style="margin-top:10px;"for="fields[email]">Email Address:</label><br />
				 <input type="email" name="fields[email]" value="" />
			 </div>
			 <div>
				<input style="margin-top:15px;" class="button-primary" type="submit" name="submit" value="Get Started" data-drip-attribute="sign-up-button" />
			 </div>
			</div>
		 </form>';
}

function ga_events_setting_input() {
	$options = get_option('ga_events_options');
	$id = $options['id'];
	echo "<label>Google Analytics Identifier</label>";
	echo "<span class='ga_intable'><input class='inputs' id='id' name='ga_events_options[id]' type='text' value='$id' /></span>";

}


function ga_events_setting_snippet_input() {
	$options = get_option('ga_events_options');
	$id = $options['exclude_snippet'];
	echo "<label>Don't add the GA tracking code ".ga_tooltip('Useful if you already have the code snippet loaded by a different plugin')."</label>";
	echo "<span class='ga_intable'><input id='snippet' name='ga_events_options[exclude_snippet]' type='checkbox' value='1' " . checked( $id , 1,false) . " /></span>";

}

function ga_events_setting_uni_input() {
	$options = get_option('ga_events_options');
	$id = $options['universal'];
	echo "<label>Universal Tracking Code</label>";
	echo "<span class='ga_intable'><input id='universal' name='ga_events_options[universal]' type='checkbox' value='1' " . checked( $id , 1,false) . " /></span>";
}

function ga_events_setting_anon_input() {
	$options = get_option('ga_events_options');
	$id = $options['anonymizeip'];
	echo "<label>IP Anonymization".ga_tooltip('Tell Google Analytics not to log IP Addresses. Requires code snippet to be checked')."</label>";
	echo "<span class='ga_intable'><input id='anonymizeip' name='ga_events_options[anonymizeip]' type='checkbox' value='1' " . checked( $id , 1,false) . " /></span>";
}

function ga_events_setting_adv_input() {
	$options = get_option('ga_events_options');
	$id = $options['advanced'];
	echo "<label>Advanced Mode ".ga_tooltip('Enable Advanced Selectors')."</label>";
	echo "<span class='ga_intable'><input id='advanced' name='ga_events_options[advanced]' type='checkbox' value='1' " . checked( $id , 1,false) . " /></span>";
}	

function ga_events_settings_download(){
		echo '<a class="button" href="http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] .'&download=1">Export settings</a>';
}

function ga_events_settings_upload(){
		echo '<a href="#" class="button btn_upload">Import settings</a>';
}

function ga_events_setting_divs_input() {
	$options = get_option('ga_events_options');
	$divs= $options['divs'];
	
	$menu_options = array(
			'id' => 'id',
			'class' => 'class',
	);	
	
	if(is_advanced_mode()){
		$menu_options['advanced'] = 'advanced'; // if enabled, add 'avanced' on the menu
	}
	
	$type='divs';
	
	echo "<table id='ga-events-inputs' class='widefat inputs inner_table'><thead><th>Element Name</th><th>Type</th><th>Event Category</th><th>Event Action</th><th>Event Label</th><th>Non-Interaction</th><th></th></thead><tbody>";
	if (!($divs[0][0])){
		$name = "ga_events_options[$type][0][1]";		
		$type_menu = createDropdown($name, $type, $menu_options,'id');
		
		echo "<tr>";
		echo "<td data-title='Element Name'><input id='divs' name='ga_events_options[divs][0][0]' type='text' value='".$divs[0][0]."' /></td>";
		echo "<td data-title='Type'>$type_menu</td>";
		echo "<td data-title='Event Category'><input id='divs' name='ga_events_options[divs][0][2]' type='text' value='".$divs[0][2]."' /></td>";
		echo "<td data-title='Event Action'><input id='divs' name='ga_events_options[divs][0][3]' type='text' value='".$divs[0][3]."' /></td>";
		echo "<td data-title='Event Label'><input id='divs' name='ga_events_options[divs][0][4]' type='text' value='".$divs[0][4]."' /></td>";

		echo "<td data-title='Non-Interaction'><select id='".$type."' name='ga_events_options[".$type."][$i][5]'>";
		if ($divs[$i][5] == 'true') {
			echo "<option selected value='true' >true</option><option value='false'>false</option></select></td>";
		} else {
			echo "<option  value='true' >true</option><option selected value='false'>false</option></select></td>";
		}

		echo "</tr>";

	}else{
		for ($i = 0; $i < sizeof($divs)+1; $i++){
				$name = "ga_events_options[$type][$i][1]";
				$selected = $divs[$i][1];
				$type_menu = createDropdown($name, $type, $menu_options, $selected);
				echo "<tr>";
				echo "<td data-title='Element Name'><input id='divs' name='ga_events_options[divs][$i][0] type='text' value='".$divs[$i][0]."' /></td>";
				echo "<td data-title='Type'>$type_menu</td>";
				echo "<td data-title='Event Category'><input id='divs' name='ga_events_options[divs][$i][2]' type='text' value='".$divs[$i][2]."' /></td>";
				echo "<td data-title='Event Action'><input id='divs' name='ga_events_options[divs][$i][3]' type='text' value='".$divs[$i][3]."' /></td>";
				echo "<td data-title='Event Label'><input id='divs' name='ga_events_options[divs][$i][4]' type='text' value='".$divs[$i][4]."' /></td>";

				echo "<td data-title='Non-Interaction'><select id='".$type."' name='ga_events_options[".$type."][$i][5]'>";
				if ($divs[$i][5] == 'true') {
					echo "<option selected value='true' >true</option><option value='false'>false</option></select></td>";
				} else {
					echo "<option  value='true' >true</option><option selected value='false'>false</option></select></td>";
				}
				
				echo "<td><a class='remove' href=''>Remove</a></td>";
				echo "</tr>";

		}

	}
	echo "</tbody></table>";
}


function ga_events_setting_click_input() {
	$options = get_option('ga_events_options');
	$click = $options['click'];
	$divs= $options['click'];

	$menu_options = array(
			'id' => 'id',
			'class' => 'class',
	);	
	
	if(is_advanced_mode()){
		$menu_options['advanced'] = 'advanced'; // if enabled, add 'avanced' on the menu
	}	

	$type='click';
	
	
	echo "<table id='ga-events-inputs' class='widefat inputs inner_table  '><thead><th>Element Name</th><th>Type</th><th>Event Category</th><th>Event Action</th><th>Event Label</th><th>Non-Interaction</th><th></th></thead><tbody>";
	if (!($click[0][0])){
		$name = "ga_events_options[click][0][1]";		
		$type_menu = createDropdown($name, $type, $menu_options,'id');
		
		echo "<tr>";
		echo "<td data-title='Element Name'><input id='click' name='ga_events_options[click][0][0]' type='text' value='".$click[0][0]."' /></td>";
		echo "<td data-title='Type'>$type_menu</td>";
		echo "<td data-title='Event Category'><input id='click' name='ga_events_options[click][0][2]' type='text' value='".$click[0][2]."' /></td>";
		echo "<td data-title='Event Action'><input id='click' name='ga_events_options[click][0][3]' type='text' value='".$click[0][3]."' /></td>";
		echo "<td data-title='Event Label'><input id='click' name='ga_events_options[click][0][4]' type='text' value='".$click[0][4]."' /></td>";

		echo "<td data-title='Non-Interaction'><select id='".$type."' name='ga_events_options[".$type."][$i][5]'>";
		if ($divs[$i][5] == 'true') {
			echo "<option selected value='true' >true</option><option value='false'>false</option></select></td>";
		} else {
			echo "<option  value='true' >true</option><option selected value='false'>false</option></select></td>";
		}


		
		echo "</tr>";

	}else{
		for ($i = 0; $i < sizeof($click)+1; $i++){
				$name = "ga_events_options[click][$i][1]";
				$selected = $click[$i][1];
				$type_menu = createDropdown($name, $type, $menu_options, $selected);
				
				echo "<tr>";
				echo "<td data-title='Element Name'><input id='divs' name='ga_events_options[click][$i][0] type='text' value='".$click[$i][0]."' /></td>";
				echo "<td data-title='Type'>$type_menu</td>";				
				echo "<td data-title='Event Category'><input id='click' name='ga_events_options[click][$i][2] type='text' value='".$click[$i][2]."' /></td>";
				echo "<td data-title='Event Action'><input id='click' name='ga_events_options[click][$i][3] type='text' value='".$click[$i][3]."' /></td>";
				echo "<td data-title='Event Label'><input id='click' name='ga_events_options[click][$i][4] type='text' value='".$click[$i][4]."' /></td>";

				echo "<td data-title='Non-Interaction'><select id='".$type."' name='ga_events_options[".$type."][$i][5]'>";
				if ($divs[$i][5] == 'true') {
					echo "<option selected value='true' >true</option><option value='false'>false</option></select></td>";
				} 
				else {
					echo "<option  value='true' >true</option><option selected value='false'>false</option></select></td>";
				}

				echo "<td><a class='remove' href=''>Remove</a></td>";
				echo "</tr>";

		}

	}
	echo "</tbody></table>";


}

function ga_events_setting_sidebar(){
}

function ga_events_validate($form){
	
	$options = get_option('ga_events_options');
	$updated = $options;

	if( array_key_exists('divs', $form)) {

		$updated['divs'] = array();
		$divFields = array_values($form['divs']); //force array index to start with 0
		for ($i = 0, $j = 0; $i< sizeof($divFields); $i++){
			if ($divFields[$i][0]){
				$updated['divs'][$j] = cleanEventFeilds($divFields[$i]);
				$j++;
			}
		}
	} 
	else if(array_key_exists('click', $form)) {
		$updated['click'] = array();
		$clickFields = array_values($form['click']); //force array index to start with 0
		for ($i = 0, $j = 0; $i< sizeof($clickFields); $i++){			
			if ($clickFields[$i][0]){
				$updated['click'][$j] = cleanEventFeilds($clickFields[$i]);
				$j++;
			}
		}
	}
	else {
		$updated['id'] = $form['id'];
		$updated['exclude_snippet'] = $form['exclude_snippet'];
		$updated['universal'] = $form['universal'];
		$updated['anonymizeip'] = $form['anonymizeip'];
		$updated['advanced'] = $form['advanced'];
	}

	return $updated;
}


add_action('admin_footer', 'ga_events_admin_footer');

function ga_events_admin_footer() {
?>
	<script>
		jQuery('body').on('click','a[href="admin.php?page=wp-google-analytics-events-upgrade"]', function (e) {
					e.preventDefault();
					window.open('https://wpflow.com/upgrade?utm_source=wpadm&utm_medium=banner&utm_content=menu&utm_campaign=wpadmin', '_blank');
				});
	</script>
	<?php
}


function ga_events_get_settings(){
		$options = get_option('ga_events_options');
		$current = json_encode($options);
		return $current;
}

function ga_events_upload_settings($file){
		$uploadedfile = $file['settings'];
		if($uploadedfile['type'] != 'application/octet-stream'){
			ga_event_popup();
			return;
		}
		$content = file_get_contents($uploadedfile["tmp_name"]);
		ga_event_get_content($content);
}

function ga_event_get_content($content){
		if(!$current = json_decode($content,true)){
			ga_event_popup();
			return;
		}
		if (!array_key_exists('id', $current) && !array_key_exists('domain', $current)) {
			ga_event_popup();
			return;
		}
		update_option( 'ga_events_options', $current );

}
function ga_event_popup(){
	echo "<dev class='popup'>";
	echo '<h1>Wrong file format <a href="#" class="btn_close_popup"><img src="'.plugins_url( 'images/close.png', dirname(__FILE__)).'"></a></h1>';
	echo "</dev>";
}
function ga_events_file(){
	header("Content-type: text/plain");
	header("Content-Disposition: attachment; filename='settings.ini'");
	echo ga_events_get_settings();
	exit();
}

function cleanEventFeilds($arr) {
	if('advanced' == $arr[1]){
		$arr[0] = str_replace("'",'"',$arr[0]);
	}else{
		$arr[0] = str_replace("'","",$arr[0]);
	}
	
	for ($i = 1; $i < sizeof($arr); $i++) {
		$arr[$i] = esc_html($arr[$i]);
	}
	return $arr;
}

function ga_tooltip($content = '') {
	$html = '<span class="ga-tooltip" title="'.$content.'"></span>';
	return $html;
}

?>
