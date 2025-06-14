<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM lost_items ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Found Items</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
        }
        .item-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .item-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }
        .item-card img {
            max-width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
        }
        .delete-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 12px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        .chat-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 14px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        .nav {
            margin-bottom: 20px;
            text-align: right;
        }
        .nav a {
            margin-left: 10px;
            text-decoration: none;
            font-weight: bold;
            color: white;
            background-color: #333;
            padding: 8px 12px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="nav">
    <a href="index.php">🏠 Home</a>
    <a href="logout.php">Logout</a>
</div>

<h1>Found Items</h1>

<div class="item-container">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="item-card">
            <a href="item_detail.php?id=<?= $row['id'] ?>">
                <img src="<?= htmlspecialchars($row['image']) ?>" alt="Item Image">
            </a>
            <h3><?= htmlspecialchars($row['item_name']) ?></h3>
            <p><strong>Type:</strong> <?= htmlspecialchars($row['item_type']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
            <p><strong>Date Lost:</strong> <?= htmlspecialchars($row['lost_day']) ?></p>

            <?php if (isset($row['user_id']) && $row['user_id'] == $_SESSION['user_id']): ?>
                <a class="delete-btn" href="delete_item.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
            <?php elseif (isset($row['user_id']) && $row['user_id'] != $_SESSION['user_id']): ?>
                <a class="chat-btn" href="chat.php?user_id=<?= htmlspecialchars($row['user_id']) ?>">Chat with Poster</a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
