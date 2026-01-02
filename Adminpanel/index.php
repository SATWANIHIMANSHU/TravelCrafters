<?php
session_start();

include '../database/dbconnect.php'; // Include database connection



// Block access if admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}


// Total Users
$result = mysqli_query($conn, "SELECT COUNT(user_id) AS total_users FROM users");
$row = mysqli_fetch_assoc($result);
$total_users = $row['total_users'];

// Total Packages
$result = mysqli_query($conn, "SELECT COUNT(id) AS total_packages FROM packages");
$row = mysqli_fetch_assoc($result);
$total_packages = $row['total_packages'];

// Total Bookings
$result = mysqli_query($conn, "SELECT COUNT(id) AS total_bookings FROM bookings");
$row = mysqli_fetch_assoc($result);
$total_bookings = $row['total_bookings'];

// Fetch total revenue
$revenue_query = "SELECT SUM(order_amount) AS total_revenue FROM payments WHERE transaction_status = 'success'";
$revenue_result = mysqli_query($conn, $revenue_query);
$revenue_row = mysqli_fetch_assoc($revenue_result);
$total_revenue = $revenue_row['total_revenue'] ?? 0;

// Pending Bookings
$result = mysqli_query($conn, "SELECT COUNT(id) AS pending_bookings FROM bookings WHERE status='pending'");
$row = mysqli_fetch_assoc($result);
$pending_bookings = $row['pending_bookings'];

// Completed Bookings
$result = mysqli_query($conn, "SELECT COUNT(id) AS completed_bookings FROM bookings WHERE status='completed'");
$row = mysqli_fetch_assoc($result);
$completed_bookings = $row['completed_bookings'];

// User Feedback Count
$result = mysqli_query($conn, "SELECT COUNT(id) AS total_feedback FROM feedback");
$row = mysqli_fetch_assoc($result);
$total_feedback = $row['total_feedback'];


$query = "SELECT payment_mode, COUNT(payment_mode) AS count 
          FROM payments 
          GROUP BY payment_mode 
          ORDER BY count DESC 
          LIMIT 1";

$result = mysqli_query($conn, $query);
$most_used_payment = mysqli_fetch_assoc($result);

$payment_method = $most_used_payment['payment_mode'] ?? 'No Data';
$payment_count = $most_used_payment['count'] ?? 0;

// Fetch admin info
$admin_id = $_SESSION['admin_id']; 

// Fetch admin info
$stmt = $conn->prepare("SELECT username, email, profile_pic FROM admin WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Fallback values
$admin_name = $admin['username'] ?? 'Admin';
$admin_email = $admin['email'] ?? 'admin@example.com';
$admin_image = !empty($admin['profile_pic']) ? "../uploads/" . $admin['profile_pic'] : "assets/img/profile.jpg";

?>


<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Admin Dashboard</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
		name='viewport' />
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
	<link rel="stylesheet" href="assets/css/ready.css">
	<link rel="stylesheet" href="assets/css/demo.css">
</head>

<body>
	<div class="wrapper">
		<div class="main-header">
			<div class="logo-header">
				<a href="index.php" class="logo">
					Admin Dashboard
				</a>
				<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
					data-target="collapse" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<button class="topbar-toggler more"><i class="la la-ellipsis-v"></i></button>
			</div>
			<nav class="navbar navbar-header navbar-expand-lg">
				<div class="container-fluid">


					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<!-- <li class="nav-item dropdown hidden-caret">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="la la-envelope"></i>
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="#">Action</a>
								<a class="dropdown-item" href="#">Another action</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Something else here</a>
							</div>
						</li> -->
						<!-- <li class="nav-item dropdown hidden-caret">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="la la-bell"></i>
								<span class="notification">3</span>
							</a>
							<ul class="dropdown-menu notif-box" aria-labelledby="navbarDropdown">
								<li>
									<div class="dropdown-title">You have 4 new notification</div>
								</li>
								<li>
									<div class="notif-center">
										<a href="#">
											<div class="notif-icon notif-primary"> <i class="la la-user-plus"></i>
											</div>
											<div class="notif-content">
												<span class="block">
													New user registered
												</span>
												<span class="time">5 minutes ago</span>
											</div>
										</a>
										<a href="#">
											<div class="notif-icon notif-success"> <i class="la la-comment"></i> </div>
											<div class="notif-content">
												<span class="block">
													Rahmad commented on Admin
												</span>
												<span class="time">12 minutes ago</span>
											</div>
										</a>
										<a href="#">
											<div class="notif-img">
												<img src="assets/img/profile2.jpg" alt="Img Profile">
											</div>
											<div class="notif-content">
												<span class="block">
													Reza send messages to you
												</span>
												<span class="time">12 minutes ago</span>
											</div>
										</a>
										<a href="#">
											<div class="notif-icon notif-danger"> <i class="la la-heart"></i> </div>
											<div class="notif-content">
												<span class="block">
													Farrah liked Admin
												</span>
												<span class="time">17 minutes ago</span>
											</div>
										</a>
									</div>
								</li>
								<li>
									<a class="see-all" href="javascript:void(0);"> <strong>See all
											notifications</strong> <i class="la la-angle-right"></i> </a>
								</li>
							</ul>
						</li> -->
						<li class="nav-item dropdown">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"
								aria-expanded="false">
								<img src="<?php echo $admin_image; ?>" alt="user-img" width="36" class="img-circle">
								<span>
									<?php echo $admin_name; ?>
								</span>
							</a>
							<ul class="dropdown-menu dropdown-user">
								<li>
									<div class="user-box">
										<div class="u-img">
											<img src="<?php echo $admin_image; ?>" alt="user">
										</div>
										<div class="u-text">
											<h4>
												<?php echo $admin_name; ?>
											</h4>
											<p class="text-muted">
												<?php echo $admin_email; ?>
											</p>
											<a href="view_profile.php" class="btn btn-rounded btn-danger btn-sm">View
												Profile</a>
										</div>
									</div>
								</li>
								<div class="dropdown-divider"></div>

								<!-- Dropdown Menu Items -->
								<!-- <a class="dropdown-item" href="profile.php"><i class="ti-user"></i> My Profile</a> -->
								<a class="dropdown-item" href="edit_profile.php"><i class="ti-wallet"></i> Edit Profile</a>
								<!--  <a class="dropdown-item" href="inbox.php"><i class="ti-email"></i> Inbox</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="settings.php"><i class="ti-settings"></i> Account Settings</a>
								<div class="dropdown-divider"></div> -->
								<a class="dropdown-item text-danger" href="logout.php"><i class="fa fa-power-off"></i>
									Logout</a>
							</ul>
						</li>

					</ul>
				</div>
			</nav>
		</div>
		<div class="sidebar">
			<div class="scrollbar-inner sidebar-wrapper">

				<ul class="nav">
					<li class="nav-item active">
						<a href="index.php">
							<i class="la la-dashboard"></i>
							<p>Dashboard</p>

						</a>
					</li>
					<li class="nav-item">
						<a href="Users.php">
							<i class="la la-users"></i>
							<p>Users</p>

						</a>
					</li>
					<li class="nav-item">
						<a href="Destination.php">
							<i class="la la-map-marker"></i>
							<p>Destination</p>
							<!-- <span class="badge badge-count">5</span> -->
						</a>
					</li>
					<li class="nav-item">
						<a href="javascript:void(0);" id="package-toggle">
							<i class="la la-suitcase"></i>
							<p>Packages</p>
						</a>
					</li>
					<!-- Submenu (Initially Hidden) -->
					<ul id="package-submenu" class="submenu">
						<li class="nav-item"><a href="add_package.php">Add Package</a></li>
						<li class="nav-item"><a href="Package_info.php">Package List</a></li>
						<li class="nav-item"><a href="Package_images.php">Package Images</a></li>
						<li class="nav-item"><a href="Package_itinerary.php">Package Itinerary</a></li>
						<li class="nav-item"><a href="Package_inclusion.php">Package Inclusions</a></li>
						<li class="nav-item"><a href="Package_exclusion.php">Package Exclusions</a></li>
						<li class="nav-item"><a href="Package_availabilty.php">Package Availabilty</a></li>
						<li class="nav-item"><a href="package_terms&conditions.php">Package Terms&conditions</a></li>
					</ul>
					<li class="nav-item">
						<a href="Bookings.php">
							<i class="la la-map-marker"></i>
							<p>Booking</p>
							<!-- <span class="badge badge-count">5</span> -->
						</a>
					</li>
					<li class="nav-item">
						<a href="Payments.php">
							<i class="la la-paypal"></i>
							<p>Payments</p>
							<!-- <span class="badge badge-count">14</span> -->
						</a>
					</li>
					<li class="nav-item">
						<a href="Feedbacks.php">
							<i class="la la-comment"></i>

							<p>Feedbacks</p>
							<!-- <span class="badge badge-count">14</span> -->
						</a>
					</li>
					<li class="nav-item">
							<a href="Contact_us.php">
                            <i class="la la-question-circle"></i>

								<p>Inquiries</p>
								<!-- <span class="badge badge-count">14</span> -->
							</a>
						</li>
				</ul>
			</div>
		</div>
		<div class="main-panel">
			<div class="content">
				<div class="container-fluid">
					<h4 class="page-title">Dashboard</h4>
					<div class="row">
						<div class="col-md-3">
							<div class="card card-stats card-warning">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="la la-users"></i>
											</div>
										</div>
										<div class="col-7 d-flex align-items-center">
											<div class="numbers">
												<p class="card-category">Users</p>
												<h4 class="card-title">
													<?php echo $total_users; ?>
												</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="card card-stats card-success">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="la la-suitcase"></i>
											</div>
										</div>
										<div class="col-7 d-flex align-items-center">
											<div class="numbers">
												<p class="card-category">Packages</p>
												<h4 class="card-title">
													<?php echo $total_packages; ?>
												</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="card card-stats card-primary">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="la la-calendar-check-o"></i>
											</div>
										</div>
										<div class="col-7 d-flex align-items-center">
											<div class="numbers">
												<p class="card-category">Bookings</p>
												<h4 class="card-title">
													<?php echo $total_bookings; ?>
												</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="card card-stats card-primary">
								<div class="card-body">
									<div class="row">
										<div class="col-3">
											<div class="icon-big text-center">
												<i class="la la-money"></i>
											</div>
										</div>
										<div class="col-9 d-flex align-items-center">
											<div class="numbers">
												<p class="card-category">Total Revenue</p>
												<h4 class="card-title">₹
													<?php echo number_format($total_revenue, 0); ?>
												</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!---->
						</div>

						<!-- 							<div class="col-md-3">
								<div class="card card-stats">
									<div class="card-body ">
										<div class="row">
											<div class="col-5">
												<div class="icon-big text-center icon-warning">
													<i class="la la-pie-chart text-warning"></i>
												</div>
											</div>
											<div class="col-7 d-flex align-items-center">
												<div class="numbers">
													<p class="card-category">Number</p>
													<h4 class="card-title">150GB</h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="card card-stats">
									<div class="card-body ">
										<div class="row">
											<div class="col-5">
												<div class="icon-big text-center">
													<i class="la la-bar-chart text-success"></i>
												</div>
											</div>
											<div class="col-7 d-flex align-items-center">
												<div class="numbers">
													<p class="card-category">Revenue</p>
													<h4 class="card-title">$ 1,345</h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="card card-stats">
									<div class="card-body">
										<div class="row">
											<div class="col-5">
												<div class="icon-big text-center">
													<i class="la la-times-circle-o text-danger"></i>
												</div>
											</div>
											<div class="col-7 d-flex align-items-center">
												<div class="numbers">
													<p class="card-category">Errors</p>
													<h4 class="card-title">23</h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="card card-stats">
									<div class="card-body">
										<div class="row">
											<div class="col-5">
												<div class="icon-big text-center">
													<i class="la la-heart-o text-primary"></i>
												</div>
											</div>
											<div class="col-7 d-flex align-items-center">
												<div class="numbers">
													<p class="card-category">Followers</p>
													<h4 class="card-title">+45K</h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div> -->
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="card card-stats card-warning">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="la la-clock-o"></i>
											</div>
										</div>
										<div class="col-7 d-flex align-items-center">
											<div class="numbers">
												<p class="card-category">Pending Bookings</p>
												<h4 class="card-title">
													<?php echo $pending_bookings; ?>
												</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card card-stats card-info">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="la la-comments"></i>
											</div>
										</div>
										<div class="col-7 d-flex align-items-center">
											<div class="numbers">
												<p class="card-category">Feedback</p>
												<h4 class="card-title">
													<?php echo $total_feedback; ?>
												</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card card-stats card-info">
								<div class="card-body">
									<div class="row">
										<div class="col-3">
											<div class="icon-big text-center">
												<i class="la la-credit-card"></i>
											</div>
										</div>
										<div class="col-9 d-flex align-items-center">
											<div class="numbers">
												<p class="card-category">Most Used Payment</p>
												<h4 class="card-title">
													<?php echo htmlspecialchars($payment_method); ?>
												</h4>
												<p>
													<?php echo $payment_count; ?> Transactions
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- <div class="card card-stats card-info">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <div class="icon-big text-center">
                        <i class="la la-comments"></i>
                    </div>
                </div>
                <div class="col-7 d-flex align-items-center">
                    <div class="numbers">
                        <p class="card-category">Feedback</p>
                        <h4 class="card-title"><?php echo $total_feedback; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
						</div>

						<div class="col-md-9">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title">Office Location</h4>
									<p class="card-category">Gala Argos, 5th Floor, Ellis Bridge, Ahmedabad, Gujarat</p>
								</div>
								<div class="card-body">
									<iframe width="100%" height="260" style="border:0;" loading="lazy" allowfullscreen
										referrerpolicy="no-referrer-when-downgrade"
										src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3672.112486036061!2d72.5590283087932!3d23.01964171635466!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e84fc1f3f2f29%3A0xa0013aafc5ace06e!2sGala%20Argos!5e0!3m2!1sen!2sin!4v1743227559322!5m2!1sen!2sin">
									</iframe>

								</div>
							</div>
						</div>

						<!-- <div class="row row-card-no-pd">
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<p class="fw-bold mt-1">My Balance</p>
										<h4><b>$ 3,018</b></h4>
										<a href="#" class="btn btn-primary btn-full text-left mt-3 mb-3"><i
												class="la la-plus"></i> Add Balance</a>
									</div>
									<div class="card-footer">
										<ul class="nav">
											<li class="nav-item"><a class="btn btn-default btn-link" href="#"><i
														class="la la-history"></i> History</a></li>
											<li class="nav-item ml-auto"><a class="btn btn-default btn-link" href="#"><i
														class="la la-refresh"></i> Refresh</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="card">
									<div class="card-body">
										<div class="progress-card">
											<div class="d-flex justify-content-between mb-1">
												<span class="text-muted">Profit</span>
												<span class="text-muted fw-bold"> $3K</span>
											</div>
											<div class="progress mb-2" style="height: 7px;">
												<div class="progress-bar bg-success" role="progressbar"
													style="width: 78%" aria-valuenow="78" aria-valuemin="0"
													aria-valuemax="100" data-toggle="tooltip" data-placement="top"
													title="78%"></div>
											</div>
										</div>
										<div class="progress-card">
											<div class="d-flex justify-content-between mb-1">
												<span class="text-muted">Orders</span>
												<span class="text-muted fw-bold"> 576</span>
											</div>
											<div class="progress mb-2" style="height: 7px;">
												<div class="progress-bar bg-info" role="progressbar" style="width: 65%"
													aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
													data-toggle="tooltip" data-placement="top" title="65%"></div>
											</div>
										</div>
										<div class="progress-card">
											<div class="d-flex justify-content-between mb-1">
												<span class="text-muted">Tasks Complete</span>
												<span class="text-muted fw-bold"> 70%</span>
											</div>
											<div class="progress mb-2" style="height: 7px;">
												<div class="progress-bar bg-primary" role="progressbar"
													style="width: 70%" aria-valuenow="70" aria-valuemin="0"
													aria-valuemax="100" data-toggle="tooltip" data-placement="top"
													title="70%"></div>
											</div>
										</div>
										<div class="progress-card">
											<div class="d-flex justify-content-between mb-1">
												<span class="text-muted">Open Rate</span>
												<span class="text-muted fw-bold"> 60%</span>
											</div>
											<div class="progress mb-2" style="height: 7px;">
												<div class="progress-bar bg-warning" role="progressbar"
													style="width: 60%" aria-valuenow="60" aria-valuemin="0"
													aria-valuemax="100" data-toggle="tooltip" data-placement="top"
													title="60%"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="card card-stats">
									<div class="card-body">
										<p class="fw-bold mt-1">Statistic</p>
										<div class="row">
											<div class="col-5">
												<div class="icon-big text-center icon-warning">
													<i class="la la-pie-chart text-warning"></i>
												</div>
											</div>
											<div class="col-7 d-flex align-items-center">
												<div class="numbers">
													<p class="card-category">Number</p>
													<h4 class="card-title">150GB</h4>
												</div>
											</div>
										</div>
										<hr />
										<div class="row">
											<div class="col-5">
												<div class="icon-big text-center">
													<i class="la la-heart-o text-primary"></i>
												</div>
											</div>
											<div class="col-7 d-flex align-items-center">
												<div class="numbers">
													<p class="card-category">Followers</p>
													<h4 class="card-title">+45K</h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title">Users Statistics</h4>
										<p class="card-category">
											Users statistics this month</p>
									</div>
									<div class="card-body">
										<div id="monthlyChart" class="chart chart-pie"></div>
									</div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title">2018 Sales</h4>
										<p class="card-category">
											Number of products sold</p>
									</div>
									<div class="card-body">
										<div id="salesChart" class="chart"></div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card">
									<div class="card-header ">
										<h4 class="card-title">Table</h4>
										<p class="card-category">Users Table</p>
									</div>
									<div class="card-body">
										<table class="table table-head-bg-success table-striped table-hover">
											<thead>
												<tr>
													<th scope="col">#</th>
													<th scope="col">First</th>
													<th scope="col">Last</th>
													<th scope="col">Handle</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td>Mark</td>
													<td>Otto</td>
													<td>@mdo</td>
												</tr>
												<tr>
													<td>2</td>
													<td>Jacob</td>
													<td>Thornton</td>
													<td>@fat</td>
												</tr>
												<tr>
													<td>3</td>
													<td colspan="2">Larry the Bird</td>
													<td>@twitter</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card card-tasks">
									<div class="card-header ">
										<h4 class="card-title">Tasks</h4>
										<p class="card-category">To Do List</p>
									</div>
									<div class="card-body ">
										<div class="table-full-width">
											<table class="table">
												<thead>
													<tr>
														<th>
															<div class="form-check">
																<label class="form-check-label">
																	<input class="form-check-input  select-all-checkbox"
																		type="checkbox" data-select="checkbox"
																		data-target=".task-select">
																	<span class="form-check-sign"></span>
																</label>
															</div>
														</th>
														<th>Task</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<div class="form-check">
																<label class="form-check-label">
																	<input class="form-check-input task-select"
																		type="checkbox">
																	<span class="form-check-sign"></span>
																</label>
															</div>
														</td>
														<td>Planning new project structure</td>
														<td class="td-actions text-right">
															<div class="form-button-action">
																<button type="button" data-toggle="tooltip"
																	title="Edit Task"
																	class="btn btn-link <btn-simple-primary">
																	<i class="la la-edit"></i>
																</button>
																<button type="button" data-toggle="tooltip"
																	title="Remove"
																	class="btn btn-link btn-simple-danger">
																	<i class="la la-times"></i>
																</button>
															</div>
														</td>
													</tr>
													<tr>
														<td>
															<div class="form-check">
																<label class="form-check-label">
																	<input class="form-check-input task-select"
																		type="checkbox">
																	<span class="form-check-sign"></span>
																</label>
															</div>
														</td>
														<td>Update Fonts</td>
														<td class="td-actions text-right">
															<div class="form-button-action">
																<button type="button" data-toggle="tooltip"
																	title="Edit Task"
																	class="btn btn-link <btn-simple-primary">
																	<i class="la la-edit"></i>
																</button>
																<button type="button" data-toggle="tooltip"
																	title="Remove"
																	class="btn btn-link btn-simple-danger">
																	<i class="la la-times"></i>
																</button>
															</div>
														</td>
													</tr>
													<tr>
														<td>
															<div class="form-check">
																<label class="form-check-label">
																	<input class="form-check-input task-select"
																		type="checkbox">
																	<span class="form-check-sign"></span>
																</label>
															</div>
														</td>
														<td>Add new Post
														</td>
														<td class="td-actions text-right">
															<div class="form-button-action">
																<button type="button" data-toggle="tooltip"
																	title="Edit Task"
																	class="btn btn-link <btn-simple-primary">
																	<i class="la la-edit"></i>
																</button>
																<button type="button" data-toggle="tooltip"
																	title="Remove"
																	class="btn btn-link btn-simple-danger">
																	<i class="la la-times"></i>
																</button>
															</div>
														</td>
													</tr>
													<tr>
														<td>
															<div class="form-check">
																<label class="form-check-label">
																	<input class="form-check-input task-select"
																		type="checkbox">
																	<span class="form-check-sign"></span>
																</label>
															</div>
														</td>
														<td>Finalise the design proposal</td>
														<td class="td-actions text-right">
															<div class="form-button-action">
																<button type="button" data-toggle="tooltip"
																	title="Edit Task"
																	class="btn btn-link <btn-simple-primary">
																	<i class="la la-edit"></i>
																</button>
																<button type="button" data-toggle="tooltip"
																	title="Remove"
																	class="btn btn-link btn-simple-danger">
																	<i class="la la-times"></i>
																</button>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="card-footer ">
										<div class="stats">
											<i class="now-ui-icons loader_refresh spin"></i> Updated 3 minutes ago
										</div>
									</div>
								</div>
							</div>
						</div> -->
					</div>
				</div>
				<footer class="footer bg-dark text-light py-3">
					<div class="container-fluid d-flex justify-content-between align-items-center">
						<!-- Navigation Links -->
						<nav class="footer-nav">
							<ul class="nav">
								<li class="nav-item">
									<a class="nav-link text-light" href="../index.php">Home</a>
								</li>
								<li class="nav-item">
									<a class="nav-link text-light" href="../about.php">About Us</a>
								</li>
								<li class="nav-item">
									<a class="nav-link text-light" href="../show_packages.php"> Packages</a>
								</li>
								<li class="nav-item">
									<a class="nav-link text-light" href="../contact.php">Contact</a>
								</li>
								<li class="nav-item">
									<a class="nav-link text-light" href="terms.php">Terms & Conditions</a>
								</li>
							</ul>
						</nav>

						<!-- Copyright -->
						<div class="copyright text-right">
							<p class="mb-0">© 2025, Designed & Developed by <a href="#"
									class="text-primary">Himanshu</a></p>
						</div>
					</div>
				</footer>

			</div>
		</div>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="modalUpdatePro"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<h6 class="modal-title"><i class="la la-frown-o"></i> Under Development</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-center">
					<p>Currently the pro version of the <b>Ready Dashboard</b> Bootstrap is in progress development</p>
					<p>
						<b>We'll let you know when it's done</b>
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</body>
<script src="assets/js/core/jquery.3.2.1.min.js"></script>
<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
<script src="assets/js/plugin/chartist/chartist.min.js"></script>
<script src="assets/js/plugin/chartist/plugin/chartist-plugin-tooltip.min.js"></script>
<script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="assets/js/plugin/jquery-mapael/jquery.mapael.min.js"></script>
<script src="assets/js/plugin/jquery-mapael/maps/world_countries.min.js"></script>
<script src="assets/js/plugin/chart-circle/circles.min.js"></script>
<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/js/ready.min.js"></script>
<script src="assets/js/demo.js"></script>

</html>