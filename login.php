<?php
session_start();
include 'db.php'; // database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
      // ✅ Store user info in session
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['name'] = $user['name'];

      // ✅ Redirect to home page
      header("Location: home.php");
      exit;
    } else {
      echo "Incorrect password.";
    }
  } else {
    echo "User not found.";
  }
}
?>

<!-- HTML login form -->
<form method="POST" action="">
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Login</button>
</form>
