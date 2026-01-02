<?php
include '../database/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $package_id = $_POST['package_id'];
    $available_seats = $_POST['available_seats'];
    $travel_date = $_POST['travel_date'];

    // Update query
    $query = "UPDATE package_availability 
              SET package_id='$package_id', available_seats='$available_seats', travel_date='$travel_date' 
              WHERE id=$id";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Record updated successfully!'); window.location.href='package_availabilty.php';</script>";
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
