<?php
$hostname = "localhost";
$username = "root"; 
$dbname = "foodtoast"; 
$conn = new mysqli($hostname, $username, "", $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
