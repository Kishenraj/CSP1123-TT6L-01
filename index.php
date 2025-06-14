<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['is_admin'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lost & Found - Home</title>
  <link rel="stylesheet" href="style-index.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #f4f4f4;
    }

    .main-content {
      text-align: center;
      padding: 100px 20px;
    }

    .main-btn {
      display: inline-block;
      padding: 14px 28px;
      margin: 20px;
      background-color: #4CAF50;
      color: white;
      text-decoration: none;
      font-size: 18px;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }

    .main-btn:hover {
      background-color: #45a049;
    }

    .btn-survey {
      background-color: #673ab7;
    }

    .btn-survey:hover {
      background-color: #5e35b1;
    }

    /* Profile Icon Top Left */
    .profile-button {
      position: absolute;
      top: 20px;
      left: 20px;
      width: 60px;
      height: 60px;
      background-color: #4CAF50;
      color: white;
      border-radius: 50%;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      font-size: 28px;
      text-decoration: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .profile-button small {
      font-size: 12px;
      line-height: 1;
    }

    .profile-button:hover {
      background-color: #45a049;
    }

    /* Navigation Bar Top Right */
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
      transition: background-color 0.3s ease;
      margin-left: 10px;
      display: inline-block;
    }

    .nav-link:hover {
      background-color: #555;
    }

    .chat-button {
      background-color: #25D366 !important;
    }

    .chat-button:hover {
      background-color: #128c3e !important;
    }
  </style>
</head>
<body>

  <!-- Profile Icon Top Left -->
  <a href="profile.php" class="profile-button" title="Profile">
    <span>&#128100;</span>
    <small>Profile</small>
  </a>

  <!-- Navigation Bar Top Right -->
  <div class="nav">
    <a href="logout.php" class="nav-link">Logout</a>
    <a href="chat_list.php" class="nav-link chat-button" title="Chat">&#128172; Chat</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h1>Welcome to the Lost & Found Portal</h1>
    <p>Select a category to continue:</p>
    <div class="btn-row">
      <a href="lost.php" class="main-btn">Report Lost Item</a>
      <a href="found.php" class="main-btn">Report Found Item</a>
      
    </div>
   <div class="btn-survey-container">
    <a href="survey.php" class="main-btn btn-survey">Survey</a>
</div>

  </div>

</body>
</html>
