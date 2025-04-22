<?php
include 'db_connection.php'; // Include your database connection

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $query = "SELECT id, username, email, type, profile_pic, qr_code, nationality, position, civil_status FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
}

if (!$user) {
    echo "User not found.";
    exit;
}

// Profile picture path
$imagePath = (!empty($user['profile_pic']) && file_exists($user['profile_pic'])) ? 
    $user['profile_pic'] : "assets/images/default.png";

// QR code path from database
$qrFilename = $user['qr_code'];
$qrPath = (!empty($qrFilename) && file_exists("uploads/" . $qrFilename)) ? 
    "uploads/" . $qrFilename : "assets/images/qr-placeholder.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print User ID</title>
    <link rel="icon" type="image/jpg" href="assets/logo1full.jpg">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <style>
       body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    padding: 20px;
}

.id-container {
    display: flex;
    justify-content: center;
    gap: 30px;
    flex-wrap: wrap;
}

.id-card {
    width: 320px;
    height: 480px;
    border-radius: 15px;
    text-align: center;
    padding: 20px;
    box-shadow: 4px 4px 15px rgba(0, 0, 0, 0.3);
    background-color: #ffcccc; /* Light Red Background */
    position: relative;
    overflow: hidden;
    border: 3px solid #d9534f; /* Red Border */
}

.id-header {
    font-size: 20px;
    font-weight: bold;
    text-transform: uppercase;
    color: #ffffff;
    background: #d9534f; /* Red Header */
    padding: 12px 0;
    border-radius: 10px 10px 0 0;
}

.logo-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin: 10px 0;
}

.logo-container .logo {
    width: 70px;
    height: auto;
}

.id-card img.profile-pic {
    width: 130px;
    height: 130px;
    border-radius: 15px;
    border: 3px solid #d9534f;
    margin-top: 10px;
}

.id-info {
    font-size: 14px;
    font-weight: 600;
    margin: 6px 0;
    color: #333;
}

.id-number {
    font-size: 14px;
    font-weight: bold;
    color: #555;
    margin-bottom: 5px;
}

.qr-code img {
    width: 280px;
    height: 280px;
    border: 2px solid #000;
    border-radius: 10px;
    margin-top: 20px;
}

.btn-print {
    margin-top: 20px;
    font-size: 16px;
    font-weight: bold;
    background: #d9534f; /* Red Button */
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
}

.btn-print:hover {
    background: #c9302c; /* Darker Red on Hover */
}

/* Hide Print Button on Print */
@media print {
    .btn-print {
        display: none;
    }
}
    </style>
</head>
<body>

<div class="container">
    <div class="id-container">
        <!-- Front Side of the ID -->
        <div class="id-card">
            <div class="id-header">DTC Training Program ID</div> <!-- Header at the top -->
            
            <!-- Profile Picture -->
            <img class="profile-pic" src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profile Picture">
            
            <div class="id-number">ID: <?php echo htmlspecialchars($user['id']); ?></div>
            <div class="id-info">Name: <?php echo htmlspecialchars($user['username']); ?></div>
            <div class="id-info">Email: <?php echo htmlspecialchars($user['email']); ?></div>
            <div class="id-info">Type: <?php echo ucfirst(htmlspecialchars($user['type'])); ?></div>
            <div class="id-info">Nationality: <?php echo htmlspecialchars($user['nationality']); ?></div>
            <div class="id-info">Position: <?php echo htmlspecialchars($user['position']); ?></div>
            <div class="id-info">Civil Status: <?php echo htmlspecialchars($user['civil_status']); ?></div>
            <div class="logo-container">
                <img class="logo" src="assets/logo1.jpg" alt="Logo 1">
                <img class="logo" src="assets/dtcroom.jpg" alt="Logo 2"> <!-- Fixed the incorrect extension -->
            </div>
        </div>

        <!-- Back Side of the ID (QR Code) -->
        <div class="id-card">
            <div class="id-header">Scan QR for Attendance</div>
            <div class="qr-code">
                <img src="<?php echo htmlspecialchars($qrPath); ?>" alt="QR Code">
            </div>
        </div>
    </div>

    <button class="btn btn-primary btn-print" onclick="window.print()">Print</button>
</div>

</body>
</html>