<?php
include '../database/dbconnect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Delete inclusion entry
    $query = "DELETE FROM inclusions WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Inclusion deleted successfully!'); window.location.href='Package_inclusion.php';</script>";
    } else {
        echo "Error deleting inclusion: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='Package_inclusion.php';</script>";
}
?>
