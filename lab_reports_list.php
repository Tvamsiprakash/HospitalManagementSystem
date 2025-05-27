<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Handle search input
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Build SQL query with search functionality
$sql = "SELECT * FROM lab_reports";
if (!empty($search)) {
    $sql .= " WHERE patient_name LIKE '%$search%' OR test_name LIKE '%$search%'";
}
$sql .= " ORDER BY report_date DESC";

$result = mysqli_query($conn, $sql);

// Check for query errors
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Reports List</title>
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
            color: var(--light);
            min-height: 100vh;
            padding: 20px 0;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            max-width: 1200px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--light);
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

        .btn {
            background: var(--primary);
            color: var(--light);
            border: none;
            border-radius: 6px;
            transition: background 0.3s ease;
            padding: 8px 12px;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background: var(--secondary);
            color: var(--light);
        }

        .btn-outline-success {
            color: var(--success);
            border: 1px solid var(--success);
            background-color: transparent;
        }

        .btn-outline-success:hover {
            background-color: var(--success);
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Lab Reports List</h1>

    <div class="d-flex justify-content-between mb-4">
        <a href="dashboard.php" class="btn"> Dashboard</a>
        <a href="create_lab_report.php" class="btn"> Create New Lab Report</a>
        <form method="GET" class="d-flex" role="search">
            <input class="form-control me-2" type="search" name="search" placeholder="Search by patient or test name" 
                   value="<?php echo htmlspecialchars($search); ?>" style="max-width: 300px;">
            <button class="btn" type="submit">🔍 Search</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient Name</th>
                    <th>Test Name</th>
                    <th>Result</th>
                    <th>Report Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) == 0): ?>
                    <tr>
                        <td colspan="6" class="text-center">No lab reports found.</td>
                    </tr>
                <?php else: ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['test_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['result']); ?></td>
                        <td><?php echo htmlspecialchars($row['report_date']); ?></td>
                        <td>
                            <a href="generate_pdf.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success" target="_blank">Download PDF</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php mysqli_close($conn); ?>
</body>
</html>
