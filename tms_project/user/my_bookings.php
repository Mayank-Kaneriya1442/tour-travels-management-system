<?php 
include('../config/db.php'); 
include('../includes/header.php'); 

if(!isset($_SESSION['user_id'])) { header("location:login.php"); }
$uid = $_SESSION['user_id'];
?>

<style>
    /* Modern Booking History Styles */
    .dashboard-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
        min-height: 60vh;
    }

    .page-header {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-header h2 { margin: 0; font-size: 2rem; }
    .page-header p { margin: 5px 0 0; opacity: 0.9; }

    .booking-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .booking-table th, .booking-table td {
        padding: 20px;
        text-align: left;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }

    .booking-table th {
        background-color: #f8f9fa;
        color: #2c3e50;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .booking-img {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        display: inline-block;
        text-transform: uppercase;
    }

    .status-confirmed { background: #d4edda; color: #155724; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-cancelled { background: #f8d7da; color: #721c24; }

    .btn-action {
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .btn-pay { background: #e67e22; color: white; }
    .btn-pay:hover { background: #d35400; }

    .btn-view { background: #3498db; color: white; }
    .btn-view:hover { background: #2980b9; }

    .btn-receipt { background: #7f8c8d; color: white; }
    .btn-receipt:hover { background: #95a5a6; }
</style>

<div class="dashboard-container">
    <div class="page-header">
        <div>
            <h2>My Bookings</h2>
            <p>Track your past and upcoming trips</p>
        </div>
        <a href="dashboard.php" style="color: white; text-decoration: none; border: 1px solid rgba(255,255,255,0.5); padding: 8px 20px; border-radius: 20px; transition: 0.3s; background: rgba(255,255,255,0.1);"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <div style="overflow-x: auto;">
        <table class="booking-table">
            <thead>
                <tr>
                    <th>Package Details</th>
                    <th>Booking Info</th>
                    <th>Travel Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT bookings.*, packages.p_name, packages.p_image, packages.p_price, packages.p_location 
                          FROM bookings 
                          JOIN packages ON bookings.p_id = packages.p_id 
                          WHERE bookings.user_id = '$uid' 
                          ORDER BY bookings.b_id DESC";
                $res = mysqli_query($conn, $query);
                
                if(mysqli_num_rows($res) > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        // Status Logic
                        $status_class = 'status-pending';
                        if($row['status'] == 'Confirmed') $status_class = 'status-confirmed';
                        if($row['status'] == 'Cancelled') $status_class = 'status-cancelled';
                        
                        // Payment Logic
                        $pay_status = $row['payment_status'];
                        $show_pay_btn = ($pay_status == 'Unpaid' && $row['status'] != 'Cancelled');
                ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img src="../assets/images/<?php echo $row['p_image']; ?>" class="booking-img" alt="Package">
                            <div>
                                <div style="font-weight: bold; color: #2c3e50; font-size: 1.1rem;"><?php echo $row['p_name']; ?></div>
                                <div style="font-size: 0.9rem; color: #777;"><i class="fas fa-map-marker-alt"></i> <?php echo $row['p_location']; ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="color: #555;"><strong>ID:</strong> #BK-<?php echo $row['b_id']; ?></div>
                        <div style="color: #e67e22; font-weight: bold;">₹<?php echo number_format($row['p_price']); ?></div>
                    </td>
                    <td>
                        <div style="font-weight: 500; color: #2c3e50;"><?php echo date('d M Y', strtotime($row['from_date'])); ?></div>
                    </td>
                    <td>
                        <span class="status-badge <?php echo $status_class; ?>"><?php echo $row['status']; ?></span>
                        <div style="font-size: 0.8rem; margin-top: 5px; color: #777;">
                            Payment: <span style="color: <?php echo ($pay_status=='Paid')?'green':'#e67e22'; ?>; font-weight:bold;"><?php echo $pay_status; ?></span>
                        </div>
                    </td>
                    <td>
                        <?php if($show_pay_btn): ?>
                            <a href="payment.php?bid=<?php echo $row['b_id']; ?>" class="btn-action btn-pay"><i class="fas fa-credit-card"></i> Pay</a>
                        <?php endif; ?>
                        
                        <?php if($pay_status != 'Unpaid'): ?>
                            <a href="receipt.php?bid=<?php echo $row['b_id']; ?>" class="btn-action btn-receipt"><i class="fas fa-file-invoice"></i> Receipt</a>
                        <?php endif; ?>
                        
                        <a href="../visitor/package_details.php?id=<?php echo $row['p_id']; ?>" class="btn-action btn-view"><i class="fas fa-eye"></i> View</a>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; padding: 50px; color: #777;'>No bookings found. <a href='../visitor/tour_packages.php' style='color:#e67e22; font-weight:bold;'>Start your journey today!</a></td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>