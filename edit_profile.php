<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Fetch existing profile
$sql = "SELECT * FROM user_profiles WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

// Handle profile update form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'] ?? '';
    $student_id = $_POST['student_id'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // Handle profile picture upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'profile_pics/';
        $tmpName = $_FILES['profile_pic']['tmp_name'];
        $filename = time() . '_' . basename($_FILES['profile_pic']['name']);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($tmpName, $targetFile)) {
            // Delete old picture if exists
            if (!empty($profile['profile_pic']) && file_exists($profile['profile_pic'])) {
                unlink($profile['profile_pic']);
            }
            $profile_pic_path = $targetFile;
        } else {
            $profile_pic_path = $profile['profile_pic'] ?? '';
        }
    } else {
        $profile_pic_path = $profile['profile_pic'] ?? '';
    }

    if ($profile) {
        // Update existing
        $updateSql = "UPDATE user_profiles SET name=?, student_id=?, phone=?, profile_pic=? WHERE user_id=?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssssi", $name, $student_id, $phone, $profile_pic_path, $user_id);
        $updateStmt->execute();
    } else {
        // Insert new profile
        $insertSql = "INSERT INTO user_profiles (user_id, name, student_id, phone, profile_pic) VALUES (?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("issss", $user_id, $name, $student_id, $phone, $profile_pic_path);
        $insertStmt->execute();
    }

    // Refresh profile data after update
    header("Location: edit_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Profile</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 500px;
      margin: 50px auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      background: #f9f9f9;
    }
    h2 {
      text-align: center;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }
    input[type="text"], input[type="file"] {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      box-sizing: border-box;
    }
    button {
      margin-top: 20px;
      padding: 10px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #45a049;
    }
    .nav {
      position: absolute;
      top: 20px;
      right: 20px;
    }
    .nav-link {
      text-decoration: none;
      font-weight: bold;
      color: white;
      background-color: #333;
      padding: 10px 15px;
      border-radius: 5px;
      margin-left: 10px;
      transition: background-color 0.3s ease;
    }
    .nav-link:hover {
      background-color: #555;
    }
    .profile-pic {
      display: block;
      margin: 20px auto;
      max-width: 150px;
      border-radius: 50%;
      object-fit: cover;
    }
    .delete-pic-btn {
      display: block;
      margin: 10px auto 20px auto;
      background-color: #e74c3c;
    }
    .delete-pic-btn:hover {
      background-color: #c0392b;
    }
  </style>
</head>
<body>

  <div class="nav">
    <a href="index.php" class="nav-link">🏠 Home</a>
    <a href="logout.php" class="nav-link">🚪 Logout</a>
  </div>

  <h2>Edit Your Profile</h2>

  <?php if (!empty($profile['profile_pic']) && file_exists($profile['profile_pic'])): ?>
    <img src="<?= htmlspecialchars($profile['profile_pic']) ?>" alt="Profile Picture" class="profile-pic">
    <form action="delete_profile_pic.php" method="post" onsubmit="return confirm('Are you sure you want to delete your profile picture?');">
      <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
      <button type="submit" class="delete-pic-btn">Delete Picture</button>
    </form>
  <?php else: ?>
    <p style="text-align:center;">No profile picture uploaded.</p>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($profile['name'] ?? '') ?>" required />

    <label for="student_id">Student/Staff ID:</label>
    <input type="text" id="student_id" name="student_id" value="<?= htmlspecialchars($profile['student_id'] ?? '') ?>" required />

    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($profile['phone'] ?? '') ?>" required />

    <label for="profile_pic">Upload Profile Picture:</label>
    <input type="file" id="profile_pic" name="profile_pic" accept="image/*" />

    <button type="submit" name="update_profile">Save Profile</button>
  </form>

</body>
</html>
