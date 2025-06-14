<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$current_user_id = $_SESSION['user_id'];

if (!isset($_GET['user_id'])) {
    die("Invalid user");
}
$chat_partner_id = intval($_GET['user_id']);

if ($chat_partner_id == $current_user_id) {
    die("You can't chat with yourself");
}

// Get all recent chat users
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

// Fetch chat partner info
$stmt_user = $conn->prepare("SELECT id, firstName, lastName FROM users WHERE id = ?");
$stmt_user->bind_param("i", $chat_partner_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
if ($result_user->num_rows == 0) die("Invalid user");
$partner = $result_user->fetch_assoc();
$partner_fullname = $partner['firstName'] . ' ' . $partner['lastName'];

// Handle message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);
    if ($message !== "") {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $current_user_id, $chat_partner_id, $message);
        $stmt->execute();
    }
}

// Get chat messages
$stmt = $conn->prepare("SELECT sender_id, message, sent_at FROM messages WHERE 
    (sender_id = ? AND receiver_id = ?) OR 
    (sender_id = ? AND receiver_id = ?) 
    ORDER BY sent_at ASC");
$stmt->bind_param("iiii", $current_user_id, $chat_partner_id, $chat_partner_id, $current_user_id);
$stmt->execute();
$messages = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chat with <?= htmlspecialchars($partner_fullname) ?></title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
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
        .user-link:hover, .user-link.active {
            background-color: #d0f0d0;
        }
        .main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
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
        .chat-header {
            background: #128c7e;
            color: white;
            padding: 10px 15px;
            font-size: 18px;
        }
        .chat-box {
            flex-grow: 1;
            overflow-y: auto;
            padding: 15px;
            background: #f9f9f9;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            max-width: 60%;
            clear: both;
        }
        .sent {
            background-color: #dcf8c6;
            float: right;
        }
        .received {
            background-color: #ffffff;
            float: left;
        }
        form {
            display: flex;
            padding: 10px;
            background: #eee;
        }
        input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 15px;
            border: none;
            background: #128c7e;
            color: white;
            margin-left: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Chats</h2>
    <?php while ($user = $users->fetch_assoc()): ?>
        <a class="user-link <?= $user['id'] == $chat_partner_id ? 'active' : '' ?>" href="chat.php?user_id=<?= $user['id'] ?>">
            <?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?>
        </a>
    <?php endwhile; ?>
</div>

<div class="main">
    <div class="nav">
        <a href="index.php">🏠 Home</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="chat-header">
        Chat with <?= htmlspecialchars($partner_fullname) ?>
    </div>

    <div class="chat-box">
        <?php while ($row = $messages->fetch_assoc()): ?>
            <div class="message <?= $row['sender_id'] == $current_user_id ? 'sent' : 'received' ?>">
                <?= htmlspecialchars($row['message']) ?><br>
                <small><?= $row['sent_at'] ?></small>
            </div>
        <?php endwhile; ?>
    </div>

    <form method="POST">
        <input type="text" name="message" placeholder="Type your message..." required>
        <button type="submit">Send</button>
    </form>
</div>

</body>
</html>
