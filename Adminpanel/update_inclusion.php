<?php
include '../database/dbconnect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $package_id = $_POST['package_id'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Update query
    $query = "UPDATE inclusions SET package_id='$package_id', description='$description' WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Inclusion updated successfully!'); window.location.href='Package_inclusion.php';</script>";
    } else {
        echo "Error updating inclusion: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='Package_inclusion.php';</script>";
}
?>
