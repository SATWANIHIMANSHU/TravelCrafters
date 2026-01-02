<?php
session_start();
include '../database/dbconnect.php'; // Include your database connection file

// Redirect to index if admin already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Fetch admin details
    $query = "SELECT * FROM `admin` WHERE `username`='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_username'] = $admin['username'];

            // Redirect to admin dashboard
            echo "<script>window.location.href='./index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Invalid username or password!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid username or password!'); window.location.href='login.php';</script>";
    }
}
?>
