<?php 
include('../config/db.php'); 
include('../includes/header.php'); 

if(isset($_POST['send_link'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    
    if(mysqli_num_rows($res) > 0) {
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        mysqli_query($conn, "UPDATE users SET reset_token='$token', token_expiry='$expiry' WHERE email='$email'");
        
        // Generate Link (Adjust 'localhost/tms_project' if your URL is different)
        $link = "http://localhost/tms_project/user/reset_password.php?email=$email&token=$token";
        
        // Send Email (Requires configured SMTP on XAMPP, otherwise use the alert below for testing)
        $subject = "Password Reset Request - TravelEase";
        $message = "Click here to reset your password: " . $link;
        $headers = "From: no-reply@travelease.com";
        
        // mail($email, $subject, $message, $headers); // Uncomment if SMTP is set up
        
        // For Localhost Testing: Show link in alert
        echo "<script>alert('Reset Link Generated! (Check console/demo): $link'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Email not found in our system.');</script>";
    }
}
?>

<style>
    .fp-wrapper {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../assets/images/banners/hero.jpg');
        background-size: cover;
        background-position: center;
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .fp-card {
        background: white;
        width: 100%;
        max-width: 400px;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        text-align: center;
        animation: fadeInUp 0.8s ease-out;
    }

    .form-group { margin-bottom: 20px; text-align: left; }
    .form-label { display: block; margin-bottom: 8px; color: #34495e; font-weight: 600; }
    
    .form-control {
        width: 100%; padding: 12px 15px; border: 1px solid #ddd;
        border-radius: 8px; font-size: 1rem; transition: all 0.3s; box-sizing: border-box;
    }
    .form-control:focus { border-color: #e67e22; outline: none; }

    .btn-submit {
        width: 100%; padding: 12px; background: #e67e22; color: white;
        border: none; border-radius: 8px; font-size: 1.1rem; font-weight: bold;
        cursor: pointer; transition: background 0.3s;
    }
    .btn-submit:hover { background: #d35400; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="fp-wrapper">
    <div class="fp-card">
        <h2 style="color:#2c3e50; margin-bottom:10px;">Forgot Password?</h2>
        <p style="color:#7f8c8d; margin-bottom:30px;">Enter your email to receive a reset link.</p>
        
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your registered email" required>
            </div>
            <button type="submit" name="send_link" class="btn-submit">Send Reset Link</button>
        </form>
        
        <div style="margin-top: 20px;">
            <a href="login.php" style="color: #7f8c8d; text-decoration: none;">Back to Login</a>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>