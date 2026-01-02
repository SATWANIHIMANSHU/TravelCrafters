<?php
include 'database/dbconnect.php';

$package_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($package_id <= 0) {
    die("Invalid package ID."); // You can also redirect: header("Location: error.php");
}

// Fetch package details
$query = "SELECT p.*, d.name as destination_name FROM packages p INNER JOIN destinations d ON p.destination_id = d.id WHERE p.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $package_id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();
$stmt->close();

if (!$package) {
    die("Package not found."); // You can redirect: header("Location: error.php");
}

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
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Tourist - Travel Agency</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="css/style.css" rel="stylesheet"> -->
     <style>
        /* General Styles */
body {
    font-family: 'Nunito', sans-serif;
    background-color: #f8f9fa;
}

.badge-custom {
    font-size: 14px;
    margin-right: 5px;
}

.card-inclusions {
    background: #e9f9e9;
    border-left: 5px solid #28a745;
}

.card-exclusions {
    background: #fbe9e9;
    border-left: 5px solid #dc3545;
}

.booking-box {
    background: #eaf4ff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 350px;
    margin: auto;
    text-align: center;
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
    transition: background 0.3s;
}

.btn-book:hover {
    background: #e66000;
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
    transition: background 0.3s, color 0.3s;
}

.btn-enquire:hover {
    background: #007bff;
    color: white;
}

/* Carousel */
.carousel-inner img {
    width: 100%;
    height: 500px;
    object-fit: cover;
}

/* Itinerary */
.accordion-button {
    font-weight: bold;
    color: #007bff;
}

.accordion-button:not(.collapsed) {
    background: #eaf4ff;
    color: #0056b3;
}

/* Footer */
.footer {
    background: #212529;
    padding: 40px 0;
    color: white;
}

.footer a {
    color: #ddd;
}

.footer a:hover {
    color: #007bff;
}

     </style>
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <!-- Image Carousel -->
                <!-- Image Carousel -->
 <!-- Image Carousel -->
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
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>

                <!-- Package Details -->
                <div class="mt-4">
                    <h2><?= htmlspecialchars($package['name'] ?? 'Package Name') ?></h2>
                    <span class="badge bg-secondary badge-custom">
                        <?= htmlspecialchars($package['duration'] ?? '0') ?> Nights / <?= ($package['duration'] ?? 0) + 1 ?> Days
                    </span>
                    <?php if (!empty($package['is_customizable'])) { ?>
                        <span class="badge bg-primary badge-custom">Fully Customisable</span>
                    <?php } ?>
                    <?php if (!empty($package['is_expert_choice'])) { ?>
                        <span class="badge bg-success badge-custom">Expert Choice</span>
                    <?php } ?>
                    <h3 class="mt-3">Starting From ₹<?= htmlspecialchars($package['discount_price'] ?? '0') ?>
                        <span class="text-decoration-line-through text-muted">₹<?= htmlspecialchars($package['old_price'] ?? '0') ?></span>
                        <span class="badge bg-success">
                            <?php 
                            if (!empty($package['old_price']) && !empty($package['discount_price'])) {
                                $discount = round((($package['old_price'] - $package['discount_price']) / $package['old_price']) * 100);
                                echo $discount . "% Off";
                            }
                            ?>
                        </span>
                    </h3>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Booking Box -->
                <div class="booking-box">
                    <p class="text-muted mb-1">Starting from</p>
                    <div class="price-box">
                        <span class="old-price">₹<?= htmlspecialchars($package['old_price'] ?? '0') ?></span>
                        <br>
                        <span class="new-price">₹<?= htmlspecialchars($package['discount_price'] ?? '0') ?></span> <span class="per-person">Per Person</span>
                    </div>
                    <button class="btn btn-book">BOOK NOW</button>
                    <button class="btn btn-enquire">ENQUIRE NOW</button>
                </div>
            </div>
        </div>

        <!-- Inclusions & Exclusions -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card card-inclusions p-3">
                    <h5 class="text-success">✔ Inclusions</h5>
                    <ul>
                        <?php while ($inclusion = $result_inclusions->fetch_assoc()) { ?>
                            <li><?= htmlspecialchars($inclusion['description']) ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-exclusions p-3">
                    <h5 class="text-danger">✖ Exclusions</h5>
                    <ul>
                        <?php while ($exclusion = $result_exclusions->fetch_assoc()) { ?>
                            <li><?= htmlspecialchars($exclusion['description']) ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
