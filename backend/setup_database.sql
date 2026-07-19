-- Database Setup Script for Royal Leather QR Menu
-- Run this script as MySQL root user

-- Create the database
CREATE DATABASE IF NOT EXISTS onchainv_royal_leather_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create the user (if it doesn't exist)
CREATE USER IF NOT EXISTS 'onchainv_royal'@'localhost' IDENTIFIED BY 'royal6263leather';

-- Grant all privileges on the database to the user
GRANT ALL PRIVILEGES ON onchainv_royal_leather_db.* TO 'onchainv_royal'@'localhost';

-- Apply the changes
FLUSH PRIVILEGES;

-- Verify the setup
SHOW DATABASES LIKE 'onchainv_royal_leather_db';
SELECT User, Host FROM mysql.user WHERE User = 'onchainv_royal';
