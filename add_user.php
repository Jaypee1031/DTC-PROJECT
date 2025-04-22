<?php
session_start();
include 'db_connection.php';

// Ensure the user is logged in and has the 'admin' role
if (!isset($_SESSION['username']) || $_SESSION['type'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not an admin
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Retrieve and sanitize form data
    $username = htmlspecialchars(trim($_POST['usernameController']));
    $email = htmlspecialchars(trim($_POST['emailController']));
    $password = trim($_POST['passwordController']);
    $confirmPassword = trim($_POST['confirmPasswordController']);
    $gender = htmlspecialchars(trim($_POST['selectedGender']));
    $address = htmlspecialchars(trim($_POST['addressController']));
    $phone = substr(htmlspecialchars(trim($_POST['phoneController'])), 0, 15); // Limit to 15 chars
    $dob = htmlspecialchars(trim($_POST['dobController']));
    $age_group = htmlspecialchars(trim($_POST['selectedAgeGroup']));
    $sector = htmlspecialchars(trim($_POST['selectedSector']));
    $agency = htmlspecialchars(trim($_POST['Agency']));
    $senior = htmlspecialchars(trim($_POST['Senior']));
    $abled = htmlspecialchars(trim($_POST['Abled']));
    $nationality = htmlspecialchars(trim($_POST['Nationality']));
    $region = htmlspecialchars(trim($_POST['Region']));
    $office = htmlspecialchars(trim($_POST['Office']));
    $position = htmlspecialchars(trim($_POST['Position']));
    $parent = htmlspecialchars(trim($_POST['Parent']));
    $civil_status = htmlspecialchars(trim($_POST['Civil']));
    $type = htmlspecialchars(trim($_POST['userType'])); // User type (user, staff, admin)
    $created_at = date('Y-m-d H:i:s');

    // Validate password match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, gender, address, phone, dob, age_group, sector, agency, senior, abled, nationality, region, office, position, parent, civil_status, type, created_at) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssssssssssss", $username, $email, $hashedPassword, $gender, $address, $phone, $dob, $age_group, $sector, $agency, $senior, $abled, $nationality, $region, $office, $position, $parent, $civil_status, $type, $created_at);

        if ($stmt->execute()) {
            echo "<script>alert('User added successfully.'); window.location.href='admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error adding user.');</script>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DTC</title>
<link rel="icon" type="image/jpg" href="assets/logo1full.jpg">
    <link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
    <link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.3/animate.min.css'>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
   <style>
        /* Custom CSS for table readability */
        .table-responsive {
            overflow-x: auto;
        }
        .table th, .table td {
            word-wrap: break-word; /* Handle long text */
            white-space: normal; /* Allow text wrapping */
        }
        .table th {
            background-color: #f8f9fa; /* Light background for headers */
        }
        .table td {
            vertical-align: middle; /* Center align content */
        }
        .btn-action {
            margin: 0px; /* Add spacing between buttons */
        }
        /* Add padding to the top of the container-fluid to account for the fixed navbar */
        .container-fluid {
            padding-top: 25px; /* Adjust this value as needed */
        }

        /* Make modal full-screen */
        .modal-fullscreen {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            max-width: 100%;
        }

        /* Make the image fit any screen */
        .full-screen-img {
            width: 100vw;  /* 100% of viewport width */
            height: 100vh; /* 100% of viewport height */
            object-fit: contain; /* Ensures full visibility without cropping */
        }

        /* Hide sidebar by default on small screens */
        @media (max-width: 768px) {
            #sidebar {
                display: none;
            }
            #sidebar.active {
                display: block;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="">
                <img src="assets/dtccaga.jpg" alt="Logo" style="height: 30px; width: 30px; display: inline-block; vertical-align: middle; margin-right: 8px;">
                DTC TRAINING
            </a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a title="View Website" href="admin_dashboard.php"><span class="glyphicon glyphicon-globe"></span></a></li>
                <li><a href="login.php.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="col-md-3">
        <div id="sidebar">
            <div class="container-fluid tmargin">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                        <button id="searchButton" class="btn btn-default">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                        <button id="clearButton" class="btn btn-default">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </span>
                </div>
            </div>
            <ul class="nav navbar-nav side-bar">
    <li class="side-bar">
        <a href="admin_dashboard.php">
            <span class="glyphicon glyphicon-list"></span> Event List
        </a>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-file"></span> Event Record <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a href="approve.php">
                    <span class="glyphicon glyphicon-ok"></span> Approved
                </a>
            </li>
            <li>
                <a href="reject.php">
                    <span class="glyphicon glyphicon-remove"></span> Rejected
                </a>
            </li>
        </ul>
    </li>
    <li class="side-bar">
        <a href="user_data.php">
            <span class="glyphicon glyphicon-user"></span> User Data
        </a>
    </li>
    <li class="side-bar">
        <a href="attendance.php">
            <span class="glyphicon glyphicon-list-alt"></span> Student Attendance Record
        </a>
    </li>
    <li class="side-bar">
        <a href="event_attendance.php">
            <span class="glyphicon glyphicon-check"></span> Event Attendance Record
        </a>
    </li>
    <li class="side-bar">
        <a href="qr_in.php">
            <span class="glyphicon glyphicon-qrcode"></span> Qr In
        </a>
    </li>
    </li>
     <li class="side-bar">
        <a href="foot_track.php">
            <span class="glyphicon glyphicon-qrcode"></span> Qr in Foot Traffic
        </a>
    </li>
    <li class="side-bar">
        <a href="add_user.php">
            <span class="glyphicon glyphicon-plus"></span> Add User
        </a>
    </li>
</ul>
        </div>
    </div>

    <div class="container-fluid">
    <div class="col-md-9">
        <h1 class="page-header">DTC TRAINING - Add User</h1>
        <div class="table-responsive">
            <table class="table table-hover">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Username/Full Name</label>
                    <input type="text" name="usernameController" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="emailController" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="passwordController" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirmPasswordController" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="selectedGender" class="form-control" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="addressController" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phoneController" class="form-control" maxlength="15" required>
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dobController" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Age Group</label>
                    <input type="text" name="selectedAgeGroup" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Sector</label>
                    <input type="text" name="selectedSector" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Agency</label>
                    <input type="text" name="Agency" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Senior</label>
                    <input type="text" name="Senior" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Abled</label>
                    <input type="text" name="Abled" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Nationality</label>
                    <input type="text" name="Nationality" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Region</label>
                    <input type="text" name="Region" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Office</label>
                    <input type="text" name="Office" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Position</label>
                    <input type="text" name="Position" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Parent</label>
                    <input type="text" name="Parent" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Civil Status</label>
                    <input type="text" name="Civil" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>User Type</label>
                    <select name="userType" class="form-control" required>
                        <option value="user">User</option>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" name="register" class="btn btn-primary btn-block">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery (Required for Bootstrap dropdowns) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS (Ensure this is included AFTER jQuery) -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>