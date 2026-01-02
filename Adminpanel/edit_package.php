<?php
include '../database/dbconnect.php'; // Database connection

// Check if an ID is passed in the URL
if (isset($_GET['id'])) {
    $package_id = $_GET['id'];

    // Fetch package details
    $query = "SELECT * FROM `packages` WHERE id = $package_id";
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Package found, store values
        $name = $row['name'];
        $destination_id = $row['destination_id'];
        $original_price = $row['original_price'];
        $old_price = $row['old_price'];
        $duration = $row['duration'];
        $is_customizable = $row['is_customizable'];
        $is_expert_choice = $row['is_expert_choice'];
    } else {
        echo "Package not found!";
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
    <title>Edit Package</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Package</h2>
        <form action="update_package.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $package_id; ?>">

            <div class="mb-3">
                <label class="form-label">Package Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Destination ID</label>
                <input type="number" name="destination_id" class="form-control" value="<?php echo $destination_id; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">New Price</label>
                <input type="text" name="original_price" class="form-control" value="<?php echo $original_price; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Old Price</label>
                <input type="text" name="old_price" class="form-control" value="<?php echo $old_price; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Duration (Days)</label>
                <input type="number" name="duration" class="form-control" value="<?php echo $duration; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Customizable</label>
                <select name="is_customizable" class="form-control">
                    <option value="1" <?php if ($is_customizable == 1) echo 'selected'; ?>>Yes</option>
                    <option value="0" <?php if ($is_customizable == 0) echo 'selected'; ?>>No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Expert Choice</label>
                <select name="is_expert_choice" class="form-control">
                    <option value="1" <?php if ($is_expert_choice == 1) echo 'selected'; ?>>Yes</option>
                    <option value="0" <?php if ($is_expert_choice == 0) echo 'selected'; ?>>No</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update Package</button>
            <a href="Package_info.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
