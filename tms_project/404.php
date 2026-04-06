<?php include('config/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - TravelEase</title>
    <style>
        /* 404 Page Styles */
        .error-section {
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)), url('assets/images/banners/hero.jpg');
            background-size: cover;
            background-position: center;
            padding: 20px;
        }

        .error-content h1 {
            font-size: 8rem;
            font-weight: 800;
            color: #2c3e50;
            margin: 0;
            line-height: 1;
            text-shadow: 4px 4px 0px #e67e22;
            animation: bounceIn 1s ease;
        }

        .error-content h2 {
            font-size: 2.5rem;
            color: #34495e;
            margin: 10px 0 20px;
            font-family: 'Poppins', sans-serif;
        }

        .error-content p {
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            font-family: 'Poppins', sans-serif;
        }

        .btn-home {
            padding: 15px 40px;
            background: #e67e22;
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: inline-block;
            box-shadow: 0 10px 20px rgba(230, 126, 34, 0.3);
            font-family: 'Poppins', sans-serif;
        }

        .btn-home:hover {
            background: #d35400;
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(230, 126, 34, 0.5);
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="error-section">
        <div class="error-content">
            <h1>404</h1>
            <h2>Oops! Page Not Found</h2>
            <p>The destination you are looking for doesn't exist or has been moved. Let's get you back on track.</p>
            <a href="visitor/index.php" class="btn-home">Go Back Home</a>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
</body>
</html>