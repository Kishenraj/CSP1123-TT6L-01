<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([]);
    exit();
}

include 'connect.php';

$currentUserId = $_SESSION['user_id'];

// Get all users except the current user
$sql = "SELECT id, CONCAT(firstName, ' ', lastName) AS name FROM users WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $currentUserId);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
?>
