<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$username = $_POST['username'];
$itemdescription = $_POST['itemdescription'];
$image = $_POST['image'];


$sql = "INSERT INTO Lost_items (username, itemdescription, image)
VALUES ('$username', '$itemdescription', '$image');

if (mysqli_query($conn, $sql) {
  echo "Lost item added.";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?> 