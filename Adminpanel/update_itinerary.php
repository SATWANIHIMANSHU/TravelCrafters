<?php
include '../database/dbconnect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $package_id = $_POST['package_id'];
    $day_number = $_POST['day_number'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Update query
    $query = "UPDATE itinerary 
              SET package_id = '$package_id', 
                  day_number = '$day_number', 
                  title = '$title', 
                  description = '$description' 
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Itinerary updated successfully!'); window.location.href='Package_itinerary.php';</script>";
    } else {
        echo "Error updating itinerary: " . mysqli_error($conn);
    }
}
?>
