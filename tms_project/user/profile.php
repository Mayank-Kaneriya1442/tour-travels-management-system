<?php 
include('../config/db.php'); 
include('../includes/header.php'); 

$uid = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$uid'"));

if(isset($_POST['upd_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $mob = mysqli_real_escape_string($conn, $_POST['mob']);
    
    if(mysqli_query($conn, "UPDATE users SET full_name='$name', mobile='$mob' WHERE id='$uid'")) {
        echo "<script>alert('Profile Updated Successfully'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating profile');</script>";
    }
}
?>

<style>
    /* Modern Profile Styles */
    .profile-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 0 20px;
        min-height: 60vh;
    }

    .profile-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .profile-header {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        padding: 40px;
        text-align: center;
        color: white;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        background: #e67e22;
        border-radius: 50%;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
        border: 4px solid rgba(255,255,255,0.3);
    }

    .profile-header h2 {
        margin: 0;
        font-size: 2rem;
    }

    .profile-header p {
        margin: 5px 0 0;
        opacity: 0.8;
    }

    .profile-body {
        padding: 40px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #2c3e50;
        font-weight: 600;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #e67e22;
        outline: none;
    }

    .form-control:disabled {
        background-color: #f8f9fa;
        color: #6c757d;
        cursor: not-allowed;
    }

    .btn-update {
        background: #e67e22;
        color: white;
        border: none;
        padding: 15px 30px;
        font-size: 1.1rem;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        font-weight: bold;
        transition: background 0.3s;
    }

    .btn-update:hover {
        background: #d35400;
    }

    .back-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #7f8c8d;
        text-decoration: none;
        font-weight: 500;
    }

    .back-link:hover {
        color: #2c3e50;
    }
</style>

<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
            <p>Manage your personal information</p>
        </div>
        
        <div class="profile-body">
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                    <small style="color: #7f8c8d; font-size: 0.85rem; margin-top: 5px; display: block;">Email address cannot be changed.</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mob" class="form-control" value="<?php echo htmlspecialchars($user['mobile']); ?>" required pattern="[0-9]{10}" title="Please enter a valid 10-digit mobile number">
                </div>
                
                <button type="submit" name="upd_profile" class="btn-update">Save Changes</button>
            </form>
            
            <a href="dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>