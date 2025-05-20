<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Lost Items</title>
</head>
<body>
    <h1>Lost Items Page</h1>
    <p>List of lost items will go here.</p>
    <a href="index.php">Back to Home</a>
</body>
</html>
