<?php 
include('../config/db.php'); 
session_start();
if(!isset($_SESSION['admin'])) { header("location:admin_login.php"); }

if(!isset($_GET['id'])) { header("location:manage_packages.php"); }
$pid = intval($_GET['id']);

// Fetch existing details
$res = mysqli_query($conn, "SELECT * FROM packages WHERE p_id='$pid'");
if(mysqli_num_rows($res) == 0) { header("location:manage_packages.php"); }
$row = mysqli_fetch_assoc($res);

if(isset($_POST['update'])) {
    $pname = mysqli_real_escape_string($conn, $_POST['pname']);
    $ptype = mysqli_real_escape_string($conn, $_POST['ptype']);
    $plocation = mysqli_real_escape_string($conn, $_POST['plocation']);
    $pprice = mysqli_real_escape_string($conn, $_POST['pprice']);
    $pdetails = mysqli_real_escape_string($conn, $_POST['pdetails']);
    
    // Image Upload Logic
    if(!empty($_FILES["pimage"]["name"])) {
        $pimage = $_FILES["pimage"]["name"];
        $target_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "packages" . DIRECTORY_SEPARATOR;
        $target_file = $target_dir . basename($pimage);
        
        if(move_uploaded_file($_FILES["pimage"]["tmp_name"], $target_file)) {
            $sql = "UPDATE packages SET p_name='$pname', p_type='$ptype', p_location='$plocation', p_price='$pprice', p_details='$pdetails', p_image='$pimage' WHERE p_id='$pid'";
        } else {
            echo "<script>alert('Error uploading image.');</script>";
            $sql = "";
        }
    } else {
        // Update without changing image
        $sql = "UPDATE packages SET p_name='$pname', p_type='$ptype', p_location='$plocation', p_price='$pprice', p_details='$pdetails' WHERE p_id='$pid'";
    }
    
    if($sql && mysqli_query($conn, $sql)) {
        echo "<script>alert('Package Updated Successfully'); window.location='manage_packages.php';</script>";
    } else if ($sql) {
        echo "<script>alert('Database Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Package</title>
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
        
        .form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
            animation: fadeInUp 0.8s ease-out;
        }

        .form-header { margin-bottom: 30px; text-align: center; color: #2c3e50; }
        .form-header h2 { font-size: 2rem; margin-bottom: 10px; font-weight: 700; }
        
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 500; color: #34495e; }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            color: #333;
            font-size: 1rem;
            outline: none;
            transition: 0.3s;
        }
        .form-control:focus {
            background: #fff;
            border-color: #e73c7e;
            box-shadow: 0 0 0 3px rgba(231, 60, 126, 0.1);
        }
        
        textarea.form-control { resize: vertical; min-height: 120px; }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, #e73c7e, #ee7752);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(231, 60, 126, 0.3);
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(231, 60, 126, 0.4); }

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
            <li><a href="manage_coupons.php" class="active"><i class="fas fa-tags"></i> <span>Coupons</span></a></li>
            <li><a href="manage_gallery.php"><i class="fas fa-images"></i> <span>Gallery</span></a></li>
            <li><a href="../admin/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="form-container">
            <div class="form-header">
                <h2>Edit Package</h2>
                <p style="color:#7f8c8d;">Update details for <?php echo htmlspecialchars($row['p_name']); ?></p>
            </div>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group"><label class="form-label">Package Name</label><input type="text" name="pname" class="form-control" value="<?php echo htmlspecialchars($row['p_name']); ?>" required></div>
                <div class="form-group"><label class="form-label">Package Type</label><input type="text" name="ptype" class="form-control" value="<?php echo htmlspecialchars($row['p_type']); ?>" required></div>
                <div class="form-group"><label class="form-label">Location</label><input type="text" name="plocation" class="form-control" value="<?php echo htmlspecialchars($row['p_location']); ?>" required></div>
                <div class="form-group"><label class="form-label">Price (₹)</label><input type="number" name="pprice" class="form-control" value="<?php echo htmlspecialchars($row['p_price']); ?>" required></div>
                <div class="form-group"><label class="form-label">Package Details</label><textarea name="pdetails" class="form-control" required><?php echo htmlspecialchars($row['p_details']); ?></textarea></div>
                <div class="form-group"><label class="form-label">Update Image (Optional)</label><input type="file" name="pimage" class="form-control" style="padding: 10px;"></div>
                <div style="margin-bottom: 20px;"><img src="../assets/images/<?php echo $row['p_image']; ?>" width="100" style="border-radius: 5px;"> <span style="color: #aaa; font-size: 0.9rem;">Current Image</span></div>
                <button type="submit" name="update" class="btn-submit">Update Package</button>
            </form>
        </div>
    </div>
</body>
</html>