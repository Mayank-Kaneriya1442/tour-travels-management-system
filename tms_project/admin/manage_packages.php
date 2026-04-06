<?php 
include('../config/db.php'); 
session_start();
if(!isset($_SESSION['admin'])) { header("location:admin_login.php"); }

// Logic to Delete Package
if(isset($_GET['del_id'])) {
    $id = intval($_GET['del_id']);
    
    // First, delete related payments to avoid foreign key constraint errors
    $bookings_query = mysqli_query($conn, "SELECT b_id FROM bookings WHERE p_id='$id'");
    while($row = mysqli_fetch_assoc($bookings_query)) {
        mysqli_query($conn, "DELETE FROM payments WHERE booking_id='" . $row['b_id'] . "'");
    }
    
    // Next, delete associated bookings
    mysqli_query($conn, "DELETE FROM bookings WHERE p_id='$id'");
    
    // Finally, delete the package
    mysqli_query($conn, "DELETE FROM packages WHERE p_id='$id'");
    echo "<script>alert('Package Deleted Successfully'); window.location='manage_packages.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Packages</title>
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
        .nav-links a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: #7f8c8d;
            text-decoration: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        .nav-links a:hover, .nav-links a.active {
            background: linear-gradient(45deg, #e73c7e, #ee7752);
            color: white;
            box-shadow: 0 5px 15px rgba(231, 60, 126, 0.3);
            transform: translateX(5px);
        }
        .nav-links a i { margin-right: 15px; width: 25px; text-align: center; font-size: 1.1rem; }

        .logout-btn {
            margin-top: auto;
            background: #ffebee !important;
            color: #e74c3c !important;
        }
        .logout-btn:hover { background: #e74c3c !important; color: white !important; }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 40px 50px;
            width: calc(100% - 280px);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            animation: fadeInDown 0.8s ease;
        }
        .page-header h2 { font-size: 2rem; color: #2c3e50; font-weight: 700; }

        .btn-add {
            background: linear-gradient(to right, #e73c7e, #ee7752);
            color: white;
            padding: 12px 25px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(231, 60, 126, 0.3);
        }
        .btn-add:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(231, 60, 126, 0.4); }

        /* Package Grid */
        .pkg-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .pkg-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: 0.3s;
            animation: fadeInUp 0.5s ease-out;
            animation-fill-mode: both;
        }

        .pkg-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .pkg-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .pkg-content { padding: 20px; }

        .pkg-title { font-size: 1.2rem; font-weight: 600; margin-bottom: 5px; color: #2c3e50; }
        .pkg-loc { color: #7f8c8d; font-size: 0.9rem; margin-bottom: 15px; }
        .pkg-price { color: #e73c7e; font-size: 1.1rem; font-weight: bold; margin-bottom: 15px; }

        .pkg-actions {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            flex: 1;
            padding: 8px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .btn-edit { background: #e3f2fd; color: #3498db; }
        .btn-edit:hover { background: #3498db; color: white; }

        .btn-delete { background: #ffebee; color: #e74c3c; }
        .btn-delete:hover { background: #e74c3c; color: white; }

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
            <li><a href="manage_packages.php" class="active"><i class="fas fa-globe-americas"></i> <span>Packages</span></a></li>
            <li><a href="manage_users.php"><i class="fas fa-users"></i> <span>Users</span></a></li>
            <li><a href="manage_bookings.php"><i class="fas fa-bookmark"></i> <span>Bookings</span></a></li>
            <li><a href="manage_payments.php"><i class="fas fa-credit-card"></i> <span>Payments</span></a></li>
            <li><a href="manage_enquiries.php"><i class="fas fa-envelope"></i> <span>Enquiries</span></a></li>
            <li><a href="manage_coupons.php"><i class="fas fa-tags"></i> <span>Coupons</span></a></li>
            <li><a href="manage_gallery.php"><i class="fas fa-images"></i> <span>Gallery</span></a></li>
            <li><a href="../admin/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="page-header">
            <div>
                <h2>Tour Packages</h2>
                <p style="color:#7f8c8d;">Manage your travel packages here.</p>
            </div>
            <a href="create_package.php" class="btn-add"><i class="fas fa-plus"></i> Add Package</a>
        </div>

        <div class="pkg-grid">
            <?php
            $res = mysqli_query($conn, "SELECT * FROM packages ORDER BY p_id DESC");
            if(mysqli_num_rows($res) > 0) {
                $delay = 0;
                while($row = mysqli_fetch_assoc($res)) {
                    $delay += 0.1;
            ?>
            <div class="pkg-card" style="animation-delay: <?php echo $delay; ?>s;">
                <img src="../assets/images/<?php echo $row['p_image']; ?>" class="pkg-img" alt="Package">
                <div class="pkg-content">
                    <div class="pkg-title"><?php echo $row['p_name']; ?></div>
                    <div class="pkg-loc"><i class="fas fa-map-marker-alt"></i> <?php echo $row['p_location']; ?></div>
                    <div class="pkg-price">₹<?php echo number_format($row['p_price']); ?></div>
                    
                    <div class="pkg-actions">
                        <a href="edit_package.php?id=<?php echo $row['p_id']; ?>" class="btn-action btn-edit"><i class="fas fa-edit"></i> Edit</a>
                        <a href="manage_packages.php?del_id=<?php echo $row['p_id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this package?');"><i class="fas fa-trash"></i> Delete</a>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p style='color:#7f8c8d;'>No packages found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>