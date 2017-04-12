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
echo '<div class="record-wrapper" id="' . $row->ID . '">' . 	html_entity_decode($row->data_string) .'</div><div class="delete_button" onclick="deleteRecord(' . $row->ID . ')">Delete This Record</div></div>';


  
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
</script>
	
	
	<?php 	endif;	?>
	
</div>
	<?php 	endif;	?>
