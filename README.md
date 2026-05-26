<div align="center">

# ✈️ Tour and Travels Management System (TMS)

### A full-featured travel booking platform with coupon support, payment tracking, and enquiry management.

[![PHP](https://img.shields.io/badge/PHP_8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-00758F?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/HTML)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)

</div>

---

## 📌 Overview

The **Tour and Travels Management System** is a PHP + MySQL web application that allows visitors to browse tour packages, registered users to book and pay for tours, and admins to manage all backend operations. It includes coupon-based discounts, online payment simulation (GPay, Paytm, UPI), booking receipts, password reset via email token, and a gallery and enquiry system.

---

## ✨ Features

### 🌍 Visitor
- Browse all available tour packages (Domestic, International, Honeymoon, Religious, Adventure, Family)
- View package details, pricing, and descriptions
- Submit an enquiry via the Contact Us page
- Browse the photo gallery

### 👤 User
- Register and log in with a personal account
- Book tours with number of travelers
- Apply coupon codes for discounts (e.g. `TRAVEL10`, `WELCOME20`)
- Complete payment online (GPay, Paytm, UPI, Credit/Debit Card)
- View booking history and payment receipts
- Cancel bookings
- Update profile details and reset password

### 🛠️ Admin
- Secure admin login
- Create, edit, and delete tour packages (with image upload)
- Manage all bookings — confirm, cancel, update status
- Track and verify payments
- Manage coupon codes (create, activate/deactivate)
- View and respond to user enquiries
- Manage the photo gallery
- View and manage all registered users

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | HTML5, CSS3, Bootstrap |
| Backend | PHP 8.2 |
| Database | MySQL (MariaDB 10.4) |
| Server | Apache (XAMPP / WAMP) |

---

## 📁 Folder Structure

```
tms_project/
├── admin/
│   ├── admin_login.php
│   ├── admin_dashboard.php
│   ├── manage_packages.php        # Create/edit/delete packages
│   ├── create_package.php
│   ├── edit_package.php
│   ├── manage_bookings.php        # View & manage all bookings
│   ├── manage_payments.php        # Payment verification
│   ├── manage_coupons.php         # Coupon management
│   ├── manage_enquiries.php       # User enquiries
│   ├── manage_users.php           # Registered users
│   ├── manage_gallery.php
│   ├── view_feedback.php
│   └── logout.php
├── user/
│   ├── login.php
│   ├── register.php
│   ├── dashboard.php
│   ├── book_tour.php              # Booking form
│   ├── check_coupon.php           # AJAX coupon validation
│   ├── payment.php                # Payment page
│   ├── my_bookings.php
│   ├── receipt.php
│   ├── profile.php
│   └── logout.php
├── visitor/
│   ├── index.php                  # Homepage
│   ├── tour_packages.php          # All packages listing
│   ├── package_details.php
│   ├── about.php
│   ├── contact_us.php
│   └── gallery.php
├── config/
│   └── db.php                     # Database connection
├── includes/
│   ├── header.php
│   └── footer.php
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   └── admin_style.css
│   ├── js/script.js
│   └── images/                    # Package images
├── database/
│   └── tms_db.sql                 # Full database dump
└── 404.php
```

---

## 🚀 Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) or [WAMP](https://www.wampserver.com/)
- PHP 8.0+
- MySQL / MariaDB

### 1. Clone the Repository

```bash
git clone https://github.com/Mayank-Kaneriya1442/Tour-Travels-Management-System.git
```

### 2. Move to Server Root

Copy the `tms_project/` folder to:
- **XAMPP**: `C:/xampp/htdocs/tms_project/`
- **WAMP**: `C:/wamp64/www/tms_project/`

### 3. Import the Database

1. Start Apache and MySQL from XAMPP/WAMP
2. Open **phpMyAdmin** at `http://localhost/phpmyadmin`
3. Create a new database named `tms_db`
4. Import the file: `database/tms_db.sql`

### 4. Configure Database Connection

Edit `config/db.php`:

```php
<?php
$conn = mysqli_connect("localhost", "root", "", "tms_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
```

### 5. Run the Application

```
http://localhost/tms_project/visitor/index.php
```

---

## 🔑 Default Credentials

> ⚠️ Change these credentials after first login.

| Role | Username / Email | Password |
|------|-----------------|----------|
| Admin | `admin` | `admin123` |
| Test User | `mk1515@gmail.com` | _(set during registration)_ |

---

## 🎟️ Available Coupon Codes

| Code | Discount |
|------|----------|
| `TRAVEL10` | 10% off |
| `WELCOME20` | 20% off |
| `TRAVEL20` | 30% off |
| `NEWUSER25` | 25% off |
| `FAMILY15` | 15% off |
| `RELIGIOUS10` | 10% off |

---

## 🗃️ Database Schema

| Table | Description |
|-------|-------------|
| `packages` | Tour packages — name, type, location, price, image, details |
| `bookings` | User bookings — package, travelers, total price, status |
| `payments` | Payment records — transaction ID, amount, method |
| `coupons` | Discount codes with percentage and active status |
| `users` | Registered users with password reset token support |
| `enquiry` | Contact form submissions |
| `admin` | Admin login credentials |

---

## 📦 Tour Package Types

`Family` · `Honeymoon` · `Religious` · `Adventure` · `International`

Sample destinations: Bali, Manali, Kedarnath, London, Paris, Goa, Ooty, Jaipur, Spiti Valley, Meghalaya, Tirupati, Golden Temple

---

## 👨‍💻 Author

**Mayank Kaneriya**
- 🌐 [LinkedIn](https://www.linkedin.com/in/mayank-kaneriya-011729363/)
- 📧 mayankkaneriya15@gmail.com
- 💻 [GitHub](https://github.com/Mayank-Kaneriya1442)

---

<div align="center">

⭐ If you found this project helpful, please give it a star!

</div>
