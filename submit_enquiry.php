<?php
include 'database/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['package_id']) || empty($_POST['package_id'])) {
        error_log("Error: Package ID is missing.");
        echo "error: Package ID is missing.";
        exit;
    }

    $package_id = intval($_POST['package_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $adult_count = intval($_POST['adult_count'] ?? 1);
    $child_count = intval($_POST['child_count'] ?? 0);

    $query = "INSERT INTO enquiries (package_id, name, mobile, email, adult_count, child_count) 
              VALUES ('$package_id', '$name', '$mobile', '$email', '$adult_count', '$child_count')";

    if (mysqli_query($conn, $query)) {
        echo "success"; // ✅ Ensure only "success" is returned
    } else {
        echo "error: " . mysqli_error($conn);
    }
}
?>
