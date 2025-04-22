<?php
session_start(); // Start session

// Database connection
$host = 'localhost';
$dbname = 'salunat_dtc';
$username = 'salunat_dtc';
$password = 'kpr%ZP!n$t-C';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle attendance recording
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['scannedData']) && isset($_POST['purpose'])) {
    $scannedData = json_decode($_POST['scannedData'], true); // Decode JSON data from QR code
    $purpose = $_POST['purpose']; // Purpose input by the user
    $attendanceTime = date('Y-m-d H:i:s');
    $status = 'Present';

    // Extract user details from the scanned data
    $userId = $scannedData['id'];
    $username = $scannedData['username'];
    $email = $scannedData['email'];

    // Check if the user has already scanned within the last 1 minute
    $stmt = $conn->prepare("SELECT attendance_time FROM attendance WHERE username = ? AND purpose = ? AND attendance_time >= NOW() - INTERVAL 1 MINUTE");
    $stmt->bind_param("ss", $username, $purpose);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<script>alert('You can only scan once per minute'); window.location.href='qr_attendance_dashboard.php';</script>";
        exit();
    }
    $stmt->close();

    // Insert attendance record
    $stmt = $conn->prepare("INSERT INTO attendance (username, email, attendance_time, status, purpose) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $attendanceTime, $status, $purpose);
    if ($stmt->execute()) {
        echo "<script>alert('Attendance recorded successfully'); window.location.href='qr_attendance_dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to record attendance');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Scanner</title>
    <link rel="icon" type="image/jpg" href="assets/logo1full.jpg">
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
        }
        .header {
            background: #007bff;
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .logo {
            height: 50px;
            width: auto;
            margin-right: 8px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background: blue;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="assets/dict.png" alt="Logo" class="logo">
        <h1>DTC Training Scan QR Attendance</h1>
    </div>

    <div class="container">
        <h1>QR Scanner</h1>
        <form id="attendanceForm" method="POST">
            <input type="text" id="username" name="username" placeholder="Username" readonly>
            <input type="email" id="email" name="email" placeholder="Email" readonly>
            <input type="text" name="purpose" id="purpose" placeholder="Enter Purpose" required>
            <div id="qr-reader"></div>
            <input type="hidden" id="scannedData" name="scannedData">
            <button type="submit" id="submitButton" style="display: none;">Submit Attendance</button>
        </form>
    </div>

    <script>
        let isScanned = false;
        let canScanAgain = true;

        function onScanSuccess(decodedText) {
            if (!canScanAgain) return;

            isScanned = true;
            canScanAgain = false;

            console.log("Scanned QR Code:", decodedText);

            try {
                // Parse the JSON data from the QR code
                const userData = JSON.parse(decodedText);

                // Validate the required fields
                if (!userData.username || !userData.email) {
                    throw new Error("Invalid QR code: Missing username or email.");
                }

                // Populate the form fields
                document.getElementById("username").value = userData.username;
                document.getElementById("email").value = userData.email;
                document.getElementById("scannedData").value = decodedText;

                // Focus on the purpose field
                const purposeField = document.getElementById("purpose");
                purposeField.focus();

                // Listen for input in the purpose field
                purposeField.addEventListener("input", () => {
                    if (purposeField.value.trim() !== "") {
                        // Auto-submit the form when the purpose is entered
                        document.getElementById("submitButton").click();
                    }
                });
            } catch (error) {
                console.error("Error processing QR code:", error);
                alert("Invalid QR code. Please scan a valid QR code.");
            }

            // Enable scanning again after 1 minute
            setTimeout(() => {
                canScanAgain = true;
                isScanned = false;
            }, 60000); // 1-minute interval
        }

        // Initialize QR Code Scanner
        const scanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 });
        scanner.render(onScanSuccess);
    </script>
</body>
</html>