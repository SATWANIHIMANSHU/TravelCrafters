<?php
include 'database/dbconnect.php';

if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

// Validate token
$stmt = $conn->prepare("SELECT * FROM password_resets WHERE token=?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Invalid or expired token.");
}

$row = $result->fetch_assoc();
$email = $row['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .container {
            background: white;
            padding: 20px;
            width: 300px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form action="update_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="password" name="password" placeholder="New Password" required>
            <input type="password" name="cpassword" placeholder="Confirm Password" required>
            <button type="submit" class="button">Reset Password</button>
        </form>
    </div>
</body>
</html>
