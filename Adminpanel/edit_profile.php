<?php
session_start();
include '../database/dbconnect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $role = $_POST['role'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    // Fetch existing password and profile_pic
    $stmt = $conn->prepare("SELECT password, profile_pic FROM admin WHERE admin_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin_data = $result->fetch_assoc();
    $existing_password = $admin_data['password'];
    $old_profile_pic = $admin_data['profile_pic'];

    // Handle image upload
    $profile_pic = $old_profile_pic;
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "../uploads/";
        $new_file_name = time() . '_' . basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $new_file_name;

        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $profile_pic = $new_file_name;
        }
    }

    // Update password only if new one is provided
    $hashed_password = $existing_password;
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    }

    // Update database
    $stmt = $conn->prepare("UPDATE admin SET username=?, email=?, phone=?, role=?, profile_pic=?, password=? WHERE admin_id=?");
    $stmt->bind_param("ssssssi", $username, $email, $phone, $role, $profile_pic, $hashed_password, $admin_id);
    if ($stmt->execute()) {
        $msg = "Profile updated successfully.";
    } else {
        $msg = "Failed to update profile.";
    }
}

// Fetch updated admin details
$stmt = $conn->prepare("SELECT username, email, phone, role, profile_pic FROM admin WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            margin-top: 50px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .form-title {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-container">
                <h3 class="text-center form-title">Edit Admin Profile</h3>

                <?php if ($msg): ?>
                    <div class="alert alert-info"><?php echo $msg; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Username:</label>
                        <input type="text" name="username" class="form-control" required value="<?php echo htmlspecialchars($admin['username']); ?>">
                    </div>
                    <div class="mb-3">
                        <label>Email:</label>
                        <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($admin['email']); ?>">
                    </div>
                    <div class="mb-3">
                        <label>Phone:</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($admin['phone']); ?>">
                    </div>
                    <div class="mb-3">
                        <label>Role:</label>
                        <input type="text" name="role" class="form-control" value="<?php echo htmlspecialchars($admin['role']); ?>">
                    </div>
                    <div class="mb-3">
                        <label>Profile Picture:</label><br>
                        <img src="../uploads/<?php echo $admin['profile_pic'] ?: 'default.png'; ?>" width="100" class="rounded-circle mb-2"><br>
                        <input type="file" name="profile_pic" class="form-control">
                    </div>

                    <hr>
                    <h5 class="mt-4">Change Password</h5>
                    <div class="mb-3">
                        <label>New Password:</label>
                        <input type="password" name="new_password" class="form-control">
                        <small class="text-muted">Leave blank to keep the current password.</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="view_profile.php" class="btn btn-secondary">Back to Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
