<?php 
include('../config/db.php'); 
include('../includes/header.php'); 

if(isset($_GET['email']) && isset($_GET['token'])) {
    $email = mysqli_real_escape_string($conn, $_GET['email']);
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Validate Token
    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND reset_token='$token' AND token_expiry > NOW()");
    if(mysqli_num_rows($res) == 0) {
        echo "<script>alert('Invalid or Expired Reset Link'); window.location='login.php';</script>";
        exit();
    }
} else if(!isset($_POST['reset_pass'])) {
    header("location:login.php");
    exit();
}

if(isset($_POST['reset_pass'])) {
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Update Password and Clear Token
    mysqli_query($conn, "UPDATE users SET password='$pass', reset_token=NULL, token_expiry=NULL WHERE email='$email'");
    echo "<script>alert('Password Reset Successfully! Please Login.'); window.location='login.php';</script>";
}
?>

<style>
    .rp-wrapper {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../assets/images/banners/hero.jpg');
        background-size: cover;
        background-position: center;
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .rp-card {
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
        width: 100%; padding: 12px; background: #2c3e50; color: white;
        border: none; border-radius: 8px; font-size: 1.1rem; font-weight: bold;
        cursor: pointer; transition: background 0.3s;
    }
    .btn-submit:hover { background: #34495e; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="rp-wrapper">
    <div class="rp-card">
        <h2 style="color:#2c3e50; margin-bottom:10px;">Reset Password</h2>
        <p style="color:#7f8c8d; margin-bottom:30px;">Create a new strong password.</p>
        
        <form method="POST">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
            
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="pass" class="form-control" placeholder="Enter new password" required>
            </div>
            
            <button type="submit" name="reset_pass" class="btn-submit">Update Password</button>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>