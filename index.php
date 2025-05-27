<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hospital Management System</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right,rgb(24, 22, 22),rgb(11, 95, 221));
      height: 50vh;
      color: white;
      text-align: center;
      padding-top: 100px;
    }
    .container {
      background-color: rgba(0, 0, 0, 0.6);
      padding: 30px;
      margin: auto;
      width: 400px;
      border-radius: 10px;
    }
    h1 {
      margin-bottom: 20px;
    }
    a {
      display: inline-block;
      margin: 10px;
      padding: 10px 20px;
      background-color: #fff;
      color: #2f80ed;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }
    a:hover {
      background-color: #e0e0e0;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Welcome to Hospital Management System</h1>
    <p>Please choose an option below:</p>
    <a href="login.php">Login</a>
    <!-- Uncomment below if you want a register option -->
    <a href="register.php">Register</a>
  </div>
</body>
</html>
