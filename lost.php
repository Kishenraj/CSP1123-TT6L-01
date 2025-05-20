<?php
session_start();
include 'connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $item_name = $_POST['item_name'];
    $item_type = $_POST['item_type'];
    $location = $_POST['location'];
    $lost_day = $_POST['lost_day'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $filename = basename($_FILES['image']['name']);
        $target_file = $target_dir . time() . '_' . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO lost_items (name, item_name, item_type, location, lost_day, image, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssssss", $name, $item_name, $item_type, $location, $lost_day, $target_file);

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
        $message = "⚠️ Please upload an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Report Lost Item</title>
  <link rel="stylesheet" href="lost-style.css" />
</head>
<body>
  <div class="nav">
    <a href="index.html" class="nav-link">🏠 Home</a>
    <a href="logout.php" class="nav-link">🚪 Logout</a>
  </div>

  <div class="container">
    <h2>📋 Report Lost Item</h2>

    <?php if ($message != ''): ?>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <label for="name">User Name</label>
      <input type="text" name="name" id="name" required>

      <label for="item_name">Item Name</label>
      <input type="text" name="item_name" id="item_name" required>

      <label for="item_type">Type of Item</label>
      <select name="item_type" id="item_type" required>
        <option value="">-- Select Type --</option>
        <option value="Wallet">Wallet</option>
        <option value="Phone">Phone</option>
        <option value="Keys">Keys</option>
        <option value="Bag">Bag</option>
        <option value="Other">Other</option>
      </select>

      <label for="location">Where was it lost?</label>
      <input type="text" name="location" id="location" required>

      <label for="lost_day">Date Lost</label>
      <input type="date" name="lost_day" id="lost_day" required>

      <label for="image">Upload Image</label>
      <input type="file" name="image" id="image" required>

      <input type="submit" value="Submit">
    </form>
  </div>
</body>
</html>
