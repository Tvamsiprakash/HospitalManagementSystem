<?php
require_once('../connection.php');
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

$query = "SELECT * FROM patients ORDER BY name";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient List</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--light);
        }

        .container {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            max-width: 1150px;
            width: 95%;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--light);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 5px;
            text-decoration: none;
            background-color: var(--primary);
            color: var(--light);
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        .btn:hover {
            background-color: var(--secondary);
            transform: scale(1.05);
        }

        .btn.danger {
            background-color: var(--danger);
        }

        .btn.danger:hover {
            background-color: #d1143e;
        }

        .search-box {
            margin-top: 10px;
            margin-bottom: 20px;
            text-align: right;
        }

        input[type="text"] {
            padding: 10px;
            width: 300px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 15px;
            color: var(--light);
            background: rgba(255, 255, 255, 0.1);
        }

        input[type="text"]:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--accent);
            box-shadow: 0 0 0 0.25rem rgba(72, 149, 239, 0.25);
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
    </style>
    <script>
        function filterPatients() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("patientTable");
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                const id = tr[i].getElementsByTagName("td")[0];
                const name = tr[i].getElementsByTagName("td")[1];

                if (id && name) {
                    const idText = id.textContent || id.innerText;
                    const nameText = name.textContent || name.innerText;

                    if (idText.toLowerCase().indexOf(filter) > -1 || nameText.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</head>
<body>
<div class="container">
    <h1>Patient List</h1>
    <div class="search-box">
        🔍 <input type="text" id="searchInput" onkeyup="filterPatients()" placeholder="Search by ID or Name...">
    </div>
    <a href="add.php" class="btn">Add New Patient</a>
    <a href="../dashboard.php" class="btn"> Back to Dashboard</a>

    <table id="patientTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Date Registered</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo $row['date_registered']; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn">Edit</a>
                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn danger" onclick="return confirm('Are you sure you want to delete this patient?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php mysqli_close($conn); ?>
