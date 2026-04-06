<?php 
include('../config/db.php'); 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelEase - Explore the World</title>
    <style>
        /* Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        html { scroll-behavior: smooth; }
        body { background-color: #f9f9f9; }
        
        /* Hero Section */
        .hero {
            height: 85vh;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('../assets/images/index.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .hero-content {
            /* animation handled by children for staggering */
            max-width: 800px;
            padding: 20px;
            position: relative;
            z-index: 2;
        }
        
        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
            animation: zoomIn 1.2s ease-out;
        }
        
        .hero p {
            font-size: 1.5rem;
            margin-bottom: 30px;
            font-weight: 300;
            animation: fadeInUp 1s ease-out 0.3s backwards;
        }
        
        .btn-hero {
            padding: 15px 40px;
            background: #e67e22;
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: inline-block;
            box-shadow: 0 5px 15px rgba(230, 126, 34, 0.4);
            animation: fadeInUp 1s ease-out 0.6s backwards;
        }
        
        .btn-hero:hover {
            background: #d35400;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(230, 126, 34, 0.6);
        }

        /* Floating Animation */
        .hero-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
            pointer-events: none;
        }
        
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: float 15s infinite linear;
        }
        
        .shape:nth-child(1) { width: 100px; height: 100px; top: 10%; left: 10%; animation-delay: 0s; }
        .shape:nth-child(2) { width: 150px; height: 150px; top: 70%; left: 80%; animation-delay: -5s; }
        .shape:nth-child(3) { width: 60px; height: 60px; top: 40%; left: 30%; animation-delay: -2s; }
        .shape:nth-child(4) { width: 80px; height: 80px; top: 20%; left: 90%; animation-delay: -8s; }
        
        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); opacity: 0.5; }
            50% { transform: translateY(-40px) rotate(180deg); opacity: 0.8; }
            100% { transform: translateY(0) rotate(360deg); opacity: 0.5; }
        }

        /* Pulse Button Effect */
        .btn-pulse {
            animation: pulse-orange 2s infinite;
        }
        
        @keyframes pulse-orange {
            0% { box-shadow: 0 0 0 0 rgba(230, 126, 34, 0.7); }
            70% { box-shadow: 0 0 0 20px rgba(230, 126, 34, 0); }
            100% { box-shadow: 0 0 0 0 rgba(230, 126, 34, 0); }
        }

        /* Search Bar (Visual) */
        .search-container {
            position: relative;
            margin-top: -40px;
            z-index: 10;
            display: flex;
            justify-content: center;
            padding: 0 20px;
            animation: fadeInUp 1s ease-out 0.9s backwards;
        }
        
        .search-box {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            max-width: 1000px;
            width: 100%;
            align-items: center;
        }
        
        .search-input {
            flex: 1;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            min-width: 200px;
        }
        
        .btn-search {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .btn-search:hover { background: #1a252f; }

        /* Section Titles */
        .section-title {
            text-align: center;
            margin: 80px 0 50px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }
        
        .section-title h2::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: #e67e22;
            margin: 10px auto 0;
            border-radius: 2px;
        }

        /* Packages Grid */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .packages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .package-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
            position: relative;
        }
        
        .package-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        }
        
        .pkg-img {
            height: 220px;
            width: 100%;
            object-fit: cover;
        }
        
        .pkg-content {
            padding: 25px;
        }
        
        .pkg-price {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #e67e22;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        .pkg-title {
            font-size: 1.4rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .pkg-meta {
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-card {
            display: block;
            text-align: center;
            background: #2c3e50;
            color: white;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .btn-card:hover { background: #34495e; }

        /* Features Section */
        .features-section {
            background: white;
            padding: 80px 0;
            margin-top: 80px;
        }
        
        .features-row {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 30px;
            text-align: center;
        }
        
        .feature-box {
            flex: 1;
            min-width: 250px;
            padding: 20px;
            transition: transform 0.3s ease;
        }
        .feature-box:hover {
            transform: translateY(-10px);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: #e67e22;
            margin-bottom: 20px;
            transition: transform 0.5s ease;
        }

        .feature-box:hover .feature-icon {
            transform: rotateY(180deg) scale(1.1);
        }

        /* Parallax CTA */
        .cta-parallax {
            background: linear-gradient(rgba(44, 62, 80, 0.9), rgba(44, 62, 80, 0.9)), url('../assets/images/banners/hero.jpg');
            background-attachment: fixed;
            background-size: cover;
            color: white;
            text-align: center;
            padding: 100px 20px;
            margin-top: 50px;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Stats Parallax */
        .stats-parallax {
            background: linear-gradient(rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.8)), url('../assets/images/banners/hero.jpg');
            background-attachment: fixed;
            background-size: cover;
            padding: 80px 0;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            color: white;
            text-align: center;
            margin-top: 80px;
        }
        .stat-item { flex: 1; min-width: 200px; margin: 20px; }
        .stat-number { font-size: 3rem; font-weight: 800; color: #e67e22; margin-bottom: 10px; }
        .stat-label { font-size: 1.2rem; text-transform: uppercase; letter-spacing: 1px; }

        /* Destinations Grid */
        .destinations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .dest-card {
            position: relative;
            height: 250px;
            border-radius: 15px;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .dest-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .dest-card:hover img { transform: scale(1.1); }
        .dest-overlay {
            position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); padding: 20px; color: white;
        }

        /* Testimonial Slider */
        .testimonial-section {
            padding: 80px 0;
            background: #f8f9fa;
            overflow: hidden;
        }

        .marquee-container {
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            padding: 20px 0;
        }

        .marquee-content {
            display: inline-flex;
            gap: 30px;
            animation: marquee 30s linear infinite;
        }

        .marquee-content:hover {
            animation-play-state: paused;
        }

        .testimonial-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            min-width: 350px;
            white-space: normal;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid #eee;
            transition: transform 0.3s;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            border-color: #e67e22;
        }

        .user-profile { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
        .user-img { width: 50px; height: 50px; background: #e67e22; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; }
        .stars { color: #f1c40f; font-size: 0.9rem; }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* Scroll Reveal Animation */
        .reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: all 1s ease-out;
        }
        
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Responsive Text */
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .hero p { font-size: 1.1rem; }
        }
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        <div class="hero-content">
            <h1>Discover Your Next Adventure</h1>
            <p>Explore the world's most beautiful destinations with TravelEase.</p>
            <a href="tour_packages.php" class="btn-hero btn-pulse">Plan Your Trip</a>
        </div>
    </div>

    <!-- Search Bar (Visual Only for UI) -->
    <div class="search-container">
        <form action="tour_packages.php" method="GET" class="search-box">
            <input type="text" name="search" class="search-input" placeholder="Where do you want to go?">
            <input type="date" name="date" class="search-input" min="<?php echo date('Y-m-d'); ?>">
            <select class="search-input">
                <option value="">Travel Type</option>
                <option value="family">Family</option>
                <option value="Adventure">Adventure</option>
                <option value="Relaxation">Relaxation</option>
                <option value="Honeymoon">Honeymoon</option>
            </select>
            <button type="submit" class="btn-search">Search Now</button>
        </form>
    </div>

    <!-- How It Works -->
    <div class="container" style="margin-top: 80px;">
        <div class="section-title reveal">
            <h2>How It Works</h2>
            <p>3 Simple Steps to Your Perfect Trip</p>
        </div>
        <div class="features-row reveal">
            <div class="feature-box">
                <div class="feature-icon" style="background:#fff; width:100px; height:100px; line-height:100px; border-radius:50%; margin:0 auto 20px; box-shadow:0 5px 15px rgba(0,0,0,0.1);">🔍</div>
                <h3>1. Search</h3>
                <p>Find your dream destination from our wide range of packages.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon" style="background:#fff; width:100px; height:100px; line-height:100px; border-radius:50%; margin:0 auto 20px; box-shadow:0 5px 15px rgba(0,0,0,0.1);">📅</div>
                <h3>2. Book</h3>
                <p>Select your dates and book securely with just a few clicks.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon" style="background:#fff; width:100px; height:100px; line-height:100px; border-radius:50%; margin:0 auto 20px; box-shadow:0 5px 15px rgba(0,0,0,0.1);">✈️</div>
                <h3>3. Travel</h3>
                <p>Pack your bags and get ready for an unforgettable experience.</p>
            </div>
        </div>
    </div>

    <!-- Popular Packages -->
    <div class="container">
        <div class="section-title reveal">
            <h2>Popular Tour Packages</h2>
            <p>Our most booked packages this month</p>
        </div>

        <div class="packages-grid reveal">
            <?php
            $res = mysqli_query($conn, "SELECT * FROM packages ORDER BY p_id DESC LIMIT 3");
            if(mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_assoc($res)) {
            ?>
            <div class="package-card">
                <div class="pkg-price">₹<?php echo number_format($row['p_price']); ?></div>
                <img src="../assets/images/<?php echo $row['p_image']; ?>" alt="<?php echo $row['p_name']; ?>" class="pkg-img">
                <div class="pkg-content">
                    <h3 class="pkg-title"><?php echo $row['p_name']; ?></h3>
                    <div class="pkg-meta">
                        <span>📍 <?php echo $row['p_location']; ?></span>
                    </div>
                    <p style="color:#666; font-size:0.95rem; margin-bottom:20px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <?php echo $row['p_details']; ?>
                    </p>
                    <a href="package_details.php?id=<?php echo $row['p_id']; ?>" class="btn-card">View Details</a>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p style='text-align:center; width:100%;'>No packages available at the moment.</p>";
            }
            ?>
        </div>
        
        <div style="text-align:center; margin-top:40px;">
            <a href="tour_packages.php" style="color: #e67e22; font-weight:bold; text-decoration:none; font-size:1.1rem;">View All Packages &rarr;</a>
        </div>
    </div>


   

    <!-- Why Choose Us -->
    <div class="features-section">
        <div class="container">
            <div class="section-title reveal" style="margin-top:0;">
                <h2>Why Travel With Us?</h2>
            </div>
            <div class="features-row reveal">
                <div class="feature-box">
                    <div class="feature-icon">🌍</div>
                    <h3>Global Coverage</h3>
                    <p>We cover destinations all around the globe with local guides.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-icon">💰</div>
                    <h3>Best Price Guarantee</h3>
                    <p>We offer the best prices for high-quality travel experiences.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-icon">🛡️</div>
                    <h3>100% Secure</h3>
                    <p>Your payments and personal information are safe with us.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="testimonial-section reveal">
        <div class="container">
            <div class="section-title" style="margin-top:0; margin-bottom:40px;">
                <h2>What Our Travelers Say</h2>
                <p>Real stories from real adventures</p>
            </div>
        </div>
        
        <div class="marquee-container">
            <div class="marquee-content">
                <!-- Original Set -->
                <div class="testimonial-card">
                    <div class="user-profile"><div class="user-img">JD</div><div><h4>John Doe</h4><div class="stars">★★★★★</div></div></div>
                    <p>"An unforgettable experience! The booking process was smooth and the tour guide was amazing."</p>
                </div>
                <div class="testimonial-card">
                    <div class="user-profile"><div class="user-img">AS</div><div><h4>Alice Smith</h4><div class="stars">★★★★★</div></div></div>
                    <p>"TravelEase made our honeymoon perfect. Highly recommended for anyone looking for a hassle-free trip."</p>
                </div>
                <div class="testimonial-card">
                    <div class="user-profile"><div class="user-img">RJ</div><div><h4>Robert Johnson</h4><div class="stars">★★★★☆</div></div></div>
                    <p>"Great value for money. The hotels were top-notch and the itinerary was well planned."</p>
                </div>
                <div class="testimonial-card">
                    <div class="user-profile"><div class="user-img">EM</div><div><h4>Emily Miller</h4><div class="stars">★★★★★</div></div></div>
                    <p>"I loved every minute of it. The customer support team was very helpful when I needed to change dates."</p>
                </div>
                <div class="testimonial-card">
                    <div class="user-profile"><div class="user-img">MK</div><div><h4>Michael King</h4><div class="stars">★★★★★</div></div></div>
                    <p>"Best travel agency I've used. The local guides were very knowledgeable and friendly."</p>
                </div>

                <!-- Duplicate Set for Seamless Loop -->
                <div class="testimonial-card">
                    <div class="user-profile"><div class="user-img">JD</div><div><h4>John Doe</h4><div class="stars">★★★★★</div></div></div>
                    <p>"An unforgettable experience! The booking process was smooth and the tour guide was amazing."</p>
                </div>
                <div class="testimonial-card">
                    <div class="user-profile"><div class="user-img">AS</div><div><h4>Alice Smith</h4><div class="stars">★★★★★</div></div></div>
                    <p>"TravelEase made our honeymoon perfect. Highly recommended for anyone looking for a hassle-free trip."</p>
                </div>
                <div class="testimonial-card">
                    <div class="user-profile"><div class="user-img">RJ</div><div><h4>Robert Johnson</h4><div class="stars">★★★★☆</div></div></div>
                    <p>"Great value for money. The hotels were top-notch and the itinerary was well planned."</p>
                </div>
                <div class="testimonial-card">
                    <div class="user-profile"><div class="user-img">EM</div><div><h4>Emily Miller</h4><div class="stars">★★★★★</div></div></div>
                    <p>"I loved every minute of it. The customer support team was very helpful when I needed to change dates."</p>
                </div>
                <div class="testimonial-card">
                    <div class="user-profile"><div class="user-img">MK</div><div><h4>Michael King</h4><div class="stars">★★★★★</div></div></div>
                    <p>"Best travel agency I've used. The local guides were very knowledgeable and friendly."</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="cta-parallax reveal">
        <h2 style="font-size:2.5rem; margin-bottom:20px;">Ready to Start Your Journey?</h2>
        <p style="font-size:1.2rem; margin-bottom:30px;">Join thousands of happy travelers today.</p>
        <a href="../user/register.php" class="btn-hero" style="background:white; color:#2c3e50;">Sign Up Now</a>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script>
        window.addEventListener('scroll', reveal);
        function reveal() {
            var reveals = document.querySelectorAll('.reveal');
            for (var i = 0; i < reveals.length; i++) {
                var windowheight = window.innerHeight;
                var revealtop = reveals[i].getBoundingClientRect().top;
                var revealpoint = 150;
                if (revealtop < windowheight - revealpoint) {
                    reveals[i].classList.add('active');
                }
            }
        }
        // Trigger once on load
        reveal();
    </script>
</body>
</html>