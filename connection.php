<?php
$host = "localhost";
$user = "root";
$password = "";  // or your MySQL root password
$dbname = "vamsi_hosps";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
