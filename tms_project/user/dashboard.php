<?php 
include('../config/db.php'); 
include('../includes/header.php'); 

if(!isset($_SESSION['user_id'])) { header("location:login.php"); }
$uname = $_SESSION['user_name'];
$uid = $_SESSION['user_id'];

// Get user stats
$total_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT b_id FROM bookings WHERE user_id='$uid'"));
$pending_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT b_id FROM bookings WHERE user_id='$uid' AND status='Pending'"));
$confirmed_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT b_id FROM bookings WHERE user_id='$uid' AND status='Confirmed'"));
?>

<style>
    /* Dashboard Styles */
    .dashboard-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
        min-height: 60vh;
    }

    .welcome-banner {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: white;
        padding: 40px;
        border-radius: 15px;
        margin-bottom: 40px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .welcome-text h1 {
        margin: 0;
        font-size: 2.5rem;
    }

    .welcome-text p {
        margin: 10px 0 0;
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }

    .stat-card {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        transition: transform 0.3s ease;
        border-left: 5px solid #e67e22;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .stat-icon {
        font-size: 3rem;
        color: #e67e22;
        margin-right: 20px;
        width: 60px;
        text-align: center;
    }

    .stat-info h3 {
        margin: 0;
        font-size: 2.5rem;
        color: #2c3e50;
    }

    .stat-info p {
        margin: 5px 0 0;
        color: #7f8c8d;
        font-weight: 500;
    }

    .dashboard-section-title {
        font-size: 1.8rem;
        color: #2c3e50;
        margin-bottom: 20px;
        border-left: 5px solid #e67e22;
        padding-left: 15px;
    }

    .recent-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .recent-table th, .recent-table td {
        padding: 15px 20px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .recent-table th {
        background-color: #f8f9fa;
        color: #2c3e50;
        font-weight: 600;
    }

    .recent-table tr:last-child td {
        border-bottom: none;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
    }

    .status-confirmed { background: #d4edda; color: #155724; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-cancelled { background: #f8d7da; color: #721c24; }

    .action-btn {
        display: inline-block;
        padding: 8px 15px;
        background: #2c3e50;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 0.9rem;
        transition: background 0.3s;
    }

    .action-btn:hover {
        background: #34495e;
    }
</style>

<div class="dashboard-container">
    
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="welcome-text">
            <h1>Hello, <?php echo htmlspecialchars($uname); ?>!</h1>
            <p>Welcome back to your travel dashboard. Plan your next adventure today.</p>
        </div>
        <div>
            <a href="../visitor/tour_packages.php" class="btn-header btn-fill" style="background: white; color: #2c3e50;">Book New Trip</a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-suitcase-rolling"></i></div>
            <div class="stat-info">
                <h3><?php echo $total_bookings; ?></h3>
                <p>Total Bookings</p>
            </div>
        </div>
        <div class="stat-card" style="border-left-color: #f1c40f;">
            <div class="stat-icon" style="color: #f1c40f;"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <h3><?php echo $pending_bookings; ?></h3>
                <p>Pending Trips</p>
            </div>
        </div>
        <div class="stat-card" style="border-left-color: #27ae60;">
            <div class="stat-icon" style="color: #27ae60;"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <h3><?php echo $confirmed_bookings; ?></h3>
                <p>Confirmed Trips</p>
            </div>
        </div>
        <div class="stat-card" style="border-left-color: #3498db;">
            <div class="stat-icon" style="color: #3498db;"><i class="fas fa-user"></i></div>
            <div class="stat-info">
                <h3>Profile</h3>
                <p><a href="profile.php" style="color: #3498db; text-decoration: none;">Edit Details</a></p>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <h2 class="dashboard-section-title">Recent Bookings</h2>
    <div style="overflow-x: auto;">
        <table class="recent-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Package</th>
                    <th>Travel Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = mysqli_query($conn, "SELECT bookings.*, packages.p_name FROM bookings 
                                            JOIN packages ON bookings.p_id = packages.p_id 
                                            WHERE bookings.user_id = '$uid' 
                                            ORDER BY bookings.b_id DESC LIMIT 5");
                
                if(mysqli_num_rows($res) > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        $status_class = 'status-pending';
                        if($row['status'] == 'Confirmed') $status_class = 'status-confirmed';
                        if($row['status'] == 'Cancelled') $status_class = 'status-cancelled';
                ?>
                <tr>
                    <td>#BK-<?php echo $row['b_id']; ?></td>
                    <td><?php echo $row['p_name']; ?></td>
                    <td><?php echo date('d M Y', strtotime($row['from_date'])); ?></td>
                    <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $row['status']; ?></span></td>
                    <td>
                        <a href="my_bookings.php" class="action-btn">View Details</a>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; color:#777;'>No bookings found. Start your journey today!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <div style="text-align: right; margin-top: 20px;">
        <a href="my_bookings.php" style="color: #e67e22; font-weight: 600; text-decoration: none;">View All History &rarr;</a>
    </div>

</div>

<?php include('../includes/footer.php'); ?>