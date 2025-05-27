<?php
require_once('../connection.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $availability = $_POST['availability'];

    $query = "INSERT INTO doctors (name, specialization, phone, email, availability)
              VALUES ('$name', '$specialization', '$phone', '$email', '$availability')";
    mysqli_query($conn, $query);
    header("Location: list.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Doctor</title>
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
            min-height: 100vh;
            color: var(--light);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        form {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border-radius: 16px;
            padding: 30px;
            width: 400px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--light);
            padding: 10px;
            margin: 12px 0;
            border-radius: 6px;
        }

        input:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--accent);
            box-shadow: 0 0 0 0.25rem rgba(72, 149, 239, 0.25);
        }

        button {
            background: var(--primary);
            color: var(--light);
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        h2 {
            color: var(--light);
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<form method="post">
    <h2>Add Doctor</h2>
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="text" name="specialization" placeholder="Specialization" required>
    <input type="text" name="phone" placeholder="Phone Number" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="availability" placeholder="e.g. Mon–Fri, 10AM–2PM" required>
    <button type="submit">Add Doctor</button>
</form>
</body>
</html>

<?php mysqli_close($conn); ?>
