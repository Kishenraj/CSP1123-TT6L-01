<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];
    $item_name = $_POST['item_name'];
    $item_type = $_POST['item_type'];
    $location = $_POST['location'];
    $lost_day = $_POST['lost_day'];
    $user_id = $_SESSION['user_id'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $filename = basename($_FILES['image']['name']);
        $target_file = $target_dir . time() . '_' . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO lost_items (name, item_name, item_type, location, lost_day, image, user_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssssssi", $name, $item_name, $item_type, $location, $lost_day, $target_file, $user_id);

            if ($stmt->execute()) {
                $message = "✅ Lost item reported successfully!";
            } else {
                $message = "❌ Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "❌ Failed to upload image.";
        }
    } else {
        $message = "❌ Please upload an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Lost Item</title>
    <link rel="stylesheet" href="lost-style.css">
    <style>
        .item-card {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        .item-card img {
            max-width: 150px;
            display: block;
            margin-bottom: 10px;
        }
        .message {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="nav">
    <a href="index.php" class="nav-link">Home</a>
    <a href="logout.php" class="nav-link">Logout</a>
</div>

<div class="container">
    <h2>Report Lost Item</h2>

    <?php if ($message != ''): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label for="name">User Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="item_name">Item Name:</label>
        <input type="text" name="item_name" id="item_name" required>

        <label for="item_type">Type of Item:</label>
        <select name="item_type" id="item_type" required>
            <option value="">-- Select Item Type --</option>
            <option value="Wallet">Wallet</option>
            <option value="Phone">Phone</option>
            <option value="Keys">Keys</option>
            <option value="Bag">Bag</option>
            <option value="Other">Other</option>
        </select>

        <label for="location">Where was it lost?</label>
        <input type="text" name="location" id="location" required>

        <label for="lost_day">Date Lost:</label>
        <input type="date" name="lost_day" id="lost_day" required>

        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>

        <input type="submit" value="Submit">
    </form>

    <h2>All Reported Lost Items</h2>

    <?php
    $result = $conn->query("SELECT * FROM lost_items ORDER BY created_at DESC");
    ?>

    <div class="items-list">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="item-card">
            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Item Image">
            <p><strong><?php echo htmlspecialchars($row['item_name']); ?></strong></p>
            <p>Type: <?php echo htmlspecialchars($row['item_type']); ?></p>
            <p>Lost at: <?php echo htmlspecialchars($row['location']); ?></p>
            <p>Date: <?php echo htmlspecialchars($row['lost_day']); ?></p>
            <p>Reported by: <?php echo htmlspecialchars($row['name']); ?></p>

            <?php if ($_SESSION['user_id'] == $row['user_id']): ?>
                <form method="post" action="delete_item.php" onsubmit="return confirm('Are you sure you want to delete this item?');">
                    <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                    <button type="submit">🗑️ Delete</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
    </div>
</div>

</body>
</html>
