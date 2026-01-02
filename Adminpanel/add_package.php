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


$message = ""; // To store success or error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Capture package details
        $package_name = $_POST['package_name'];
        $destination_id = $_POST['destination_id'];
        $duration = $_POST['duration'];
        $original_price = $_POST['original_price'];
        $old_price = $_POST['old_price'];
        $is_customizable = $_POST['is_customizable'];
        $is_expert_choice = $_POST['is_expert_choice'];

        // Insert package details into `packages` table
        $stmt = $conn->prepare("INSERT INTO packages (name, destination_id, duration, original_price, old_price, is_customizable, is_expert_choice) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiddii", $package_name, $destination_id, $duration, $original_price, $old_price, $is_customizable, $is_expert_choice);
        $stmt->execute();
        $package_id = $stmt->insert_id; // Get newly inserted package ID
        $stmt->close();

        // Handle image uploads and insert into `package_images` table
        if (!empty($_FILES['package_images']['name'][0])) { 
            foreach ($_FILES['package_images']['name'] as $key => $filename) {
                $file_tmp = $_FILES['package_images']['tmp_name'][$key];
                $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
                if (in_array($file_ext, $allowed_types)) {
                    $new_filename = basename($filename); // Store only the original image name
                    move_uploaded_file($file_tmp, "uploads/" . $new_filename); // Save image in 'uploads/' folder
        
                    // Insert only the image name into the database (not the full path)
                    $stmt = $conn->prepare("INSERT INTO package_images (package_id, image_url) VALUES (?, ?)");
                    $stmt->bind_param("is", $package_id, $new_filename);
                    $stmt->execute();
                }
            }
        }
        

        // Insert itinerary details
        if (!empty($_POST['day_number'])) {
            $stmt = $conn->prepare("INSERT INTO itinerary (package_id, day_number, title, description) VALUES (?, ?, ?, ?)");
            foreach ($_POST['day_number'] as $key => $day_number) {
                $day_title = $_POST['day_title'][$key];
                $day_description = $_POST['day_description'][$key];
                $stmt->bind_param("iiss", $package_id, $day_number, $day_title, $day_description);
                $stmt->execute();
            }
            $stmt->close();
        }

        // Insert inclusions
        if (!empty($_POST['inclusion'])) {
            $stmt = $conn->prepare("INSERT INTO inclusions (package_id, description) VALUES (?, ?)");
            foreach ($_POST['inclusion'] as $inclusion) {
                if (!empty($inclusion)) {
                    $stmt->bind_param("is", $package_id, $inclusion);
                    $stmt->execute();
                }
            }
            $stmt->close();
        }

        // Insert exclusions
        if (!empty($_POST['exclusion'])) {
            $stmt = $conn->prepare("INSERT INTO exclusions (package_id, description) VALUES (?, ?)");
            foreach ($_POST['exclusion'] as $exclusion) {
                if (!empty($exclusion)) {
                    $stmt->bind_param("is", $package_id, $exclusion);
                    $stmt->execute();
                }
            }
            $stmt->close();
        }

        
// Insert available dates and seats
if (!empty($_POST['available_date'])) {
    $stmt = $conn->prepare("INSERT INTO package_availability (package_id, travel_date, available_seats) VALUES (?, ?, ?)");
    
    foreach ($_POST['available_date'] as $key => $travel_date) {
        $available_seats = $_POST['available_seats'][$key];
        $stmt->bind_param("isi", $package_id, $travel_date, $available_seats);
        $stmt->execute();
    }
    $stmt->close();

}
    

if (!empty($_POST['terms'])) {
    foreach ($_POST['terms'] as $term) {
        $term = mysqli_real_escape_string($conn, $term);
        $query = "INSERT INTO terms_conditions (package_id,content) VALUES ('$package_id', '$term')";
        mysqli_query($conn, $query);
    }
}

$message = "<div class='alert alert-success'>Package added successfully!</div>";
    } catch (Exception $e) {
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}





// Fetch destinations for the dropdown
$destQuery = $conn->query("SELECT id, name FROM destinations");
$destinations = $destQuery->fetch_all(MYSQLI_ASSOC);




 ?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Ready Bootstrap Dashboard</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/css/ready.css">
    <link rel="stylesheet" href="assets/css/demo.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        /* Hide submenu by default */
        .submenu {
            display: none;
            list-style: none;
            padding-left: 20px;
        }

        .submenu li {
            padding: 5px 0;
        }

        .form-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 25px;
        }

        .btn-add {
            margin-top: 10px;
            background-color: #28a745;
            color: white;
        }

        .btn-remove {
            margin-top: 10px;
            background-color: #dc3545;
            color: white;
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
                            <!-- <span class="badge badge-count">5</span> -->
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="Users.php">
                            <i class="la la-users"></i>
                            <p>Users</p>
                            <!-- <span class="badge badge-count">5</span> -->
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="Destination.php">
                            <i class="la la-map-marker"></i>
                            <p>Destination</p>
                            <!-- <span class="badge badge-count">5</span> -->
                        </a>
                    </li>
                    <li class="nav-item active">
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
                <div class="Addpackage ">
                    <div class="container py-8">
                        <div class="row justify-content-center">
                            <div class="col-lg-10">
                                <h1 class="text-center mb-4">
                                    <i class="fas fa-plane"></i> Create New Travel Package
                                </h1>


                                <?php echo $message; ?> <!-- Display success/error messages -->
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-section">
                                        <h2 class="h4 mb-3"><i class="fas fa-info-circle"></i> Package Details</h2>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Package Name</label>
                                                <input type="text" class="form-control" name="package_name" required>
                                            </div>
                                            <div class="col-md-6 mb-3">

                                                <label class="form-label">Destination</label>
                                                <select class="form-select form-control" name="destination_id" required>
                                                    <option value="">Select Destination</option>
                                                    <?php foreach ($destinations as $dest) { ?>
                                                    <option value="<?php echo $dest['id']; ?>">
                                                        <?php echo $dest['name']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Duration (Days)</label>
                                                <input type="number" class="form-control" name="duration" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">New Price</label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="original_price" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Old Price</label>
                                                <input type="number" step="0.01" class="form-control" name="old_price"
                                                    required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Customizable</label>
                                                <select class="form-select form-control" name="is_customizable">
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Expert Choice</label>
                                                <select class="form-select form-control" name="is_expert_choice">
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-section">
                                        <h2 class="h4 mb-3"><i class="fas fa-route"></i> Itinerary</h2>
                                        <div id="itinerary-container">
                                            <div class="itinerary-item mb-3">
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label">Day Number</label>
                                                        <input type="number" class="form-control" name="day_number[]"
                                                            required>
                                                    </div>
                                                    <div class="col-md-8 mb-2">
                                                        <label class="form-label">Day Title</label>
                                                        <input type="text" class="form-control" name="day_title[]"
                                                            required>
                                                    </div>
                                                    <div class="col-12 mb-2">
                                                        <label class="form-label">Day Description</label>
                                                        <textarea class="form-control" name="day_description[]" rows="3"
                                                            required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-add" onclick="addItineraryDay()">
                                            <i class="fas fa-plus"></i> Add Another Day
                                        </button>
                                    </div>

                                    <div class="form-section">
                                        <h2 class="h4 mb-3"><i class="fas fa-image"></i> Package Images</h2>
                                        <div id="image-container">
                                            <div class="mb-3">
                                                <input type="file" class="form-control" name="package_images[]"
                                                    accept="image/*">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-add" onclick="addImageUpload()">
                                            <i class="fas fa-plus"></i> Add Another Image
                                        </button>
                                    </div>


                                    <div class="form-section">
                                        <h2 class="h4 mb-3"><i class="fas fa-check-circle"></i> Inclusions</h2>
                                        <div id="inclusion-container">
                                            <div class="mb-3">
                                                <input type="text" class="form-control" name="inclusion[]"
                                                    placeholder="Enter inclusion">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-add" onclick="addInclusion()">
                                            <i class="fas fa-plus"></i> Add Inclusion
                                        </button>
                                    </div>

                                    <div class="form-section">
                                        <h2 class="h4 mb-3"><i class="fas fa-times-circle"></i> Exclusions</h2>
                                        <div id="exclusion-container">
                                            <div class="mb-3">
                                                <input type="text" class="form-control" name="exclusion[]"
                                                    placeholder="Enter exclusion">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-add" onclick="addExclusion()">
                                            <i class="fas fa-plus"></i> Add Exclusion
                                        </button>
                                    </div>



                                    <div class="form-section">
                                        <h2 class="h4 mb-3"><i class="fas fa-calendar"></i> Available Dates & Seats</h2>
                                        <div id="dates-container">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Travel Date</label>
                                                    <input type="date" class="form-control" name="available_date[]"
                                                        required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Available Seats</label>
                                                    <input type="number" class="form-control" name="available_seats[]"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-add" onclick="addDateField()">
                                            <i class="fas fa-plus"></i> Add Another Date
                                        </button>
                                    </div>

                                    <div class="form-section">
                                        <h2 class="h4 mb-3"><i class="fas fa-file-alt"></i> Terms & Conditions</h2>
                                        <div id="terms-container">
                                            <div class="mb-3">
                                                <input type="text" class="form-control" name="terms[]"
                                                    placeholder="Enter term" required>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-add" onclick="addTermField()">
                                            <i class="fas fa-plus"></i> Add Another Term
                                        </button>
                                    </div>




                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save"></i> Create Package
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Bootstrap JS and dependencies -->
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                    <script>
                        function addItineraryDay() {
                            const container = document.getElementById('itinerary-container');
                            const newItem = document.createElement('div');
                            newItem.className = 'itinerary-item mb-3 p-3 border rounded bg-light'; // Added border for visibility

                            newItem.innerHTML = `
        <div class="row">
            <div class="col-md-4 mb-2">
                <label class="form-label">Day Number</label>
                <input type="number" class="form-control" name="day_number[]" required>
            </div>
            <div class="col-md-8 mb-2">
                <label class="form-label">Day Title</label>
                <input type="text" class="form-control" name="day_title[]" required>
            </div>
            <div class="col-12 mb-2">
                <label class="form-label">Day Description</label>
                <textarea class="form-control" name="day_description[]" rows="3" required></textarea>
            </div>
            <div class="col-12 text-end">
                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeItinerary(this)">
                    <i class="fas fa-trash"></i> Remove Day
                </button>
            </div>
        </div>
    `;

                            container.appendChild(newItem);
                        }

                        // ✅ Corrected removeItinerary function
                        function removeItinerary(button) {
                            let parent = button.closest('.itinerary-item'); // Find the nearest .itinerary-item div
                            if (parent) {
                                parent.remove(); // Remove the entire itinerary block
                            }
                        }




                        // Function to add Image Upload with Remove Button
                        function addImageUpload() {
                            const container = document.getElementById('image-container');
                            const newDiv = document.createElement('div');
                            newDiv.className = 'mb-3 image-upload';
                            newDiv.innerHTML = `
            <input type="file" class="form-control" name="package_images[]" accept="image/*">
            <button type="button" class="btn btn-remove mt-2" onclick="removeElement(this)">
                <i class="fas fa-trash"></i> Remove Image
            </button>
        `;
                            container.appendChild(newDiv);
                        }

                        // Function to add Inclusion with Remove Button
                        function addInclusion() {
                            const container = document.getElementById('inclusion-container');
                            const newDiv = document.createElement('div');
                            newDiv.className = 'mb-3 inclusion-item';
                            newDiv.innerHTML = `
            <input type="text" class="form-control" name="inclusion[]" placeholder="Enter inclusion">
            <button type="button" class="btn btn-remove mt-2" onclick="removeElement(this)">
                <i class="fas fa-trash"></i> Remove Inclusion
            </button>
        `;
                            container.appendChild(newDiv);
                        }

                        // Function to add Exclusion with Remove Button
                        function addExclusion() {
                            const container = document.getElementById('exclusion-container');
                            const newDiv = document.createElement('div');
                            newDiv.className = 'mb-3 exclusion-item';
                            newDiv.innerHTML = `
            <input type="text" class="form-control" name="exclusion[]" placeholder="Enter exclusion">
            <button type="button" class="btn btn-remove mt-2" onclick="removeElement(this)">
                <i class="fas fa-trash"></i> Remove Exclusion
            </button>
        `;
                            container.appendChild(newDiv);
                        }

                        // General function to remove an element
                        function removeElement(button) {
                            button.parentElement.remove();
                        }




                        function addDateField() {
                            let container = document.getElementById('dates-container');
                            let div = document.createElement('div');
                            div.classList.add('row', 'mb-3');
                            div.innerHTML = `
        <div class="col-md-6">
            <input type="date" class="form-control" name="available_date[]" required>
        </div>
        <div class="col-md-6">
            <input type="number" class="form-control" name="available_seats[]" required>
        </div>
    `;
                            container.appendChild(div);
                        }


                        function addTermField() {
                            const container = document.getElementById("terms-container");
                            const input = document.createElement("div");
                            input.classList.add("mb-3");
                            input.innerHTML = `<input type="text" class="form-control" name="terms[]" placeholder="Enter term" required>`;
                            container.appendChild(input);
                        }
                    </script>
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
    document.getElementById("package-toggle").addEventListener("click", function () {
        var submenu = document.getElementById("package-submenu");
        submenu.style.display = (submenu.style.display === "block") ? "none" : "block";
    });
</script>

</html>