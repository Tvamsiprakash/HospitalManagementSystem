<?php
require_once('../connection.php');
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

// Handle search by patient name or doctor name
$search = '';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

// Fetch appointments with patient and doctor names
$sql = "
SELECT a.*, p.name AS patient_name, d.name AS doctor_name 
FROM appointments a
JOIN patients p ON a.patient_id = p.id
JOIN doctors d ON a.doctor_id = d.id
WHERE p.name LIKE '%$search%' OR d.name LIKE '%$search%'
ORDER BY a.appointment_date, a.appointment_time
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Appointments</title>
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
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--light);
        }

        .container {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            max-width: 1200px;
            width: 100%;
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--light);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 5px 20px 0;
            text-decoration: none;
            background-color: var(--primary);
            color: var(--light);
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s;
            cursor: pointer;
            border: none;
        }

        .btn:hover {
            background-color: var(--secondary);
            transform: scale(1.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
            color: var(--light);
        }

        th, td {
            padding: 12px 16px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        thead {
            background-color: rgba(255, 255, 255, 0.1);
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        form.search-form {
            margin-bottom: 20px;
            text-align: right;
        }

        input[type="text"] {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 300px;
            box-sizing: border-box;
            margin-bottom: 15px;
            font-size: 14px;
            color: var(--light);
            background: rgba(255, 255, 255, 0.1);
        }

        input[type="text"]:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--accent);
            box-shadow: 0 0 0 0.25rem rgba(72, 149, 239, 0.25);
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Appointments</h1>

    <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search by patient or doctor name" value="<?php echo htmlspecialchars($search); ?>" />
        <button type="submit" class="btn">Search</button>
    </form>

    <a href="add.php" class="btn"> Add New Appointment</a>
    <a href="../dashboard.php" class="btn"> Back to Dashboard</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) == 0): ?>
                <tr><td colspan="9">No appointments found.</td></tr>
            <?php else: ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                    <td><?php echo $row['appointment_date']; ?></td>
                    <td><?php echo substr($row['appointment_time'], 0, 5); ?></td>
                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn danger" onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
