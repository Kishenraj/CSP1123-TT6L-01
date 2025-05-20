<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login & Register</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>

<div class="container" id="signup" style="display:none;">
    <h1>Register</h1>
    <form method="post" action="register_login.php">
        <input type="text" name="fName" placeholder="First Name" required />
        <input type="text" name="lName" placeholder="Last Name" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" name="signUp">Sign Up</button>
    </form>
    <p>Already have an account? <button id="showLogin">Login</button></p>
</div>

<div class="container" id="signin">
    <h1>Login</h1>
    <form method="post" action="register_login.php">
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" name="signIn">Sign In</button>
    </form>
    <p>Don't have an account? <button id="showSignup">Register</button></p>
</div>

<script>
const showSignup = document.getElementById("showSignup");
const showLogin = document.getElementById("showLogin");
const signup = document.getElementById("signup");
const signin = document.getElementById("signin");

showSignup.addEventListener("click", () => {
    signup.style.display = "block";
    signin.style.display = "none";
});
showLogin.addEventListener("click", () => {
    signup.style.display = "none";
    signin.style.display = "block";
});
</script>

</body>
</html>
