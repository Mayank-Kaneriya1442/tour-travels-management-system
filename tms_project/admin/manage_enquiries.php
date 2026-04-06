<?php 
include('../config/db.php'); 
session_start();
if(!isset($_SESSION['admin'])) { header("location:admin_login.php"); }

// Delete Logic
if(isset($_GET['del_id'])) {
    $did = intval($_GET['del_id']);
    mysqli_query($conn, "DELETE FROM enquiry WHERE id='$did'");
    echo "<script>alert('Enquiry Deleted Successfully'); window.location='manage_enquiries.php';</script>";
}

// Mark as Read Logic
if(isset($_GET['read_id'])) {
    $rid = intval($_GET['read_id']);
    mysqli_query($conn, "UPDATE enquiry SET status=1 WHERE id='$rid'");
    echo "<script>window.location='manage_enquiries.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Enquiries</title>
    <!-- Fonts & Icons -->
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
        .main-content {
            margin-left: 280px;
            padding: 40px 50px;
            width: calc(100% - 280px);
        }

        .page-header {
            margin-bottom: 30px;
            animation: fadeInDown 0.8s ease;
        }
        .page-header h2 { font-size: 2rem; color: #2c3e50; font-weight: 700; }
        .page-header p { color: #7f8c8d; }

        /* Enquiries Grid */
        .enquiry-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        .enquiry-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 25px;
            transition: 0.3s;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.5s ease-out;
            animation-fill-mode: both;
        }

        .enquiry-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .enquiry-card.unread {
            border-left: 4px solid #e73c7e;
        }

        .enquiry-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .enquiry-date {
            font-size: 0.8rem;
            color: #95a5a6;
        }

        .enquiry-subject {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .enquiry-msg {
            font-size: 0.9rem;
            color: #7f8c8d;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .card-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        .btn-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-delete { background: #ffebee; color: #e74c3c; }
        .btn-delete:hover { background: #e74c3c; color: white; }

        .btn-read { background: #e8f8f5; color: #2ecc71; }
        .btn-read:hover { background: #2ecc71; color: white; }

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
            <li><a href="manage_payments.php"><i class="fas fa-credit-card"></i> <span>Payments</span></a></li>
            <li><a href="manage_enquiries.php" class="active"><i class="fas fa-envelope"></i> <span>Enquiries</span></a></li>
            <li><a href="manage_coupons.php"><i class="fas fa-tags"></i> <span>Coupons</span></a></li>
            <li><a href="manage_gallery.php"><i class="fas fa-images"></i> <span>Gallery</span></a></li>
            <li><a href="../admin/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="page-header">
            <h2>Customer Enquiries</h2>
            <p style="color:#7f8c8d;">Manage and respond to user questions.</p>
        </div>

        <div class="enquiry-container">
            <?php
            $res = mysqli_query($conn, "SELECT * FROM enquiry ORDER BY posting_date DESC");
            if(mysqli_num_rows($res) > 0) {
                $delay = 0;
                while($row = mysqli_fetch_assoc($res)) {
                    $delay += 0.1;
                    $status_class = ($row['status'] == 0) ? 'unread' : '';
                    $initial = strtoupper(substr($row['full_name'], 0, 1));
            ?>
            <div class="enquiry-card <?php echo $status_class; ?>" style="animation-delay: <?php echo $delay; ?>s;">
                <div class="enquiry-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div class="user-avatar"><?php echo $initial; ?></div>
                        <div>
                            <div style="font-weight:600;"><?php echo $row['full_name']; ?></div>
                            <div style="font-size:0.8rem; opacity:0.7;"><?php echo $row['email']; ?></div>
                        </div>
                    </div>
                    <div class="enquiry-date"><?php echo date('d M', strtotime($row['posting_date'])); ?></div>
                </div>
                
                <div class="enquiry-subject"><?php echo $row['subject']; ?></div>
                <div class="enquiry-msg">
                    <?php echo substr($row['description'], 0, 100) . '...'; ?>
                </div>

                <div class="card-actions">
                    <?php if($row['status'] == 0): ?>
                        <a href="manage_enquiries.php?read_id=<?php echo $row['id']; ?>" class="btn-icon btn-read" title="Mark as Read"><i class="fas fa-check"></i></a>
                    <?php endif; ?>
                    <a href="mailto:<?php echo $row['email']; ?>" class="btn-icon" style="background:#e3f2fd; color:#3498db;" title="Reply"><i class="fas fa-reply"></i></a>
                    <a href="manage_enquiries.php?del_id=<?php echo $row['id']; ?>" class="btn-icon btn-delete" onclick="return confirm('Delete this enquiry?');" title="Delete"><i class="fas fa-trash"></i></a>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p style='color:#7f8c8d;'>No enquiries found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>