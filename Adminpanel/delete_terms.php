<?php
include '../database/dbconnect.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $deleteQuery = "DELETE FROM terms_conditions WHERE id = $id";
    mysqli_query($conn, $deleteQuery);
}

header("Location: package_terms&conditions.php"); // redirect back to listing page
exit();
?>
