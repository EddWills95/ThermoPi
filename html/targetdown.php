<?php 
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '**Password In Here**';
	$dbname = 'temperature';
	
	$conn = new mysqli($dbhost,$dbuser,$dbpass, $dbname);
	
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "UPDATE temp SET target=target - 1";

	if ($conn->query($sql) === TRUE) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . $conn->error;
	}
	$conn->close();
	?>
