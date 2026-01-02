<?php
include '../database/dbconnect.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $display_type = mysqli_real_escape_string($conn, $_POST['display_type']);

    // File Upload Handling
    $targetDir = "../uploads/"; // Ensure this directory exists and is writable
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . time() . "_" . $fileName; // Unique file name
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Allowed file types
    $allowedTypes = array("jpg", "jpeg", "png", "gif","webp");

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            // Insert into database
            $sql = "INSERT INTO destinations (name, description, image, display_type) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $name, $description, $targetFilePath, $display_type);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                echo "<script>
                alert('Destination added successfully!');
                window.location.href = 'Destination.php';
                </script>";
            } else {
                echo "<script>
                alert('Database error: " . mysqli_error($conn) . "');
                window.history.back();
                </script>";
            }
        } else {
            echo "<script>
            alert('File upload failed!');
            window.history.back();
            </script>";
        }
    } else {
        echo "<script>
        alert('Invalid file type. Only JPG, JPEG, PNG, WEBP and GIF are allowed.');
        window.history.back();
        </script>";
    }
}
?>
