<?php
session_start();
include 'database/dbconnect.php';


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

// Fetch destinations from database
$query =  "SELECT * FROM destinations WHERE display_type = 'card' LIMIT 4";
$result = mysqli_query($conn, $query);
$destinations = mysqli_fetch_all($result, MYSQLI_ASSOC);

// $query_carousel = "SELECT * FROM destinations WHERE display_type = 'carousel' LIMIT 4";
// $result_carousel = mysqli_query($conn, $query_carousel);

// Fetch destinations from the database
$query = "SELECT * FROM destinations WHERE display_type = 'grid' LIMIT 4"; 
$result = mysqli_query($conn, $query);
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
        .destination-container {
            display: flex;
            justify-content: start;
            gap: 20px;
            flex-wrap: wrap;

        }

        .destination-card {
            position: relative;
            width: 260px;
            height: 260px;
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
        }

        .destination-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }

        .destination-card:hover img {
            transform: scale(1.1);
        }

        .destination-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .destination-card:hover .destination-overlay {
            opacity: 1;
        }

        .trip-container {
            display: flex;
            flex-wrap: wrap;
            /* gap: 10px; */
            justify-content: space-around;
            padding: 20px;
        }

        .trip-card {
            width: 320px;
            background: white;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .trip-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
        }

        .trip-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 15px 15px 0 0;
        }

        .trip-details {
            padding: 15px;
            text-align: center;
        }

        .trip-details h5 {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .trip-details p {
            font-size: 1rem;
            color: #444;
            margin: 5px 0;
        }

        .trip-details span {
            font-weight: bold;
            color: #000;
        }

        .explore-link {
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            font-size: 1rem;
            margin-top: 10px;
        }

        .explore-link i {
            margin-left: 5px;
        }

        .grid-container {
            display: grid;
            gap: 15px;
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: 200px;
            padding: 50px;
            padding-top: 0;
        }

        .grid-item {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .grid-item:hover {
            transform: scale(1.02);
        }

        .grid-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 15px;
        }

        .info-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.3));
            padding: 15px;
            color: white;
            text-align: left;
            border-radius: 0 0 15px 15px;
        }

        .info-overlay h4 {
            font-size: 1.2rem;
            margin: 0;
        }

        .info-overlay p {
            font-size: 1rem;
            margin: 5px 0 0;
        }

        .info-overlay span {
            font-weight: bold;
            color: #FFD700;
        }

        /* Adjust sizes dynamically */
        .grid-item:nth-child(1) {
            grid-column: span 2;
            grid-row: span 2;
        }

        .grid-item:nth-child(2),
        .grid-item:nth-child(3),
        .grid-item:nth-child(4) {
            grid-column: span 1;
            grid-row: span 1;
        }

        .grid-item:nth-child(5) {
            grid-column: span 2;
            grid-row: span 1;
        }

        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .grid-item:nth-child(1),
            .grid-item:nth-child(5) {
                grid-column: span 2;
                grid-row: span 1;
            }
        }

        @media (max-width: 480px) {
            .grid-container {
                grid-template-columns: repeat(1, 1fr);
            }

            .grid-item {
                grid-column: span 1;
                grid-row: span 1;
            }
        }

        .info-overlay {
            background: rgba(0, 0, 0, 0.6);
            /* Dark semi-transparent background */
            color: #fff;
            /* White text */
            padding: 10px;
            text-align: center;
            position: absolute;
            bottom: 0;
            width: 100%;
            /* transition: background 0.3s ease-in-out; */
        }

        .info-overlay h4 {
            color: #fff;
            /* White text */
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        /* ========== Responsive for Tablet Screens (<= 768px) ========== */
@media (max-width: 768px) {
    .destination-container {
        justify-content: center;
    }

    .destination-card {
        width: 45%;
        height: 200px;
    }

    .trip-container {
        justify-content: center;
    }

    .trip-card {
        width: 80%;
    }

    .grid-container {
        padding: 20px;
        grid-template-columns: repeat(2, 1fr);
        grid-auto-rows: 180px;
    }
}

/* ========== Responsive for Mobile Screens (<= 480px) ========== */
@media (max-width: 480px) {
    .destination-card {
        width: 100%;
        height: 180px;
    }

    .trip-card {
        width: 100%;
    }

    .grid-container {
        grid-template-columns: 1fr;
        grid-auto-rows: 180px;
        padding: 10px;
    }

    .info-overlay h4 {
        font-size: 16px;
    }

    .destination-overlay {
        font-size: 14px;
        padding-bottom: 10px;
    }

    .explore-link {
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    .grid-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        padding: 10px;
    }

    .grid-item {
        height: auto;
        border-radius: 15px;
    }

    .grid-item img {
        width: 100%;
        height: auto;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        border-radius: 15px;
    }

    .info-overlay {
        position: static;
        background: rgba(0, 0, 0, 0.7);
        text-align: center;
        padding: 10px;
        border-radius: 0 0 15px 15px;
    }

    .info-overlay h4 {
        font-size: 16px;
    }
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
                <h1 class="text-primary m-0"><i class="fa fa-map-marker-alt me-3"></i>Travel Crafters</h1>
                <!-- <img src="img/logo.png" alt="Logo"> -->
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.php" class="nav-item nav-link active">Home</a>
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
    </div>
    </nav>
    </nav>

    <div class="container-fluid bg-primary py-5 mb-5 hero-header">
        <div class="container py-5">
            <div class="row justify-content-center py-5">
                <div class="col-lg-10 pt-lg-5 mt-lg-5 text-center">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">Enjoy Your Vacation With Us</h1>
                    <div class="position-relative w-75 mx-auto animated slideInDown">
                        <div class="py-3 ps-4 pe-5"></div>
                        <!-- <input class="form-control border-0 rounded-pill w-100 py-3 ps-4 pe-5" 
                           type="text" id="heroSearch" placeholder="Eg: Thailand"> -->
                        <!-- <input type="text" name="search" id="search" class="form-control border-0 rounded-pill w-100 py-3 ps-4 pe-5" placeholder="Search Your Package"> -->
                        <!-- 
                    <button type="button" id="heroSearchBtn" 
                            class="btn btn-primary rounded-pill py-2 px-4 position-absolute top-0 end-0 me-2" 
                            style="margin-top: 7px;">Search</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <!-- Navbar & Hero End -->







    <!-- Destination Start -->
    <!-- <div class="container-xxl py-5 destination">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Destination</h6>
                <h1 class="mb-5">Popular Destination</h1>
            </div>
            <div class="row g-3">
                <div class="col-lg-7 col-md-6">
                    <div class="row g-3">
                        <div class="col-lg-12 col-md-12 wow zoomIn" data-wow-delay="0.1s">
                            <a class="position-relative d-block overflow-hidden" href="">
                                <img class="img-fluid" src="img/rajasthan.jpg" alt="">
                                <div class="bg-white text-danger fw-bold position-absolute top-0 start-0 m-3 py-1 px-2">30% OFF</div>
                                <div class="bg-white text-primary fw-bold position-absolute bottom-0 end-0 m-3 py-1 px-2">Rajasthan</div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-12 wow zoomIn" data-wow-delay="0.3s">
                            <a class="position-relative d-block overflow-hidden" href="">
                                <img class="img-fluid" src="img/destination-2.jpg" alt="">
                                <div class="bg-white text-danger fw-bold position-absolute top-0 start-0 m-3 py-1 px-2">25% OFF</div>
                                <div class="bg-white text-primary fw-bold position-absolute bottom-0 end-0 m-3 py-1 px-2">Malaysia</div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-12 wow zoomIn" data-wow-delay="0.5s">
                            <a class="position-relative d-block overflow-hidden" href="">
                                <img class="img-fluid" src="img/destination-3.jpg" alt="">
                                <div class="bg-white text-danger fw-bold position-absolute top-0 start-0 m-3 py-1 px-2">35% OFF</div>
                                <div class="bg-white text-primary fw-bold position-absolute bottom-0 end-0 m-3 py-1 px-2">Australia</div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6 wow zoomIn" data-wow-delay="0.7s" style="min-height: 350px;">
                    <a class="position-relative d-block h-100 overflow-hidden" href="">
                        <img class="img-fluid position-absolute w-100 h-100" src="img/Phuket.jpg" alt="" style="object-fit: cover;">
                        <div class="bg-white text-danger fw-bold position-absolute top-0 start-0 m-3 py-1 px-2">20% OFF</div>
                        <div class="bg-white text-primary fw-bold position-absolute bottom-0 end-0 m-3 py-1 px-2">Indonesia</div>
                    </a>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Top Travel Destinations -->


    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Destinations</h6>
                <h1 class="mb-5">Top Travel Destinations</h1>
            </div>
            <div class="destination-container">
                <?php foreach ($destinations as $destination) { ?>
                <div class="destination-card wow zoomIn" data-wow-delay="0.1s">
                    <a href="Tour_packages.php?destination_id=<?php echo $destination['id']; ?>">
                        <img src="uploads/<?php echo $destination['image']; ?>"
                            alt="<?php echo $destination['name']; ?>">
                        <div class="destination-overlay">
                            <?php echo $destination['name']; ?>
                        </div>
                    </a>
                </div>
                <?php } ?>
            </div>

        </div>
    </div>

    <!-- Top Travel Destinations End -->


    <!-- Awesome Packages -->


    <div class="heading">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title bg-white text-center text-primary px-3">Packages</h6>
            <h1 class="mb-5">Awesome Packages</h1>
        </div>
    </div>

    <div class="trip-container">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="trip-card  wow zoomIn" data-wow-delay="0.1s">
            <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <div class="trip-details">
                <h5>
                    <?php echo $row['name']; ?>
                </h5>
                <p>From </p>
                <a href="Tour_packages.php?destination_id=<?php echo $row['id']; ?>" class="explore-link">
                    Explore <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- Awesome Packages End  -->
    

    <!-- Seasonal Journeys -->


    <?php
    include 'database/dbconnect.php';
    
    // Fetch destinations from the database
    $query = "SELECT * FROM destinations WHERE display_type = 'image_grid' LIMIT 5"; 
    $result = mysqli_query($conn, $query);
    ?>
    <div class="heading2" style="padding-top: 20px;">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title bg-white text-center text-primary px-3">Deals You Can't Miss</h6>
            <h1 class="mb-5">Seasonal Journeys</h1>
        </div>
    </div>
    <div class="grid-container">

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="grid-item wow zoomIn" data-wow-delay="0.1s">
            <a href="Tour_packages.php?destination_id=<?php echo $row['id']; ?>">
                <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            </a>
            <div class="info-overlay">
                <h4>
                    <?php echo $row['name']; ?>
                </h4>
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- Seasonal Journeys End  -->

    <!-- <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="booking p-5">
                <div class="row g-5 align-items-center">
                    <div class="col-md-6 text-white">
                        <h6 class="text-white text-uppercase">Booking</h6>
                        <h1 class="text-white mb-4">Online Booking</h1>
                        <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit.</p>
                        <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet</p>
                        <a class="btn btn-outline-light py-3 px-5 mt-2" href="">Read More</a>
                    </div>
                    <div class="col-md-6">
                        <h1 class="text-white mb-4">Book A Tour</h1>
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-transparent" id="name" placeholder="Your Name">
                                        <label for="name">Your Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control bg-transparent" id="email" placeholder="Your Email">
                                        <label for="email">Your Email</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating date" id="date3" data-target-input="nearest">
                                        <input type="text" class="form-control bg-transparent datetimepicker-input" id="datetime" placeholder="Date & Time" data-target="#date3" data-toggle="datetimepicker" />
                                        <label for="datetime">Date & Time</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select bg-transparent" id="select1">
                                            <option value="1">Destination 1</option>
                                            <option value="2">Destination 2</option>
                                            <option value="3">Destination 3</option>
                                        </select>
                                        <label for="select1">Destination</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control bg-transparent" placeholder="Special Request" id="message" style="height: 100px"></textarea>
                                        <label for="message">Special Request</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-outline-light w-100 py-3" type="submit">Book Now</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> -->




    <!-- Booking Start -->


    <!-- Process Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center pb-4 wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Process</h6>
                <h1 class="mb-5">3 Easy Steps</h1>
            </div>
            <div class="row gy-5 gx-4 justify-content-center">
                <div class="col-lg-4 col-sm-6 text-center pt-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="position-relative border border-primary pt-5 px-2">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary rounded-circle position-absolute top-0 start-50 translate-middle shadow"
                            style="width: 100px; height: 100px;">
                            <i class="fa fa-globe fa-3x text-white"></i>
                        </div>
                        <h5 class="mt-4">Choose A Destination</h5>
                        <hr class="w-25 mx-auto bg-primary mb-1">
                        <hr class="w-50 mx-auto bg-primary mt-0">
                        <p class="mb-0">Explore a wide range of breathtaking destinations across the globe. Whether you
                            crave mountains, beaches, or cultural cities, simply select your favorite spot and let the
                            journey begin.</p>

                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 text-center pt-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="position-relative border border-primary pt-5 pb-4 px-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary rounded-circle position-absolute top-0 start-50 translate-middle shadow"
                            style="width: 100px; height: 100px;">
                            <i class="fa fa-dollar-sign fa-3x text-white"></i>
                        </div>
                        <h5 class="mt-4">Pay Online</h5>
                        <hr class="w-25 mx-auto bg-primary mb-1">
                        <hr class="w-50 mx-auto bg-primary mt-0">
                        <p class="mb-0">Book your dream trip in just a few clicks. Our secure online payment system
                            ensures a hassle-free booking experience with multiple payment options available.</p>

                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 text-center pt-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="position-relative border border-primary pt-5 pb-4 px-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary rounded-circle position-absolute top-0 start-50 translate-middle shadow"
                            style="width: 100px; height: 100px;">
                            <i class="fa fa-plane fa-3x text-white"></i>
                        </div>
                        <h5 class="mt-4">Fly Today</h5>
                        <hr class="w-25 mx-auto bg-primary mb-1">
                        <hr class="w-50 mx-auto bg-primary mt-0">
                        <p class="mb-0">Get ready to pack your bags! After booking, you'll receive all travel details.
                            Head to the airport and let the adventure begin — it's that simple.</p>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Process Start -->





    <!-- Testimonial Start -->
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="text-center">
                <h6 class="section-title bg-white text-center text-primary px-3">Testimonial</h6>
                <h1 class="mb-5">Our Clients Say!!!</h1>
            </div>
            <div class="owl-carousel testimonial-carousel position-relative">
                <div class="testimonial-item bg-white text-center border p-4">
                    <img class="bg-white rounded-circle shadow p-1 mx-auto mb-3" src="img/testimonial-1.jpg"
                        style="width: 80px; height: 80px;">

                    <h5 class="mb-0">Emily Watson</h5>
                    <p>London, UK</p>
                    <p class="mb-0">I loved how easy it was to find the perfect package. Customer support was super
                        helpful, and I had a wonderful time in Bali. Will use again!</p>

                </div>
                <div class="testimonial-item bg-white text-center border p-4">
                    <img class="bg-white rounded-circle shadow p-1 mx-auto mb-3" src="img/testimonial-2.jpg"
                        style="width: 80px; height: 80px;">
                    <h5 class="mb-0">Aarav Sharma</h5>
                    <p>Mumbai, India</p>
                    <p class="mb-0">Booking a vacation through this platform was the best decision I made. The process
                        was smooth, and everything was perfectly planned. Highly recommended!</p>

                </div>
                <div class="testimonial-item bg-white text-center border p-4">
                    <img class="bg-white rounded-circle shadow p-1 mx-auto mb-3" src="img/testimonial-3.jpg"
                        style="width: 80px; height: 80px;">
                    <h5 class="mb-0">Ravi Patel</h5>
                    <p>Ahmedabad, India</p>
                    <p class="mb-0">Amazing deals and great service. I planned a family trip to Dubai, and everything
                        from flights to stay was taken care of professionally.</p>

                </div>
                <div class="testimonial-item bg-white text-center border p-4">
                    <img class="bg-white rounded-circle shadow p-1 mx-auto mb-3" src="img/testimonial-4.jpg"
                        style="width: 80px; height: 80px;">
                    <h5 class="mb-0">Sophia Lee</h5>
                    <p>Singapore</p>
                    <p class="mb-0">A smooth and pleasant experience from start to finish. The website was
                        user-friendly, and the entire process took just minutes. Loved it!</p>

                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->


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
        document.getElementById("heroSearchBtn").addEventListener("click", function () {
            let searchQuery = document.getElementById("heroSearch").value.trim();
            if (searchQuery !== "") {
                // Redirect to the package page with the search query
                window.location.href = "show_packages.php?search=" + encodeURIComponent(searchQuery);
            }
        });

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