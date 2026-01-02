<?php
session_start();

if($_SERVER["REQUEST_METHOD"]=="POST"){
    include 'database/dbconnect.php';

    // Sanitize inputs to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];

    // Validate mobile number format
    if(!preg_match("/^[0-9]{10}$/", $mobile)) {
        echo "<script>
        alert('Please enter a valid 10-digit mobile number');
        window.location.href = 'Login.php';
        </script>";
        exit();
    }

    // Check if username, email, or mobile already exists
    $sql = "SELECT * FROM `users` WHERE `email`=? OR `mobile`=? OR `username`=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $email, $mobile, $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rowexists = mysqli_num_rows($result);

    if($rowexists > 0){
        $row = mysqli_fetch_assoc($result);
        if($row['email'] == $email) {
            echo "<script>
            alert('This email is already registered');
            window.location.href = 'Login.php';
            </script>";
        } elseif($row['mobile'] == $mobile) {
            echo "<script>
            alert('This mobile number is already registered');
            window.location.href = 'Login.php';
            </script>";
        } elseif($row['username'] == $username) {
            echo "<script>
            alert('This username is already taken');
            window.location.href = 'Login.php';
            </script>";
        }
    } else {
        if($pass == $cpass){
            $hash = password_hash($pass, PASSWORD_DEFAULT);

            // Insert user data into the database
            $sql = "INSERT INTO `users` (`username`, `email`, `mobile`, `password`) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $mobile, $hash);
            $result = mysqli_stmt_execute($stmt);

            if($result){
                // Get the user ID of the newly registered user
                $user_id = mysqli_insert_id($conn);

                // Set session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['mobile'] = $mobile;

                echo "<script>
                alert('Registration successful! You are now logged in.');
                window.location.href = 'index.php';
                </script>";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "<script>
            alert('Passwords do not match!');
            window.location.href = 'Login.php';
            </script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login & Registration Form</title>
    <link rel="stylesheet" href="Login.css">
    <style>
    </style>
</head>
<body>
    
    <div class="container">
        <input type="checkbox" id="check">
        <div class="login form">
            <header>Login</header>
            <form action="login_fuctinality.php" method="post">
                <input type="text" name="email" placeholder="Enter your email" required>
                <input type="password" name="password" placeholder="Enter your password" required>
                <a href="forgot_password.php">Forgot password?</a>
                <input type="submit" class="button" value="Login">
            </form>
            <div class="signup">
                <span class="signup">Don't have an account?
                    <label for="check">Signup</label>
                </span>
            </div>
        </div>
        <div class="registration form" style="margin-top: 100px;">
            <header>Signup</header>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="username" placeholder="Enter your name" required>    
            <input type="email" name="email" placeholder="Enter your email" required>
                <input type="tel" name="mobile" placeholder="Enter your mobile number" pattern="[0-9]{10}" title="Please enter a valid 10-digit mobile number" required>
                <input type="password" name="password" placeholder="Create a password" required>
                <input type="password" name="cpassword" placeholder="Confirm your password" required>
                <input type="submit" class="button" value="Signup">
            </form>
            <div class="signup">
                <span class="signup">Already have an account?
                    <label for="check">Login</label>
                </span>
            </div>
        </div>
    </div>
</body>
</html>