<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the user is an admin
$user_id = $_SESSION['user_id'];
$check_admin = $conn->query("SELECT is_admin FROM users WHERE id = $user_id");
$admin_row = $check_admin->fetch_assoc();

if (!$admin_row || $admin_row['is_admin'] != 1) {
    echo "Access denied. You are not an admin.";
    exit();
}

// Handle delete requests
if (isset($_GET['delete_lost_id'])) {
    $id = intval($_GET['delete_lost_id']);
    $conn->query("DELETE FROM lost_items WHERE id = $id");
    header("Location: admin.php");
    exit();
}

if (isset($_GET['delete_survey_id'])) {
    $id = intval($_GET['delete_survey_id']);
    $conn->query("DELETE FROM surveys WHERE id = $id");
    header("Location: admin.php");
    exit();
}

// Get all lost items
$lost_items = $conn->query("SELECT * FROM lost_items");

// Get all surveys
$surveys = $conn->query("SELECT * FROM surveys");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }
        h2 {
            color: #444;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        table, th, td {
            border: 1px solid #aaa;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        a.delete {
            color: red;
        }
        .nav {
            margin-bottom: 20px;
        }
        .nav a {
            margin-right: 15px;
            text-decoration: none;
            color: blue;
        }
    </style>
</head>
<body>

<div class="nav">
    <a href="index.php">🏠 Home</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<h2>Admin Panel - All Lost Items</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Date Reported</th>
        <th>User ID</th>
        <th>Action</th>
    </tr>
    <?php while ($item = $lost_items->fetch_assoc()): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= htmlspecialchars($item['title']) ?></td>
            <td><?= htmlspecialchars($item['description']) ?></td>
            <td><?= $item['date_reported'] ?></td>
            <td><?= $item['user_id'] ?></td>
            <td><a class="delete" href="?delete_lost_id=<?= $item['id'] ?>" onclick="return confirm('Delete this lost item?')">Delete</a></td>
        </tr>
    <?php endwhile; ?>
</table>

<h2>Admin Panel - All Surveys</h2>
<table>
    <tr>
        <th>ID</th>
        <th>User Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Item Type</th>
        <th>Model</th>
        <th>Location</th>
        <th>Date Lost</th>
        <th>Action</th>
    </tr>
    <?php while ($survey = $surveys->fetch_assoc()): ?>
        <tr>
            <td><?= $survey['id'] ?></td>
            <td><?= htmlspecialchars($survey['user_name']) ?></td>
            <td><?= htmlspecialchars($survey['phone_number']) ?></td>
            <td><?= htmlspecialchars($survey['user_email']) ?></td>
            <td><?= htmlspecialchars($survey['item_type']) ?></td>
            <td><?= htmlspecialchars($survey['model']) ?></td>
            <td><?= htmlspecialchars($survey['lost_location']) ?></td>
            <td><?= htmlspecialchars($survey['date_lost']) ?></td>
            <td><a class="delete" href="?delete_survey_id=<?= $survey['id'] ?>" onclick="return confirm('Delete this survey?')">Delete</a></td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
