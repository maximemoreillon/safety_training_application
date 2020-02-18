<?php
$servername = "localhost";
$username = "moreillon";
$password = "00174000";
$dbname = "shoushuudan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO `Zero v2` (Higashi, Tamura, Fujitani, Shoji, Moreillon)
VALUES ('38', '11', '45', '35', '48')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
