-- 1. Create the database
CREATE DATABASE IF NOT EXISTS contact_form_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- 2. Select the database
USE contact_form_db;

-- 3. Create the contacts table
CREATE TABLE IF NOT EXISTS contacts (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
