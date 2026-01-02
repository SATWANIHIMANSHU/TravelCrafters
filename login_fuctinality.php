<?php
session_start();
include 'database/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM `users` WHERE `email`=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['email'] = $row['email'];
        echo "<script>alert('Login successful!'); window.location.href = 'index.php';</script> ";
    } else {
        echo "<script>alert('Invalid email or password!'); window.location.href = 'Login.php';</script>";
    }
}
?>
