<?php
include '../database/dbconnect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Delete query
    $query = "DELETE FROM itinerary WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Itinerary deleted successfully!'); window.location.href='Package_itinerary.php';</script>";
    } else {
        echo "Error deleting itinerary: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='Package_itinerary.php';</script>";
}
?>
