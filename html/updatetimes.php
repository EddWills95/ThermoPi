<?php 		        

    $times = $_POST['time'];
    $temps = $_POST['temp'];

    $servername = "localhost";
    $username = "root";
    $password = "**Password In Here**";
    $dbname = "set_temp";

    $con2=new mysqli($servername, $username, $password, $dbname);

    for ($i=0; $i<8; $i++) {
        $id = ($i + 1); 
        $sql = "UPDATE timedtemp SET time = $times[$i], tempset = $temps[$i] WHERE id = ($id)"; 
        
        if ($con2->query($sql) === TRUE) {
//            print_r("Record updated successfully \n");
        } else {
            print_r("Error updating record: " . $conn->error);
        }
    }

	header("Location: /index.php", TRUE, 303); 
?>

<!--
<form action="index.php">
    <input type="submit" value="Go Home" />
</form>-->
