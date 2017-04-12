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