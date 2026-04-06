<?php 
include('../config/db.php');
include('../includes/header.php'); 

// Dynamic Stats
$pkg_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM packages");
$pkg_count = mysqli_fetch_assoc($pkg_query)['total'];

$user_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$user_count = mysqli_fetch_assoc($user_query)['total'];
?>

<style>
    /* Modern & Stylish CSS */
    .about-hero {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('../assets/images/about.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed; /* Parallax effect */
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        position: relative;
    }
    
    .about-hero-content {
        animation: fadeInUp 1.5s ease-out;
    }

    .about-hero h1 {
        font-size: 3.5rem;
        margin: 0;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 3px;
        text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
    }

    .container-custom {
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 20px;
    }

    .about-section {
        display: flex;
        flex-wrap: wrap;
        gap: 50px;
        align-items: center;
        margin-bottom: 80px;
    }

    .about-img {
        flex: 1;
        min-width: 300px;
        position: relative;
    }

    .about-img img {
        width: 100%;
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        transition: transform 0.5s ease;
    }

    .about-img img:hover {
        transform: scale(1.03) rotate(1deg);
    }

    .about-content {
        flex: 1;
        min-width: 300px;
    }

    .about-content h2 {
        font-size: 2.5rem;
        color: #2c3e50;
        margin-bottom: 20px;
        position: relative;
        display: inline-block;
    }
    
    .about-content h2::after {
        content: '';
        display: block;
        width: 50%;
        height: 4px;
        background: #e67e22;
        margin-top: 10px;
        border-radius: 2px;
    }

    .about-content p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #555;
        margin-bottom: 20px;
    }

    .stats-row {
        display: flex;
        justify-content: space-around;
        background: #2c3e50;
        color: white;
        padding: 40px 20px;
        border-radius: 10px;
        margin-bottom: 80px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .stat-box {
        text-align: center;
    }

    .stat-box h3 {
        font-size: 3rem;
        margin: 0;
        color: #e67e22;
    }

    .stat-box p {
        font-size: 1.2rem;
        margin-top: 5px;
        opacity: 0.9;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }

    .feature-card {
        background: #fff;
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        text-align: center;
        transition: all 0.4s ease;
        border: 1px solid #eee;
    }

    .feature-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        border-color: #e67e22;
    }

    .feature-icon {
        font-size: 3rem;
        margin-bottom: 20px;
        display: inline-block;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* New Sections CSS */
    .mission-vision-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 80px;
    }
    .mv-card {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 15px;
        border-left: 5px solid #e67e22;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s;
    }
    .mv-card:hover { transform: translateY(-5px); }
    .mv-card h3 { color: #2c3e50; margin-top: 0; font-size: 1.5rem; margin-bottom: 15px; }
    .mv-card p { color: #555; line-height: 1.6; }

    .team-section { margin-top: 80px; text-align: center; }
    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }
    .team-member {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s;
    }
    .team-member:hover { transform: translateY(-10px); }
    .team-member img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
        border: 3px solid #e67e22;
    }
    .team-member h4 { margin: 0; color: #2c3e50; font-size: 1.2rem; }
    .team-member p { color: #777; font-size: 0.9rem; margin-top: 5px; }

    /* FAQ Section */
    .faq-section { margin-top: 80px; margin-bottom: 50px; }
    .faq-title { text-align: center; font-size: 2.5rem; color: #2c3e50; margin-bottom: 40px; }
    
    .accordion {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .accordion-item {
        background: white;
        border-radius: 10px;
        margin-bottom: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        overflow: hidden;
        border: 1px solid #eee;
    }
    
    .accordion-header {
        padding: 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        transition: background 0.3s;
    }
    
    .accordion-header:hover {
        background: #f9f9f9;
    }
    
    .accordion-header h4 {
        margin: 0;
        font-size: 1.1rem;
        color: #2c3e50;
        font-weight: 600;
    }
    
    .accordion-icon {
        font-size: 1.2rem;
        color: #e67e22;
        transition: transform 0.3s;
    }
    
    .accordion-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        background: #fcfcfc;
        border-top: 1px solid transparent;
    }
    
    .accordion-content p {
        padding: 20px;
        margin: 0;
        color: #555;
        line-height: 1.6;
    }
    
    .accordion-item.active .accordion-icon {
        transform: rotate(180deg);
    }
    
    .accordion-item.active .accordion-content {
        border-top-color: #eee;
    }
</style>

<div class="about-hero">
    <div class="about-hero-content">
        <h1>About TravelEase</h1>
        <p style="font-size: 1.5rem; margin-top: 10px;">Your Journey Begins Here</p>
    </div>
</div>

<div class="container-custom">
    
    <!-- Intro Section -->
    <div class="about-section">
        <div class="about-img">
            <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Travel">
        </div>
        <div class="about-content">
            <h2>Who We Are</h2>
            <p>Welcome to <strong>TravelEase</strong>, the ultimate Tour and Travels Management System designed to make your vacations unforgettable. We are dedicated to providing the best travel experiences with a focus on reliability, customer satisfaction, and unique adventures.</p>
            <p>Our mission is to connect you with the world's most beautiful destinations. Whether you are looking for a relaxing beach holiday, an adventurous mountain trek, or a cultural city tour, we have something for everyone.</p>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="mission-vision-grid">
        <div class="mv-card">
            <h3>Our Mission</h3>
            <p>To empower travelers with seamless, affordable, and unforgettable journeys. We strive to make exploring the world accessible to everyone through innovative technology and personalized service.</p>
        </div>
        <div class="mv-card" style="border-left-color: #2c3e50;">
            <h3>Our Vision</h3>
            <p>To become the world's most trusted travel partner, known for sustainable tourism, exceptional customer support, and creating memories that last a lifetime.</p>
        </div>
    </div>

    <!-- Dynamic Stats -->
    <div class="stats-row">
        <div class="stat-box">
            <h3><?php echo $pkg_count; ?>+</h3>
            <p>Tour Packages</p>
        </div>
        <div class="stat-box">
            <h3><?php echo $user_count; ?>+</h3>
            <p>Happy Travelers</p>
        </div>
        <div class="stat-box">
            <h3>100%</h3>
            <p>Satisfaction</p>
        </div>
        <div class="stat-box">
            <h3>24/7</h3>
            <p>Support</p>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div style="text-align: center; margin-bottom: 50px;">
        <h2 style="font-size: 2.5rem; color: #2c3e50;">Why Choose Us?</h2>
        <p style="color: #777;">We go the extra mile to ensure your trip is perfect.</p>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">✈️</div>
            <h3>Seamless Booking</h3>
            <p>Easy and fast booking process for all our exclusive tour packages.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🛡️</div>
            <h3>Secure Payments</h3>
            <p>We use top-notch security to ensure your transactions are safe.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🌍</div>
            <h3>Global Destinations</h3>
            <p>Explore a wide range of destinations across the globe with us.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🤝</div>
            <h3>Trusted Guides</h3>
            <p>Our professional guides ensure you have a safe and informative trip.</p>
        </div>
    </div>



    <!-- FAQ Section -->
    <div class="faq-section">
        <h2 class="faq-title">Frequently Asked Questions</h2>
        <div class="accordion">
            <div class="accordion-item">
                <div class="accordion-header">
                    <h4>How do I book a tour package?</h4>
                    <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                    <p>Booking is easy! Simply browse our tour packages, select the one you like, choose your travel dates, and click 'Book Now'. You'll need to create an account or login to complete the reservation.</p>
                </div>
            </div>
            <div class="accordion-item">
                <div class="accordion-header">
                    <h4>What payment methods do you accept?</h4>
                    <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                    <p>We accept various payment methods including Credit/Debit cards, Net Banking, UPI (GPay, PhonePe, Paytm), and direct bank transfers. All transactions are secured.</p>
                </div>
            </div>
            <div class="accordion-item">
                <div class="accordion-header">
                    <h4>Can I cancel my booking?</h4>
                    <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                    <p>Yes, you can cancel your booking from your dashboard. Cancellation charges may apply depending on how close to the travel date you cancel. Please refer to our refund policy for details.</p>
                </div>
            </div>
            <div class="accordion-item">
                <div class="accordion-header">
                    <h4>Are meals included in the packages?</h4>
                    <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                    <p>Most of our packages include breakfast and dinner. Specific inclusions are listed in the details section of each tour package. Please check the 'What's Included' section before booking.</p>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    const accordions = document.querySelectorAll('.accordion-header');

    accordions.forEach(acc => {
        acc.addEventListener('click', function() {
            const item = this.parentElement;
            const content = this.nextElementSibling;
            
            // Close other open items
            document.querySelectorAll('.accordion-item').forEach(other => {
                if(other !== item && other.classList.contains('active')) {
                    other.classList.remove('active');
                    other.querySelector('.accordion-content').style.maxHeight = null;
                }
            });

            // Toggle current item
            item.classList.toggle('active');
            
            if (item.classList.contains('active')) {
                content.style.maxHeight = content.scrollHeight + "px";
            } else {
                content.style.maxHeight = null;
            }
        });
    });
</script>

<?php include('../includes/footer.php'); ?>