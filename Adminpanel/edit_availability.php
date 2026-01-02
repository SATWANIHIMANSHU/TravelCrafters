<?php
include '../database/dbconnect.php';

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch the existing data
    $query = "SELECT * FROM package_availability WHERE id = $id";
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Package Availability</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Edit Package Availability</h4>
                </div>
                <div class="card-body">
                    <form action="update_availability.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                        <div class="mb-3">
                            <label class="form-label">Package ID</label>
                            <input type="text" name="package_id" class="form-control" 
                                value="<?php echo $row['package_id']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Available Seats</label>
                            <input type="number" name="available_seats" class="form-control" 
                                value="<?php echo $row['available_seats']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Travel Date</label>
                            <input type="date" name="travel_date" class="form-control" 
                                value="<?php echo $row['travel_date']; ?>" required>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success px-4">Update</button>
                            <a href="package_availabilty.php" class="btn btn-secondary px-4">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
    } else {
        echo "<div class='alert alert-danger text-center mt-5'>Record not found!</div>";
    }
} else {
    echo "<div class='alert alert-warning text-center mt-5'>No ID provided!</div>";
}
?>
