<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$survey_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Delete only if the survey belongs to the current user
$stmt = $conn->prepare("DELETE FROM surveys WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $survey_id, $user_id);
$stmt->execute();

header("Location: survey.php");
exit();
