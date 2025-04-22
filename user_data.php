<?php
include 'db_connection.php'; // Include your database connection

$query = "SELECT * FROM users"; // Fetch all columns from users table
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
            padding-top: 25px; /* Adjusted to prevent navbar overlap */
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
            <h1 class="page-header">DTC TRAINING - User Data</h1>
            <div class="table-responsive">
                <table id="userTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Profile</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>DOB</th>
                            <th>QR Code</th>
                            <th>Type</th>
                            <th>Age Group</th>
                            <th>Sector</th>
                            <th>Agency</th>
                            <th>Senior</th>
                            <th>Abled</th>
                            <th>Nationality</th>
                            <th>Region</th>
                            <th>Office</th>
                            <th>Position</th>
                            <th>Parent</th>
                            <th>Civil Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $row) { ?>
                            <tr>
                                <td>
                                    <button class="btn btn-primary btn-action" onclick="printUser(<?php echo $row['id']; ?>)">Print</button>
                                    <button class="btn btn-info btn-action" onclick="viewUserInfo(<?php echo $row['id']; ?>)">View Info</button>
                                    <button class="btn btn-success btn-action" onclick="viewQR('<?php echo $row['qr_code']; ?>')">View QR</button>
                                </td>
                                <td>
                                    <?php
                                    $imagePath = (!empty($row['profile_pic']) && file_exists($row['profile_pic'])) ? 
                                        $row['profile_pic'] : "assets/images/default.png";
                                    ?>
                                    <img src="<?php echo $imagePath; ?>" alt="User Image" width="50" height="50">
                                </td>
                                <td><?php echo ucfirst(htmlspecialchars($row['username'])); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['dob']); ?></td>
                                <td><?php echo htmlspecialchars($row['qr_code']); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td><?php echo htmlspecialchars($row['age_group']); ?></td>
                                <td><?php echo htmlspecialchars($row['sector']); ?></td>
                                <td><?php echo htmlspecialchars($row['agency']); ?></td>
                                <td><?php echo htmlspecialchars($row['senior']); ?></td>
                                <td><?php echo htmlspecialchars($row['abled']); ?></td>
                                <td><?php echo htmlspecialchars($row['nationality']); ?></td>
                                <td><?php echo htmlspecialchars($row['region']); ?></td>
                                <td><?php echo htmlspecialchars($row['office']); ?></td>
                                <td><?php echo htmlspecialchars($row['position']); ?></td>
                                <td><?php echo htmlspecialchars($row['parent']); ?></td>
                                <td><?php echo htmlspecialchars($row['civil_status']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Viewing User Info -->
<div class="modal fade" id="userInfoModal" tabindex="-1" role="dialog" aria-labelledby="userInfoModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="userInfoModalLabel">User Information</h4>
            </div>
            <div class="modal-body" id="userInfoContent">
                <!-- User info will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Viewing QR Code -->
<div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="qrModalLabel">QR Code</h4>
            </div>
            <div class="modal-body text-center">
                <img id="qrImage" src="" alt="QR Code" style="max-width: 100%; height: auto;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="downloadQR()">Download QR</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script>
  $(document).ready(function() {
      // Initialize DataTables with sorting and export buttons
      $('#userTable').DataTable({
          dom: 'Bfrtip',
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],
          ordering: true // Enable sorting
      });

      // Search functionality
      $("#searchButton").click(function() {
          var searchTerm = $("#searchInput").val().toLowerCase();
          $("#userTable tbody tr").each(function() {
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
      $("#clearButton").click(function() {
          $("#searchInput").val("");
          $("#userTable tbody tr").show();
      });

      // Trigger search on pressing Enter key
      $("#searchInput").keypress(function(event) {
          if (event.keyCode === 13) {
              $("#searchButton").click();
          }
      });
  });

  function printUser(userId) {
      var printWindow = window.open('print_user.php?id=' + userId, '_blank');
      printWindow.focus();
  }

  function viewUserInfo(userId) {
      $.ajax({
          url: 'get_user_info.php',
          type: 'GET',
          data: { id: userId },
          success: function(response) {
              $('#userInfoContent').html(response);
              $('#userInfoModal').modal('show');
          },
          error: function() {
              alert('Failed to load user information.');
          }
      });
  }

  function viewQR(qrCodePath) {
      $('#qrImage').attr('src', 'uploads/' + qrCodePath);
      $('#qrModal').modal('show');
  }

  function downloadQR() {
      var qrImageSrc = $('#qrImage').attr('src');
      var link = document.createElement('a');
      link.href = qrImageSrc;
      link.download = 'qr_code.png';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
  }
</script>

</body>
</html>