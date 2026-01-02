<?php
include '../database/dbconnect.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $query = "SELECT * FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        echo "<script>alert('User not found!'); window.location.href='users.php';</script>";
        exit;
    }
}

if (isset($_POST['update'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET email='$email', username='$username', password='$hashed_password', mobile='$mobile', address='$address' WHERE user_id=$user_id";
    } else {
        $update_query = "UPDATE users SET email='$email', username='$username', mobile='$mobile', address='$address' WHERE user_id=$user_id";
    }

    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('User updated successfully!'); window.location.href='users.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Error updating user: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4><i class="fas fa-user-edit"></i> Edit User</h4>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-user"></i> Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $row['username']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-lock"></i> New Password (Optional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter new password">
                            <small class="text-muted">Leave blank to keep the current password.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-phone"></i> Mobile</label>
                            <input type="text" name="mobile" class="form-control" value="<?php echo $row['mobile']; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-map-marker-alt"></i> Address</label>
                            <textarea name="address" class="form-control" rows="3"><?php echo $row['address']; ?></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="users.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                            <button type="submit" name="update" class="btn btn-success"><i class="fas fa-save"></i> Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
