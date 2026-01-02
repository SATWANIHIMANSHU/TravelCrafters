<?php
session_start();
$orderId = isset($_GET['order_id']) ? htmlspecialchars($_GET['order_id']) : 'Unknown';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .spinner {
            border: 5px solid rgba(0, 0, 0, 0.1);
            border-top: 5px solid #28a745;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = "show_bookings.php";
        }, 5000); // Redirect after 5 seconds
    </script>
</head>
<body>
    <h3>Processing your booking...</h3>
    <div class="spinner"></div>
    <p>Order ID: <strong><?php echo $orderId; ?></strong></p>
</body>
</html>
