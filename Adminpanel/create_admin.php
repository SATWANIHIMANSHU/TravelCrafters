<?php
include '../database/dbconnect.php'; // Update the path if needed

// New admin credentials
$username = 'Mohit'; // Change this
$password_plain = 'Mohit`'; // Change this

// Hash the password
$hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

// Insert into admin table
$stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo "✅ Admin '$username' added successfully.";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
