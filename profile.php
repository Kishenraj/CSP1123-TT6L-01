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

$user_id = $_SESSION['user_id'];
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
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
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

    /* Top right nav bar */
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
      border-radius: 6px;
      margin-left: 10px;
      transition: background-color 0.3s ease;
    }

    .nav-link:hover {
      background-color: #555;
    }

    .nav-container {
      position: fixed;
      top: 0;
      right: 0;
      padding: 20px;
      z-index: 100;
    }
  </style>
</head>
<body>

  <!-- Top-right Navigation -->
  <div class="nav-container">
    <div class="nav">
      <a href="index.php" class="nav-link">🏠 Home</a>
      <a href="logout.php" class="nav-link">🚪 Logout</a>
    </div>
  </div>

  <!-- Centered Profile Box -->
  <div class="profile-container">
    <img src="<?php echo $profile && $profile['profile_pic'] ? $profile['profile_pic'] : 'default-profile.png'; ?>" alt="Profile Picture" class="profile-pic">
    <h2><?php echo $profile ? htmlspecialchars($profile['name']) : 'No Name'; ?></h2>
    <p><strong>Student/Staff ID:</strong> <?php echo $profile ? htmlspecialchars($profile['student_id']) : 'N/A'; ?></p>
    <p><strong>Phone:</strong> <?php echo $profile ? htmlspecialchars($profile['phone']) : 'N/A'; ?></p>
    <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
  </div>

</body>
</html>
