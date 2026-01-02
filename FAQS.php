
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
} ?>
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
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

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
         .faq-container {
      max-width: 800px;
      margin: 60px auto;
      background: #fff;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .faq-container h2 {
      text-align: center;
      font-size: 32px;
      margin-bottom: 30px;
      color: #333;
    }

    .faq-item {
      border-bottom: 1px solid #ddd;
      padding: 20px 0;
    }

    .faq-question {
      font-size: 18px;
      font-weight: 600;
      color: #007BFF;
      cursor: pointer;
      position: relative;
    }

    .faq-question::after {
      content: '+';
      position: absolute;
      right: 0;
      transition: transform 0.3s;
    }

    .faq-item.active .faq-question::after {
      content: '-';
      transform: rotate(180deg);
    }

    .faq-answer {
      max-height: 0;
      overflow: hidden;
      font-size: 16px;
      color: #444;
      margin-top: 10px;
      transition: max-height 0.4s ease, opacity 0.4s ease;
      opacity: 0;
    }

    .faq-item.active .faq-answer {
      max-height: 200px;
      opacity: 1;
    }

    @media (max-width: 600px) {
      .faq-container {
        padding: 20px;
      }
    }
    </style>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
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
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>Gala Argos, 5th Floor Ellis Bridge , Ahmedabad , Gujarat</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+91 7997576990</small>
                    <small class="text-light"><i class="fa fa-envelope-open me-2"></i>info@travelcrafter.com</small>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-twitter fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-facebook-f fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-linkedin-in fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-instagram fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle" href=""><i class="fab fa-youtube fw-normal"></i></a>
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
                    <a href="about.php" class="nav-item nav-link ">About</a>
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
        <a href="#" class="nav-link dropdown-toggle btn btn-primary rounded-pill py-2 px-4" id="userDropdown" data-bs-toggle="dropdown">
            <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($username ?? 'User'); ?>
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

        <div class="container-fluid bg-primary py-5 mb-5 hero-header">
            <div class="container py-5">
                <div class="row justify-content-center py-5">
                    <div class="col-lg-10 pt-lg-5 mt-lg-5 text-center">
                        <h1 class="display-3 text-white animated slideInDown">FAQs & Help</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                                <li class="breadcrumb-item text-white active" aria-current="page">FAQs & Help</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar & Hero End -->

<!-- Bootstrap 5 Accordion for FAQs -->
<div class="faq-container">
  <h2>Top 10 Frequently Asked Questions</h2>

  <div class="faq-item">
    <div class="faq-question">1. How do I book a tour or travel package on your website?</div>
    <div class="faq-answer">You can book by selecting your desired destination or package, choosing the dates, filling out the booking form, and making the payment online.</div>
  </div>

  <div class="faq-item">
    <div class="faq-question">2. Can I customize my travel itinerary?</div>
    <div class="faq-answer">Yes, we offer customizable packages. You can contact our travel experts to tailor the itinerary according to your preferences.</div>
  </div>

  <div class="faq-item">
    <div class="faq-question">3. What payment methods do you accept?</div>
    <div class="faq-answer">We accept all major credit/debit cards, UPI, net banking, and wallets. You can also choose EMI options for select packages.</div>
  </div>

  <div class="faq-item">
    <div class="faq-question">4. Are flight/train/bus tickets included in the package price?</div>
    <div class="faq-answer">It depends on the package. Details about inclusions are clearly mentioned in each package description.</div>
  </div>

  <div class="faq-item">
    <div class="faq-question">5. Can I cancel or reschedule my booking?</div>
    <div class="faq-answer">Yes, you can cancel or reschedule your trip. Cancellation and rescheduling charges may apply as per our policy.</div>
  </div>

  <div class="faq-item">
    <div class="faq-question">6. Will I receive a confirmation after booking?</div>
    <div class="faq-answer">Absolutely! You’ll get a booking confirmation via email and SMS with all your travel details.</div>
  </div>

  <div class="faq-item">
    <div class="faq-question">7. Do you offer travel insurance?</div>
    <div class="faq-answer">Yes, we offer optional travel insurance at an additional cost. It covers medical emergencies, cancellations, and lost baggage.</div>
  </div>

  <div class="faq-item">
    <div class="faq-question">8. Is there any discount for group bookings?</div>
    <div class="faq-answer">Yes! We offer attractive discounts for group bookings. Please reach out to our support team for more info.</div>
  </div>

  <div class="faq-item">
    <div class="faq-question">9. Are your packages suitable for family travel?</div>
    <div class="faq-answer">Definitely! We have specially curated packages for families, including kid-friendly activities and accommodation.</div>
  </div>

  <div class="faq-item">
    <div class="faq-question">10. How can I contact customer support during the trip?</div>
    <div class="faq-answer">Our support team is available 24x7 via phone, email, and WhatsApp for any assistance you need during your journey.</div>
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
  const faqItems = document.querySelectorAll(".faq-item");

  faqItems.forEach(item => {
    item.addEventListener("click", () => {
      // Close all
      faqItems.forEach(i => i.classList.remove("active"));
      // Open current
      item.classList.add("active");
    });
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