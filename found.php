<?php
session_start();
include 'connect.php';

// Fetch all lost items from the database
$sql = "SELECT * FROM lost_items ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Reported Lost Items</title>
  <link rel="stylesheet" href="found-style.css" />
</head>
<body>
  <div class="nav">
    <a href="index.html" class="nav-link">🏠 Home</a>
    <a href="logout.php" class="nav-link">🚪 Logout</a>
  </div>

  <div class="container">
    <h2>📦 Reported Lost Items (Found List)</h2>

    <?php if ($result->num_rows > 0): ?>
      <div class="items">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="item-card">
            <?php if (!empty($row['image']) && file_exists($row['image'])): ?>
              <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Item Image">
            <?php else: ?>
              <div class="no-image">No Image</div>
            <?php endif; ?>

            <div class="info">
              <strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?><br>
              <strong>Item Name:</strong> <?php echo htmlspecialchars($row['item_name']); ?><br>
              <strong>Item Type:</strong> <?php echo htmlspecialchars($row['item_type']); ?><br>
              <strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?><br>
              <strong>Date Lost:</strong> <?php echo htmlspecialchars($row['lost_day']); ?><br>
              <strong>Reported On:</strong> <?php echo htmlspecialchars($row['created_at']); ?><br>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p>No lost items reported yet.</p>
    <?php endif; ?>
  </div>
</body>
</html>
