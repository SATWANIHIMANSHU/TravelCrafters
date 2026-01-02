<?php
session_start(); 
include 'database/dbconnect.php';

$package_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($package_id <= 0) {
    die("Invalid package ID."); // You can also redirect: header("Location: error.php");
}

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    
    // Fetch username from the database
    $stmt = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
} 


// Fetch package details
$query = "SELECT p.*, d.name as destination_name FROM packages p INNER JOIN destinations d ON p.destination_id = d.id WHERE p.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $package_id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();
$stmt->close();



// Fetch package images from the database
$query_images = "SELECT image_url FROM package_images WHERE package_id = ?";
$stmt_images = $conn->prepare($query_images);
$stmt_images->bind_param("i", $package_id);
$stmt_images->execute();
$result_images = $stmt_images->get_result();

$images = [];
while ($row = $result_images->fetch_assoc()) {
    $images[] = $row['image_url'];
}
$stmt_images->close();

$package_image = !empty($images) ? rawurlencode($images[0]) : ''; 

// Fetch itinerary
$query_itinerary = "SELECT * FROM itinerary WHERE package_id = ? ORDER BY day_number ASC";
$stmt_itinerary = $conn->prepare($query_itinerary);
$stmt_itinerary->bind_param("i", $package_id);
$stmt_itinerary->execute();
$result_itinerary = $stmt_itinerary->get_result();
$stmt_itinerary->close();

// Fetch inclusions
$query_inclusions = "SELECT * FROM inclusions WHERE package_id = ?";
$stmt_inclusions = $conn->prepare($query_inclusions);
$stmt_inclusions->bind_param("i", $package_id);
$stmt_inclusions->execute();
$result_inclusions = $stmt_inclusions->get_result();
$stmt_inclusions->close();

// Fetch exclusions
$query_exclusions = "SELECT * FROM exclusions WHERE package_id = ?";
$stmt_exclusions = $conn->prepare($query_exclusions);
$stmt_exclusions->bind_param("i", $package_id);
$stmt_exclusions->execute();
$result_exclusions = $stmt_exclusions->get_result();
$stmt_exclusions->close();


// Fetch all packages excluding the current package
$currentPackageId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$allPackagesQuery = "SELECT p.id, pi.image_url 
                     FROM packages p
                     LEFT JOIN package_images pi ON p.id = pi.package_id 
                     WHERE p.id != $currentPackageId
                     ORDER BY p.id DESC 
                     LIMIT 5"; // Fetch 5 packages

$allPackagesResult = mysqli_query($conn, $allPackagesQuery);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Tourist - Travel Agency HTML Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        .badge-custom {
            font-size: 14px;
            margin-right: 5px;
        }

        .card-inclusions {
            background: #e9f9e9;
        }

        .card-exclusions {
            background: #fbe9e9;
        }

        .booking-box {
            background: #eaf4ff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 350px;
            margin: auto;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .price-box {
            background: #fff;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .old-price {
            text-decoration: line-through;
            color: #888;
            font-size: 14px;
        }

        .new-price {
            font-size: 26px;
            font-weight: bold;
            color: #000;
        }

        .per-person {
            font-size: 14px;
            color: #555;
        }

        .btn-book {
            background: #ff7300;
            color: white;
            font-weight: bold;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 25px;
            font-size: 16px;
        }

        .btn-enquire {
            background: none;
            border: 2px solid #007bff;
            color: #007bff;
            font-weight: bold;
            width: 100%;
            padding: 12px;
            border-radius: 25px;
            font-size: 16px;
            margin-top: 10px;
        }

        .btn-enquire:hover {
            background: #007bff;
            color: white;
        }

        .carousel-inner img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        /* Modal Customization */
        .modal-content {
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .modal-title {
            font-weight: bold;
        }

        /* Count Adjust Buttons */
        .count-btn {
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            padding: 5px 12px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.2s;
        }

        .count-btn:hover {
            background-color: #ddd;
        }

        /* Total Amount Styling */
        #totalAmount {
            font-size: 22px;
            font-weight: bold;
            color: #28a745;
        }

        .available-date-card {
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .available-date-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
    </style>

</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid bg-dark px-5 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>Gala Argos, 5th Floor Ellis
                        Bridge , Ahmedabad , Gujarat</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+91 7997576990</small>
                    <small class="text-light"><i class="fa fa-envelope-open me-2"></i>info@travelcrafter.com</small>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i
                            class="fab fa-twitter fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i
                            class="fab fa-facebook-f fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i
                            class="fab fa-linkedin-in fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i
                            class="fab fa-instagram fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle" href=""><i
                            class="fab fa-youtube fw-normal"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar & Hero Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
            <a href="" class="navbar-brand p-0">
                <h1 class="text-primary m-0"><i class="fa fa-map-marker-alt me-3"></i>TravelCrafters</h1>
                <!-- <img src="img/logo.png" alt="Logo"> -->
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="about.php" class="nav-item nav-link">About</a>
                    <!-- <a href="service.html" class="nav-item nav-link">Services</a> -->
                    <a href="show_packages.php" class="nav-item nav-link">Packages</a>
                    <!-- <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="destination.html" class="dropdown-item">Destination</a>
                            <a href="booking.html" class="dropdown-item">Booking</a>
                            <a href="team.html" class="dropdown-item">Travel Guides</a>
                            <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                            <a href="404.html" class="dropdown-item">404 Page</a>
                        </div>
                    </div> -->
                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                </div>
                <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <div class="nav-item dropdown ms-3">
                    <a href="#" class="nav-link dropdown-toggle btn btn-primary rounded-pill py-2 px-4"
                        id="userDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        <?php echo htmlspecialchars($username ?? 'User'); ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="profile.php" class="dropdown-item">Profile</a>
                        <a href="show_bookings.php" class="dropdown-item">My Bookings</a>
                        <hr class="dropdown-divider">
                        <a href="logout.php" class="dropdown-item text-danger">Logout</a>
                    </div>
                </div>
                <?php else: ?>
                <a href="Login.php" class="btn btn-primary rounded-pill py-2 px-4 ms-3">Register</a>
                <?php endif; ?>
            </div>
        </nav>

        <div class="container-fluid bg-primary py-1 mb-1 hero-header">
            <div class="container py-5">
                <!-- <div class="row justify-content-center py-5">
                    <div class="col-lg-10 pt-lg-5 mt-lg-5 text-center">
                        <h1 class="display-3 text-white animated slideInDown">Contact Us</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                                <li class="breadcrumb-item text-white active" aria-current="page">Contact</li>
                            </ol>
                        </nav>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <!-- Navbar & Hero End -->


    <div class="container mt-4">
        <div class="container">
            <!-- First Row: Full-width Carousel -->
            <div class="row">
                <div class="col-12">
                    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner">
                            <?php 
                    if (!empty($images)) {
                        $isFirst = true;
                        foreach ($images as $image) {
                            echo '<div class="carousel-item ' . ($isFirst ? "active" : "") . '">
                                    <img src="uploads/' . htmlspecialchars($image) . '" class="d-block w-100" alt="Package Image">
                                  </div>';
                            $isFirst = false;
                        }
                    } else {
                        echo '<div class="carousel-item active">
                                <img src="uploads/default.jpg" class="d-block w-100" alt="No Image Available">
                              </div>';
                    }
                    ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Second Row: Package Details (Left) and Booking Box (Right) -->
            <div class="row mt-4">
                <!-- Package Details (Left Side) -->
                <div class="col-md-8">
                    <h2>
                        <?= htmlspecialchars($package['name'] ?? 'Package Name') ?>
                    </h2>
                    <span class="badge bg-secondary badge-custom">

                        <?= ($package['duration'] ?? 0) - 1 ?>
                        Nights/
                        <?= htmlspecialchars($package['duration'] ?? '0') ?> Days
                    </span>
                    <?php if (!empty($package['is_customizable'])) { ?>
                    <span class="badge bg-primary badge-custom">Fully Customizable</span>
                    <?php } ?>
                    <?php if (!empty($package['is_expert_choice'])) { ?>
                    <span class="badge bg-success badge-custom">Expert Choice</span>
                    <?php } ?>
                    <h3 class="mt-3">Starting From ₹
                        <?= htmlspecialchars($package['original_price'] ?? '0') ?>
                        <span class="text-decoration-line-through text-muted">₹
                            <?= htmlspecialchars($package['old_price'] ?? '0') ?>
                        </span>
                        <span class="badge bg-success">
                            <?php 
                    if (!empty($package['original_price']) && !empty($package['old_price'])) {
                        $discount = round((($package['old_price'] - $package['original_price']) / $package['old_price']) * 100);
                        echo $discount . "% Off";
                    }
                    ?>
                        </span>
                    </h3>
                    <div class="mt-4">
            <h4 class="mb-3">Available Dates</h4>
            <div class="d-flex flex-wrap gap-2">
                <?php
                $package_id = $package['id'] ?? 0;
                $query = "SELECT travel_date FROM package_availability WHERE package_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $package_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="date-pill px-3 py-2 rounded-pill bg-light border">';
                        echo '<small class="text-dark fw-bold">' . date('d M Y', strtotime($row['travel_date'])) . '</small>';
                        echo '</div>';
                    }
                } else {
                    echo '<div><small class="text-muted">No available dates</small></div>';
                }
                ?>
            </div>
        </div>
            </div>

                    <!-- Booking Box (Right Side) -->
                    <div class="col-md-4">
                        <div class="booking-box p-3 border rounded shadow">
                            <p class="text-muted mb-1">Starting from</p>
                            <div class="price-box">
                                <span class="old-price">₹
                                    <?= htmlspecialchars($package['old_price'] ?? '0') ?>
                                </span>
                                <br>
                                <span class="new-price">₹
                                    <?= htmlspecialchars($package['original_price'] ?? '0') ?>
                                </span>
                                <span class="per-person">Per Person</span>
                            </div>
                            <?php
                if (isset($_SESSION['user_id'])) {
                    echo '<button class="btn btn-book w-100 mt-3" data-bs-toggle="modal" data-bs-target="#bookingModal">BOOK NOW</button>';
                } else {
                    echo '<a href="login.php" class="btn btn-book w-100 mt-3">BOOK NOW</a>';
                }
                ?>
                            <!-- <button class="btn btn-enquire w-100 mt-2">ENQUIRE NOW</button> -->
                        </div>
                    </div>
                </div>
            </div>


            <!-- Right Section: Booking Box -->



            <h4 class="mt-4">Detailed Itinerary</h4>
            <div class="accordion" id="itineraryAccordion">
                <?php while ($itinerary = $result_itinerary->fetch_assoc()) { ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#day<?= $itinerary['day_number'] ?>">
                            Day
                            <?= $itinerary['day_number'] ?> -
                            <?= htmlspecialchars($itinerary['title']) ?>
                        </button>
                    </h2>
                    <div id="day<?= $itinerary['day_number'] ?>" class="accordion-collapse collapse"
                        data-bs-parent="#itineraryAccordion">
                        <div class="accordion-body">
                            <?= htmlspecialchars($itinerary['description']) ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>



            <!-- Inclusions & Exclusions -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card card-inclusions p-3">
                        <h5 class="text-success">✔ Inclusions</h5>
                        <ul>
                            <?php while ($inclusion = $result_inclusions->fetch_assoc()) { ?>
                            <li>
                                <?= htmlspecialchars($inclusion['description']) ?>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-exclusions p-3">
                        <h5 class="text-danger">✖ Exclusions</h5>
                        <ul>
                            <?php while ($exclusion = $result_exclusions->fetch_assoc()) { ?>
                            <li>
                                <?= htmlspecialchars($exclusion['description']) ?>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>


            <!-- Payment & Cancellation Policy -->
            <!-- <div class="row mt-4">
                <h4><i class="fas fa-wallet text-primary"></i> Payment & Cancellation Policy</h4>
                <div class="card p-3">
                    <h5 class="text-primary">Payment Terms</h5>
                    <ul>
                        <li>50% advance payment required at booking.</li>
                        <li>Remaining 50% before departure.</li>
                    </ul>
                    <h5 class="text-danger">Cancellation Policy</h5>
                    <ul>
                        <li>100% refund if canceled 15+ days before travel.</li>
                        <li>50% refund if canceled 7-14 days before travel.</li>
                        <li>No refund if canceled within 5 days of travel.</li>
                    </ul>
                </div>
            </div> -->

            <!-- Terms & Conditions -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h4><i class="fas fa-file-alt text-primary"></i> Terms & Conditions</h4>
                    <div class="card p-2">
                        <ul>
                            <?php
                $sql = "SELECT content FROM terms_conditions WHERE package_id = $package_id";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<li>" . htmlspecialchars($row['content']) . "</li>";
                    }
                } else {
                    echo "<li>No terms & conditions available for this package.</li>";
                }

                mysqli_close($conn);
                ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Company</h4>
                    <a class="btn btn-link" href="about.php">About Us</a>
                    <a class="btn btn-link" href="contact.php">Contact Us</a>
                    <a class="btn btn-link" href="privacy_policy.php">Privacy Policy</a>
                    <a class="btn btn-link" href="terms&conditions.php">Terms & Condition</a>
                    <a class="btn btn-link" href="FAQS.php">FAQs & Help</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Contact</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Gala Argos, 5th Floor Ellis Bridge ,
                        Ahmedabad , Gujarat</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+91 7997576990</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@travelcrafter.com</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Gallery</h4>
                    <div class="row g-2 pt-2">
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/package-1.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/package-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/package-3.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/package-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/package-3.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/package-1.jpg" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Newsletter</h4>
                    <p>Get updates about new packages, travel tips, and exclusive offers right to your inbox!</p>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control border-primary w-100 py-3 ps-4 pe-5" id="newsletterEmail"
                            type="email" placeholder="Your email">
                        <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2"
                            onclick="subscribe()">Subscribe</button>

                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">TravelCrafter</a>, All Rights Reserved.
                        Designed & Developed by <a class="border-bottom" href="#">Himanshu</a>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="index.php">Home</a>
                            <a href="privacy_policy.php">Privacy</a>
                            <a href="faqs.php">Help</a>
                            <a href="terms&conditions.php">Terms</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Booking Modal -->
    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Book Your Trip</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <label><strong>Select Travel Date</strong></label>
        <input type="date" id="travelDate" class="form-control mb-3" required onchange="checkAvailability()">

        <h6><strong>Passengers</strong></h6>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <label class="me-2">Adults:</label>
          <button class="count-btn" onclick="changeCount('adult', -1)">-</button>
          <span id="adultCount">1</span>
          <button class="count-btn" onclick="changeCount('adult', 1)">+</button>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <label class="me-2">Children:</label>
          <button class="count-btn" onclick="changeCount('child', -1)">-</button>
          <span id="childCount">0</span>
          <button class="count-btn" onclick="changeCount('child', 1)">+</button>
        </div>

        <h5>Total Amount: ₹<span id="totalAmount">0</span></h5>

        <p id="seatInfo" class="text-danger"></p>
        <p id="combinationMessage" class="text-danger"></p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="proceedToPayment()" disabled id="proceedBtn">
          Proceed to Payment
        </button>
      </div>
    </div>
  </div>
</div>





    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
<script>
  $(document).ready(function () {
    $("#bookingModal").on("show.bs.modal", function () {
      adults = 1;
      children = 0;
      document.getElementById("adultCount").innerText = adults;
      document.getElementById("childCount").innerText = children;
      updateTotalAmount();
      document.getElementById("proceedBtn").disabled = true;
      document.getElementById("seatInfo").innerText = "";
    });
  });

  const packageId = <?= $package['id'] ?>;
  const adultPrice = <?= $package['original_price'] ?? 0 ?>;
  const childPrice = adultPrice * 0.8;
  let adults = 1, children = 0, availableSeats = 0;

  function changeCount(type, change) {
    let totalPassengers = adults + children + change;
    if (totalPassengers > availableSeats) {
      alert("Not enough seats available.");
      return;
    }

    if (type === "adult") {
      adults = Math.max(1, adults + change);
      document.getElementById("adultCount").innerText = adults;
    } else if (type === "child") {
      if (children + change > adults * 2) {
        document.getElementById("combinationMessage").innerText = "Each adult can bring a maximum of 2 children!";
        return;
      } else {
        children = Math.max(0, children + change);
      }
      document.getElementById("childCount").innerText = children;
    }

    validateCombination();
    updateTotalAmount();
  }

  function validateCombination() {
    let message = "";
    let proceedBtn = document.getElementById("proceedBtn");

    if (adults < 1) {
      message = "At least 1 adult is required.";
    } else if (children > adults * 2) {
      message = "Each adult can bring up to 2 children.";
      proceedBtn.disabled = true;
    } else if (adults + children > availableSeats) {
      message = "Not enough seats available.";
      proceedBtn.disabled = true;
    } else {
      proceedBtn.disabled = false;
    }

    document.getElementById("combinationMessage").innerText = message;
  }

  function updateTotalAmount() {
    let total = (adults * adultPrice) + (children * childPrice);
    document.getElementById("totalAmount").innerText = Math.round(total);
  }

  function checkAvailability() {
    let travelDate = document.getElementById("travelDate").value;
    if (!travelDate) {
      $("#seatInfo").text("Please select a travel date.");
      return;
    }

    // Check if date is expired
    let selectedDate = new Date(travelDate);
    let today = new Date();
    today.setHours(0, 0, 0, 0);

    if (selectedDate < today) {
      $("#seatInfo").text("The selected travel date has already expired.");
      document.getElementById("proceedBtn").disabled = true;
      return;
    }

    // If valid date, fetch seat availability
    $.ajax({
      url: "fetch_seats.php",
      type: "GET",
      data: { package_id: packageId, travel_date: travelDate },
      success: function (response) {
        let data = JSON.parse(response);
        if (data.status === "success") {
          availableSeats = data.available_seats;
          $("#seatInfo").text(`Seats available: ${availableSeats}`);
          if (availableSeats <= 0) {
            document.getElementById("proceedBtn").disabled = true;
            alert("No seats available for this date.");
          } else {
            validateCombination();
          }
        } else {
          $("#seatInfo").text("No seats available for this date.");
          document.getElementById("proceedBtn").disabled = true;
        }
      }
    });
  }

  function proceedToPayment() {
    let travelDate = document.getElementById("travelDate").value;
    if (!travelDate) {
      alert("Please select a travel date!");
      return;
    }

    let selectedDate = new Date(travelDate);
    let today = new Date();
    today.setHours(0, 0, 0, 0);

    if (selectedDate < today) {
      alert("The selected travel date has already expired.");
      return;
    }

    if (availableSeats <= 0) {
      alert("No seats available for this date. Please select another date.");
      return;
    }

    let totalAmount = Math.round((adults * adultPrice) + (children * childPrice));
    window.location.href = `process_booking.php?package_id=${packageId}&package_name=<?= urlencode($package['name']) ?>&package_image=<?= $package_image ?>&travel_date=${travelDate}&adults=${adults}&children=${children}&total_amount=${totalAmount}`;
  }
</script>



    <script>
        function reinitializeScripts() {
            document.querySelectorAll(".btn-book").forEach(btn => {
                btn.addEventListener("click", function () {
                    let bookingModal = new bootstrap.Modal(document.getElementById("bookingModal"));
                    bookingModal.show();
                });
            });
        }

        // Call this function after explore page content loads
        // window.location.href = `process_booking.php?package_id=<?= $package['id'] ?>&package_name=<?= urlencode($package['name']) ?>&package_image=${packageImage}&travel_date=${travelDate}&adults=${adults}&children=${children}&total_amount=${totalAmount}`;
    </script>
    <script>
        function subscribe() {
            let email = document.getElementById("newsletterEmail").value;
            if (email) {
                alert("Thanks for subscribing with: " + email);
                document.getElementById("newsletterEmail").value = "";
            } else {
                alert("Please enter your email!");
            }
        }
    </script>
</body>

</html>