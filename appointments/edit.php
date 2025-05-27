<?php
require_once('../connection.php');
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

// Get appointment ID from query parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: list.php");
    exit();
}
$id = (int)$_GET['id'];

// Fetch patients and doctors for selects
$patients = mysqli_query($conn, "SELECT id, name FROM patients ORDER BY name");
$doctors = mysqli_query($conn, "SELECT id, name FROM doctors ORDER BY name");

// Fetch existing appointment data
$result = mysqli_query($conn, "SELECT * FROM appointments WHERE id = $id");
if (mysqli_num_rows($result) == 0) {
    header("Location: list.php");
    exit();
}
$appointment = mysqli_fetch_assoc($result);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'] ?? '';
    $doctor_id = $_POST['doctor_id'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $reason = $_POST['reason'] ?? '';

    if (!$patient_id) $errors[] = 'Patient is required.';
    if (!$doctor_id) $errors[] = 'Doctor is required.';
    if (!$appointment_date) $errors[] = 'Date is required.';
    if (!$appointment_time) $errors[] = 'Time is required.';

    if (empty($errors)) {
        $patient_id = mysqli_real_escape_string($conn, $patient_id);
        $doctor_id = mysqli_real_escape_string($conn, $doctor_id);
        $appointment_date = mysqli_real_escape_string($conn, $appointment_date);
        $appointment_time = mysqli_real_escape_string($conn, $appointment_time);
        $reason = mysqli_real_escape_string($conn, $reason);

        $sql = "UPDATE appointments 
                SET patient_id='$patient_id', doctor_id='$doctor_id', appointment_date='$appointment_date', 
                    appointment_time='$appointment_time', reason='$reason'
                WHERE id=$id";

        if (mysqli_query($conn, $sql)) {
            header("Location: list.php");
            exit();
        } else {
            $errors[] = "Error updating appointment: " . mysqli_error($conn);
        }
    }
} else {
    // Pre-fill form with existing data if GET request
    $patient_id = $appointment['patient_id'];
    $doctor_id = $appointment['doctor_id'];
    $appointment_date = $appointment['appointment_date'];
    $appointment_time = $appointment['appointment_time'];
    $reason = $appointment['reason'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Edit Appointment</title>
<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .container {
        background-color: #ffffffee;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        max-width: 600px;
        width: 100%;
    }
    h1 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
    }
    .btn {
        display: inline-block;
        padding: 10px 20px;
        margin: 10px 5px 20px 0;
        text-decoration: none;
        background-color: #2196F3;
        color: white;
        border-radius: 8px;
        transition: background-color 0.3s ease, transform 0.2s;
        cursor: pointer;
        border: none;
        font-size: 16px;
    }
    .btn:hover {
        background-color: #1976d2;
        transform: scale(1.05);
    }
    label {
        font-weight: 600;
        margin-bottom: 5px;
        display: block;
        color: #333;
    }
    select, input[type="date"], input[type="time"], textarea {
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ccc;
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 15px;
        font-size: 14px;
        resize: vertical;
        transition: border-color 0.3s;
    }
    select:focus, input[type="date"]:focus, input[type="time"]:focus, textarea:focus {
        border-color: #2196F3;
        outline: none;
    }
    textarea {
        height: 80px;
    }
    .error {
        background-color: #f8d7da;
        color: #842029;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 8px;
    }
</style>
</head>
<body>

<div class="container">
    <h1>Edit Appointment</h1>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
            <?php foreach ($errors as $e): ?>
                <li><?php echo htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="patient_id">Patient:</label>
        <select name="patient_id" id="patient_id" required>
            <option value="">Select Patient</option>
            <?php while ($p = mysqli_fetch_assoc($patients)): ?>
                <option value="<?php echo $p['id']; ?>" <?php if (isset($patient_id) && $patient_id == $p['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($p['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="doctor_id">Doctor:</label>
        <select name="doctor_id" id="doctor_id" required>
            <option value="">Select Doctor</option>
            <?php while ($d = mysqli_fetch_assoc($doctors)): ?>
                <option value="<?php echo $d['id']; ?>" <?php if (isset($doctor_id) && $doctor_id == $d['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($d['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="appointment_date">Date:</label>
        <input type="date" name="appointment_date" id="appointment_date" value="<?php echo htmlspecialchars($appointment_date ?? ''); ?>" required>

        <label for="appointment_time">Time:</label>
        <input type="time" name="appointment_time" id="appointment_time" value="<?php echo htmlspecialchars($appointment_time ?? ''); ?>" required>

        <label for="reason">Reason for Appointment (optional):</label>
        <textarea name="reason" id="reason"><?php echo htmlspecialchars($reason ?? ''); ?></textarea>

        <button type="submit" class="btn">Update Appointment</button>
        <a href="list.php" class="btn" style="background-color:#888; margin-left:10px;">Cancel</a>
    </form>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
