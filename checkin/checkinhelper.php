<?php

date_default_timezone_set("PST"); 

$servername = "https://p3nlmysqladm001.secureserver.net/wp/166";
$username = "n71a3580342437";
$password = "Tyt9P3!OtiV";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$doRegister = $_REQUEST['REGISTER'];

if($doRegister == 0){

	$phone = $_REQUEST['PHONE'];

	// Create database
	$sql = "SELECT * FROM clientsList WHERE phone = " . ;
	if ($result = $conn->query($sql) === TRUE) {
	    if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		    	$sql2 = "INSERT INTO clientCheckIns (phone, time)
				VALUES ('" + $phone + "', "+ time() +")";

				if ($conn->query($sql2) === TRUE) {
				   echo '{
			        	"found":true,
			        	"fname": ' . $row["fname"] . ',
			        	"lname": ' . $row["lname"] . ',
			        }';
				} else {
				    echo "Error: " . $sql2 . "<br>" . $conn->error;
				}
		    }


		} else {
			echo '{	"found":false }';
		    
		}
	} else {
	   
	}

}

if($doRegister == 1){

	$fname = $_REQUEST['FNAME'];
	$lname = $_REQUEST['LNAME'];
	$email = $_REQUEST['EMAIL'];
	$phone = $_REQUEST['PHONE'];

	$sql = "INSERT INTO clientsList (fname,lname,email,phone,registeredtime)
	VALUES ('" + $fname + "','" + $lname + "','" + $email + "','" + $phone + "','"+ time() +"')";

	if ($conn->query($sql) === TRUE) {
	   $sql2 = "INSERT INTO clientCheckIns (phone, time)
		VALUES ('" + $phone + "', "+ time() +")";

		if ($conn->query($sql2) === TRUE) {
		   echo '{
	        	"found":true,
	        	"fname": $row["fname"],
	        	"lname": $row["lname"],
	        }';
		} else {
		    echo "Error: " . $sql2 . "<br>" . $conn->error;
		}
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}

}

if(isset($_REQUEST['bykov'])){
	$sql = "SELECT * FROM clientsList";
	if ($result = $conn->query($sql) === TRUE) {
	    if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		    	echo "<div> " . $row["id"] . " | " . $row["fname"] . " | " . $row["lname"] . " | " . $row["email"] . " | " . $row["phone"] . " | " . $row["registeredtime"] . "</div>";				
		    }

		} else {
			echo "No Clients Found":
		}
	} else {
	   echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$sql = "SELECT * FROM clientCheckIns";
	if ($result = $conn->query($sql) === TRUE) {
	    if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		    	echo "<div> " . $row["id"] . " | " . $row["phone"] . " | " . $row["time"] . "</div>";				
		    }

		} else {
			echo "No Clients Found":
		}
	} else {
	   echo "Error: " . $sql . "<br>" . $conn->error;
	}
}



$conn->close();

?>
