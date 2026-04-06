<?php 
include('../config/db.php'); 
session_start();
if(!isset($_SESSION['admin'])) { header("location:admin_login.php"); }

// Logic to Confirm Payment
if(isset($_GET['action']) && $_GET['action'] == 'confirm') {
    $bid = intval($_GET['bid']);
    mysqli_query($conn, "UPDATE bookings SET payment_status='Paid', status='Confirmed' WHERE b_id='$bid'");
    echo "<script>alert('Booking Confirmed!'); window.location='manage_payments.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Payments</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        body {
            background-color: #f0f2f5;
            min-height: 100vh;
            overflow-x: hidden;
            color: #333;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0,0,0,0.05);
            z-index: 1000;
            transition: 0.3s;
        }

        .brand {
            font-size: 1.6rem;
            font-weight: 700;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 50px;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .brand i { color: #e73c7e; font-size: 1.8rem; }
        .brand span { color: #e73c7e; }

        .nav-links { list-style: none; flex: 1; }
        .nav-links li { margin-bottom: 10px; }
        .nav-links a { display: flex; align-items: center; padding: 15px 25px; color: #7f8c8d; text-decoration: none; border-radius: 15px; transition: all 0.3s ease; font-weight: 500; position: relative; overflow: hidden; }
        .nav-links a:hover, .nav-links a.active { background: linear-gradient(45deg, #e73c7e, #ee7752); color: white; box-shadow: 0 5px 15px rgba(231, 60, 126, 0.3); transform: translateX(5px); }
        .nav-links a i { margin-right: 15px; width: 25px; text-align: center; font-size: 1.1rem; }
        
        .logout-btn { margin-top: auto; background: #ffebee !important; color: #e74c3c !important; }
        .logout-btn:hover { background: #e74c3c !important; color: white !important; }

        /* Main Content */
        .main-content { margin-left: 280px; padding: 40px 50px; width: calc(100% - 280px); }
        
        .page-header { margin-bottom: 30px; animation: fadeInDown 0.8s ease; }
        .page-header h2 { font-size: 2rem; color: #2c3e50; font-weight: 700; }
        .page-header p { color: #7f8c8d; }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
        }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #eee; color: #555; }
        th { background: #f8f9fa; color: #2c3e50; font-weight: 600; text-transform: uppercase; font-size: 0.9rem; }
        tr:hover { background: #fafafa; }

        .btn-confirm { color: #2ecc71; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .btn-confirm:hover { text-decoration: underline; }

        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand"><i class="fas fa-paper-plane"></i> <span>TravelEase</span></div>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="manage_packages.php"><i class="fas fa-globe-americas"></i> <span>Packages</span></a></li>
            <li><a href="manage_users.php"><i class="fas fa-users"></i> <span>Users</span></a></li>
            <li><a href="manage_bookings.php"><i class="fas fa-bookmark"></i> <span>Bookings</span></a></li>
            <li><a href="manage_payments.php" class="active"><i class="fas fa-credit-card"></i> <span>Payments</span></a></li>
            <li><a href="manage_enquiries.php"><i class="fas fa-envelope"></i> <span>Enquiries</span></a></li>
            <li><a href="manage_coupons.php"><i class="fas fa-tags"></i> <span>Coupons</span></a></li>
            <li><a href="manage_gallery.php"><i class="fas fa-images"></i> <span>Gallery</span></a></li>
            <li><a href="../admin/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="page-header">
            <h2>Verify User Payments</h2>
            <p style="color:#7f8c8d;">Check transaction details and confirm bookings.</p>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Booking ID</th>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT payments.*, users.full_name FROM payments 
                            JOIN bookings ON payments.booking_id = bookings.b_id 
                            JOIN users ON bookings.user_id = users.id
                            ORDER BY payments.pay_id DESC";
                    $res = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                    ?>
                    <tr>
                        <td><?php echo $row['full_name']; ?></td>
                        <td>#BK-<?php echo $row['booking_id']; ?></td>
                        <td style="color:#e73c7e; font-weight:bold;"><?php echo $row['transaction_id']; ?></td>
                        <td>₹<?php echo number_format($row['amount']); ?></td>
                        <td><?php echo $row['method']; ?></td>
                        <td><a href="manage_payments.php?action=confirm&bid=<?php echo $row['booking_id']; ?>" class="btn-confirm" onclick="return confirm('Confirm this payment?');"><i class="fas fa-check-circle"></i> Confirm</a></td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center;'>No payments found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>