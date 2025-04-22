<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db_connection.php';

// Ensure the user is logged in and has the 'staff' role
if (!isset($_SESSION['username']) || $_SESSION['type'] !== 'staff') {
    header("Location: login.php"); // Redirect to login if not a staff member
    exit();
}

// Get form data
$Ideas = $_POST['Ideas'];
$title = $_POST['title'];
$description = $_POST['description'];
$training_datetime = $_POST['training_datetime'];
$username = $_SESSION['username']; // Get the username from the session

// Insert data into the database
$sql = "INSERT INTO training_content (Ideas, title, description, time, status, username) 
        VALUES (?, ?, ?, ?, 'pending', ?)";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("sssss", $Ideas, $title, $description, $training_datetime, $username);
    if ($stmt->execute()) {
        // Redirect back to the staff dashboard with a success message
        header("Location: staff_dashboard.php?success=1");
        exit();
    } else {
        // Log the error
        error_log("Database error: " . $stmt->error);
        header("Location: staff_dashboard.php?error=1");
        exit();
    }
} else {
    // Log the SQL preparation error
    error_log("SQL preparation error: " . $conn->error);
    header("Location: staff_dashboard.php?error=1");
    exit();
}
?>