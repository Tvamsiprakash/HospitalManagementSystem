<?php
session_start();
include 'connection.php'; // your DB connection

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['user'];
$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_billing'])) {
    $full_name = trim($_POST['full_name']);
    $address   = trim($_POST['address']);
    $phone     = trim($_POST['phone']);
    $email     = trim($_POST['email']);
    $amount    = trim($_POST['amount']);
    $date      = date('Y-m-d'); // current date

    // Basic validation
    if (empty($full_name) || empty($address) || empty($phone) || empty($email) || empty($amount)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $errors[] = "Amount must be a positive number.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO billing (username, full_name, address, phone, email, amount, billing_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $username, $full_name, $address, $phone, $email, $amount, $date);

        if ($stmt->execute()) {
            $success = "✅ Billing details submitted successfully.";
        } else {
            $errors[] = "❌ Failed to submit billing details.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Billing Session</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --danger: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --warning: #f8961e;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: var(--light);
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            width: 400px;
        }

        h2 {
            text-align: center;
            color: var(--light);
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: var(--light);
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.1);
            color: var(--light);
        }

        input:focus, textarea:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--accent);
            box-shadow: 0 0 0 0.25rem rgba(72, 149, 239, 0.25);
        }

        .btn {
            margin-top: 20px;
            padding: 10px;
            background: var(--primary);
            color: var(--light);
            border: none;
            border-radius: 6px;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: var(--secondary);
        }

        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 8px;
        }

        .success {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .error {
            background-color: #f8d7da;
            color: #842029;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Billing Session</h2>

    <?php if ($success): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="message error">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Billing Form -->
    <form method="POST">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" required>

        <label for="address">Address</label>
        <textarea id="address" name="address" rows="3" required></textarea>

        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" required>

        <label for="email">Email</label>
        <input type="text" id="email" name="email" required>

        <label for="amount">Amount</label>
        <input type="text" id="amount" name="amount" required>

        <button type="submit" class="btn" name="submit_billing">Submit Billing</button>
    </form>

    <!-- Return to Dashboard Button -->
    <form action="dashboard.php" method="get">
        <button type="submit" class="btn btn-secondary">🔙 Return to Dashboard</button>
    </form>
</div>
</body>
</html>
