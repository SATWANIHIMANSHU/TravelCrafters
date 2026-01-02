<?php
include '../database/dbconnect.php'; // Database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM itinerary WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
} else {
    echo "<script>alert('Invalid request!'); window.location.href='Package_itinerary.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Itinerary</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Itinerary</h2>
    <form action="update_itinerary.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

        <div class="mb-3">
            <label class="form-label">Package ID:</label>
            <input type="number" name="package_id" class="form-control" value="<?php echo $row['package_id']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Day Number:</label>
            <input type="number" name="day_number" class="form-control" value="<?php echo $row['day_number']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Title:</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description:</label>
            <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($row['description']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-success">Update Itinerary</button>
        <a href="Package_itinerary.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
