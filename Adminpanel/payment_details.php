<?php
include '../database/dbconnect.php';

if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];

    // Fetch payment details
    $query = "SELECT * FROM payments WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $payment_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3>Payment Details</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Payment ID</th>
                    <td><?php echo $row['id']; ?></td>
                </tr>
                <tr>
                    <th>Booking ID</th>
                    <td><?php echo $row['order_id']; ?></td>
                </tr>
                <tr>
                    <th>Payment Date</th>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
                <tr>
                    <th>Payment Mode</th>
                    <td><?php echo $row['payment_mode']; ?></td>
                </tr>
                <tr>
                    <th>Transaction ID</th>
                    <td><?php echo $row['reference_id']; ?></td>
                </tr>
                <tr>
                    <th>Amount Paid</th>
                    <td>₹<?php echo number_format($row['order_amount'], 2); ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge 
                            <?php echo ($row['transaction_status'] == 'success') ? 'bg-success' : 
                                      (($row['transaction_status'] == 'failed') ? 'bg-danger' : 'bg-warning'); ?>">
                            <?php echo ucfirst($row['transaction_status']); ?>
                        </span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="card-footer text-center">
            <a href="Payments.php" class="btn btn-secondary">Back to Payments</a>
        </div>
    </div>
</div>

</body>
</html>

<?php
    } else {
        echo "<div class='alert alert-danger text-center'>Payment record not found!</div>";
    }
} else {
    echo "<div class='alert alert-warning text-center'>Invalid request!</div>";
}
?>
