<?php
// Include database connection
include 'db_connection.php';

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    gender VARCHAR(50),
    address TEXT,
    phone VARCHAR(15),
    dob DATE,
    profile_pic VARCHAR(255),
    qr_code VARCHAR(255),
    created_at DATETIME,
    type VARCHAR(50) NOT NULL,
    age_group VARCHAR(50),
    sector VARCHAR(100),
    agency VARCHAR(100),
    senior VARCHAR(10),
    abled VARCHAR(10),
    nationality VARCHAR(100),
    region VARCHAR(100),
    office VARCHAR(100),
    position VARCHAR(100),
    parent VARCHAR(10),
    civil_status VARCHAR(50)
)";

if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully<br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br>";
}

// You can add more tables here as needed

echo "Database setup completed!";
?>
