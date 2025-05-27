<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            background-attachment: fixed;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            color: var(--light);
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
        }

        header {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
            width: 100%;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 600;
            background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            display: inline-block;
        }

        h1::after {
            content: '';
            position: absolute;
            width: 50%;
            height: 3px;
            background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
            bottom: -10px;
            left: 25%;
            border-radius: 3px;
        }

        .welcome-message {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 15px;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            width: 100%;
            margin-bottom: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 15px 15px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            text-decoration: none;
            color: var(--light);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            aspect-ratio: 1/0.5;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        }

        .icon-container {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: white;
            font-size: 1.8rem;
            transition: all 0.3s ease;
        }

        .card:hover .icon-container {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            transform: scale(1.1);
        }

        .card span {
            font-weight: 500;
            font-size: 1.1rem;
            display: block;
        }

        .logout-container {
            margin-top: 60px;
            text-align: center;
        }

        .logout {
            padding: 12px 30px;
            background: rgba(247, 37, 133, 0.2);
            color: white;
            border: 1px solid rgba(247, 37, 133, 0.4);
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .logout:hover {
            background: rgba(247, 37, 133, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(247, 37, 133, 0.2);
        }

        .logout i {
            font-size: 1.1rem;
        }

        /* Profile button styles */
        .profile-btn {
            position: absolute;
            top: 0;
            right: 0;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            cursor: pointer;
        }

        .profile-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        /* Special styling for different cards */
        .card:nth-child(1):hover { background: rgba(67, 97, 238, 0.2); }
        .card:nth-child(2):hover { background: rgba(72, 149, 239, 0.2); }
        .card:nth-child(3):hover { background: rgba(76, 201, 240, 0.2); }
        .card:nth-child(4):hover { background: rgba(248, 150, 30, 0.2); }
        .card:nth-child(5):hover { background: rgba(247, 37, 133, 0.2); }
        .card:nth-child(6):hover { background: rgba(60, 179, 113, 0.2); }

        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }
            
            .dashboard {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            
            .card {
                padding: 20px 15px;
            }
            
            .icon-container {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Profile button in top right corner -->
        <a href="myprofile.php" class="profile-btn">
            <i class="bi bi-person-circle"></i>
        </a>

        <header>
            <h1>Hospital Management System</h1>
            <p class="welcome-message">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong> 👋</p>
        </header>

        <!-- First row with 3 cards -->
        <div class="dashboard">
            <a href="doctors/list.php" class="card">
                <div class="icon-container">
                    <i class="bi bi-person-badge"></i>
                </div>
                <span>Doctors</span>
            </a>
            <a href="patients/list.php" class="card">
                <div class="icon-container">
                    <i class="bi bi-people-fill"></i>
                </div>
                <span>Patients</span>
            </a>
            <a href="appointments/list.php" class="card">
                <div class="icon-container">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <span>Appointments</span>
            </a>
        </div>

        <!-- Second row with 3 cards -->
        <div class="dashboard">
            <a href="billing.php" class="card">
                <div class="icon-container">
                    <i class="bi bi-receipt"></i>
                </div>
                <span>Billing</span>
            </a>
            <a href="create_lab_report.php" class="card">
                <div class="icon-container">
                    <i class="bi bi-file-medical"></i>
                </div>
                <span>Lab Reports</span>
            </a>
            <a href="inventory.php" class="card">
                <div class="icon-container">
                    <i class="bi bi-box-seam"></i>
                </div>
                <span>Inventory</span>
            </a>
        </div>

        <div class="logout-container">
            <a href="logout.php" class="logout">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</body>
</html>