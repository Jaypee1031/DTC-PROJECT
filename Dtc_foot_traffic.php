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

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch user details
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$conn->close(); // Close the database connection
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard</title>
    <link rel="icon" type="image/jpg" href="assets/logo1full.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .bg-light {
            color: white;
            padding: 8px;
            font-size: 18px; /* Reduced font size for mobile */
            background: linear-gradient(135deg, #0056b3, #007bff);
            font-weight: bold;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: 60px; /* Reduced height for mobile */
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .container {
            margin-top: 10px; /* Reduced margin for mobile */
            padding: 10px; /* Reduced padding for mobile */
        }
        .profile-card {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 15px; /* Added padding for mobile */
        }
        .profile-pic {
            width: 100px; /* Reduced size for mobile */
            height: 100px; /* Reduced size for mobile */
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px; /* Reduced margin for mobile */
        }
        .qr-code {
            width: 100px; /* Reduced size for mobile */
            height: 100px; /* Reduced size for mobile */
            margin-top: 10px; /* Reduced margin for mobile */
            cursor: pointer; /* Add pointer cursor to indicate clickability */
        }
        .detail-item {
            margin-bottom: 10px; /* Reduced margin for mobile */
            padding: 8px; /* Reduced padding for mobile */
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 14px; /* Reduced font size for mobile */
        }
        .logo-img {
            width: 40px; /* Reduced size for mobile */
            height: auto;
        }
        .navbar-brand span {
            font-size: 16px; /* Reduced font size for mobile */
        }
        /* Modal styling */
        .modal-content {
            border-radius: 15px;
        }
        .modal-body {
            text-align: center;
        }
        .modal-qr-code {
            width: 100%;
            max-width: 300px;
            height: auto;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <header class="bg-light">
        <div class="container d-flex align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <div class="logo-container">
                    <img src="assets/dict.png" alt="Logo" class="logo-img">
                </div>
                <span class="ms-2">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</span>
            </a>
            <div class="logout-btn ms-auto">
                <a href="login.php" class="btn btn-danger btn-sm">Logout</a> <!-- Smaller button for mobile -->
            </div>
        </div>
    </header>

    <div class="container">
        <div class="card profile-card p-3"> <!-- Reduced padding for mobile -->
            <div class="row">
                <div class="col-md-4 text-center">
                    <?php if (!empty($user['profile_pic'])): ?>
                        <img src="<?php echo htmlspecialchars($user['profile_pic']); ?>" class="profile-pic" alt="Profile Picture">
                    <?php endif; ?>
                    <?php if (!empty($user['qr_code'])): ?>
                        <!-- Make the QR code clickable to open a modal -->
                        <img src="uploads/<?php echo htmlspecialchars($user['qr_code']); ?>" class="qr-code mt-2" alt="QR Code" data-bs-toggle="modal" data-bs-target="#qrModal">
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <h3 class="mb-3">Personal Information</h3> <!-- Reduced margin for mobile -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
                            </div>
                            <div class="detail-item">
                                <strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?>
                            </div>
                            <div class="detail-item">
                                <strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?>
                            </div>
                            <div class="detail-item">
                                <strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?>
                            </div>
                            <div class="detail-item">
                                <strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?>
                            </div>
                            <div class="detail-item">
                                <strong>Nationality:</strong> <?php echo htmlspecialchars($user['nationality']); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <strong>Agency:</strong> <?php echo htmlspecialchars($user['agency']); ?>
                            </div>
                            <div class="detail-item">
                                <strong>Position:</strong> <?php echo htmlspecialchars($user['position']); ?>
                            </div>
                            <div class="detail-item">
                                <strong>Civil Status:</strong> <?php echo htmlspecialchars($user['civil_status']); ?>
                            </div>
                            <div class="detail-item">
                                <strong>Region:</strong> <?php echo htmlspecialchars($user['region']); ?>
                            </div>
                            <div class="detail-item">
                                <strong>Sector:</strong> <?php echo htmlspecialchars($user['sector']); ?>
                            </div>
                            <div class="detail-item">
                                <strong>Senior Citizen:</strong> <?php echo htmlspecialchars($user['senior']); ?>
                            </div>
                            <div class="detail-item">
                                <strong>Persons with Disabilities:</strong> <?php echo htmlspecialchars($user['abled']); ?>
                            </div>
                            <div class="detail-item">
                                <strong>Office:</strong> <?php echo htmlspecialchars($user['office']); ?>
                            </div>
                            <div class="detail-item">
                                <strong>Parent:</strong> <?php echo htmlspecialchars($user['parent']); ?>
                            </div>
                            <div class="detail-item">
                                <strong>Age Group:</strong> <?php echo htmlspecialchars($user['age_group']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($user['qr_code'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($user['qr_code']); ?>" class="modal-qr-code" alt="QR Code">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>