<?php
include '../database/dbconnect.php';

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // Update booking status to "cancelled"
    $query = "UPDATE bookings SET status = 'cancelled' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $booking_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Booking has been cancelled successfully!'); 
              window.location.href = 'Bookings.php';</script>";
    } else {
        echo "<script>alert('Error cancelling booking. Please try again.'); 
              window.location.href = 'Bookings.php';</script>";
    }
}
?>
