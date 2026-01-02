<?php
session_start();
include '../database/dbconnect.php';

// Redirect if admin not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'] ?? null;

// Fetch admin details
$stmt = $conn->prepare("SELECT email, username, profile_pic FROM admin WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Fallbacks if values are missing
$admin_email = $admin['email'] ?? 'Not Provided';
$admin_username = $admin['username'] ?? 'admin_user';
$profile_pic = $admin['profile_pic'] ?? 'default.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #dfe9f3, #ffffff);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .profile-card {
            background-color: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-5px);
        }

        .profile-img {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #0d6efd;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .profile-info h3 {
            margin-top: 15px;
            font-weight: 600;
        }

        .profile-info p {
            color: #6c757d;
        }

        .edit-btn {
            background-color: #0d6efd;
            color: white;
        }

        .edit-btn:hover {
            background-color: #0056b3;
        }

        .fallback-icon {
            font-size: 60px;
            color: #ced4da;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h2 class="text-center mb-4">Admin Profile</h2>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="profile-card text-center">
                    <?php if ($profile_pic && file_exists("../uploads/$profile_pic")): ?>
                        <img src="../uploads/<?php echo $profile_pic; ?>" alt="Profile Picture" class="profile-img">
                    <?php else: ?>
                        <div class="d-flex justify-content-center mb-3">
                            <i class="fas fa-user-circle fallback-icon"></i>
                        </div>
                    <?php endif; ?>

                    <div class="profile-info">
                        <h3><?php echo htmlspecialchars($admin_username); ?></h3>
                        <p class="mb-2"><i class="fas fa-envelope me-2 text-primary"></i> <?php echo htmlspecialchars($admin_email); ?></p>
                    </div>

                    <a href="edit_profile.php" class="btn edit-btn mt-4 px-4">
                        <i class="fas fa-user-edit me-2"></i> Edit Profile
                    </a>
                    <a href="index.php" class="btn edit-btn mt-4 px-4">
                         Back to panel
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
