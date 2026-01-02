<?php
include 'database/dbconnect.php';

$package_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($package_id <= 0) {
    die("Invalid package ID.");
}

// Fetch package details including latitude & longitude
$query = "SELECT p.*, d.name as destination_name, d.latitude, d.longitude 
          FROM packages p 
          INNER JOIN destinations d ON p.destination_id = d.id 
          WHERE p.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $package_id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();
$stmt->close();

if (!$package) {
    die("Package not found.");
}

// Store latitude & longitude
$latitude = $package['latitude'] ?? '0';
$longitude = $package['longitude'] ?? '0';
$destination = $package['destination_name'] ?? 'Unknown';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Package Details</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2><?= htmlspecialchars($package['name'] ?? 'Package Name') ?></h2>
        <h3>Package Location: <?= htmlspecialchars($destination) ?></h3>
        <div id="map" style="width: 100%; height: 400px; border-radius: 10px;"></div>
    </div>

    <script>
        function initMap() {
            var location = { lat: <?= $latitude ?>, lng: <?= $longitude ?> };
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: location
            });
            var marker = new google.maps.Marker({
                position: location,
                map: map,
                title: "<?= htmlspecialchars($destination) ?>"
            });
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script>
</body>
</html>
