<?php 
include('../config/db.php'); 
include('../includes/header.php'); 

if(isset($_POST['send_enquiry'])) {
    $name = mysqli_real_escape_string($conn, $_POST['fname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO enquiry (full_name, email, subject, description) VALUES ('$name', '$email', '$subject', '$msg')";
    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Your enquiry has been sent. We will contact you soon.');</script>";
    }
}
?>

<style>
    /* Modern Contact Page Styles */
    .contact-hero {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('../assets/images/contact.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
    }

    .contact-hero h1 {
        font-size: 3rem;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 2px;
        animation: fadeInUp 1s ease-out;
    }

    .container-custom {
        max-width: 1100px;
        margin: 0 auto;
        padding: 60px 20px;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 50px;
        margin-bottom: 50px;
    }

    .contact-info-card {
        background: #2c3e50;
        color: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .contact-info-item {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
    }

    .contact-icon {
        font-size: 2rem;
        color: #e67e22;
        margin-right: 20px;
        width: 50px;
        text-align: center;
    }

    .contact-form-card {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        border: 1px solid #eee;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-control {
        width: 100%;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: border 0.3s;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #e67e22;
        outline: none;
    }

    .btn-submit {
        background: #e67e22;
        color: white;
        border: none;
        padding: 15px 30px;
        font-size: 1.1rem;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        transition: background 0.3s;
        font-weight: bold;
    }

    .btn-submit:hover {
        background: #d35400;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="contact-hero">
    <div>
        <h1>Contact Us</h1>
        <p style="font-size: 1.2rem; margin-top: 10px;">We'd Love to Hear From You</p>
    </div>
</div>

<div class="container-custom">
    <div class="contact-grid">
        <!-- Contact Info -->
        <div class="contact-info-card">
            <h2 style="margin-bottom: 30px; border-bottom: 2px solid #e67e22; padding-bottom: 10px; display: inline-block;">Get In Touch</h2>
            
            <div class="contact-info-item">
                <div class="contact-icon">📍</div>
                <div>
                    <h4 style="margin: 0;">Address</h4>
                    <p style="margin: 5px 0; opacity: 0.8;">123 Travel Street,Vip Road,Surat-852364</p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon">📞</div>
                <div>
                    <h4 style="margin: 0;">Phone</h4>
                    <p style="margin: 5px 0; opacity: 0.8;">+91 98765 43210</p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon">✉️</div>
                <div>
                    <h4 style="margin: 0;">Email</h4>
                    <p style="margin: 5px 0; opacity: 0.8;">info@travelease.com</p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon">🕒</div>
                <div>
                    <h4 style="margin: 0;">Business Hours</h4>
                    <p style="margin: 5px 0; opacity: 0.8;">Mon - Sat: 9:00 AM - 7:00 PM</p>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form-card">
            <h2 style="color: #2c3e50; margin-bottom: 20px;">Send a Message</h2>
            <form method="POST">
                <div class="form-group">
                    <input type="text" name="fname" class="form-control" placeholder="Your Full Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Your Email Address" required>
                </div>
                <div class="form-group">
                    <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                </div>
                <div class="form-group">
                    <textarea name="message" class="form-control" placeholder="How can we help you?" style="height: 150px; resize: none;" required></textarea>
                </div>
                <button type="submit" name="send_enquiry" class="btn-submit">Send Message</button>
            </form>
        </div>
    </div>

    <!-- Map Section -->
    <div style="margin-top: 50px; margin-bottom: 50px;">
        <h2 style="text-align: center; color: #2c3e50; margin-bottom: 30px;">Find Us on Map</h2>
        <div style="border-radius: 15px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.1); height: 450px;">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3721.441567679624!2d72.77342731488156!3d21.13482498594103!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be05276ea0d3147%3A0x6d7a70370389956e!2sVIP%20Road%2C%20Surat%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1645432123456!5m2!1sen!2sin" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</div>