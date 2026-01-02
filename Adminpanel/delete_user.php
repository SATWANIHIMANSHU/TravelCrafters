<?php
include '../database/dbconnect.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $delete_query = "DELETE FROM users WHERE user_id = $user_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('User deleted successfully!'); window.location.href='users.php';</script>";
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
} else {
    echo "Invalid Request!";
}
?>
