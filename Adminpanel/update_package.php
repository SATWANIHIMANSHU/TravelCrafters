<?php
include '../database/dbconnect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $destination_id = $_POST['destination_id'];
    $original_price = $_POST['original_price'];
    $old_price = $_POST['old_price'];
    $duration = $_POST['duration'];
    $is_customizable = $_POST['is_customizable'];
    $is_expert_choice = $_POST['is_expert_choice'];

    // Update query
    $query = "UPDATE `packages` 
              SET name='$name', destination_id='$destination_id', original_price='$original_price', 
                  old_price='$old_price', duration='$duration', is_customizable='$is_customizable', 
                  is_expert_choice='$is_expert_choice' 
              WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Package updated successfully!'); window.location.href='Package_info.php';</script>";
    } else {
        echo "Error updating package: " . mysqli_error($conn);
    }
}
?>
