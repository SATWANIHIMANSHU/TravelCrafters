<?php
session_start();

// Ensure all required parameters are passed
if (!isset($_GET['package_id'], $_GET['package_name'], $_GET['package_image'], $_GET['travel_date'], $_GET['adults'], $_GET['children'], $_GET['total_amount'])) {
    die("Invalid request! Missing required parameters.");
}

// Store package details in session
$_SESSION['package_id'] = $_GET['package_id'];
$_SESSION['package_name'] = $_GET['package_name'];
$_SESSION['package_image'] = $_GET['package_image'];
$_SESSION['travel_date'] = $_GET['travel_date'];
$_SESSION['adults'] = $_GET['adults'];
$_SESSION['children'] = $_GET['children'];
$_SESSION['total_amount'] = $_GET['total_amount'];

// Include DB connection
require 'database/dbconnect.php';

// Extract variables
$package_id = (int)$_SESSION['package_id'];
$adults = (int)$_SESSION['adults'];
$children = (int)$_SESSION['children'];
$travel_date = $_SESSION['travel_date'];
$total_passengers = $adults + $children;

// Step 1: Check if seats are available for the selected date
$query = "SELECT available_seats FROM package_availability WHERE package_id = ? AND travel_date = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $package_id, $travel_date);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && $row['available_seats'] >= $total_passengers) {
    // Step 2: Deduct the seats
    $updateQuery = "UPDATE package_availability SET available_seats = available_seats - ? WHERE package_id = ? AND travel_date = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("iis", $total_passengers, $package_id, $travel_date);

    if ($updateStmt->execute()) {
        // Step 3: Redirect to booking page
        header("Location: Booking.php");
        exit();
    } else {
        die("❌ Error updating available seats.");
    }
} else {
    die("❌ Not enough seats available for the selected date!");
}
?>
