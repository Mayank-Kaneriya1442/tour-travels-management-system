<?php 
include('../config/db.php'); 
session_start();
if(!isset($_SESSION['admin'])) { header("location:admin_login.php"); }

// Add Coupon Logic
if(isset($_POST['add_coupon'])) {
    $code = mysqli_real_escape_string($conn, strtoupper($_POST['code']));
    $percent = intval($_POST['percent']);
    
    // Check if coupon already exists
    $check = mysqli_query($conn, "SELECT id FROM coupons WHERE code='$code'");
    if(mysqli_num_rows($check) > 0) {
        echo "<script>alert('Coupon Code already exists!');</script>";
    } else {
        if(mysqli_query($conn, "INSERT INTO coupons (code, discount_percent) VALUES ('$code', '$percent')")) {
            echo "<script>alert('Coupon Added Successfully'); window.location='manage_coupons.php';</script>";
        } else {
            echo "<script>alert('Error adding coupon');</script>";
        }
    }
}

// Delete Coupon Logic
if(isset($_GET['del_id'])) {
    $did = intval($_GET['del_id']);
    mysqli_query($conn, "DELETE FROM coupons WHERE id='$did'");
    echo "<script>alert('Coupon Deleted Successfully'); window.location='manage_coupons.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Coupons</title>
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

        /* Add Form */
        .add-coupon-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 40px;
            margin-bottom: 40px;
            animation: fadeInDown 1s ease;
        }

        .form-row { display: flex; gap: 20px; align-items: flex-end; flex-wrap: wrap; }
        .form-group { flex: 1; min-width: 200px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 500; color: #34495e; }
        .form-control {
            width: 100%; padding: 12px 15px; background: #f9f9f9;
            border: 1px solid #ddd; border-radius: 8px;
            color: #333; outline: none; transition: 0.3s;
        }
        .form-control:focus { background: #fff; border-color: #e73c7e; box-shadow: 0 0 0 3px rgba(231, 60, 126, 0.1); }
        
        .btn-add {
            padding: 12px 30px; background: linear-gradient(to right, #e73c7e, #ee7752); color: white; border: none;
            border-radius: 10px; font-weight: bold; cursor: pointer; transition: 0.3s;
            height: 48px; box-shadow: 0 5px 15px rgba(231, 60, 126, 0.3);
        }
        .btn-add:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(231, 60, 126, 0.4); }

        /* Coupon Grid */
        .coupon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }

        .coupon-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 25px;
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.5s ease-out;
            animation-fill-mode: both;
            transition: 0.3s;
        }
        
        .coupon-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }

        .coupon-code {
            font-size: 1.8rem; font-weight: 800; color: #2c3e50; letter-spacing: 2px;
            border: 2px dashed #e73c7e; padding: 10px; border-radius: 10px;
            margin-bottom: 15px; background: #fff0f6;
        }

        .coupon-discount { font-size: 1.2rem; margin-bottom: 20px; color: #7f8c8d; }
        
        .btn-delete {
            background: #ffebee; color: #e74c3c; padding: 8px 15px;
            border-radius: 20px; text-decoration: none; font-size: 0.9rem; transition: 0.3s; font-weight: 600;
        }
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
            <li><a href="manage_packages.php"><i class="fas fa-globe-americas"></i> <span>Packages</span></a></li>
            <li><a href="manage_users.php"><i class="fas fa-users"></i> <span>Users</span></a></li>
            <li><a href="manage_bookings.php"><i class="fas fa-bookmark"></i> <span>Bookings</span></a></li>
            <li><a href="manage_payments.php"><i class="fas fa-credit-card"></i> <span>Payments</span></a></li>
            <li><a href="manage_enquiries.php"><i class="fas fa-envelope"></i> <span>Enquiries</span></a></li>
            <li><a href="manage_coupons.php" class="active"><i class="fas fa-tags"></i> <span>Coupons</span></a></li>
            <li><a href="manage_gallery.php"><i class="fas fa-images"></i> <span>Gallery</span></a></li>
            <li><a href="../admin/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="page-header">
            <h2>Manage Coupons</h2>
            <p style="color:#7f8c8d;">Create and manage discount codes.</p>
        </div>

        <!-- Add Coupon Form -->
        <div class="add-coupon-card">
            <h3 style="margin-bottom: 20px; color: #2c3e50;">Add New Coupon</h3>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Coupon Code</label>
                        <input type="text" name="code" class="form-control" placeholder="e.g. SUMMER2024" required style="text-transform: uppercase;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Discount Percentage (%)</label>
                        <input type="number" name="percent" class="form-control" placeholder="e.g. 10" min="1" max="100" required>
                    </div>
                    <button type="submit" name="add_coupon" class="btn-add"><i class="fas fa-plus"></i> Add Coupon</button>
                </div>
            </form>
        </div>

        <!-- Coupons List -->
        <div class="coupon-grid">
            <?php
            $res = mysqli_query($conn, "SELECT * FROM coupons ORDER BY id DESC");
            if(mysqli_num_rows($res) > 0) {
                $delay = 0;
                while($row = mysqli_fetch_assoc($res)) {
                    $delay += 0.1;
            ?>
            <div class="coupon-card" style="animation-delay: <?php echo $delay; ?>s;">
                <div class="coupon-code"><?php echo $row['code']; ?></div>
                <div class="coupon-discount">
                    <i class="fas fa-percent" style="color:#e73c7e;"></i> <?php echo $row['discount_percent']; ?>% OFF
                </div>
                <a href="manage_coupons.php?del_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this coupon?');"><i class="fas fa-trash"></i> Delete</a>
            </div>
            <?php
                }
            } else {
                echo "<p style='color:#7f8c8d;'>No coupons found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>