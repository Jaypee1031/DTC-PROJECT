<?php
session_start(); // Start the session

include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the user ID from the session
$username = $_SESSION['username']; // Assuming the username is stored in the session

// Fetch user's QR code from the database
$query = "SELECT qr_code FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch approved training sessions
$sql = "SELECT * FROM training_content WHERE status = 'approved'";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_training'])) {
    $training_id = intval($_POST['training_id']);

    if ($training_id) {
        // Fetch the training details
        $trainingQuery = "SELECT * FROM training_content WHERE id = ?";
        $trainingStmt = $conn->prepare($trainingQuery);
        $trainingStmt->bind_param("i", $training_id);
        $trainingStmt->execute();
        $trainingResult = $trainingStmt->get_result();
        $training = $trainingResult->fetch_assoc();
        $trainingStmt->close();

        if ($training) {
            // Check if the user is already enrolled
            $checkStmt = $conn->prepare("SELECT id FROM user_trainings WHERE user_id = ? AND training_id = ?");
            $checkStmt->bind_param("ii", $user_id, $training_id);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                echo "<script>alert('You have already joined this training.');</script>";
            } else {
                // Insert training enrollment with all required fields
                $stmt = $conn->prepare("INSERT INTO user_trainings (user_id, training_id, created_at, Status, username, Ideas, title, description) VALUES (?, ?, NOW(), 'joined', ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("iissss", $user_id, $training_id, $username, $training['Ideas'], $training['title'], $training['description']);
                    if ($stmt->execute()) {
                        // Show success message and redirect back to the dashboard
                        echo "<script>
                                alert('Training joined successfully!');
                                window.location.href = 'user_dashboard.php'; // Redirect to the same page
                              </script>";
                    } else {
                        echo "<script>alert('Failed to join training.');</script>";
                    }
                    $stmt->close();
                }
            }
            $checkStmt->close();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard</title>
    <link rel="icon" type="image/jpg" href="assets/logo1full.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <style>
        .bg-light {
            color: white;
            padding: 8px;
            font-size: 22px;
            background: linear-gradient(135deg, #0056b3, #007bff);
            font-weight: bold;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .container {
            margin-top: 2px;
            flex: 1.0;
            padding: 2px;
        }
        .card-custom {
            padding-top: 2px;
            border-radius: 15px;
            background: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .logo-container {
            width: 50px;
            height: 50px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .logo-container img {
            width: 80%;
            height: auto;
        }
        .logout-btn {
            margin-left: auto;
        }
    </style>
</head>
<body>
    <header class="bg-light">
        <div class="container d-flex align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <div class="logo-container">
                    <img src="assets/dict.png" alt="Logo">
                </div>
                <span class="ms-2">USER DASHBOARD</span>
            </a>
            <div class="logout-btn ms-auto">
                <a href="login.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </header>
    <div class="container py-5">
        <div class="card card-custom p-4">
            <h3 class="text-center">Approved Training Sessions</h3>
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ideas</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Date & Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $count = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $count++ . "</td>";
                                echo "<td>" . htmlspecialchars($row['Ideas']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                                echo '<td>
                                        <form method="POST">
                                            <input type="hidden" name="training_id" value="' . $row['id'] . '">
                                            <button type="submit" name="join_training" class="btn btn-success btn-sm">Join</button>
                                        </form>
                                      </td>';
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-muted'>No approved training sessions available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>