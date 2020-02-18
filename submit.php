<?php

// Connectto MySQL
require "includes/mysql_connect.php";

// Extract info from request
$group_name = mysqli_real_escape_string($conn, $_REQUEST['group_name']);
$unique_image_name = uniqid();

// Query to add a line of scores to the table
// Set each player's score
$sql .= "INSERT INTO `$group_name` (image";

// Get the names from the request
for ($i = 0; $i < count($_REQUEST['members_name']); $i++) {
  $member_name = mysqli_real_escape_string($conn, $_REQUEST['members_name'][$i]);
  $sql .= ", `".$member_name."`";
}

// Image: The results are always in png
$sql .= ") VALUES ('" .$unique_image_name.".png'";

// Get the score from the request and add them to the query
for ($i = 0; $i < count($_REQUEST['members_points']); $i++) {
  $member_points = mysqli_real_escape_string($conn, $_REQUEST['members_points'][$i]);
  $sql .= ", '".$member_points."'";
}

$sql.= ");";

// Count errors
$errors = 0;

// Send the query
if ($conn->multi_query($sql) === TRUE) {
  // Query OK
}
else {
  echo "Error: " . $sql . "<br>" . $conn->error;
  $errors ++;
}

mysqli_close($conn);

// Upload results image
$upload_dir = "images/results/";
$img = $_POST['image'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$file = $upload_dir . $unique_image_name . ".png";

if (file_put_contents($file, $data)) {
  // Image OK
}
else {
  echo "Error uploading image";
  $errors ++;
}

// Redirect
if($errors == 0) {
  header("Location: show_results.php?group_name=$group_name");
  die();
}

?>
