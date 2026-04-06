<?php 
include('../config/db.php'); 
session_start();
if(!isset($_SESSION['admin'])) { header("location:admin_login.php"); }

// Fetch Stats
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));
$total_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT b_id FROM bookings"));
$pending_payments = mysqli_num_rows(mysqli_query($conn, "SELECT b_id FROM bookings WHERE payment_status='Pending Verification'"));
$total_packages = mysqli_num_rows(mysqli_query($conn, "SELECT p_id FROM packages"));
$total_enquiries = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM enquiry"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | TravelEase Admin</title>
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

        /* Sidebar Styles */
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
            min-height: 100vh;
        }

        .header-title {
            font-size: 2.2rem;
            margin-bottom: 10px;
            color: #2c3e50;
            font-weight: 700;
        }

        .header-subtitle {
            color: #7f8c8d;
            margin-bottom: 50px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: 0.3s;
            position: relative;
            overflow: hidden;
            border-bottom: 4px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        /* Card Colors */
        .card-1 { border-color: #3498db; }
        .card-1 .stat-icon { color: #3498db; background: rgba(52, 152, 219, 0.1); }
        
        .card-2 { border-color: #e67e22; }
        .card-2 .stat-icon { color: #e67e22; background: rgba(230, 126, 34, 0.1); }
        
        .card-3 { border-color: #2ecc71; }
        .card-3 .stat-icon { color: #2ecc71; background: rgba(46, 204, 113, 0.1); }
        
        .card-4 { border-color: #9b59b6; }
        .card-4 .stat-icon { color: #9b59b6; background: rgba(155, 89, 182, 0.1); }

        .stat-info h3 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #95a5a6;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            transition: 0.5s;
        }

        .stat-card:hover .stat-icon { transform: rotate(15deg) scale(1.1); }

        /* Quick Actions */
        .recent-section {
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .recent-header h3 { color: #2c3e50; margin-bottom: 25px; font-size: 1.3rem; }

        .action-btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 25px;
            background: #f8f9fa;
            color: #2c3e50;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            margin-right: 15px;
            margin-bottom: 15px;
            transition: 0.3s;
            border: 1px solid #eee;
        }

        .action-btn i { margin-right: 10px; color: #e73c7e; }
        
        .action-btn:hover {
            background: #e73c7e;
            color: white;
            border-color: #e73c7e;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(231, 60, 126, 0.2);
        }
        .action-btn:hover i { color: white; }

        /* Animations */
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .sidebar { animation: slideIn 0.5s ease-out; }
        .stat-card { animation: fadeInUp 0.6s ease-out backwards; }
        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
        .recent-section { animation: fadeInUp 0.8s ease-out backwards; animation-delay: 0.5s; }

        @media (max-width: 900px) {
            .sidebar { width: 80px; padding: 20px 10px; }
            .brand span, .nav-links a span { display: none; }
            .nav-links a { justify-content: center; padding: 15px; }
            .nav-links a i { margin: 0; font-size: 1.4rem; }
            .main-content { margin-left: 80px; width: calc(100% - 80px); padding: 30px; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand"><i class="fas fa-paper-plane"></i> <span>TravelEase</span></div>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="manage_packages.php"><i class="fas fa-globe-americas"></i> <span>Packages</span></a></li>
            <li><a href="manage_users.php"><i class="fas fa-users"></i> <span>Users</span></a></li>
            <li><a href="manage_bookings.php"><i class="fas fa-bookmark"></i> <span>Bookings</span></a></li>
            <li><a href="manage_payments.php"><i class="fas fa-credit-card"></i> <span>Payments</span></a></li>
            <li><a href="manage_enquiries.php"><i class="fas fa-envelope"></i> <span>Enquiries</span></a></li>
            <li><a href="manage_coupons.php"><i class="fas fa-tags"></i> <span>Coupons</span></a></li>
            <li><a href="manage_gallery.php"><i class="fas fa-images"></i> <span>Gallery</span></a></li>
            <li><a href="../admin/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1 class="header-title">Dashboard Overview</h1>
        <p class="header-subtitle">Welcome back! Here's your daily performance summary.</p>

        <div class="stats-grid">
            <div class="stat-card card-1">
                <div class="stat-info">
                    <h3 class="counter" data-target="<?php echo $total_users; ?>">0</h3>
                    <p>Total Users</p>
                </div>
                <div class="stat-icon"><i class="fas fa-user-friends"></i></div>
            </div>
            <div class="stat-card card-2">
                <div class="stat-info">
                    <h3 class="counter" data-target="<?php echo $total_bookings; ?>">0</h3>
                    <p>Total Bookings</p>
                </div>
                <div class="stat-icon"><i class="fas fa-plane-departure"></i></div>
            </div>
            <div class="stat-card card-3">
                <div class="stat-info">
                    <h3 class="counter" data-target="<?php echo $pending_payments; ?>">0</h3>
                    <p>Pending Payments</p>
                </div>
                <div class="stat-icon"><i class="fas fa-file-invoice-dollar"></i></div>
            </div>
            <div class="stat-card card-4">
                <div class="stat-info">
                    <h3 class="counter" data-target="<?php echo $total_packages; ?>">0</h3>
                    <p>Active Packages</p>
                </div>
                <div class="stat-icon"><i class="fas fa-map-marked-alt"></i></div>
            </div>
        </div>

        <div class="recent-section">
            <div class="recent-header">
                <h3>Quick Actions</h3>
            </div>
            <div>
                <a href="manage_packages.php" class="action-btn"><i class="fas fa-plus"></i> Add New Package</a>
                <a href="manage_bookings.php" class="action-btn"><i class="fas fa-list"></i> View All Bookings</a>
                <a href="manage_enquiries.php" class="action-btn"><i class="fas fa-comment-dots"></i> Check Enquiries</a>
            </div>
        </div>
    </div>

    <script>
        // Number Counter Animation
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            counter.innerText = '0';
            const updateCounter = () => {
                const target = +counter.getAttribute('data-target');
                const c = +counter.innerText;
                const increment = target / 50; // Adjust speed

                if (c < target) {
                    counter.innerText = Math.ceil(c + increment);
                    setTimeout(updateCounter, 20);
                } else {
                    counter.innerText = target;
                }
            };
            updateCounter();
        });
    </script>
</body>
</html>