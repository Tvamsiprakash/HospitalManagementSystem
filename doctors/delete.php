<?php
require_once('../connection.php');
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM doctors WHERE id = $id");
header("Location: list.php");
?>
