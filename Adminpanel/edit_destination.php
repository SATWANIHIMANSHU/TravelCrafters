<?php
include '../database/dbconnect.php'; // Database connection

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch existing destination data
    $sql = "SELECT * FROM destinations WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $destination = mysqli_fetch_assoc($result);

    if (!$destination) {
        echo "<script>alert('Destination not found!'); window.location.href='Destination.php';</script>";
        exit();
    }

}    // else {
//     echo "<script>alert('Invalid request!'); window.location.href='Destination.php';</script>";
//     exit();
// }

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $display_type = mysqli_real_escape_string($conn, $_POST['display_type']);
    $id = $_POST['id'];

    $updateQuery = "UPDATE destinations SET name=?, description=?, display_type=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "sssi", $name, $description, $display_type, $id);
    
    // Handle image upload
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "../uploads/";
        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = array("jpg", "jpeg", "png", "gif", "webp");

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                // Delete old image if exists
                if (!empty($destination['image']) && file_exists($destination['image'])) {
                    unlink($destination['image']);
                }
                // Update query to include new image
                $updateQuery = "UPDATE destinations SET name=?, description=?, display_type=?, image=? WHERE id=?";
                $stmt = mysqli_prepare($conn, $updateQuery);
                mysqli_stmt_bind_param($stmt, "ssssi", $name, $description, $display_type, $targetFilePath, $id);
            } else {
                echo "<script>alert('Image upload failed!');</script>";
            }
        } else {
            echo "<script>alert('Invalid image type!');</script>";
        }
    }

    // Execute update query
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Destination updated successfully!'); window.location.href='Destination.php';</script>";
    } else {
        echo "<script>alert('Update failed: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Destination</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Destination</h2>
        <form action="edit_destination.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $destination['id']; ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Destination Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $destination['name']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $destination['description']; ?></textarea>
            </div>

            <div class="mb-3">
                <label for="display_type" class="form-label">Display Type</label>
                <select class="form-control" id="display_type" name="display_type" required>
                    <option value="card" <?php echo ($destination['display_type'] == 'card') ? 'selected' : ''; ?>>Card</option>
                    <option value="grid" <?php echo ($destination['display_type'] == 'grid') ? 'selected' : ''; ?>>Grid</option>
                    <option value="image_grid" <?php echo ($destination['display_type'] == 'image_grid') ? 'selected' : ''; ?>>Image-Grid</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Upload New Image (Optional)</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <?php if (!empty($destination['image'])): ?>
                    <p>Current Image:</p>
                    <img src="../uploads/<?php echo $destination['image']; ?>" alt="Current Image" width="150">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Update Destination</button>
            <a href="Destination.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
