<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Fonts & Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* Global Header Styles */
    :root {
        --primary-color: #2c3e50;
        --accent-color: #e67e22;
        --text-light: #ecf0f1;
    }

    body {
        margin: 0;
        padding-top: 80px;
        font-family: 'Poppins', sans-serif;
    }

    .main-header {
        background-color: var(--primary-color);
        height: 80px;
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 5%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        box-sizing: border-box;
    }

    .brand-logo {
        font-size: 1.8rem;
        font-weight: 700;
        color: white;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .brand-logo span {
        color: var(--accent-color);
    }

    .nav-menu {
        display: flex;
        gap: 30px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-menu li a {
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
    }

    .nav-menu li a:hover {
        color: white;
    }

    .nav-menu li a::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -5px;
        left: 0;
        background-color: var(--accent-color);
        transition: width 0.3s ease;
    }

    .nav-menu li a:hover::after {
        width: 100%;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .btn-header {
        padding: 8px 25px;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline {
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
    }

    .btn-outline:hover {
        border-color: white;
        background: rgba(255,255,255,0.1);
    }

    .btn-fill {
        background-color: var(--accent-color);
        color: white;
        box-shadow: 0 4px 10px rgba(230, 126, 34, 0.3);
    }

    .btn-fill:hover {
        background-color: #d35400;
        transform: translateY(-2px);
    }

    @media (max-width: 900px) {
        .nav-menu { display: none; } /* Simple hide for mobile in this snippet */
        .main-header { padding: 0 20px; }
    }
</style>

<header class="main-header">
    <!-- Brand Logo -->
    <a href="../visitor/index.php" class="brand-logo">
        <i class="fas fa-paper-plane"></i> Travel<span>Ease</span>
    </a>

    <!-- Navigation Menu -->
    <ul class="nav-menu">
        <li><a href="../visitor/index.php">Home</a></li>
        <li><a href="../visitor/about.php">About Us</a></li>
        <li><a href="../visitor/tour_packages.php">Packages</a></li>
        <li><a href="../visitor/gallery.php">Gallery</a></li>
        <li><a href="../visitor/contact_us.php">Contact</a></li>
    </ul>

    <!-- User Actions -->
    <div class="header-actions">
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="../user/dashboard.php" class="btn-header btn-outline">
                <i class="fas fa-user-circle"></i> Dashboard
            </a>
            <a href="../user/logout.php" class="btn-header btn-fill" style="background-color: #c0392b;">
                Logout
            </a>
        <?php else: ?>
            <a href="../user/login.php" class="btn-header btn-outline">Login</a>
            <a href="../user/register.php" class="btn-header btn-fill">Sign Up</a>
        <?php endif; ?>
    </div>
</header>