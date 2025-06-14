<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "loginn");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['save'])) {
    $user_id = $_SESSION['user_id'];
    $name = $conn->real_escape_string($_POST['name']);
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $phone = $conn->real_escape_string($_POST['phone']);

    // Handle profile picture
    $imageName = $_FILES['profile_pic']['name'];
    $imageTmp = $_FILES['profile_pic']['tmp_name'];
    $imagePath = "profile_pic/" . basename($imageName);

    if (move_uploaded_file($imageTmp, $imagePath)) {
        // Insert or update profile
        $check = $conn->query("SELECT * FROM profiles WHERE user_id = $user_id");

        if ($check->num_rows > 0) {
            // Update existing
            $query = "UPDATE profiles SET name='$name', student_id='$student_id', phone='$phone', image_path='$imagePath' WHERE user_id=$user_id";
        } else {
            // Insert new
            $query = "INSERT INTO profiles (user_id, name, student_id, phone, image_path)
                      VALUES ($user_id, '$name', '$student_id', '$phone', '$imagePath')";
        }

        if ($conn->query($query)) {
            header("Location: profile.php");
            exit();
        } else {
            echo "Error saving profile: " . $conn->error;
        }
    } else {
        echo "Failed to upload image.";
    }
}
?>
