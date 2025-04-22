<?php
session_start();
include 'db_connection.php';

if (!isset($_GET['id'])) {
    die("Invalid user.");
}

$user_id = intval($_GET['id']);

$query = "SELECT username, email, gender, address, phone, dob, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="icon" type="image/jpg" href="assets/logo1full.jpg">
    <title><?php echo htmlspecialchars($user['username']); ?> - Profile</title>
</head>
<body>
    <h2><?php echo htmlspecialchars($user['username']); ?>'s Profile</h2>
    <img src="<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture" width="150">
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
    <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?></p>
</body>
</html>
