<?php
// booking_details.php

$connection = new mysqli("localhost", "root", "", "travelcrafters");
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$id = $_GET['id'];

// Fetch booking
$booking_sql = "SELECT * FROM bookings WHERE id='$id'";
$booking_result = $connection->query($booking_sql);

if ($booking_result->num_rows > 0) {
    $booking = $booking_result->fetch_assoc();

    // Fetch package image and data
    $package_id = $booking['package_id'];
    $package_sql = "SELECT pi.image_url, p.name, p.original_price, p.duration 
                    FROM package_images pi 
                    JOIN packages p ON p.id = pi.package_id 
                    WHERE pi.package_id = '$package_id' LIMIT 1";
    $package_result = $connection->query($package_sql);
    $package = ($package_result->num_rows > 0) ? $package_result->fetch_assoc() : null;
} else {
    echo "No booking found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Details - TravelCrafters</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeIn 0.6s ease;
        }
        .card img {
            max-height: 300px;
            object-fit: cover;
        }
        .card-title {
            font-size: 1.6rem;
            font-weight: 600;
        }
        .info-label {
            font-weight: 500;
            color: #333;
        }
        .info-value {
            color: #555;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <?php if ($package && !empty($package['image_url'])): ?>
                    <img src="uploads/<?php echo $package['image_url']; ?>" class="card-img-top" alt="Package Image">
                <?php else: ?>
                    <img src="uploads/default-package.jpg" class="card-img-top" alt="Default Image">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title mb-4 text-primary">Booking Details</h5>
                    <div class="row g-3">
                        <div class="col-sm-6"><span class="info-label">Name:</span> <div class="info-value"><?php echo htmlspecialchars($booking['user_name']); ?></div></div>
                        <div class="col-sm-6"><span class="info-label">Email:</span> <div class="info-value"><?php echo htmlspecialchars($booking['email']); ?></div></div>
                        <div class="col-sm-6"><span class="info-label">Phone:</span> <div class="info-value"><?php echo htmlspecialchars($booking['phone']); ?></div></div>
                        <div class="col-sm-6"><span class="info-label">Booking Date:</span> <div class="info-value"><?php echo date('d M Y', strtotime($booking['created_at'])); ?></div></div>
                        <div class="col-sm-6"><span class="info-label">Status:</span> <div class="info-value"><?php echo ucfirst($booking['status']); ?></div></div>
                        <div class="col-sm-6"><span class="info-label">Total Amount:</span> <div class="info-value">₹<?php echo htmlspecialchars($booking['total_amount']); ?></div></div>
                        <?php if ($package): ?>
                            <div class="col-sm-6"><span class="info-label">Destination:</span> <div class="info-value"><?php echo htmlspecialchars($package['name']); ?></div></div>
                            <div class="col-sm-6"><span class="info-label">Package Price:</span> <div class="info-value">₹<?php echo htmlspecialchars($package['original_price']); ?></div></div>
                            <div class="col-sm-6"><span class="info-label">Duration:</span> <div class="info-value"><?php echo htmlspecialchars($package['duration']); ?> days</div></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
