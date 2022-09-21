<?php
// Testing File

include ('connect.php');

$sqlEvents = "SELECT MAX(`id`) FROM `vocher`;";
$resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));
//$resp = mysqli_fetch_assoc($resultset); //MAX(`id`)

while( $rows = mysqli_fetch_assoc($resultset) ) {
	print $rows['MAX(`id`)'];
}


?>