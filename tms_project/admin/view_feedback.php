<?php 
include('../config/db.php'); 
session_start();
if(!isset($_SESSION['admin'])) { header("location:admin_login.php"); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Feedback</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .brand { font-size: 1.8rem; font-weight: 700; color: white; text-align: center; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 2px; }
        .brand span { color: #e67e22; }
        .nav-links { list-style: none; }
        .nav-links li { margin-bottom: 15px; }
        .nav-links a { display: flex; align-items: center; padding: 12px 20px; color: rgba(255, 255, 255, 0.7); text-decoration: none; border-radius: 10px; transition: all 0.3s ease; }
        .nav-links a:hover, .nav-links a.active { background: rgba(255, 255, 255, 0.1); color: white; transform: translateX(5px); }
        .nav-links a i { margin-right: 15px; width: 20px; text-align: center; }

        /* Main Content */
        .main-content { margin-left: 260px; padding: 40px; width: calc(100% - 260px); }
        
        .page-header { margin-bottom: 30px; animation: fadeInDown 0.8s ease; }
        .page-header h2 { font-size: 2rem; }
        .page-header p { color: rgba(255,255,255,0.6); }

        /* Table Styles */
        .table-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
        }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px 20px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.1); color: rgba(255, 255, 255, 0.8); }
        th { background: rgba(0, 0, 0, 0.2); color: white; font-weight: 600; text-transform: uppercase; font-size: 0.9rem; }
        tr:hover { background: rgba(255, 255, 255, 0.05); }

        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">Admin<span>Panel</span></div>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="manage_packages.php"><i class="fas fa-globe-americas"></i> <span>Packages</span></a></li>
            <li><a href="manage_users.php"><i class="fas fa-users"></i> <span>Users</span></a></li>
            <li><a href="manage_bookings.php"><i class="fas fa-bookmark"></i> <span>Bookings</span></a></li>
            <li><a href="manage_payments.php"><i class="fas fa-credit-card"></i> <span>Payments</span></a></li>
            <li><a href="manage_enquiries.php"><i class="fas fa-envelope"></i> <span>Enquiries</span></a></li>
            <li><a href="manage_coupons.php"><i class="fas fa-tags"></i> <span>Coupons</span></a></li>
            <li><a href="manage_gallery.php"><i class="fas fa-images"></i> <span>Gallery</span></a></li>
            <li><a href="view_feedback.php" class="active"><i class="fas fa-comments"></i> <span>Feedback</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="page-header">
            <h2>User Feedback</h2>
            <p>Reviews and messages from users.</p>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Subject</th>
                        <th>Feedback</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM enquiry ORDER BY posting_date DESC");
                    if(mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                    ?>
                    <tr>
                        <td>
                            <div style="font-weight:bold;"><?php echo $row['full_name']; ?></div>
                            <div style="font-size:0.8rem; opacity:0.7;"><?php echo $row['email']; ?></div>
                        </td>
                        <td><?php echo $row['subject']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo date('d M Y', strtotime($row['posting_date'])); ?></td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='4' style='text-align:center;'>No feedback found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>