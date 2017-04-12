<?php

/*

    Template Name: Records

*/

?>
<div id="records-wrapper">
<?php get_header(); ?>

<script>
	function recordChanged(){
		if(jQuery('#recordSelect').val() != ''){
			 window.location="/records/?RECORD=" + jQuery('#recordSelect').val();
		}
	}
</script>

    <div class="main-content content">
		<div class="white-background container">
		<br>
		<h1>MEMBERS RECORDS RANKS</h1>
		<h5>Select a record to view current leaderboard.</h5>
		<select name="" id="recordSelect" onchange="recordChanged();">
		<option value="">Select A Record</option>
			<?php
				$recordList = getRecordsList();

				foreach ($recordList as $key => $value): 
					if(strcasecmp($value,"Other Records") != 0):
				?>
			
				<option value="<?php echo $value; ?>"><?php echo $value; ?></option>

				<?php
					endif;
				endforeach;
			?>
		</select>
		<div style="font-size: 12px;"><sub>*Some records are based on # of reps in 2 minutes. 
		<br>(ex. Air Squats, Pull-Ups, Full release Push-Ups, Target Burpees (6 inches above max reach), Double Unders, Diver Sit ups, Bench Press (75/135 lbs))</sub>
		<br><br>
		<sub>**If your records aren't showing or you're in the wrong table below, please log in and update your records/gender.</sub>

		</div>


   	<?php

   $userArr = get_users();

   	if(isset($_REQUEST['USER'])){
       $user = get_userdatabylogin($_REQUEST['USER']);

       $userID = $user->ID;
    }
    elseif (isset($_REQUEST['RECORD'])) {
   		$recordName = $_REQUEST['RECORD'];   	   	 
    }

   	if(isset($_REQUEST['RECORD'])){
   		$recordName = $_REQUEST['RECORD'];
   	}

	if(isset($userID) && isset($recordName)){
		print_r( $recordName . " Record is " . getUserRecord($userID, $recordName));
	}elseif(isset($recordName)){
		showAllRecord($userArr, $recordName);
	}



   		function showAllRecord($userArr, $recordName){// given a record name return the list of every users record
   			//global $ultimatemember; 
   			foreach ($userArr as $key => $value) {
   				
   				um_fetch_user( $value->ID );
				
   				// var_dump($ultimatemember->user);
   				// $name = um_user('display_name');
   				// var_dump($name);
   				$gender = um_user('user_gender');
   				$gender = $gender[0];
   				$username = $value->user_login;
   				//var_dump($gender);
   				if(strcasecmp($gender,"Male") == 0 || $gender == false){//male
	   				$userRecord = getUserRecord($value->ID, $recordName);
	   				if(strcasecmp($userRecord,'NOT SET') != 0){
	   					$fullname = $value->first_name . ' ' . $value->last_name;
	   					$resultsMale[$fullname] = $userRecord;
	   					$resultsMaleUserName[$fullname] = $username;
						echo '<div class="hidden">'. $fullname .' (' .$username . ' - ' . $gender . ')</div>';
	   					
	   				}
   				}else{
   					$userRecord = getUserRecord($value->ID, $recordName);
	   				if(strcasecmp($userRecord,'NOT SET') != 0){
	   					$fullname = $value->first_name . ' ' . $value->last_name;
	   					$resultsFemale[$fullname] = $userRecord;
	   					$resultsFemaleUserName[$fullname] = $username;
						echo '<div class="hidden">'. $fullname .' (' .$username . ' - ' . $gender . ')</div>';
	   					
	   				}
   				}
   				um_reset_user();
   			}

   			if($recordName == '1 Mile Run'){
   				asort($resultsMale);
   				asort($resultsFemale);
   			}else{
   				arsort($resultsMale);
   				arsort($resultsFemale);
   			}

   			

   			echo '<h3 style="text-align:center;">MENS ' . strtoupper($recordName) . '</h3>';
   			echo '<table>';
   			echo '<tr><td><b>Name</b></td><td><b>Best Record</b></td></tr>';
   			foreach ($resultsMale as $key => $value) {
   					echo '<tr>';
   					echo '<td><a href="/user/' . $resultsMaleUserName[$key] . '">' . $key . '</a></td><td>' . $value . '</td>';
   					echo '</tr>';
   			}

   			echo '</table>';

   			echo '<h3 style="text-align:center;">WOMENS ' .  strtoupper($recordName) . '</h3>';
   			echo '<table>';
   			echo '<tr><td><b>Name</b></td><td><b>Best Record</b></td></tr>';
   			foreach ($resultsFemale as $key => $value) {
   					echo '<tr>';
   					echo '<td><a href="/user/' . $resultsFemaleUserName[$key] . '">' . $key . '</a></td><td>' . $value . '</td>';
   					echo '</tr>';
   			}

   			echo '</table>';
   		}

	    function getUserRecord($userID, $recordName){// given userID and record name returns latest record value

	       global $wpdb;
		   $query = "SELECT * FROM wppr_members_log WHERE member_id = " . $userID;
		   $results = $wpdb->get_results($query);

		   // print_r($results);

		   foreach ($results as $key => $value) {//records
		   	if( $value->data_type == 'records'){
		   		$records = html_entity_decode($value->data_string);
		   		$records = explode('<div class="record">',$records);
		   		foreach ($records as $key => $value) {
		   			$value = str_ireplace('</div>', '', $value);
					if(strlen($value) <= 1){ continue; }
		   			$recordArr[$key] = explode(' - ',$value);
		   			$recordArr[$key][0] = str_ireplace('<span class="record_name">', '', $recordArr[$key][0]);
		   			$recordArr[$key][0] = str_ireplace('</span>', '', $recordArr[$key][0]);
					$recordArr[$key][0] = trim($recordArr[$key][0]);

		   			$recordArr[$key][1] = str_ireplace('<span class="record_value">', '', $recordArr[$key][1]);
		   			$recordArr[$key][1] = str_ireplace('</span>', '', $recordArr[$key][1]);
					$recordArr[$key][1] = trim($recordArr[$key][1]);
		   			
		   			if(strcasecmp($recordName,$recordArr[$key][0]) == 0){
		   				if($recordArr[$key][1] == ""){
		   					return 'NOT SET';}
		   				else{
		   					return $recordArr[$key][1];
		   				}
		   			}
		   			//print_r('<div>' . $recordArr[$key][0] . " - " . $recordArr[$key][1] . '</div>');
		   		}
		   		// var_dump($recordArr);
		   		// echo html_entity_decode($value->data_string);
		   	break;
		   	}
		   	
		   }

		   return 'NOT SET';
		}

		function getRecordsList(){// returns array of available records

	       global $wpdb;
		   $query = "SELECT * FROM wppr_members_log";
		   $results = $wpdb->get_results($query);

		   //print_r($results);

	   foreach ($results as $key => $value) {//records
	   	if( $value->data_type == 'records'){
	   		$records = html_entity_decode($value->data_string);
	   		$records = explode('<div class="record">',$records);
	   		foreach ($records as $key => $value) {
	   			$value = str_ireplace('</div>', '', $value);
				if(strlen($value) <= 1){ continue; }
	   			$recordArr[$key] = explode(' - ',$value);
	   			$recordArr[$key][0] = str_ireplace('<span class="record_name">', '', $recordArr[$key][0]);
	   			$recordArr[$key][0] = str_ireplace('</span>', '', $recordArr[$key][0]);
				$recordArr[$key][0] = trim($recordArr[$key][0]);
	   		}
		 //break;
	   	}
	   	
	   }
		
		$recordList;

		foreach ($recordArr as $key => $value) {
			$recordList[$key] = $value[0];
		}

		sort($recordList);

		return $recordList;

		}



       ?>

<br>
<br>
<br>
<br>
<br>
		</div>
    </div>



<?php get_footer(); ?>
</div>