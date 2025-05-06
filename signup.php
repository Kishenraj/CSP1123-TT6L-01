<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

  if ($conn->query($sql) === TRUE) {
    header("Location: login.php");
  } else {
    echo "Error: " . $conn->error;
  }
}
?>

<!-- HTML form -->
<form method="post">
  <input type="text" name="name" placeholder="Name" required />
  <input type="email" name="email" placeholder="Email" required />
  <input type="password" name="password" placeholder="Password" required />
  <button type="submit">Sign Up</button>
</form>
