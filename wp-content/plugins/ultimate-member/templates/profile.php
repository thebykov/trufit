<?php //CUSTOM CODE START *************************** ?>
<?php if(isset($_REQUEST['deleteRecord'])): 
	
	global $wpdb;
	$query = "DELETE FROM wppr_members_log WHERE id = " . $_REQUEST['deleteRecord'] . ";";
	$results = $wpdb->get_results($query);
	
	print_r("Query: ");
	print_r($query);
	
	elseif(isset($_REQUEST['addRecord'])):
		

	
	global $wpdb;
	$query = "INSERT INTO wppr_members_log (member_id,data_type,data_string) VALUES ('" . $_REQUEST['memberID'] . "','" . $_REQUEST['dataType'] . "','" . htmlentities($_REQUEST['dataString'],ENT_QUOTES) . "');";
	$results = $wpdb->get_results($query);
	
	print_r("Query: ");
	print_r($query);

	 else: ?>
<?php //CUSTOM CODE END *************************** ?>
<div class="um <?php echo $this->get_class( $mode ); ?> um-<?php echo $form_id; ?> um-role-<?php echo um_user('role'); ?> ">

	<div class="um-form">
	
		<?php do_action('um_profile_before_header', $args ); ?>
		
		<?php if ( um_is_on_edit_profile() ) { ?><form method="post" action=""><?php } ?>
		
			<?php do_action('um_profile_header_cover_area', $args ); ?>
			
			<?php do_action('um_profile_header', $args ); ?>
			
			<?php do_action('um_profile_navbar', $args ); ?>
			
			<?php
				
			$nav = $ultimatemember->profile->active_tab;
			$subnav = ( get_query_var('subnav') ) ? get_query_var('subnav') : 'default';
				
			print "<div class='um-profile-body $nav $nav-$subnav'>";
				
				// Custom hook to display tabbed content
				do_action("um_profile_content_{$nav}", $args);
				do_action("um_profile_content_{$nav}_{$subnav}", $args);
				
			print "</div>";
				
			?>
		
		<?php if ( um_is_on_edit_profile() ) { ?></form><?php } ?>
	
	</div>
	
</div>
<?php //CUSTOM CODE START *************************** ?>
<?php if(isset( $_GET['profiletab']) && $_GET['profiletab'] != "main"): ?>
	
	<div class="old_data-wrapper">
<h2>Previous Data</h2>
		<?php
		
		//$user = um_profile_id();
		$userID =  um_profile_id();
		
		global $wpdb;
$query = "SELECT * FROM wppr_members_log WHERE member_id = " . $userID . " AND data_type = '" . $_GET['profiletab'] . "' ORDER BY ID DESC;";

//echo $query;

$results = $wpdb->get_results($query);
		
//var_dump($results);
		
		foreach($results as $key => $row){


$old_date = $row->updated;
$old_date_timestamp = strtotime($old_date);
$new_date = date('m-d-Y H:i:s', $old_date_timestamp);   
			
echo '<div class="record_wrapper"><h3><u>' . $new_date .'</u></h3>';
echo '<div class="record-wrapper" id="' . $row->id . '">' . 	html_entity_decode($row->data_string) .'</div><div class="delete_button" onclick="deleteRecord(' . $row->id . ')">Delete This Record</div></div>';


  
		}

		?>
		
	</div>
	
	
	<script>
	
	<?php 
	$userID =  um_profile_id();
	?>
	
	userID = <?php echo $userID; ?>;
		
	allData = "";

	data_type = "<?php echo  $_GET['profiletab']; ?>";

function deleteRecord(ID){
	if(confirm('Are you sure you want to delete that records?')){
		jQuery.ajax({
		   url: '',
		   type: 'POST',
		   data: {'deleteRecord':ID},
		   complete: function(xhr, textStatus) {
		     //called when complete
		     //console.log(xhr);
		   },
		   success: function(data, textStatus, xhr) {
		   	console.log("Delete Record: ");
		   	console.log(data);
		     //called when successful
		     
		     location.reload();
		   },
		   error: function(xhr, textStatus, errorThrown) {
		     //called when there is an error
		   }
		 });
	}
}

function getRecords(){

record = "";

jQuery('.um-profile-body .um-field').each(function(){
	if(jQuery(this).find('input').val() != undefined){
	record = 
"<div class=\"record\"><span class=\"record_name\">" + 
jQuery(this).find('label').text() + 
"</span> - <span class=\"record_value\">"+ 
jQuery(this).find('input').val()  +
"</span></div>";

allData = allData + record;
}else if((jQuery(this).find('textarea').text() != undefined) && (!jQuery(this).find('textarea').text() == "")){
	record = 
"<div class=\"record\"><span class=\"record_name\">" + 
jQuery(this).find('label').text() + 
"</span> - <span class=\"record_value\">"+ 
jQuery(this).find('textarea').text()  +
"</span></div>";

allData = allData + record;
}




});

 wpnonce = jQuery('#_wpnonce').val();

	formData = {
		"addRecord":"true",
		"memberID":userID,
		"dataString":allData,
		"dataType":data_type	
	};
	
	
}
//console.log(allData);


skipSubmit = true;
jQuery('.um-form form').submit(function (evt) {
	if(skipSubmit){
    evt.preventDefault();
	skipSubmit = false;
	
		getRecords();
	
		jQuery.ajax({
		   url: '',
		   type: 'POST',
		   data: formData,
		   complete: function(xhr, textStatus) {
		     //called when complete
		     //console.log(xhr);
		   },
		   success: function(data, textStatus, xhr) {
		   	console.log("Insert Record: ");
		   	console.log(data);
		     //called when successful
		     jQuery('.um-form form').submit();
		   },
		   error: function(xhr, textStatus, errorThrown) {
		     //called when there is an error
		   }
		 });
	
	
   
	}
});

function is_int(value){ 
  if((parseFloat(value) == parseInt(value)) && !isNaN(value)){
      return true;
  } else { 
      return false;
  } 
}

jQuery('.records .um-field-value').click(function(event) {
	basevalue = jQuery(this).text();
if(is_int(basevalue)){

	basevalue = parseInt(basevalue);

	sixty = Math.floor(basevalue * .6);
	sixtyfive = Math.floor(basevalue * .65);
	seventy = Math.floor(basevalue * .7);
	seventyfive = Math.floor(basevalue * .75);
	eighty = Math.floor(basevalue * .8);
	eightyfive = Math.floor(basevalue * .85);
	ninety = Math.floor(basevalue * .9);
	ninetyfive = Math.floor(basevalue * .95);
	hundred = Math.floor(basevalue * 1);

	jQuery('#weightdata').html("<table>" + 
		"<thead><tr><td>%</td><td>Weight</td><td>Per side (-45lb Bar)</td><td>Per side (-35lb Bar)</td></tr></thead>"+
		"<tr><td>60%</td><td>"+ sixty + "</td><td>" +   ((sixty-45) / 2) + "</td><td>" + ((sixty-35) / 2) + "</td><td></tr>" +
		"<tr><td>65%</td><td>"+ sixtyfive + "</td><td>" +   ((sixtyfive-45) / 2) + "</td><td>" + ((sixtyfive-35) / 2) + "</td><td></tr>" +
		"<tr><td>70%</td><td>"+ seventy + "</td><td>" +   ((seventy-45) / 2) + "</td><td>" + ((seventy-35) / 2) + "</td><td></tr>" +
		"<tr><td>75%</td><td>"+ seventyfive + "</td><td>" +   ((seventyfive-45) / 2) + "</td><td>" + ((seventyfive-35) / 2) + "</td><td></tr>" +
		"<tr><td>80%</td><td>"+ eighty + "</td><td>" +   ((eighty-45) / 2) + "</td><td>" + ((eighty-35) / 2) + "</td><td></tr>" +
		"<tr><td>85%</td><td>"+ eightyfive + "</td><td>" +   ((eightyfive-45) / 2) + "</td><td>" + ((eightyfive-35) / 2) + "</td><td></tr>" +
		"<tr><td>90%</td><td>"+ ninety + "</td><td>" +   ((ninety-45) / 2) + "</td><td>" + ((ninety-35) / 2) + "</td><td></tr>" +
		"<tr><td>95%</td><td>"+ ninetyfive + "</td><td>" +   ((ninetyfive-45) / 2) + "</td><td>" + ((ninetyfive-35) / 2) + "</td><td></tr>" +
		"<tr><td>100%</td><td>"+ hundred + "</td><td>" +   ((hundred-45) / 2) + "</td><td>" + ((hundred-35) / 2) + "</td><td></tr>" +
		"</table>");
	jQuery('.modal-overlay').show();
	jQuery('#weightdata').show();

}else{return false;}
	parseInt

	
});

function hideModal(){
	jQuery('.modal-overlay').hide();
	jQuery('#weightdata').hide();
}

</script>
	
	
	<?php 	endif;	?>
	
</div>
	<?php 	endif;	?>
<div class="modal-overlay" onclick="hideModal();"></div>
<div id="weightdata" class="modal" onclick="hideModal();"></div>

<?php //CUSTOM CODE END *************************** ?>
