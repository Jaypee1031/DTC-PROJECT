<?php
session_start();
include 'db_connection.php';

// Ensure the user is logged in and has the 'staff' role
if (!isset($_SESSION['username']) || $_SESSION['type'] !== 'staff') {
    header("Location: login.php"); // Redirect to login if not a staff member
    exit();
}

// Fetch training requests from the database
$sql = "SELECT id, Ideas, title, description, time, status, username FROM training_content ORDER BY time DESC";
$result = mysqli_query($conn, $sql);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Dashboard</title><link rel="icon" type="image/jpg" href="assets/logo1full.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #007bff;
            padding: 15px 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            color: white;
        }
        .container-2 {
            display: flex;
            align-items: center;
            gap: 15px;
        }
       .logo-container {
    width: 50px;  /* Adjust circle size */
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
}

.logo-container img {
    width: 120%;  /* Increase size to prevent blank spaces */
    height: 120%;
    object-fit: cover;
    transform: translateY(3px); /* Adjust positioning */
}


        .title {
            font-size: 22px;
            font-weight: bold;
            white-space: nowrap;
        }
        .navigation {
            display: flex;
        }
        .navigation .tab {
            text-decoration: none;
            color: white;
            font-size: 18px;
            padding: 8px 15px;
            border-radius: 6px;
            transition: background 0.3s;
        }
        .navigation .tab:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .container {
            max-width: 90%;
            margin: 20px auto;
            padding: 40px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 24px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        input, button, textarea {
            font-size: 16px;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        table th, table td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 16px;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="top-bar">
            <div class="container-2">
                
                    <img src="assets/dict.png" alt="Logo" style="height: 30px; width: 30px; display: inline-block; vertical-align: middle; margin-right: 8px;">
                
                <div class="title">DTC TRAINING</div>
            </div>
            <div class="navigation">
                <a href="login.php" class="tab">Logout</a>
            </div>
        </div>
        <div class="container">
            <h2>Request a Training</h2>
            <form action="submit_training.php" method="POST">
                <input type="text" name="Ideas" placeholder="Training Idea" required>
                <input type="text" name="title" placeholder="Training Title" required>
                <textarea name="description" placeholder="Training Description" required></textarea>
                <input type="datetime-local" name="training_datetime" required>
                <button type="submit">Request Training</button>
            </form>
            <h2>My Training Requests</h2>
            <table>
                <thead>
                    <tr>
                        
                        <th>ID</th>
                        <th>Training Ideas</th>
                        <th>Training Title</th>
                        <th>Training Description</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Requested By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0) { ?>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['Ideas']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['time']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr><td colspan="6" class="text-center">No training requests found</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php mysqli_close($conn); ?>
</body>
</html>
