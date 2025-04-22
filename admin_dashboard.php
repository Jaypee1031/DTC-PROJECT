<?php
session_start();
include 'db_connection.php';

// Ensure the user is logged in and has the 'admin' role
if (!isset($_SESSION['username']) || $_SESSION['type'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not an admin
    exit();
} // Added the missing closing bracket

// Fetch training data from the database with status = 'pending'
$sql = "SELECT id, Ideas, title, description, time, status, username FROM training_content WHERE status = 'pending'";
$result = $conn->query($sql); // Corrected variable name from $query to $sql
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DTC</title><link rel="icon" type="image/jpg" href="assets/logo1full.jpg">
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
                <li><a href="login.php">Logout</a></li>
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
            <span class="glyphicon glyphicon-qrcode"></span> Qr In Event Attendance
        </a>
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
            <h1 class="page-header">DTC TRAINING - Requested Trainings</h1>
            <div class="table-responsive">
                <table id="pendingTable" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Time</th>
                            <th>Ideas</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Assuming you have a 'date' field in your database
        $date = $row['time']; // Replace with your actual date field
        $time = $row['time'];

        // Format date and time
        $formattedDateTime = date('Y-m-d', strtotime($time)) . ' ' . date('h:i A', strtotime($time));

        echo "<tr>
                <td>{$row['title']}</td>
                <td>{$row['description']}</td>
                <td>{$formattedDateTime}</td>
                <td>{$row['Ideas']}</td>
                <td>{$row['status']}</td>
                <td>{$row['username']}</td>
                <td class='text-center'>
                    <button class='btn btn-success update-status' data-id='{$row['id']}' data-status='approved'>Approve</button>
                    <button class='btn btn-danger update-status' data-id='{$row['id']}' data-status='rejected'>Reject</button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>No pending training requests found.</td></tr>";
}
?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery (Required for Bootstrap dropdowns) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS (Ensure this is included AFTER jQuery) -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function () {
    // Initialize DataTables with sorting and buttons
    $('#pendingTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        ordering: true // Enable sorting
    });

    // Search functionality
    $("#searchButton").click(function () {
        var searchTerm = $("#searchInput").val().toLowerCase();
        $("#pendingTable tbody tr").each(function () {
            var row = $(this);
            var rowText = row.text().toLowerCase();
            if (rowText.indexOf(searchTerm) !== -1) {
                row.show();
            } else {
                row.hide();
            }
        });
    });

    // Clear search functionality
    $("#clearButton").click(function () {
        $("#searchInput").val("");
        $("#pendingTable tbody tr").show();
    });

    // Trigger search on pressing Enter key
    $("#searchInput").keypress(function (event) {
        if (event.keyCode === 13) {
            $("#searchButton").click();
        }
    });

    // Update status functionality
    $(".update-status").click(function () {
        var id = $(this).data("id");
        var status = $(this).data("status");
        var redirectPage = (status === "approved") ? "admin_dashboard.php" : "admin_dashboard.php";

        $.ajax({
            url: "update_status.php",
            type: "POST",
            data: { id: id, status: status },
            success: function (response) {
                if (response === "success") {
                    alert("Status updated successfully!");
                    window.location.href = redirectPage; // Redirect to respective page
                } else {
                    alert("Failed to update status.");
                }
            }
        });
    });
});
</script>

</body>
</html>