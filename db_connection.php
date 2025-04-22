<?php
$host = 'localhost';
$db = 'salunat_dtc';
$user = 'root';
$pass = '';

// Create connection without database first
$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $db";
if ($conn->query($sql) === TRUE) {
    // Database created successfully or already exists
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($db);

// Create users table if it doesn't exist
$createTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    address TEXT,
    phone VARCHAR(15),
    dob DATE,
    profile_pic VARCHAR(255),
    created_at DATETIME NOT NULL,
    type ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    age_group ENUM('18-25', '26-35', '36-45', '46-55', '56-65', '66+') NOT NULL,
    sector ENUM('Public', 'Private', 'Non-profit') NOT NULL,
    agency VARCHAR(255),
    senior ENUM('Yes', 'No') NOT NULL,
    abled ENUM('Yes', 'No') NOT NULL,
    nationality VARCHAR(100) NOT NULL,
    region VARCHAR(100) NOT NULL,
    office VARCHAR(255),
    position VARCHAR(255),
    parent ENUM('Yes', 'No') NOT NULL,
    civil_status VARCHAR(50),
    qr_code VARCHAR(255)
)";

if ($conn->query($createTable) === TRUE) {
    // Table created successfully or already exists
} else {
    die("Error creating table: " . $conn->error);
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully"; // Uncomment to test connection
?>
