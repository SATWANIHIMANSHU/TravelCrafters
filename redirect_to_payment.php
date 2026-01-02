<?php
session_start();
require_once __DIR__ . "/config/cashfree.php";

// Ensure required session variables exist
if (!isset($_SESSION['order_id'], $_SESSION['order_amount'], $_SESSION['customer_name'], $_SESSION['customer_email'], $_SESSION['customer_phone'], $_SESSION['user_id'])) {
    die("Error: Payment details not found.");
}

// ✅ Set a cookie to preserve user_id even if session is lost (1 day expiry)
setcookie("user_id", $_SESSION['user_id'], time() + (24 * 60 * 60), "/","", true, true);

// Payment Gateway Parameters
$appId = CASHFREE_APP_ID;
$orderCurrency = "INR"; 
$orderNote = "Package Booking Payment";
$returnUrl = "http://localhost/TravelCrafters/php-pg-integration-cashfree-master/checkout/response.php";
$notifyUrl = "http://localhost/TravelCrafters/payment-notify.php";

// Redirect to request.php with payment parameters
header("Location: http://localhost/TravelCrafters/php-pg-integration-cashfree-master/checkout/request.php?"
    . "orderId={$_SESSION['order_id']}"
    . "&orderAmount={$_SESSION['order_amount']}"
    . "&customerName={$_SESSION['customer_name']}"
    . "&customerEmail={$_SESSION['customer_email']}"
    . "&customerPhone={$_SESSION['customer_phone']}"
    . "&userId={$_SESSION['user_id']}" 
    . "&appId=$appId"
    . "&orderCurrency=$orderCurrency"
    . "&orderNote=$orderNote"
    . "&returnUrl=$returnUrl"
    . "&notifyUrl=$notifyUrl"
);
exit();
?>
