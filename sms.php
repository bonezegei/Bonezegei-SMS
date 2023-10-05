<?php
/**
 * Author : Jofel Batutay (Bonezegei)
 * Date: October 2023
 * Database for Bonezegei SMS API
 */

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// sql to create table
$sql = "CREATE TABLE Messages (
			msg_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			msg_phone   VARCHAR(30)  NOT NULL,
			msg_message VARCHAR(256) NOT NULL,
			msg_status  VARCHAR(30)  NOT NULL,
			msg_time    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
		)";
		
// check if table is already created
if ($conn->query($sql) === TRUE) {} 
else {}

header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');

if(isset($_GET['update'])){
	
	$ms_id=$_GET['update'];
	$ms_status=$_GET['status'];
	$sql = "UPDATE messages SET msg_status='$ms_status' WHERE msg_id=$ms_id";
	if ($conn->query($sql) === TRUE) {
	  echo "msg_status:updated";
	} else {
	  echo "Error: " . $conn->error;
	}
}
else{
	$sql = "SELECT msg_id, msg_phone, msg_message, msg_status, msg_time FROM messages";
	$result = $conn->query($sql);

	$data_count = 0;
	if ( $result !== false && $result->num_rows) {
	  echo "{ \n \"Messages\": [\n  ";
	  while($row = $result->fetch_assoc()) {
		if($data_count>0){
			echo ",\n  ";
		}
		echo "{";
		echo " \"msg_id\":".$row["msg_id"].",\n";
		echo "    \"msg_phone\": \""  .$row["msg_phone"]."\",\n";
		echo "    \"msg_message\": \"".$row["msg_message"]."\",\n";
		echo "    \"msg_status\": \"" .$row["msg_status"]."\",\n";
		echo "    \"msg_time\": \""   .$row["msg_time"]."\"";
		echo "}";
		
		$data_count++;
	  }
	  echo "\n ]\n}";
	} else {
	  echo "0 results";
	}
}

$conn->close();
?>
