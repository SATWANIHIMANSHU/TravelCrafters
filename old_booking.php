<?php
session_start();

// Ensure session data exists
if (!isset($_SESSION['package_id'])) {
    header("Location: package-details.php");
    exit();
}

// Retrieve package details from session
$packageName = $_SESSION['package_name'] ?? "Unknown Package";
$packageImage = $_SESSION['package_image'] ?? "default.jpg"; // Ensure image path is stored
$travelDate = $_SESSION['travel_date'] ?? "Not Selected";
$adults = $_SESSION['adults'] ?? 1;
$children = $_SESSION['children'] ?? 0;
$totalAmount = $_SESSION['total_amount'] ?? 0;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>

    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS for Styling -->
    <style>
        .checkout-container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .order-summary img {
            border-radius: 8px;
        }
        .form-control[readonly] {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="checkout-container">
            <div class="row g-4">
                <!-- Left Side - User Details -->
                <div class="col-md-7">
                    <h2 class="mb-3">Enter your personal details</h2>
                    <div class="d-flex align-items-center text-success mb-3">
                        <i class="fas fa-lock me-2"></i>
                        <span>Checkout is fast and secure</span>
                    </div>

                    <form>
                        <div class="mb-3">
                            <label for="full-name" class="form-label fw-bold">Full name *</label>
                            <input type="text" id="full-name" class="form-control" >
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email *</label>
                            <input type="email" id="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">Mobile phone number *</label>
                            <input type="text" id="phone" class="form-control" >
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" checked id="offers">
                            <label class="form-check-label" for="offers">
                                Send me discounts and other offers related to my trip
                            </label>
                        </div>

                        <p class="text-muted small">
                            You'll receive occasional promotional emails about your upcoming trip, as well as other travel inspiration. You can opt out at any time.
                        </p>
                    </form>
                </div>

                <!-- Right Side - Order Summary -->
                <div class="col-md-5">
                    <div class="order-summary p-3 border rounded shadow-sm bg-white">
                        <h3 class="mb-3">Order Summary</h3>
                        <div class="d-flex align-items-center mb-3">
                            <img src="uploads/<?= htmlspecialchars($packageImage) ?>" class="img-fluid me-3" width="80" height="80" alt="Package Image">
                            <div>
                                <h5 class="fw-bold"><?= htmlspecialchars($packageName) ?></h5>
                                <span class="badge bg-primary">Top Rated</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1"><i class="fas fa-calendar-alt me-2"></i> <?= htmlspecialchars($travelDate) ?></p>
                            <p class="mb-1"><i class="fas fa-user-friends me-2"></i> <?= $adults ?> Adults, <?= $children ?> Children</p>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-danger">₹<?= number_format($totalAmount) ?></span>
                        </div>
                        <p class="text-muted small">All taxes and fees included</p>

                        <button class="btn btn-primary w-100 mt-3">Proceed for Payment</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (for any modal or dropdown functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
