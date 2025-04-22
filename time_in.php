<?php
// Include your database connection file
include 'db_connection.php';

// Fetch attendance records by joining attendance2 with attendance
$sql = "SELECT a.username, a.attendance_time, att2.status 
        FROM attendance2 att2
        JOIN attendance a ON att2.user_id = a.id";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DTC Time In</title><link rel="icon" type="image/jpg" href="assets/logo1full.jpg">
  <link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
  <link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.3/animate.min.css'>
  <link rel="stylesheet" href="admin.css">
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
      <a class="navbar-brand" href="admin_dashboard.php">
        <img src="assets/images/IISE.png" alt="Logo" style="height: 30px; width: 30px; display: inline-block; vertical-align: middle; margin-right: 8px;">
        DTC TRAINING 
      </a>
    </div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-user"></span> Hello</a></li>
        <li class="active"><a title="View Website" href="#"><span class="glyphicon glyphicon-globe"></span></a></li>
        <li>
          <a href="#" class="dropdown-toggle" id="notificationBell" data-toggle="dropdown">
            <span class="glyphicon glyphicon-bell"></span>
          </a>
          <ul class="dropdown-menu" id="notificationDropdown"></ul>
        </li>
        <li><a href="logout.php">Logout</a></li>
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
          </span>
        </div>
      </div>
      <ul class="nav navbar-nav side-bar">
        <li class="side-bar"><a href="admin_dashboard.php"><span class="glyphicon glyphicon-list"></span> Event List</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-file"></span> Event Record <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="approve.php"><span class="glyphicon glyphicon-ok"></span> Approved</a></li>
            <li><a href="reject.php"><span class="glyphicon glyphicon-remove"></span> Rejected</a></li>
          </ul>
        </li>
        <li class="side-bar"><a href="user_data.php"><span class="glyphicon glyphicon-cog"></span> User Data</a></li>
        <li class="side-bar"><a href="attendance.php"><span class="glyphicon glyphicon-plus"></span> Attendance Record</a></li>
        <li class="side-bar"><a href="time_in.php"><span class="glyphicon glyphicon-plus"></span> Time In </a></li>
        <li class="side-bar"><a href="qr_in.php"><span class="glyphicon glyphicon-plus"></span> Qr In</a></li>
      </ul>
    </div>
  </div>

  <div class="col-md-9 animated bounce">
    <h1 class="page-header">DTC TRAINING</h1>
    <ul class="breadcrumb">
      <li><span class="glyphicon glyphicon-home"></span> Home</li>
      <li><a href="#">Attendance</a></li>
    </ul>

    <h2>Attendance Records</h2>
    <table class="table table-hover table-bordered">
      <thead class="active">
        <tr>
          <th>Username</th>
          <th>Attendance Time</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($result) > 0) { ?>
          <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
              <td><?php echo htmlspecialchars($row['username']); ?></td>
              <td><?php echo htmlspecialchars($row['attendance_time']); ?></td>
              <td><?php echo htmlspecialchars($row['status'] ?? 'Pending'); ?></td>
            </tr>
          <?php } ?>
        <?php } else { ?>
          <tr><td colspan="3" class="text-center">No records found</td></tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<?php mysqli_close($conn); ?>
</body>
</html>
