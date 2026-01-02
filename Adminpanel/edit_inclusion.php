<?php
include '../database/dbconnect.php'; // Database connection

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the inclusion details
    $query = "SELECT * FROM inclusions WHERE id = $id";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Inclusion not found!'); window.location.href='Package_inclusion.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='Package_inclusion.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inclusion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Inclusion</h2>
        <form action="update_inclusion.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div class="mb-3">
                <label for="package_id" class="form-label">Package ID</label>
                <input type="text" class="form-control" id="package_id" name="package_id" 
                    value="<?php echo htmlspecialchars($row['package_id']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($row['description']); ?></textarea>
            </div>

            <button type="submit" class="btn btn-success">Update Inclusion</button>
            <a href="Package_inclusion.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
