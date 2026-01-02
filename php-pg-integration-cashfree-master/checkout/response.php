<?php
session_start();
include('dbconnect.php'); // Database connection
require_once __DIR__ . "/config/cashfree.php";



$secretkey = CASHFREE_SECRET_KEY;
$orderId = $_POST["orderId"];
$orderAmount = $_POST["orderAmount"];
$referenceId = $_POST["referenceId"];
$txStatus = $_POST["txStatus"];
$paymentMode = $_POST["paymentMode"];
$txMsg = $_POST["txMsg"];
$txTime = $_POST["txTime"];
$signature = $_POST["signature"];

// Compute signature
$data = $orderId . $orderAmount . $referenceId . $txStatus . $paymentMode . $txMsg . $txTime;
$hash_hmac = hash_hmac('sha256', $data, $secretkey, true);
$computedSignature = base64_encode($hash_hmac);

$is_valid_signature = ($signature == $computedSignature);
$is_successful = ($txStatus === "SUCCESS");

// Insert payment details into the database
$stmt = $conn->prepare("INSERT INTO payments (order_id, order_amount, reference_id, transaction_status, payment_mode, transaction_time) 
                        VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $orderId, $orderAmount, $referenceId, $txStatus, $paymentMode, $txTime);
$stmt->execute();
$stmt->close();

if ($is_valid_signature && $is_successful) {
    // Update booking status to completed
    $updateStmt = $conn->prepare("UPDATE bookings SET status = 'completed' WHERE id = ?");
    $updateStmt->bind_param("s", $orderId);
    $updateStmt->execute();
    $updateStmt->close();
    
// Retrieve user_id from session or cookie
$user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;
if ($user_id) {
    $_SESSION['user_id'] = $user_id; // Make sure it's set for future
}

    // Set success message
    $payment_message = "Payment Successful";
    $status_class = "border-success";
    $header_class = "bg-success text-white";
} else {
    // Set failed message
    $payment_message = "Payment Failed";
    $status_class = "border-danger";
    $header_class = "bg-danger text-white";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="5;url=feedback.php">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Response</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        .card-header {
            font-size: 22px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="card <?php echo $status_class; ?>">
    <div class="card-header text-center <?php echo $header_class; ?>">
        <?php echo $payment_message; ?>
    </div>
    <div class="card-body">
        <table class="table">
            <tbody>
                <tr><td><strong>Order ID</strong></td><td><?php echo htmlspecialchars($orderId); ?></td></tr>
                <tr><td><strong>Order Amount</strong></td><td><?php echo htmlspecialchars($orderAmount); ?></td></tr>
                <tr><td><strong>Reference ID</strong></td><td><?php echo htmlspecialchars($referenceId); ?></td></tr>
                <tr><td><strong>Transaction Status</strong></td><td><?php echo htmlspecialchars($txStatus); ?></td></tr>
                <tr><td><strong>Payment Mode</strong></td><td><?php echo htmlspecialchars($paymentMode); ?></td></tr>
                <tr><td><strong>Transaction Time</strong></td><td><?php echo htmlspecialchars($txTime); ?></td></tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



