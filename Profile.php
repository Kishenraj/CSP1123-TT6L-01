<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "loginn");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM user_profiles WHERE user_id = $user_id";
$result = $conn->query($sql);
$profile = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Profile</title>
  <style>
    body, html {
        height: 100%;
        width: 100%;
        font-family: Arial, sans-serif;
        background-image: url('Campus.webp');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        margin: 0;
        padding: 0;
    }

    .profile-container {
      max-width: 400px;
      margin: 100px auto 0;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .profile-pic {
      width: 130px;
      height: 130px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #4CAF50;
      margin-bottom: 20px;
    }

    h2 {
      margin-bottom: 10px;
      color: #333;
    }

    p {
      margin: 5px 0;
      color: #555;
    }

    .edit-btn {
      display: inline-block;
      margin-top: 15px;
      padding: 10px 20px;
      background: #4CAF50;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      transition: background 0.3s ease;
    }

    .edit-btn:hover {
      background: #45a049;
    }

    .nav-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1000;
      display: flex;
      gap: 10px;
    }

    .nav-container a {
      text-decoration: none;
      font-weight: bold;
      color: #fff;
      background-color: #000;
      padding: 10px 15px;
      border-radius: 6px;
      transition: background-color 0.3s ease;
    }

    .nav-container a:hover {
      background-color: #333;
    }
  </style>
</head>
<body>

  <div class="nav-container">
    <a href="index.php">🏠 Home</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <div class="profile-container">
    <img src="<?= $profile && $profile['profile_pic'] ? htmlspecialchars($profile['profile_pic']) : 'default-profile.png'; ?>" alt="Profile Picture" class="profile-pic">
    <h2><?= $profile ? htmlspecialchars($profile['name']) : 'No Name'; ?></h2>
    <p><strong>Student/Staff ID:</strong> <?= $profile ? htmlspecialchars($profile['student_id']) : 'N/A'; ?></p>
    <p><strong>Phone:</strong> <?= $profile ? htmlspecialchars($profile['phone']) : 'N/A'; ?></p>
    <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
  </div>

</body>
</html>
