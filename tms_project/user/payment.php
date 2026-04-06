<?php 
include('../config/db.php'); 
include('../includes/header.php'); 

if(!isset($_SESSION['user_id'])) { header("location:login.php"); }
$uid = $_SESSION['user_id'];

if(!isset($_GET['bid'])) { header("location:my_bookings.php"); }
$bid = mysqli_real_escape_string($conn, $_GET['bid']);

// Fetch Booking & Package Details to show summary
$query = "SELECT bookings.*, packages.p_name, packages.p_price, packages.p_image, packages.p_location 
          FROM bookings 
          JOIN packages ON bookings.p_id = packages.p_id 
          WHERE bookings.b_id='$bid' AND bookings.user_id='$uid'";
$res = mysqli_query($conn, $query);

if(mysqli_num_rows($res) == 0) {
    echo "<script>alert('Invalid Booking Access'); window.location='my_bookings.php';</script>";
    exit();
}

$booking = mysqli_fetch_assoc($res);

// Calculate Total Cost based on Travelers
if(isset($booking['total_price']) && $booking['total_price'] > 0) {
    $total_cost = $booking['total_price'];
} else {
    $total_cost = $booking['p_price'] * $booking['travelers'];
}

// Read the personal static QR code image from D: drive
$qr_path = "D:\\WhatsApp Image 2026-03-23 at 4.06.52 PM.jpeg";
$qr_base64 = file_exists($qr_path) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($qr_path)) : '';
$upi_id = "mayankkaneriya15@okicici"; // Your correct UPI ID

if(isset($_POST['submit_pay'])) {
    $tid = mysqli_real_escape_string($conn, $_POST['tid']);
    $amt = mysqli_real_escape_string($conn, $_POST['amount']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);

    $sql = "INSERT INTO payments (booking_id, transaction_id, amount, method) 
            VALUES ('$bid', '$tid', '$amt', '$method')";
    
    // Update booking to show payment is 'Pending Verification'
    if(mysqli_query($conn, $sql)) {
        mysqli_query($conn, "UPDATE bookings SET payment_status='Pending Verification' WHERE b_id='$bid'");
        echo "<script>alert('Payment Details Submitted! Waiting for Admin Approval.'); window.location='receipt.php?bid=$bid';</script>";
    } else {
        echo "<script>alert('Error submitting payment details.');</script>";
    }
}
?>

<style>
    .payment-container {
        max-width: 1000px;
        margin: 50px auto;
        padding: 0 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
    }

    .order-summary {
        flex: 1;
        min-width: 300px;
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        height: fit-content;
    }

    .payment-form-card {
        flex: 1.5;
        min-width: 350px;
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-top: 5px solid #e67e22;
    }

    .section-title {
        font-size: 1.5rem;
        color: #2c3e50;
        margin-bottom: 25px;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }

    .pkg-preview img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        color: #555;
        font-size: 1rem;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 2px dashed #ddd;
        font-size: 1.3rem;
        font-weight: bold;
        color: #2c3e50;
    }

    .form-group { margin-bottom: 20px; }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #34495e;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        box-sizing: border-box;
    }

    .btn-pay {
        width: 100%;
        padding: 15px;
        background: #e67e22;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-pay:hover { background: #d35400; }

    .qr-box {
        text-align: center;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        border: 1px dashed #ccc;
    }
</style>

<div class="payment-container">
    <!-- Order Summary -->
    <div class="order-summary">
        <h3 class="section-title">Booking Summary</h3>
        <div class="pkg-preview">
            <img src="../assets/images/<?php echo $booking['p_image']; ?>" alt="Package">
            <h4><?php echo $booking['p_name']; ?></h4>
            <p style="color:#777; font-size:0.9rem;"><i class="fas fa-map-marker-alt"></i> <?php echo $booking['p_location']; ?></p>
        </div>
        
        <div class="summary-row">
            <span>Booking ID:</span>
            <strong>#BK-<?php echo $booking['b_id']; ?></strong>
        </div>
        <div class="summary-row">
            <span>Travel Date:</span>
            <strong><?php echo date('d M Y', strtotime($booking['from_date'])); ?></strong>
        </div>
        <div class="summary-row">
            <span>Travelers:</span>
            <strong><?php echo $booking['travelers']; ?> Person(s)</strong>
        </div>
        
        <div class="total-row">
            <span>Total Amount:</span>
            <span style="color: #e67e22;">₹<?php echo number_format($total_cost); ?></span>
        </div>
    </div>

    <!-- Payment Form -->
    <div class="payment-form-card">
        <h3 class="section-title">Complete Payment</h3>
        
        <div class="qr-box">
            <p style="margin-bottom: 10px; font-weight: 600;">Scan to Pay via UPI</p>
            <img src="<?php echo $qr_base64; ?>" alt="QR Code" style="border: 5px solid white; max-width: 250px;">
            <p style="margin-top: 10px; color: #555; font-size: 0.9rem;">UPI ID: <strong><?php echo $upi_id; ?></strong></p>
            <p style="margin-top: 5px; color: #e74c3c; font-size: 0.85rem;"><strong>Note:</strong> Please manually enter the total amount (₹<?php echo number_format($total_cost); ?>) in your UPI app.</p>
        </div>

        <form method="POST">
            <div class="form-group">
                <label class="form-label">Payment Method</label>
                <select name="method" class="form-control">
                    <option value="GPay">Google Pay</option>
                    <option value="PhonePe">PhonePe</option>
                    <option value="Paytm">Paytm</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Transaction ID / Reference No.</label>
                <input type="text" name="tid" class="form-control" placeholder="e.g. T1234567890" required>
                <small style="color:#777;">Enter the transaction ID from your payment app.</small>
            </div>

            <div class="form-group">
                <label class="form-label">Amount Paid</label>
                <input type="number" name="amount" class="form-control" value="<?php echo $total_cost; ?>" required>
                <small style="color:#777;">You can adjust this amount if paying a partial advance.</small>
            </div>

            <button type="submit" name="submit_pay" class="btn-pay">
                <i class="fas fa-lock"></i> Submit Payment Details
            </button>
            
            <div style="text-align:center; margin-top:15px;">
                <a href="my_bookings.php" style="color:#777; text-decoration:none;">Cancel & Go Back</a>
            </div>
        </form>
    </div>
</div>