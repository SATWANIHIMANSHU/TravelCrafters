<?php
session_start();

$userId = $_SESSION['user_id']; // Assuming you store the user ID in the session

include 'database/dbconnect.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // Ensure package details exist in session
    if (!isset($_SESSION['package_id'])) {
        die("Error: Package not selected.");
    }

    // Retrieve package details from session
    $package_id = $_SESSION['package_id'];
    $package_name = $_SESSION['package_name'] ?? "Unknown Package";
    $package_image = $_SESSION['package_image'] ?? "default.jpg";
    $travel_date = $_SESSION['travel_date'] ?? "Not Selected";
    $adults = $_SESSION['adults'] ?? 1;
    $children = $_SESSION['children'] ?? 0;
    $total_amount = $_SESSION['total_amount'] ?? 0;

    // Validate input fields
    if (empty($full_name) || empty($email) || empty($phone)) {
        die("Error: All fields are required.");
    }

    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("Error: You must be logged in to book.");
    }

    $user_id = $_SESSION['user_id']; // Fetch user ID from session

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, package_id, travel_date, adults, children, total_amount, user_name, email, phone) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisiiisss", $user_id, $package_id, $travel_date, $adults, $children, $total_amount, $full_name, $email, $phone);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;

        // Store order details in session for redirection
        $_SESSION['order_id'] = $order_id;
        $_SESSION['order_amount'] = $total_amount;
        $_SESSION['customer_name'] = $full_name;
        $_SESSION['customer_email'] = $email;
        $_SESSION['customer_phone'] = $phone;

        // Redirect to payment page
        header("Location: redirect_to_payment.php");
        exit();
    } else {
        die("Error: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>
