<?php
include 'database/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password !== $cpassword) {
        die("Passwords do not match.");
    }

    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Invalid or expired token.");
    }

    $row = $result->fetch_assoc();
    $email = $row['email'];

    // Hash the new password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update password in users table
    $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
    $stmt->bind_param("ss", $hashed_password, $email);
    $stmt->execute();

    // Delete the reset token
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    echo "<script>alert('Password updated successfully!'); window.location.href='Login.php';</script>";
}
?>
