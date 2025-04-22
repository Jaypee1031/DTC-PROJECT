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
        <h1 class="page-header">DTC TRAINING - QR DTC Foot Traffic</h1>
        <div class="table-responsive">
            <table class="table table-hover">

   <div class="text-center">
    <!-- Image above the QR Scanner -->
    <img id="qrImage" src="assets/dtc_foot_traffic_qr.png" alt="QR Code Placeholder" 
         style="width: 500px; height: auto; margin-bottom: 20px; cursor: pointer;">

    <!-- Button to View Fullscreen Image -->
    <button class="btn btn-primary" id="viewFullScreen">View Fullscreen</button>
    <!-- Button to Print QR Code -->
    <button class="btn btn-success" id="printQR">Print QR Code</button>
    <!-- New Button to Go to QR Attendance Dashboard -->
    <button class="btn btn-info" id="goToDashboard">Go to Attendance Dashboard</button>
</div>
<!-- Fullscreen Modal -->
<div id="imageModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="color: white; font-size: 30px;">&times;</button>
      </div>
      <div class="modal-body d-flex align-items-center justify-content-center" style="display: flex; flex-wrap: wrap; align-items: center; text-align: left; padding: 20px;">
        <!-- QR Code on the Left -->
        <div style="flex: 1; text-align: center;">
          <img id="modalImage" src="assets/dtc_foot_traffic_qr.png" alt="QR Code" class="img-responsive full-screen-img" style="max-width: 80%; height: auto;">
        </div>
        
        <!-- Details on the Right -->
        <div style="flex: 1; padding-left: 20px; max-width: 500px;">
          <h2><strong>QR Code Details</strong></h2>
          <p style="font-size: 18px;">
            The QR code is designed to facilitate easy access to the login page and attendance recording system 
            for individuals entering the **DTC (Digital Training Center) room**. <br><br>
            - Scan the QR code to log in.<br>
            - It ensures accurate attendance tracking.<br>
            - Quick and secure access for all users.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>



<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
$(document).ready(function () {
    // Toggle sidebar visibility
    $("#toggleSidebar").click(function () {
        $("#sidebar").toggleClass("active");
    });

    // Show full-screen image on button click
    $("#viewFullScreen").click(function () {
        $("#imageModal").modal("show");
    });

    // Print QR Code
   $("#printQR").click(function () {
    var printWindow = window.open('', '', 'height=500,width=500');
    printWindow.document.write('<html><head><title> DTC TRAINING CENTER</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-size: 30px; text-align: center; }'); // Increases text size
    printWindow.document.write('h1 { font-size: 30px; font-weight: bold; }'); // Optional heading size
    printWindow.document.write('</style></head><body>');
    printWindow.document.write('<h1>QR Code for Attendance</h1>'); // Adds a header
    printWindow.document.write('<img src="assetsdtc_foot_traffic_qr.png" alt="QR Code" style="width: 100%; height: auto;">');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();

    });
});
// Redirect to qr_attendance_dashboard.php when the button is clicked
document.getElementById('goToDashboard').addEventListener('click', function() {
    window.location.href = 'qr_attendance_dashboard.php';
});
</script>

</body>
</html>