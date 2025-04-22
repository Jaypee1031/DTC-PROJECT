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

// Ensure session contains user ID
$username = '';
$email = '';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Fetch user details from database
    $stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($username, $email);
    $stmt->fetch();
    $stmt->close();
}

// Handle attendance recording
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['scannedData']) && isset($_POST['purpose'])) {
    $attendanceTime = date('Y-m-d H:i:s');
    $status = 'Present';
    $purpose = $_POST['purpose'];

    // Check if the user has already scanned within the last 1 minute
    $stmt = $conn->prepare("SELECT attendance_time FROM attendance WHERE username = ? AND purpose = ? AND attendance_time >= NOW() - INTERVAL 1 MINUTE");
    $stmt->bind_param("ss", $username, $purpose);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'You can only scan once per minute']);
        exit();
    }
    $stmt->close();

    if (!empty($username) && !empty($email) && !empty($purpose)) {
        $stmt = $conn->prepare("INSERT INTO attendance (username, email, attendance_time, status, purpose) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $attendanceTime, $status, $purpose);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Attendance recorded successfully']);
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to record attendance']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    }
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
        <form id="attendanceForm">
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" readonly>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
            <input type="text" name="purpose" placeholder="Enter Purpose" required>
            <div id="qr-reader"></div>
            <input type="hidden" id="scannedData" name="scannedData">
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

            // Set the scanned data in the form
            document.getElementById("scannedData").value = decodedText;

            // Prepare form data for AJAX submission
            const formData = new FormData(document.getElementById("attendanceForm"));

            // Submit the form data via AJAX
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);

                    // If the QR code contains the specific URL, redirect after a delay
                    if (decodedText.includes("https://salun-at.site/Dtc/DTC/Dtc_foot_traffic.php")) {
                        setTimeout(() => {
                            window.location.href = decodedText;
                        }, 1000); // Redirect after 1 second
                    } else {
                        // Otherwise, redirect to user_dashboard.php
                        window.location.href = 'user_dashboard.php';
                    }
                } else {
                    alert(data.message);
                    window.location.href = 'user_dashboard.php';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while recording attendance.');
                window.location.href = 'user_dashboard.php';
            });

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