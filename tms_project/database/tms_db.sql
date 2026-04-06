CREATE DATABASE IF NOT EXISTS `tms_db`;
USE `tms_db`;

CREATE TABLE `admin` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
);

INSERT INTO `admin` (`username`, `password`) VALUES ('admin', 'admin123');

CREATE TABLE `users` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(15) NOT NULL
);

CREATE TABLE `packages` (
  `p_id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `p_name` varchar(200) NOT NULL,
  `p_type` varchar(100) NOT NULL,
  `p_location` varchar(200) NOT NULL,
  `p_price` decimal(10,2) NOT NULL,
  `p_details` text NOT NULL,
  `p_image` varchar(255) NOT NULL
);

CREATE TABLE `bookings` (
  `b_id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `p_id` int(11),
  `user_id` int(11),
  `from_date` date,
  `status` varchar(20) DEFAULT 'Pending',
  `payment_status` varchar(20) DEFAULT 'Unpaid',
  FOREIGN KEY (p_id) REFERENCES packages(p_id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE `payments` (
  `pay_id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `booking_id` int(11),
  `transaction_id` varchar(100),
  `amount` decimal(10,2),
  `method` varchar(50)
);
CREATE TABLE `enquiry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `posting_date` timestamp NULL DEFAULT current_timestamp(),
  `status` int(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
