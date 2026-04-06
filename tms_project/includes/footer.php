<style>
    .footer-section {
        background-color: #2c3e50;
        color: #ecf0f1;
        padding: 70px 0 0;
        font-family: 'Poppins', sans-serif;
        margin-top: auto;
    }
    
    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 40px;
    }
    
    .footer-col {
        flex: 1;
        min-width: 250px;
    }
    
    .footer-col h3 {
        font-size: 1.8rem;
        margin-bottom: 20px;
        color: white;
        font-weight: 700;
    }
    
    .footer-col h3 span {
        color: #e67e22;
    }
    
    .footer-col h4 {
        font-size: 1.2rem;
        margin-bottom: 25px;
        color: white;
        position: relative;
        padding-bottom: 10px;
    }
    
    .footer-col h4::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 2px;
        background-color: #e67e22;
    }
    
    .footer-col p {
        line-height: 1.6;
        color: #bdc3c7;
        margin-bottom: 20px;
    }
    
    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .footer-links li {
        margin-bottom: 12px;
    }
    
    .footer-links a {
        color: #bdc3c7;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .footer-links a:hover {
        color: #e67e22;
        transform: translateX(5px);
    }
    
    .social-icons a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.1);
        color: white;
        border-radius: 50%;
        margin-right: 10px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .social-icons a:hover {
        background: #e67e22;
        transform: translateY(-3px);
    }
    
    .newsletter-form {
        position: relative;
    }
    
    .newsletter-form input {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 5px;
        margin-bottom: 10px;
        outline: none;
    }
    
    .newsletter-form button {
        width: 100%;
        padding: 12px;
        background: #e67e22;
        border: none;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: background 0.3s;
    }
    
    .newsletter-form button:hover {
        background: #d35400;
    }
    
    .footer-bottom {
        background: #1a252f;
        text-align: center;
        padding: 25px;
        margin-top: 60px;
        border-top: 1px solid rgba(255,255,255,0.05);
        color: #95a5a6;
        font-size: 0.9rem;
    }
</style>

<footer class="footer-section">
    <div class="footer-container">
        <!-- Brand Section -->
        <div class="footer-col">
            <h3>Travel<span>Ease</span></h3>
            <p>Your trusted partner for exploring the world's most beautiful destinations. We make travel simple, affordable, and unforgettable.</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul class="footer-links">
                <li><a href="../visitor/index.php">Home</a></li>
                <li><a href="../visitor/about.php">About Us</a></li>
                <li><a href="../visitor/tour_packages.php">Tour Packages</a></li>
                <li><a href="../visitor/gallery.php">Gallery</a></li>
                <li><a href="../visitor/contact_us.php">Contact Us</a></li>
            </ul>
        </div>

        <!-- Contact Info -->
        <div class="footer-col">
            <h4>Contact Info</h4>
            <ul class="footer-links">
                <li><i class="fas fa-map-marker-alt" style="color:#e67e22; margin-right:10px;"></i> 123 Travel Street,Vip Road,Surat-852364</li>
                <li><i class="fas fa-phone" style="color:#e67e22; margin-right:10px;"></i> +91 98765 43210</li>
                <li><i class="fas fa-envelope" style="color:#e67e22; margin-right:10px;"></i> info@travelease.com</li>
                <li><i class="fas fa-clock" style="color:#e67e22; margin-right:10px;"></i> Mon - Sat: 9:00 AM - 7:00 PM</li>
            </ul>
        </div>

        <!-- Newsletter -->
        <div class="footer-col">
            <h4>Newsletter</h4>
            <p>Subscribe to get the latest travel offers and news.</p>
            <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing!');">
                <input type="email" placeholder="Your Email Address" required>
                <button type="submit">Subscribe Now</button>
            </form>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> TravelEase. All Rights Reserved </p>
    </div>
</footer>
</body>
</html>