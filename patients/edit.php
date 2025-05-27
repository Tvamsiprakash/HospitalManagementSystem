<?php
require_once('../connection.php');
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit();
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = (int)$_POST['age'];
    $gender = $_POST['gender'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $query = "UPDATE patients SET 
                name = '$name', 
                age = $age, 
                gender = '$gender', 
                phone = '$phone', 
                address = '$address' 
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        header("Location: list.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

$query = "SELECT * FROM patients WHERE id = $id";
$result = mysqli_query($conn, $query);
$patient = mysqli_fetch_assoc($result);
if (!$patient) {
    echo "Patient not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Patient</title>
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
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: var(--light);
        }

        .form-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            width: 500px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--light);
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            font-size: 16px;
            color: var(--light);
            background: rgba(255, 255, 255, 0.1);
        }

        input:focus, select:focus, textarea:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--accent);
            box-shadow: 0 0 0 0.25rem rgba(72, 149, 239, 0.25);
        }

        button {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: var(--light);
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s;
        }

        button:hover {
            background: var(--secondary);
            transform: scale(1.03);
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: var(--light);
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Edit Patient</h2>
    <form method="post">
        <input type="text" name="name" value="<?php echo htmlspecialchars($patient['name']); ?>" required>
        <input type="number" name="age" value="<?php echo $patient['age']; ?>" required>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option <?php if ($patient['gender'] == 'Male') echo 'selected'; ?>>Male</option>
            <option <?php if ($patient['gender'] == 'Female') echo 'selected'; ?>>Female</option>
            <option <?php if ($patient['gender'] == 'Other') echo 'selected'; ?>>Other</option>
        </select>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>" required>
        <textarea name="address" required><?php echo htmlspecialchars($patient['address']); ?></textarea>
        <button type="submit"> Update Patient</button>
    </form>
    <a href="list.php">⬅ Back to Patient List</a>
</div>
</body>
</html>

<?php mysqli_close($conn); ?>
