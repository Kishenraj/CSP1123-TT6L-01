<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Get current profile pic path
$sql = "SELECT profile_pic FROM user_profiles WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

if ($profile && !empty($profile['profile_pic']) && file_exists($profile['profile_pic'])) {
    unlink($profile['profile_pic']);
}

$update = "UPDATE user_profiles SET profile_pic = '' WHERE user_id = ?";
$updateStmt = $conn->prepare($update);
$updateStmt->bind_param("i", $user_id);
$updateStmt->execute();

header('Location: edit_profile.php');
exit();
