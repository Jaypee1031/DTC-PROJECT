<?php
// get_user_info.php
include 'db_connection.php'; // Include your database connection

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $query = "SELECT * FROM users WHERE id = $userId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        echo "<div class='user-info'>";
        echo "<p><strong>Username:</strong> " . htmlspecialchars($user['username']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>";
        echo "<p><strong>Gender:</strong> " . htmlspecialchars($user['gender']) . "</p>";
        echo "<p><strong>Address:</strong> " . htmlspecialchars($user['address']) . "</p>";
        echo "<p><strong>Phone:</strong> " . htmlspecialchars($user['phone']) . "</p>";
        echo "<p><strong>DOB:</strong> " . htmlspecialchars($user['dob']) . "</p>";
        echo "<p><strong>QR Code:</strong> " . htmlspecialchars($user['qr_code']) . "</p>";
        echo "<p><strong>Type:</strong> " . htmlspecialchars($user['type']) . "</p>";
        echo "<p><strong>Age Group:</strong> " . htmlspecialchars($user['age_group']) . "</p>";
        echo "<p><strong>Sector:</strong> " . htmlspecialchars($user['sector']) . "</p>";
        echo "<p><strong>Agency:</strong> " . htmlspecialchars($user['agency']) . "</p>";
        echo "<p><strong>Senior:</strong> " . htmlspecialchars($user['senior']) . "</p>";
        echo "<p><strong>Abled:</strong> " . htmlspecialchars($user['abled']) . "</p>";
        echo "<p><strong>Nationality:</strong> " . htmlspecialchars($user['nationality']) . "</p>";
        echo "<p><strong>Region:</strong> " . htmlspecialchars($user['region']) . "</p>";
        echo "<p><strong>Office:</strong> " . htmlspecialchars($user['office']) . "</p>";
        echo "<p><strong>Position:</strong> " . htmlspecialchars($user['position']) . "</p>";
        echo "<p><strong>Parent:</strong> " . htmlspecialchars($user['parent']) . "</p>";
        echo "<p><strong>Civil Status:</strong> " . htmlspecialchars($user['civil_status']) . "</p>";
        echo "</div>";
    } else {
        echo "<p>User not found.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>