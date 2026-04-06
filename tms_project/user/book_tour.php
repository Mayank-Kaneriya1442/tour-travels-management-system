<?php 
include('../config/db.php'); 
include('../includes/header.php'); 

if(!isset($_SESSION['user_id'])) { header("location:login.php"); }

if(!isset($_GET['id'])) { header("location:../visitor/tour_packages.php"); }
$pid = mysqli_real_escape_string($conn, $_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch Package Details
$res = mysqli_query($conn, "SELECT * FROM packages WHERE p_id='$pid'");
if(mysqli_num_rows($res) == 0) { header("location:../visitor/tour_packages.php"); }
$package = mysqli_fetch_assoc($res);

if(isset($_POST['book'])) {
    $from_date = mysqli_real_escape_string($conn, $_POST['from_date']);
    $travelers = mysqli_real_escape_string($conn, $_POST['travelers']);
    $coupon_code = isset($_POST['coupon_code']) ? mysqli_real_escape_string($conn, $_POST['coupon_code']) : '';
    
    // Calculate Total Price
    $total_price = $package['p_price'] * $travelers;
    
    // Apply Coupon if valid
    if(!empty($coupon_code)) {
        $cpn_res = mysqli_query($conn, "SELECT discount_percent FROM coupons WHERE code='$coupon_code' AND status=1");
        if($cpn_row = mysqli_fetch_assoc($cpn_res)) {
            $discount = ($total_price * $cpn_row['discount_percent']) / 100;
            $total_price -= $discount;
        }
    }
    
    // Basic validation to ensure date is not in the past
    if(strtotime($from_date) < strtotime(date('Y-m-d'))) {
        echo "<script>alert('Please select a valid future date.');</script>";
    } else {
        $sql = "INSERT INTO bookings (p_id, user_id, from_date, travelers, total_price, status, payment_status) 
                VALUES ('$pid', '$user_id', '$from_date', '$travelers', '$total_price', 'Pending', 'Unpaid')";
                
        if(mysqli_query($conn, $sql)) {
            $last_id = mysqli_insert_id($conn);
            echo "<script>alert('Booking Initiated! Proceed to Payment.'); window.location='payment.php?bid=$last_id';</script>";
        } else {
            echo "<script>alert('Error processing booking. Please try again.');</script>";
        }
    }
}
?>

<style>
    .booking-page-container {
        max-width: 1000px;
        margin: 50px auto;
        padding: 0 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        min-height: 60vh;
    }

    .booking-summary {
        flex: 1;
        min-width: 300px;
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        height: fit-content;
    }

    .booking-form-card {
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

    .price-highlight {
        font-size: 1.5rem;
        color: #e67e22;
        font-weight: bold;
    }

    .form-group { margin-bottom: 25px; }
    
    .form-label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: #34495e;
    }

    .form-control {
        width: 100%;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        box-sizing: border-box;
        transition: 0.3s;
    }

    .form-control:focus {
        border-color: #e67e22;
        outline: none;
    }

    .btn-confirm {
        width: 100%;
        padding: 15px;
        background: #2c3e50;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-confirm:hover { background: #34495e; }
</style>

<div class="booking-page-container">
    <!-- Package Summary -->
    <div class="booking-summary">
        <h3 class="section-title">Package Details</h3>
        <div class="pkg-preview">
            <img src="../assets/images/<?php echo $package['p_image']; ?>" alt="Package">
            <h4><?php echo $package['p_name']; ?></h4>
            <p style="color:#777; font-size:0.9rem; margin-bottom: 10px;"><i class="fas fa-map-marker-alt"></i> <?php echo $package['p_location']; ?></p>
            <p style="color:#777; font-size:0.9rem;"><i class="fas fa-tag"></i> <?php echo $package['p_type']; ?></p>
        </div>
        
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px dashed #ddd;">
            <div class="summary-row">
                <span>Price per person:</span>
                <span class="price-highlight">₹<?php echo number_format($package['p_price']); ?></span>
            </div>
        </div>
    </div>

    <!-- Booking Form -->
    <div class="booking-form-card">
        <h3 class="section-title">Confirm Booking</h3>
        <p style="margin-bottom: 30px; color: #777;">Please select your travel date to proceed.</p>

        <form method="POST">
            <div class="form-group">
                <label class="form-label">Select Travel Date</label>
                <input type="date" name="from_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                <small style="color: #999; margin-top: 5px; display: block;">* Dates are subject to availability confirmation.</small>
            </div>

            <div class="form-group">
                <label class="form-label">Number of Travelers</label>
                <input type="number" name="travelers" id="travelers" class="form-control" value="1" min="1" required oninput="updatePrice()">
            </div>

            <div class="form-group">
                <label class="form-label">Coupon Code</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Enter Code" style="text-transform: uppercase;">
                    <button type="button" onclick="checkCoupon()" style="background: #3498db; color: white; border: none; padding: 0 20px; border-radius: 8px; cursor: pointer;">Apply</button>
                </div>
                <small id="couponMessage" style="display: block; margin-top: 5px;"></small>
            </div>

            <div class="form-group">
                <label class="form-label">Total Amount</label>
                <div style="font-size: 1.5rem; font-weight: bold; color: #e67e22;" id="totalPrice">₹<?php echo number_format($package['p_price']); ?></div>
                <small id="discountText" style="color: green; font-weight: bold;"></small>
            </div>

            <div class="form-group">
                <label class="form-label">Traveler Name</label>
                <input type="text" value="<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?>" class="form-control" readonly style="background: #f9f9f9;">
            </div>

            <button type="submit" name="book" class="btn-confirm">
                <i class="fas fa-check-circle"></i> Confirm & Proceed to Pay
            </button>
            
            <div style="text-align:center; margin-top:20px;">
                <a href="../visitor/package_details.php?id=<?php echo $pid; ?>" style="color:#777; text-decoration:none;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    let discountPercent = 0;

    function checkCoupon() {
        const code = document.getElementById('coupon_code').value;
        if(code === "") return;

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "check_coupon.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if(this.responseText === "invalid") {
                document.getElementById('couponMessage').style.color = 'red';
                document.getElementById('couponMessage').innerText = "Invalid Coupon Code";
                discountPercent = 0;
            } else {
                discountPercent = parseInt(this.responseText);
                document.getElementById('couponMessage').style.color = 'green';
                document.getElementById('couponMessage').innerText = "Coupon Applied! " + discountPercent + "% Off";
            }
            updatePrice();
        }
        xhr.send("code=" + code);
    }

    function updatePrice() {
        const price = <?php echo $package['p_price']; ?>;
        const travelers = document.getElementById('travelers').value;
        let originalTotal = price * travelers;
        let total = originalTotal;
        
        if(discountPercent > 0) {
            const discountAmount = (originalTotal * discountPercent) / 100;
            total = originalTotal - discountAmount;
            document.getElementById('discountText').innerText = "(Discount Applied: " + discountPercent + "%)";
            document.getElementById('totalPrice').innerHTML = '<span style="text-decoration: line-through; color: #95a5a6; font-size: 1.1rem; margin-right: 10px;">₹' + originalTotal.toLocaleString('en-IN') + '</span> ₹' + total.toLocaleString('en-IN');
        } else {
            document.getElementById('discountText').innerText = "";
            document.getElementById('totalPrice').innerText = '₹' + total.toLocaleString('en-IN');
        }
    }
</script>

<?php include('../includes/footer.php'); ?>