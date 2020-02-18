<?php

// Get MySQL credentials from external file
require "mysql_credentials.php";

// The name of the Database
$DB_name = 'hazard_prediction_training';

// Create connection
$conn = new mysqli("localhost", $username, $password);

// Check connection
if (mysqli_connect_error()) {
  die("Database connection failed: " . mysqli_connect_error());
}


// Select the database
$db_selected = mysqli_select_db($conn, $DB_name);

// Check if selection succeded. If not, then the DB might not exist
if (!$db_selected) {
  // If we couldn't, then it either doesn't exist, or we can't see it.
  $sql = "CREATE DATABASE $DB_name";

  if($conn->query($sql) === FALSE ) {
    echo 'DB not found and unable to create it. Error: ' . mysql_error() . "\n";
  }
}

?>
