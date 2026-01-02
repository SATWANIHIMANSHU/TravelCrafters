<?php 
session_start(); // Start session
// session_start();
require_once __DIR__ . "/config/cashfree.php";


if (!isset($_SESSION['user_id']) && isset($_GET['user_id'])) {
    $_SESSION['user_id'] = $_GET['user_id'];
}

$mode = "TEST"; //<------------ Change to TEST for test server, PROD for production
$_SESSION['temp_user_id'] = $_SESSION['user_id']; 
// Fetch data from GET instead of POST
$appId = isset($_GET['appId']) ? $_GET['appId'] : null;
$orderId = isset($_GET['orderId']) ? $_GET['orderId'] : null;
$orderAmount = isset($_GET['orderAmount']) ? $_GET['orderAmount'] : null;
$orderCurrency = isset($_GET['orderCurrency']) ? $_GET['orderCurrency'] : null;
$orderNote = isset($_GET['orderNote']) ? $_GET['orderNote'] : null;
$customerName = isset($_GET['customerName']) ? $_GET['customerName'] : null;
$customerPhone = isset($_GET['customerPhone']) ? $_GET['customerPhone'] : null;
$customerEmail = isset($_GET['customerEmail']) ? $_GET['customerEmail'] : null;
$returnUrl = isset($_GET['returnUrl']) ? $_GET['returnUrl'] : null;
$notifyUrl = isset($_GET['notifyUrl']) ? $_GET['notifyUrl'] : null;

// Check for missing data
if (!$appId || !$orderId || !$orderAmount || !$customerName || !$customerPhone || !$customerEmail) {
    die("Error: Missing payment details. Please check the request data.");
}

// Secret key for generating signature
$secretKey = CASHFREE_SECRET_KEY;

// Prepare data for signature
$postData = array( 
  "appId" => $appId, 
  "orderId" => $orderId, 
  "orderAmount" => $orderAmount, 
  "orderCurrency" => $orderCurrency, 
  "orderNote" => $orderNote, 
  "customerName" => $customerName, 
  "customerPhone" => $customerPhone, 
  "customerEmail" => $customerEmail,
  "returnUrl" => $returnUrl, 
  "notifyUrl" => $notifyUrl,
);
ksort($postData);
$signatureData = "";
foreach ($postData as $key => $value){
    $signatureData .= $key.$value;
}
$signature = hash_hmac('sha256', $signatureData, $secretKey,true);
$signature = base64_encode($signature);

// Set Cashfree Payment URL
$url = ($mode == "PROD") 
    ? "https://www.cashfree.com/checkout/post/submit"
    : "https://test.cashfree.com/billpay/checkout/post/submit";

?>

<!DOCTYPE html>
<html>
<head>
  <title>Cashfree - Signature Generator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body onload="document.frm1.submit()">
    <form action="<?php echo $url; ?>" name="frm1" method="post">
        <p>Please wait.......</p>
        <input type="hidden" name="signature" value='<?php echo $signature; ?>'/>
        <input type="hidden" name="orderNote" value='<?php echo $orderNote; ?>'/>
        <input type="hidden" name="orderCurrency" value='<?php echo $orderCurrency; ?>'/>
        <input type="hidden" name="customerName" value='<?php echo $customerName; ?>'/>
        <input type="hidden" name="customerEmail" value='<?php echo $customerEmail; ?>'/>
        <input type="hidden" name="customerPhone" value='<?php echo $customerPhone; ?>'/>
        <input type="hidden" name="orderAmount" value='<?php echo $orderAmount; ?>'/>
        <input type="hidden" name="notifyUrl" value='<?php echo $notifyUrl; ?>'/>
        <input type="hidden" name="returnUrl" value='<?php echo $returnUrl; ?>'/>
        <input type="hidden" name="appId" value='<?php echo $appId; ?>'/>
        <input type="hidden" name="orderId" value='<?php echo $orderId; ?>'/>
    </form>
</body>
</html>
