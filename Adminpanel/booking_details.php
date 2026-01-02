<?php
include '../database/dbconnect.php';

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // Fetch booking details
    $query = "SELECT * FROM bookings WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $booking_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            text-transform: capitalize;
        }
        .status-pending { background-color: orange; color: white; }
        .status-completed { background-color: green; color: white; }
        .status-cancelled { background-color: red; color: white; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3>Booking Details</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Booking ID</th>
                    <td><?php echo $row['id']; ?></td>
                </tr>
                <tr>
                    <th>Package ID</th>
                    <td><?php echo $row['package_id']; ?></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                </tr>
                <tr>
                    <th>Booking Date</th>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
                <tr>
                    <th>Travel Date</th>
                    <td><?php echo $row['travel_date']; ?></td>
                </tr>
                <tr>
                    <th>Adults</th>
                    <td><?php echo $row['adults']; ?></td>
                </tr>
                <tr>
                    <th>Children</th>
                    <td><?php echo $row['children']; ?></td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td>₹<?php echo number_format($row['total_amount'], 2); ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="status-badge 
                            <?php echo ($row['status'] == 'pending') ? 'status-pending' : 
                                      (($row['status'] == 'completed') ? 'status-completed' : 'status-cancelled'); ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="card-footer text-center">
            <a href="Bookings.php" class="btn btn-secondary">Back to Bookings</a>
        </div>
    </div>
</div>

</body>
</html>

<?php
    } else {
        echo "<div class='alert alert-danger text-center'>Booking not found!</div>";
    }
} else {
    echo "<div class='alert alert-warning text-center'>Invalid request!</div>";
}
?>
