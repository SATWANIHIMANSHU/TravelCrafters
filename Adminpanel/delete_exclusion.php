<?php
include '../database/dbconnect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Delete query
    $query = "DELETE FROM exclusions WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Exclusion deleted successfully!'); window.location.href='Package_exclusion.php';</script>";
    } else {
        echo "Error deleting exclusion: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='Package_exclusion.php';</script>";
}
?>
