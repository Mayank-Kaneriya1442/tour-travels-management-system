<?php 
include('../config/db.php'); 
include('../includes/header.php'); 

if(isset($_SESSION['user_id'])) {
    echo "<script>window.location='dashboard.php';</script>";
    exit();
}

if(isset($_POST['login'])){
    $e = mysqli_real_escape_string($conn, $_POST['email']);
    $p = mysqli_real_escape_string($conn, $_POST['pass']);
    
    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$e' AND password='$p'");
    if($row = mysqli_fetch_assoc($res)){
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['full_name'];
        echo "<script>alert('Login Successful!'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Invalid Email or Password');</script>";
    }
}
?>

<style>
    .login-wrapper {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../assets/images/banners/hero.jpg');
        background-size: cover;
        background-position: center;
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .login-card {
        background: white;
        width: 100%;
        max-width: 400px;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        text-align: center;
        animation: fadeInUp 0.8s ease-out;
    }

    .login-header h2 {
        color: #2c3e50;
        font-size: 2rem;
        margin-bottom: 5px;
        font-weight: 700;
    }

    .login-header p {
        color: #7f8c8d;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
        text-align: left;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #34495e;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #e67e22;
        outline: none;
        box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1);
    }

    .btn-login {
        width: 100%;
        padding: 12px;
        background: #2c3e50;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s;
        margin-top: 10px;
    }

    .btn-login:hover {
        background: #34495e;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p>Login to access your account</p>
        </div>
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="pass" class="form-control" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="login" class="btn-login">Login</button>
        </form>
        <div style="margin-top: 20px; font-size: 0.9rem; color: #777;">
            Don't have an account? <a href="register.php" style="color: #e67e22; text-decoration: none; font-weight: 600;">Sign Up</a>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>