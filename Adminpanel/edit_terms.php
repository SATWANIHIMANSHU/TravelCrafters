<?php
include '../database/dbconnect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM terms_conditions WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $package_id = $_POST['package_id'];
    $terms_text = $_POST['terms_text'];

    $updateQuery = "UPDATE terms_conditions SET package_id = '$package_id', content = '$terms_text' WHERE id = $id";
    mysqli_query($conn, $updateQuery);
    header("Location: package_terms&conditions.php"); // redirect back to listing page
    exit();
}
?>

<!-- Simple Bootstrap Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Edit Terms</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Terms & Conditions</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <div class="form-group">
            <label>Package ID</label>
            <input type="text" name="package_id" class="form-control" value="<?php echo $row['package_id']; ?>" required>
        </div>
        <div class="form-group">
            <label>Terms & Conditions</label>
            <textarea name="terms_text" class="form-control" rows="5" required><?php echo $row['content']; ?></textarea>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="Package_terms&conditions.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
