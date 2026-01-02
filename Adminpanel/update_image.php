<?php
include '../database/dbconnect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Check if file is uploaded
    if (isset($_FILES["new_image"]) && $_FILES["new_image"]["error"] == 0) {
        $image_name = basename($_FILES["new_image"]["name"]); // Store only the image name
        $target_dir = "uploads/";
        $target_file = $target_dir . $image_name;

        // Move file to server
        if (move_uploaded_file($_FILES["new_image"]["tmp_name"], $target_file)) {
            // Update database with only the image name
            $query = "UPDATE `package_images` SET image_url='$image_name' WHERE id=$id";

            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Image updated successfully!'); window.location.href='Package_images.php';</script>";
            } else {
                echo "Error updating image: " . mysqli_error($conn);
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No file uploaded.";
    }
}
?>
