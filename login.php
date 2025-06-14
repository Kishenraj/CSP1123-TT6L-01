<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login & Register</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      font-family: Arial;
      background: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 300px;
      text-align: center;
    }
    input, button {
      margin: 10px 0;
      width: 100%;
      padding: 10px;
    }
    .switch-btn {
      background: none;
      border: none;
      color: blue;
      cursor: pointer;
      text-decoration: underline;
    }
    .admin-btn {
      background-color: #800080;
      color: white;
      border: none;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="container" id="signup" style="display:none;">
  <h2>Register</h2>
  <form method="post" action="register_login.php">
    <input type="text" name="fName" placeholder="First Name" required />
    <input type="text" name="lName" placeholder="Last Name" required />
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit" name="signUp">Sign Up</button>
  </form>
  <p><button class="switch-btn" id="showLogin">Already have an account? Login</button></p>
</div>

<div class="container" id="signin">
  <h2>Login</h2>
  <form method="post" action="register_login.php">
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit" name="signIn">Sign In</button>
    <button type="submit" name="adminLogin" class="admin-btn">Admin Login</button>
  </form>
  <p><button class="switch-btn" id="showSignup">Don't have an account? Register</button></p>
</div>

<script>
  const showSignup = document.getElementById("showSignup");
  const showLogin = document.getElementById("showLogin");
  const signup = document.getElementById("signup");
  const signin = document.getElementById("signin");

  showSignup?.addEventListener("click", () => {
    signup.style.display = "block";
    signin.style.display = "none";
  });
  showLogin?.addEventListener("click", () => {
    signup.style.display = "none";
    signin.style.display = "block";
  });
</script>

</body>
</html>
