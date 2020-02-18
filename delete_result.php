<?php

// Connectto MySQL
require "includes/mysql_connect.php";

// Read the request
$group_name = mysqli_real_escape_string($conn, $_REQUEST['group_name']);
$id = mysqli_real_escape_string($conn, $_REQUEST['id']);

$sql = "DELETE FROM `". $group_name ."` WHERE id=".$id.";";

if ($conn->query($sql) === TRUE) {
  $conn->close();
  header("Location: show_results.php?group_name=$group_name");
  die();
}
else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();

?>
