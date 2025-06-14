<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle survey submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';
    $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $item_type = isset($_POST['item_type']) ? $_POST['item_type'] : '';
    $model = isset($_POST['model']) ? $_POST['model'] : '';
    $lost_location = isset($_POST['lost_location']) ? $_POST['lost_location'] : '';
    $lost_date = isset($_POST['lost_date']) ? $_POST['lost_date'] : '';
    $image_path = null;

    if ($user_name && $phone_number && $email && $item_type && $model && $lost_location && $lost_date) {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageName = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES['image']['name']));
            $targetPath = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $image_path = $targetPath;
            }
        }

        $stmt = $conn->prepare("INSERT INTO surveys (user_id, user_name, phone_number, email, item_type, model, lost_location, lost_date, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $user_id, $user_name, $phone_number, $email, $item_type, $model, $lost_location, $lost_date, $image_path);
        $stmt->execute();
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $survey_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM surveys WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $survey_id, $user_id);
    $stmt->execute();
}

// Fetch all surveys
$survey_list = $conn->query("
    SELECT surveys.*, users.firstName, users.lastName 
    FROM surveys 
    JOIN users ON surveys.user_id = users.id 
    ORDER BY surveys.submitted_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Survey Form</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f7f7f7;
            padding: 30px;
        }
        h1 {
            text-align: center;
        }
        form {
            max-width: 600px;
            background: #fff;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        .survey-list {
            margin-top: 40px;
        }
        .survey-card {
            background: white;
            margin: 15px auto;
            padding: 15px;
            border-radius: 10px;
            max-width: 800px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        .survey-card img {
            max-width: 150px;
            height: auto;
            margin-top: 10px;
        }
        .btn-delete {
            background: #e74c3c;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn-chat {
            background: #2980b9;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            margin-left: 10px;
        }
        .nav {
            margin-bottom: 20px;
            text-align: right;
        }
        .nav a {
            margin-left: 10px;
            text-decoration: none;
            background-color: #333;
            color: white;
            padding: 8px 14px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="nav">
    <a href="index.php">🏠 Home</a>
    <a href="logout.php">Logout</a>
</div>

<h1>Lost Item Survey</h1>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="user_name" placeholder="Your Name" required>
    <input type="text" name="phone_number" placeholder="Phone Number" required>
    <input type="email" name="email" placeholder="Email" required>
    <select name="item_type" required>
        <option value="">Select Item Type</option>
        <option>Phone</option>
        <option>Tablet</option>
        <option>Laptop</option>
        <option>Gadget</option>
        <option>Other</option>
    </select>
    <input type="text" name="model" placeholder="Model" required>
    <input type="text" name="lost_location" placeholder="Where was it lost?" required>
    <input type="date" name="lost_date" required>
    <input type="file" name="image" accept="image/*">
    <button type="submit">Submit Survey</button>
</form>

<div class="survey-list">
    <?php while ($row = $survey_list->fetch_assoc()): ?>
        <div class="survey-card">
            <h3><?= htmlspecialchars($row['item_type']) ?> - <?= htmlspecialchars($row['model']) ?></h3>
            <p><strong>Reported by:</strong> <?= htmlspecialchars($row['user_name']) ?> (<?= htmlspecialchars($row['email']) ?>)</p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone_number']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($row['lost_location']) ?> | <strong>Date:</strong> <?= htmlspecialchars($row['lost_date']) ?></p>
            <?php if ($row['image_path']): ?>
                <img src="<?= $row['image_path'] ?>" alt="Item Image">
            <?php endif; ?>
            <div>
                <?php if ($row['user_id'] == $_SESSION['user_id']): ?>
                    <a class="btn-delete" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this survey?')">Delete</a>
                <?php else: ?>
                    <a class="btn-chat" href="chat.php?user_id=<?= $row['user_id'] ?>">Chat with Poster</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
