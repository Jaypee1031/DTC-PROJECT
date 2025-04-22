<?php
// Include database connection
include 'db_connection.php';

// Fetch approved training content from the database
$query = "SELECT * FROM training_content WHERE status = 'approved'";
$result = $conn->query($query);
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
            <h1 class="page-header">DTC TRAINING - Approved Trainings</h1>
            <div class="table-responsive">
                <table id="approvedTable" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Ideas</th>
                            <th>Title</th>
                            <th class="text-center">Description</th>
                            <th>Time</th>
                            <th>Requested By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['Ideas']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td class='text-center'>" . htmlspecialchars($row['description']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>No approved trainings found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Remove duplicate jQuery import -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<!-- Add required DataTables dependencies -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<!-- Add PDFMake and JSZip for PDF support -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTables with proper configuration
    $('#approvedTable').DataTable({
        dom: 'Blfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        order: [[3, "desc"]],
        responsive: true,
        initComplete: function() {
            // Integrate Datatables search with custom search input
            var api = this.api();
            $('#searchInput').on('keyup', function() {
                api.search(this.value).draw();
            });
            
            $('#clearButton').on('click', function() {
                $('#searchInput').val('');
                api.search('').draw();
            });
        }
    });
});
</script>
</body>
</html>