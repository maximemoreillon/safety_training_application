<?php

// Connectto MySQL
require "includes/mysql_connect.php";

$group_name = mysqli_real_escape_string($conn, $_REQUEST['group_name']);

$sql = "DROP TABLE `".$group_name."`;";

if ($conn->query($sql) === TRUE) {
  $conn->close();
  header("Location: manage_groups.php");
  die();
}
else {
  echo "Error deleting group: " . $conn->error;
}
$conn->close();

?>
