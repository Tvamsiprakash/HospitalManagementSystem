<?php
// Start a new or resume existing session
session_start();

// Include database connection script
include 'connection.php';

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form inputs
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Check if password and confirm password fields match
    if ($password !== $confirm) {
        echo "Passwords do not match!";
    } else {
        // Prepare SQL statement to check if the username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // If user already exists, show message
        if ($result->num_rows > 0) {
            echo "Username already taken!";
        } else {
            // Hash the password before storing in DB
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL statement to insert new user
            $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $insert->bind_param("ss", $username, $hashed_password);

            // Execute the insertion
            if ($insert->execute()) {
                echo "Registered successfully. <a href='login.php'>Login now</a>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }
}
?>

<!-- HTML PART: Registration Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        /* Styling for registration form */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right,rgb(255, 255, 255),rgb(0, 10, 28));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(214, 22, 22, 0.15);
            width: 320px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type=text],
        input[type=password] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type=submit] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
        .error, .success {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Create an Account</h2>
        <form method="POST" action="">
            <!-- Username Field -->
            <label>Username</label>
            <input type="text" name="username" required>

            <!-- Password Field -->
            <label>Password</label>
            <input type="password" name="password" required>

            <!-- Confirm Password Field -->
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>

            <!-- Submit Button -->
            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>