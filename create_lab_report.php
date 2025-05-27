<?php
session_start();
include 'connection.php';

$success = false;
$last_id = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_name = $_POST['patient_name'];
    $test_name = $_POST['test_name'];
    $result_text = $_POST['result'];
    $report_date = $_POST['report_date'];

    $stmt = $conn->prepare("INSERT INTO lab_reports (patient_name, test_name, result, report_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $patient_name, $test_name, $result_text, $report_date);
    
    if ($stmt->execute()) {
        $last_id = $stmt->insert_id;
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Lab Report</title>
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

        h4 {
            text-align: center;
            color: var(--light);
            margin-bottom: 20px;
        }

        .form-label {
            color: var(--light);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--light);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--accent);
            box-shadow: 0 0 0 0.25rem rgba(72, 149, 239, 0.25);
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--secondary);
        }

        .alert {
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h4>Create New Lab Report</h4>

    <?php if ($success): ?>
        <div class="alert alert-success d-flex justify-content-between align-items-center">
            Lab report created successfully!
            <a href="generate_pdf.php?id=<?= $last_id ?>" target="_blank" class="btn btn-sm btn-outline-success">
                Download PDF
            </a>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="patient_name" class="form-label">Patient Name</label>
            <input type="text" class="form-control" id="patient_name" name="patient_name" required>
        </div>
        <div class="mb-3">
            <label for="test_name" class="form-label">Test Name</label>
            <input type="text" class="form-control" id="test_name" name="test_name" required>
        </div>
        <div class="mb-3">
            <label for="result" class="form-label">Result</label>
            <textarea class="form-control" id="result" name="result" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label for="report_date" class="form-label">Report Date</label>
            <input type="date" class="form-control" id="report_date" name="report_date" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Create Report</button>
        </div>
    </form>

    <div class="text-center mt-3">
        <a href="lab_reports_list.php" class="btn btn-link">View All Reports</a>
    </div>
</div>
</body>
</html>
