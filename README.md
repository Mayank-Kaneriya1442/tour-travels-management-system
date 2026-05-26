<div align="center">

# ✈️ Tour and Travels Management System

### A web-based travel booking platform for exploring packages and managing reservations.

[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-00758F?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/HTML)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)

</div>

---

## 📌 Overview

The **Tour and Travels Management System** is a PHP + MySQL web application that enables users to browse travel packages, make bookings, and manage their trips. An admin panel provides full control over tours, users, and bookings — making it suitable for small travel agencies or personal travel platforms.

---

## ✨ Features

### 👤 User
- Register and log in to a personal account
- Browse available tour packages with details and pricing
- Book tours and manage upcoming travel plans
- View and cancel existing bookings

### 🛠️ Admin
- Add, edit, and remove tour packages
- View and manage all user bookings
- Manage registered users
- Dashboard overview of tours and booking activity

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | HTML5, CSS3, Bootstrap 5 |
| Backend | PHP |
| Database | MySQL |
| Server | Apache (XAMPP / WAMP recommended) |

---

## 📁 Folder Structure

```
tour-travels-management/
├── admin/
│   ├── dashboard.php
│   ├── manage-tours.php
│   ├── manage-bookings.php
│   └── manage-users.php
├── includes/
│   ├── db.php              # Database connection
│   ├── header.php
│   └── footer.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── index.php               # Home / Tour listings
├── tour-details.php
├── booking.php
├── login.php
├── register.php
└── README.md
```

---

## 🚀 Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) or [WAMP](https://www.wampserver.com/) installed
- PHP 7.4+
- MySQL 5.7+

### 1. Clone the Repository

```bash
git clone https://github.com/Mayank-Kaneriya1442/Tour-Travels-Management-System.git
```

### 2. Move to Server Root

Copy the project folder into your server's root directory:

- **XAMPP**: `C:/xampp/htdocs/`
- **WAMP**: `C:/wamp64/www/`

### 3. Import the Database

1. Start Apache and MySQL in XAMPP/WAMP
2. Open **phpMyAdmin** at `http://localhost/phpmyadmin`
3. Create a new database: `tour_travels_db`
4. Import the SQL file: `database/tour_travels_db.sql`

### 4. Configure Database Connection

Edit `includes/db.php`:

```php
<?php
$host     = "localhost";
$username = "root";
$password = "";           // Your MySQL password
$database = "tour_travels_db";

$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
```

### 5. Run the Application

Open your browser and go to:

```
http://localhost/tour-travels-management/
```

---

## 🔑 Default Admin Credentials

> ⚠️ Change these credentials after first login.

| Field | Value |
|-------|-------|
| Email | `admin@tours.com` |
| Password | `Admin@123` |

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
