<?php
// record_attendance.php

// Database connection
$host = 'localhost';
$dbname = 'salunat_dtc';
$username = 'salunat_dtc';
$password = 'kpr%ZP!n$t-C';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from GET parameters
$userName = $_GET['username'] ?? '';
$userEmail = $_GET['email'] ?? '';
$attendanceTime = $_GET['attendance_time'] ?? '';
$status = 'Present';  // Assuming status is always 'Present', modify as needed
$purpose = $_GET['purpose'] ?? '';  // Get purpose from the URL

// Validate required fields
if (empty($userName) || empty($userEmail) || empty($attendanceTime) || empty($purpose)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
    exit;
}

// Insert attendance record into the database
$query = "INSERT INTO attendance (username, email, attendance_time, status, purpose) 
          VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param("sssss", $userName, $userEmail, $attendanceTime, $status, $purpose);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Attendance marked successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to record attendance']);
}

// Close the connection
$stmt->close();
$conn->close();
?>
