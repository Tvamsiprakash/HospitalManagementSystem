<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_name = $_POST['patient_name'];
    $test_name = $_POST['test_name'];
    $result_text = $_POST['result'];
    $report_date = $_POST['report_date'];

    $stmt = $conn->prepare("INSERT INTO lab_reports (patient_name, test_name, result, report_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $patient_name, $test_name, $result_text, $report_date);
    $stmt->execute();

    $last_id = $stmt->insert_id;  // <-- Add this to get the new report's ID
    $success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Lab Report</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Create New Lab Report</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            Lab report created successfully!
                            <a href="generate_pdf.php?id=<?= $last_id ?>" target="_blank" class="btn btn-sm btn-outline-secondary ms-2">
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
                </div>
                <div class="card-footer text-center">
                    <a href="lab_reports_list.php" class="btn btn-link">View All Reports</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS CDN (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
