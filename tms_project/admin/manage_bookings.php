<?php 
include('../config/db.php'); 
session_start();
if(!isset($_SESSION['admin'])) { header("location:admin_login.php"); }

// Logic to Cancel a Booking
if(isset($_GET['cancel_id'])) {
    $cid = intval($_GET['cancel_id']);
    mysqli_query($conn, "UPDATE bookings SET status='Cancelled' WHERE b_id='$cid'");
    echo "<script>alert('Booking Cancelled Successfully'); window.location='manage_bookings.php';</script>";
}

// Logic to Confirm a Booking
if(isset($_GET['confirm_id'])) {
    $cid = intval($_GET['confirm_id']);
    mysqli_query($conn, "UPDATE bookings SET status='Confirmed' WHERE b_id='$cid'");
    echo "<script>alert('Booking Confirmed Successfully'); window.location='manage_bookings.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings</title>
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
        
        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
            color: #555;
        }

        th {
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        tr:hover { background: #fafafa; }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-confirmed { background: rgba(46, 204, 113, 0.2); color: #2ecc71; }
        .status-pending { background: rgba(241, 196, 15, 0.2); color: #f1c40f; }
        .status-cancelled { background: rgba(231, 76, 60, 0.2); color: #e74c3c; }

        .action-links a {
            margin-right: 10px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .btn-verify { color: #3498db; }
        .btn-verify:hover { text-decoration: underline; }
        .btn-cancel { color: #e74c3c; }
        .btn-cancel:hover { text-decoration: underline; }
        .btn-confirm { color: #2ecc71; }
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
            <li><a href="manage_bookings.php" class="active"><i class="fas fa-bookmark"></i> <span>Bookings</span></a></li>
            <li><a href="manage_payments.php"><i class="fas fa-credit-card"></i> <span>Payments</span></a></li>
            <li><a href="manage_enquiries.php"><i class="fas fa-envelope"></i> <span>Enquiries</span></a></li>
            <li><a href="manage_coupons.php"><i class="fas fa-tags"></i> <span>Coupons</span></a></li>
            <li><a href="manage_gallery.php"><i class="fas fa-images"></i> <span>Gallery</span></a></li>
            <li><a href="../admin/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="page-header">
            <h2>Manage Bookings</h2>
            <p>View and manage all customer tour bookings.</p>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Package Info</th>
                        <th>Travel Details</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT bookings.*, users.full_name, users.email, packages.p_name 
                            FROM bookings 
                            JOIN users ON bookings.user_id = users.id 
                            JOIN packages ON bookings.p_id = packages.p_id 
                            ORDER BY bookings.b_id DESC";
                    $res = mysqli_query($conn, $sql);
                    
                    if(mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            $status_class = 'status-pending';
                            if($row['status'] == 'Confirmed') $status_class = 'status-confirmed';
                            if($row['status'] == 'Cancelled') $status_class = 'status-cancelled';
                            
                            // Handle total price display (fallback if column empty)
                            $display_price = ($row['total_price'] > 0) ? $row['total_price'] : 'N/A';
                    ?>
                    <tr>
                        <td>#BK-<?php echo $row['b_id']; ?></td>
                        <td>
                            <div style="font-weight:bold;"><?php echo $row['full_name']; ?></div>
                            <div style="font-size:0.8rem; opacity:0.7;"><?php echo $row['email']; ?></div>
                        </td>
                        <td><?php echo $row['p_name']; ?></td>
                        <td>
                            <div><?php echo date('d M Y', strtotime($row['from_date'])); ?></div>
                            <div style="font-size:0.8rem; opacity:0.7;"><?php echo $row['travelers']; ?> Person(s)</div>
                        </td>
                        <td style="color:#e73c7e; font-weight:bold;">₹<?php echo number_format((float)$display_price); ?></td>
                        <td>
                            <span class="status-badge <?php echo $status_class; ?>"><?php echo $row['status']; ?></span>
                            <div style="font-size:0.8rem; margin-top:5px;"><?php echo $row['payment_status']; ?></div>
                        </td>
                        <td class="action-links">
                            <?php if($row['status'] == 'Pending'): ?>
                                <a href="manage_bookings.php?confirm_id=<?php echo $row['b_id']; ?>" class="btn-confirm" onclick="return confirm('Confirm this booking?');"><i class="fas fa-check"></i> Confirm</a>
                            <?php endif; ?>
                            
                            <?php if($row['status'] != 'Cancelled'): ?>
                                <a href="manage_bookings.php?cancel_id=<?php echo $row['b_id']; ?>" class="btn-cancel" onclick="return confirm('Cancel this booking?');"><i class="fas fa-times"></i> Cancel</a>
                            <?php endif; ?>
                            
                            <?php if($row['payment_status'] == 'Pending Verification'): ?>
                                <br><a href="manage_payments.php" class="btn-verify"><i class="fas fa-search"></i> Verify Pay</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align:center;'>No bookings found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>