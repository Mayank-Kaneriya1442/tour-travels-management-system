<?php 
include('../config/db.php'); 
session_start();

if(isset($_SESSION['admin'])) {
    header("location:admin_dashboard.php");
    exit();
}

if(isset($_POST['admin_login'])){
    $u = mysqli_real_escape_string($conn, $_POST['user']);
    $p = mysqli_real_escape_string($conn, $_POST['pass']);
    
    $res = mysqli_query($conn, "SELECT * FROM admin WHERE username='$u' AND password='$p'");
    if(mysqli_num_rows($res) > 0){
        $_SESSION['admin'] = $u;
        echo "<script>window.location='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Access Denied! Invalid Credentials.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | TravelEase</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        body {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            perspective: 1000px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
            text-align: center;
            animation: cardEntrance 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        @keyframes cardEntrance {
            from { transform: translateY(50px) scale(0.9); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }

        .brand-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }
        .brand-title span { color: #e73c7e; }
        
        .subtitle { color: #666; font-size: 0.9rem; margin-bottom: 30px; }

        .input-box {
            position: relative;
            margin-bottom: 20px;
        }

        .input-box input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            background: #f4f4f4;
            border: 2px solid transparent;
            outline: none;
            border-radius: 10px;
            color: #333;
            font-size: 1rem;
            transition: 0.3s;
        }

        .input-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            transition: 0.3s;
        }

        .input-box input:focus {
            background: #fff;
            border-color: #e73c7e;
            box-shadow: 0 5px 15px rgba(231, 60, 126, 0.1);
        }

        .input-box input:focus + i { color: #e73c7e; }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(to right, #e73c7e, #ee7752);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(231, 60, 126, 0.3);
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(231, 60, 126, 0.4);
        }

        /* Decorative Circle */
        .circle-decor {
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            top: -50px;
            right: -50px;
            z-index: 0;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="circle-decor"></div>
            <div style="position: relative; z-index: 1;">
                <div class="brand-title">Admin<span>Panel</span></div>
                <p class="subtitle">Enter your credentials to access the dashboard.</p>
                
                <form method="POST">
                    <div class="input-box">
                        <input type="text" name="user" placeholder="Username" required autocomplete="off">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" name="pass" placeholder="Password" required>
                        <i class="fas fa-lock"></i>
                    </div>
                    <button type="submit" name="admin_login" class="btn-submit">Login <i class="fas fa-arrow-right"></i></button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>