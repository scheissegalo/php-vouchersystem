<?php
$host = 'localhost';
$DB = 'vocher';
$user = 'root';
$password = '';

$conn = mysqli_connect($host, $user, $password, $DB) or die("Connection failed: " . mysqli_connect_error());
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>