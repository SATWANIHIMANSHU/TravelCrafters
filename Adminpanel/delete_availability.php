<?php
include '../database/dbconnect.php';

// Check if ID is provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Delete query
    $query = "DELETE FROM package_availability WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo "Record deleted successfully!";
        header("Location: package_availabilty.php"); // Redirect back to list
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request!";
}
?>
