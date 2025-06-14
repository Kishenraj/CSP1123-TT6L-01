<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$current_user_id = $_SESSION['user_id'];

// Get all users who have chatted with this user
$sql = "
    SELECT u.id, u.firstName, u.lastName, MAX(m.sent_at) as last_message_time
    FROM users u
    LEFT JOIN messages m ON (u.id = m.sender_id AND m.receiver_id = ?) OR (u.id = m.receiver_id AND m.sender_id = ?)
    WHERE u.id != ?
    GROUP BY u.id
    ORDER BY last_message_time DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $current_user_id, $current_user_id, $current_user_id);
$stmt->execute();
$users = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chat List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 300px;
            background-color: #ededed;
            overflow-y: auto;
            border-right: 1px solid #ccc;
        }
        .sidebar h2 {
            margin: 0;
            padding: 15px;
            background-color: #075e54;
            color: white;
        }
        .user-link {
            display: block;
            padding: 15px;
            border-bottom: 1px solid #ccc;
            text-decoration: none;
            color: black;
        }
        .user-link:hover {
            background-color: #ddd;
        }
        .main {
            flex-grow: 1;
            padding: 20px;
        }
        .nav {
            padding: 10px;
            background-color: #075e54;
            text-align: right;
        }
        .nav a {
            color: white;
            margin-left: 15px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Chats</h2>
    <?php while ($user = $users->fetch_assoc()): ?>
        <a class="user-link" href="chat.php?user_id=<?= $user['id'] ?>">
            <?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?>
        </a>
    <?php endwhile; ?>
</div>

<div class="main">
    <div class="nav">
        <a href="index.php">🏠 Home</a>
        <a href="logout.php">Logout</a>
    </div>
    <h2>Select a user to start chatting</h2>
</div>

</body>
</html>
