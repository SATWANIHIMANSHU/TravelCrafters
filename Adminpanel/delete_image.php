<?php
include '../database/dbconnect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Fetch image path
    $query = "SELECT image_url FROM `package_images` WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $image_path = $row['image_url'];

        // Delete image file from server
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete image from database
        $delete_query = "DELETE FROM `package_images` WHERE id = $id";
        if (mysqli_query($conn, $delete_query)) {
            echo "<script>alert('Image deleted successfully!'); window.location.href='Package_images.php';</script>";
        } else {
            echo "Error deleting image: " . mysqli_error($conn);
        }
    } else {
        echo "Image not found!";
    }
}
?>
