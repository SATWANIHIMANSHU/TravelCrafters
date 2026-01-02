<?php
include '../database/dbconnect.php'; // Database connection

if (isset($_GET['id'])) {
    $image_id = $_GET['id'];

    // Fetch image details
    $query = "SELECT * FROM `package_images` WHERE id = $image_id";
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $image_path = $row['image_url'];
    } else {
        echo "Image not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Image</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Image</h2>
        <img src="../uploads/<?php echo $image_path; ?>" class="img-fluid mb-3" alt="Image Preview" width="200">
        
        <form action="update_image.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $image_id; ?>">

            <div class="mb-3">
                <label class="form-label">Upload New Image</label>
                <input type="file" name="new_image" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Update Image</button>
            <a href="Package_images.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
