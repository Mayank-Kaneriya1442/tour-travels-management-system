<?php 
include('../config/db.php'); 
include('../includes/header.php'); 

if(!isset($_GET['id'])) {
    echo "<script>window.location='tour_packages.php';</script>";
    exit();
}

$pid = mysqli_real_escape_string($conn, $_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM packages WHERE p_id = '$pid'");

if(mysqli_num_rows($res) == 0) {
    echo "<script>window.location='tour_packages.php';</script>";
    exit();
}

$row = mysqli_fetch_assoc($res);
?>

<style>
    .details-hero {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../assets/images/packages/<?php echo $row['p_image']; ?>');
        background-size: cover;
        background-position: center;
        height: 50vh;
        min-height: 400px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        color: white;
        padding-bottom: 50px;
        position: relative;
    }

    .details-hero-content {
        text-align: center;
        animation: fadeInUp 1s ease-out;
        max-width: 800px;
        padding: 0 20px;
    }

    .details-hero h1 {
        font-size: 3.5rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
    }

    .details-hero p {
        font-size: 1.2rem;
        margin-top: 10px;
        opacity: 0.9;
    }

    .container-custom {
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 20px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
    }

    .content-card {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border: 1px solid #eee;
    }

    .section-heading {
        font-size: 1.8rem;
        color: #2c3e50;
        margin-bottom: 20px;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }

    .info-badges {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .badge {
        background: #f8f9fa;
        padding: 10px 20px;
        border-radius: 50px;
        color: #555;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #ddd;
    }

    .badge i { color: #e67e22; }

    .booking-sidebar {
        position: sticky;
        top: 100px;
        height: fit-content;
    }

    .booking-card {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-top: 5px solid #e67e22;
        text-align: center;
    }

    .price-tag {
        font-size: 2.5rem;
        color: #2c3e50;
        font-weight: 700;
        margin: 10px 0;
    }

    .price-sub {
        color: #777;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .btn-book {
        display: block;
        width: 100%;
        padding: 15px;
        background: #e67e22;
        color: white;
        text-decoration: none;
        font-weight: bold;
        border-radius: 8px;
        font-size: 1.1rem;
        transition: 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn-book:hover {
        background: #d35400;
        transform: translateY(-2px);
    }

    .login-alert {
        background: #fff3cd;
        color: #856404;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
        font-size: 0.9rem;
        border: 1px solid #ffeeba;
    }
   

    @media (max-width: 768px) {
        .details-grid { grid-template-columns: 1fr; }
        .details-hero h1 { font-size: 2.5rem; }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="details-hero">
    <div class="details-hero-content">
        <img src="../assets/images/<?php echo $row['p_image']; ?>" alt="<?php echo $row['p_name']; ?>" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 5px solid white; margin-bottom: 20px;">   
        <h1><?php echo $row['p_name']; ?></h1>
        <p><i class="fas fa-map-marker-alt"></i> <?php echo $row['p_location']; ?></p>
    </div>
</div>

<div class="container-custom">
    <div class="details-grid">
        <!-- Left Content -->
        <div class="content-card">
            <div class="info-badges">
                <div class="badge"><i class="fas fa-map-marked-alt"></i> <?php echo $row['p_location']; ?></div>
                <div class="badge"><i class="fas fa-tag"></i> <?php echo $row['p_type']; ?></div>
                <div class="badge"><i class="fas fa-clock"></i> 5 Days / 4 Nights</div>
            </div>

            <h2 class="section-heading">Package Overview</h2>
            <p style="line-height: 1.8; color: #555; font-size: 1.05rem;">
                <?php echo nl2br($row['p_details']); ?>
            </p>

            <h2 class="section-heading" style="margin-top: 40px;">What's Included</h2>
            <ul style="list-style: none; padding: 0; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <li style="color: #555;"><i class="fas fa-check-circle" style="color: #27ae60; margin-right: 10px;"></i> Accommodation</li>
                <li style="color: #555;"><i class="fas fa-check-circle" style="color: #27ae60; margin-right: 10px;"></i> Breakfast & Dinner</li>
                <li style="color: #555;"><i class="fas fa-check-circle" style="color: #27ae60; margin-right: 10px;"></i> Sightseeing</li>
                <li style="color: #555;"><i class="fas fa-check-circle" style="color: #27ae60; margin-right: 10px;"></i> Transport</li>
            </ul>
        </div>

        <!-- Right Sidebar -->
        <div class="booking-sidebar">
            <div class="booking-card">
                <p style="margin: 0; font-weight: 600; color: #777;">Starting From</p>
                <div class="price-tag">₹<?php echo number_format($row['p_price']); ?></div>
                <div class="price-sub">per person</div>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="../user/book_tour.php?id=<?php echo $pid; ?>" class="btn-book">
                        <i class="fas fa-calendar-check"></i> Book Now
                    </a>
                <?php else: ?>
                    <a href="../user/login.php" class="btn-book" style="background: #2c3e50;">
                        Login to Book
                    </a>
                    <div class="login-alert">
                        You must be logged in to book this package.
                    </div>
                <?php endif; ?>

                <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                    <p style="margin-bottom: 10px; font-weight: 600;"><i class="fas fa-phone-alt" style="color: #e67e22;"></i> Need Help?</p>
                    <p style="color: #777; font-size: 0.9rem;">Call us at: +91 98765 43210</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>