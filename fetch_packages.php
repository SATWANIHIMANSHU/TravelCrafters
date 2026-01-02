<?php
include 'database/dbconnect.php';

$searchQuery = $_POST['search'] ?? '';
$priceFilter = $_POST['price_range'] ?? '';
$nightsFilter = $_POST['nights'] ?? '';

// Construct SQL Query
$sql = "SELECT p.id, p.name, p.duration, p.original_price, pi.image_url 
        FROM packages p 
        LEFT JOIN package_images pi ON p.id = pi.package_id 
        WHERE 1";

if (!empty($searchQuery)) {
    $searchQuery = mysqli_real_escape_string($conn, $searchQuery);
    $sql .= " AND p.name LIKE '%$searchQuery%'";
}

if (!empty($priceFilter)) {
    $priceRange = explode("-", $priceFilter);
    if (count($priceRange) == 2) {
        $minPrice = (int)$priceRange[0];
        $maxPrice = (int)$priceRange[1];
        $sql .= " AND p.original_price BETWEEN $minPrice AND $maxPrice";
    }
}

if (!empty($nightsFilter)) {
    $nightsFilter = (int)$nightsFilter;
    $sql .= " AND p.duration = $nightsFilter";
}

$sql .= " GROUP BY p.id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

// Generate Package Cards
while ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="uploads/' . ($row['image_url'] ?: 'default.jpg') . '" 
                             class="img-fluid rounded package-img" alt="' . htmlspecialchars($row['name']) . '">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>
                            <p class="card-text">
                                <span class="badge bg-warning">' . $row['duration'] . ' Nights</span>
                            </p>
                            <p class="text-muted">From ₹<strong>' . number_format($row['original_price'], 2) . '</strong></p>
                            <a href="Package.php?id=' . $row['id'] . '" class="btn btn-primary rounded-pill">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
          </div>';
}
?>
