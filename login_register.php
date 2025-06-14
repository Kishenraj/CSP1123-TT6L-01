<?php
session_start();
include 'connect.php';

$conn = new mysqli("localhost", "root", "", "loginn");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ========== SIGN UP ==========
if (isset($_POST['signUp'])) {
    $firstName = $conn->real_escape_string($_POST['fName']);
    $lastName = $conn->real_escape_string($_POST['lName']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = md5($_POST['password']); // Still using MD5 to match older data

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already exists!'); window.location.href='login.php';</script>";
        exit();
    }
    $stmt->close();

    // Insert new user
    $insert = $conn->prepare("INSERT INTO users (firstName, lastName, email, password, is_admin) VALUES (?, ?, ?, ?, 0)");
    $insert->bind_param("ssss", $firstName, $lastName, $email, $password);

    if ($insert->execute()) {
        $_SESSION['user_id'] = $insert->insert_id;
        $_SESSION['user_name'] = $firstName . ' ' . $lastName;
        $_SESSION['email'] = $email;
        $_SESSION['is_admin'] = 0;
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Registration failed!'); window.location.href='login.php';</script>";
    }
    $insert->close();
}

// ========== NORMAL USER LOGIN ==========
if (isset($_POST['signIn'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT id, firstName, lastName, is_admin FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $firstName, $lastName, $is_admin);
        $stmt->fetch();

        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $firstName . ' ' . $lastName;
        $_SESSION['email'] = $email;
        $_SESSION['is_admin'] = $is_admin;

        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Incorrect Email or Password!'); window.location.href='login.php';</script>";
    }
    $stmt->close();
}

// ========== ADMIN LOGIN ==========
if (isset($_POST['adminLogin'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT id, firstName, lastName FROM users WHERE email = ? AND password = ? AND is_admin = 1");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $firstName, $lastName);
        $stmt->fetch();

        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $firstName . ' ' . $lastName;
        $_SESSION['email'] = $email;
        $_SESSION['is_admin'] = 1;

        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Invalid admin credentials!'); window.location.href='login.php';</script>";
    }
    $stmt->close();
}

$conn->close();
?>
