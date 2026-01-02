<?php
session_start();

include 'dbconnect.php'; // Ensure this file connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $rating = intval($_POST['rating']);
    $message = htmlspecialchars($_POST['message']);

    // Prepare SQL Query
    $stmt = $conn->prepare("INSERT INTO feedback (name, email, rating, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $email, $rating, $message);

    if ($stmt->execute()) {
        // Redirect to Show Booking Page After Successful Submission
        header("Location: ../../show_bookings.php?success=1");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect if accessed directly
    header("Location: ../../show_bookings.php");
    exit();
}

if (isset($_GET['skip'])) {
    // session_unset();
    // session_destroy();
    header("Location: ../../show_bookings.php");
    exit;
}
?>
