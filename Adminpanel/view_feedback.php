<?php
include '../database/dbconnect.php';

if (isset($_GET['id'])) {
    $feedback_id = $_GET['id'];

    // Fetch feedback details
    $query = "SELECT * FROM feedback WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $feedback_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3>Feedback Details</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Feedback ID</th>
                    <td><?php echo $row['id']; ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                </tr>
                <tr>
                    <th>Rating</th>
                    <td>
                        <?php 
                            for ($i = 1; $i <= 5; $i++) {
                                echo ($i <= $row['rating']) ? "⭐" : "☆";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                </tr>
                <tr>
                    <th>Submitted On</th>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
            </table>
        </div>
        <div class="card-footer text-center">
            <a href="Feedbacks.php" class="btn btn-secondary">Back to Feedback List</a>
        </div>
    </div>
</div>

</body>
</html>

<?php
    } else {
        echo "<div class='alert alert-danger text-center'>Feedback record not found!</div>";
    }
} else {
    echo "<div class='alert alert-warning text-center'>Invalid request!</div>";
}
?>
