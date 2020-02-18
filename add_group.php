<?php

// Connectto MySQL
require "includes/mysql_connect.php";

// Get group name from request
$group_name = mysqli_real_escape_string($conn, $_REQUEST['group_name']);

// Create a table without members
// ADD FILEDS FOR IMAGE AND RESULTS
$sql = "CREATE TABLE `$group_name` (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, date TIMESTAMP, image TEXT);";

// Adding a column for each member
for ($i = 0; $i < count($_REQUEST['member_names']); $i++) {
  $member_name = mysqli_real_escape_string($conn, $_REQUEST['member_names'][$i]);
  $sql .= "ALTER TABLE `$group_name` ADD `$member_name` INT( 6 );";
}

// Set each player's score to zero
$sql .= "INSERT INTO `$group_name` (";

for ($i = 0; $i < count($_REQUEST['member_names']); $i++) {
  $member_name = mysqli_real_escape_string($conn, $_REQUEST['member_names'][$i]);
  if($i > 0){
    $sql .= ",";
  }
  $sql .= "`".$member_name."`";
}

$sql .= ") VALUES (";

for ($i = 0; $i < count($_REQUEST['member_names']); $i++) {
  if($i > 0) {
    $sql .= ",";
  }
  $sql .= "0";
}

$sql.= ");";

// Send the query
if ($conn->multi_query($sql) === TRUE) {
  $conn->close();
  header("Location: manage_groups.php");
  die();
}
else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>
