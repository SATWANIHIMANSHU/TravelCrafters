<?php
include '../database/dbconnect.php'; // Database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare delete query
    $query = "DELETE FROM destinations WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $execute = mysqli_stmt_execute($stmt);

        if ($execute) {
            echo "<script>alert('Destination deleted successfully.'); window.location.href='Destination.php';</script>";
        } else {
            echo "<script>alert('Error deleting destination.'); window.location.href='Destination.php';</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Query preparation failed.'); window.location.href='Destination.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='manage_destinations.php';</script>";
}

mysqli_close($conn);
?>
