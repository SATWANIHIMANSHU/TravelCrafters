<?php

session_start();
include '../database/dbconnect.php'; // Include database connection


// Block access if admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}


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
    <!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
     .main-container {
            margin-top: 30px;
            /* max-width: 1600px; */
        }

        .form-container {
            padding: 20px;
            max-width: 1600px;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
       
</style>
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
                    <li class="nav-item">
                        <a href="index.php">
                            <i class="la la-dashboard"></i>
                            <p>Dashboard</p>
                            
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="Users.php">
                            <i class="la la-users"></i>
                            <p>Users</p>
                            <!-- <span class="badge badge-count">5</span> -->
                        </a>
                    </li>
                    <li class="nav-item active">
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
                    <!-- <li class="nav-item">
							<a href="components.html">
								<i class="la la-table"></i>
								<p>Components</p>
								<span class="badge badge-count">14</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="forms.html">
								<i class="la la-keyboard-o"></i>
								<p>Forms</p>
								<span class="badge badge-count">50</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="tables.html">
								<i class="la la-th"></i>
								<p>Tables</p>
								<span class="badge badge-count">6</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="notifications.html">
								<i class="la la-bell"></i>
								<p>Notifications</p>
								<span class="badge badge-success">3</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="typography.html">
								<i class="la la-font"></i>
								<p>Typography</p>
								<span class="badge badge-danger">25</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="icons.html">
								<i class="la la-fonticons"></i>
								<p>Icons</p>
							</a>
						</li>
						<li class="nav-item update-pro">
							<button  data-toggle="modal" data-target="#modalUpdate">
								<i class="la la-hand-pointer-o"></i>
								<p>Update To Pro</p>
							</button>
						</li> -->
                </ul>
            </div>
            

        </div>

        

       
        <div class="main-panel">
       
				<div class="content">
					<div class="container-fluid">
                    <div class="main-container">
        <h4 class="text-center mb-4">Manage Destinations</h4>

        <!-- Toggle Buttons -->
        <ul class="nav nav-pills mb-3 justify-content-center">
            <li class="nav-item">
                <button class="nav-link active" id="showForm">Add Destination</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="showTable">View Destinations</button>
            </li>
        </ul>

        <!-- Form Section -->
        <div id="formSection" class="form-container">
            <h4>Add New Destination</h4>
            <form id="destinationForm" action="insert_destination.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Destination Name</label>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Enter destination name">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required placeholder="Enter description"></textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Upload Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>

                <div class="mb-3">
                    <label for="display_type" class="form-label">Display Type</label>
                    <select class="form-control" id="display_type" name="display_type" required>
                        <option value="">Select Display Type</option>
                        <option value="card">Card</option>
                        <option value="grid">Grid</option>
                        <option value="image_grid">Image-Grid</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-submit">Submit</button>
            </form>
        </div>

        <!-- Table Section -->
        <div id="tableSection" class="table-container" style="display: none;">
            <h4 class="page-title">Destinations Table</h4>
            <div class="table-responsive">
                <table id="usersTable" class="table table-striped table-bordered w-100">
                    <thead class="table">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Display Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include '../database/dbconnect.php'; 
                        $query = "SELECT * FROM destinations"; 
                        $result = mysqli_query($conn, $query);
        
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><img src="../uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" width="100"></td>
                                <td><?php echo $row['display_type']; ?></td>
                                <td>
                                    <!-- <a href="edit_destination.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a> -->
                                    <a href="edit_destination.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>

                                    <a href="delete_destination.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

			</div>
        
          
</div>
</div>
   


  


</body>


<!-- jQuery (Required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- <script src="assets/js/core/jquery.3.2.1.min.js"></script> -->
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
<script>
    $(document).ready(function () {
        $('#usersTable').DataTable({
            "paging": true,      // Enable pagination
            "searching": true,   // Enable search bar
            "ordering": true,    // Enable sorting
            "info": true,        // Show info (Showing 1 to 10 of X entries)
            "lengthMenu": [5, 10, 25, 50] // Dropdown for entries per page
        });
    });
</script>
<script>
        $(document).ready(function () {
            $("#destinationForm").submit(function (e) {
                let image = $("#image").val();
                if (image === '') {
                    alert("Please upload an image");
                    e.preventDefault();
                }
            });
        });
    </script>
<script>
        $(document).ready(function () {
            $("#showForm").click(function () {
                $("#formSection").fadeIn();
                $("#tableSection").hide();
                $("#showForm").addClass("active");
                $("#showTable").removeClass("active");
            });

            $("#showTable").click(function () {
                $("#tableSection").fadeIn();
                $("#formSection").hide();
                $("#showTable").addClass("active");
                $("#showForm").removeClass("active");
            });
        });
    </script>

</html>