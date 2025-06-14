<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$my_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['receiver_id'], $data['message'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$receiver_id = intval($data['receiver_id']);
$message = trim($data['message']);

if ($receiver_id === $my_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Cannot message yourself']);
    exit;
}

if ($message === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Empty message']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $my_id, $receiver_id, $message);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
