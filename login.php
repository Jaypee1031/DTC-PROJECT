<?php
session_start();
include 'db_connection.php';
require 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Registration Logic
if (isset($_POST['register'])) {
    // Retrieve and sanitize form data
    $username = htmlspecialchars(trim($_POST['usernameController']));
    $email = htmlspecialchars(trim($_POST['emailController']));
    $password = trim($_POST['passwordController']);
    $confirmPassword = trim($_POST['confirmPasswordController']);
    
    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit();
    }
    
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already registered. Please use a different email.'); window.history.back();</script>";
        $stmt->close();
        exit();
    }
    $stmt->close();

    // Handle Profile Picture Upload
    $profilePic = null;
    if (!empty($_POST['captured_image'])) {
        $imageData = base64_decode($_POST['captured_image']);
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        $fileName = "uploads/profile_" . time() . ".png";
        file_put_contents($fileName, $imageData);
        $profilePic = $fileName;
    }

    // Get other form data
    $gender = htmlspecialchars(trim($_POST['selectedGender']));
    $address = htmlspecialchars(trim($_POST['addressController']));
    $phone = substr(htmlspecialchars(trim($_POST['phoneController'])), 0, 15);
    $dob = htmlspecialchars(trim($_POST['dobController']));
    $age_group = htmlspecialchars(trim($_POST['selectedAgeGroup']));
    $sector = htmlspecialchars(trim($_POST['selectedSector']));
    $agency = htmlspecialchars(trim($_POST['Agency']));
    $senior = htmlspecialchars(trim($_POST['Senior']));
    $abled = htmlspecialchars(trim($_POST['Abled']));
    $nationality = htmlspecialchars(trim($_POST['Nationality']));
    $region = htmlspecialchars(trim($_POST['Region']));
    $office = htmlspecialchars(trim($_POST['Office']));
    $position = htmlspecialchars(trim($_POST['Position']));
    $parent = htmlspecialchars(trim($_POST['Parent']));
    $civil_status = isset($_POST['civil_status']) ? htmlspecialchars(trim($_POST['civil_status'])) : '';
    $type = 'user';
    $created_at = date('Y-m-d H:i:s');

    // Hash Password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert User Data
    $stmt = $conn->prepare("INSERT INTO users 
        (username, email, password, gender, address, phone, dob, profile_pic, created_at, type, age_group, sector, agency, senior, abled, nationality, region, office, position, parent, civil_status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        echo "<script>alert('Database error: " . $conn->error . "'); window.history.back();</script>";
        exit();
    }

    $stmt->bind_param("sssssssssssssssssssss", 
        $username, $email, $hashedPassword, $gender, $address, $phone, $dob, 
        $profilePic ?? '', $created_at, $type, $age_group, $sector, $agency, $senior, 
        $abled, $nationality, $region, $office, $position, $parent, $civil_status
    );

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        $stmt->close();

        // Generate QR Code Content
        $qrContent = json_encode([
            'id' => $user_id,
            'username' => $username,
            'email' => $email,
            'gender' => $gender,
            'phone' => $phone,
            'address' => $address,
            'dob' => $dob,
            'type' => $type,
            'age_group' => $age_group,
            'sector' => $sector,
            'agency' => $agency,
            'senior' => $senior,
            'abled' => $abled,
            'nationality' => $nationality,
            'region' => $region,
            'office' => $office,
            'position' => $position,
            'parent' => $parent,
            'civil_status' => $civil_status
        ]);

        // Ensure uploads directory exists
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }

        // Generate and save QR Code
        $qrFilename = "qr_" . $user_id . ".png";
        $qrPath = "uploads/" . $qrFilename;

        // Create QR code with Endroid QR Code
        $qrCode = (new QrCode($qrContent))
            ->setEncoding('UTF-8')
            ->setErrorCorrectionLevel('H')
            ->setSize(300)
            ->setMargin(10)
            ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])
            ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255]);

        // Write the QR code to file
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        file_put_contents($qrPath, $result->getString());

        // Update QR Code in DB
        $updateQuery = "UPDATE users SET qr_code = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $qrFilename, $user_id);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Registration successful! Please log in.'); window.location.href='login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Registration failed: " . $stmt->error . "'); window.history.back();</script>";
        exit();
    }
}

// Rest of your code remains the same
