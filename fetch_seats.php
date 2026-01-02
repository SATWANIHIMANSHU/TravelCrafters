<?php
require 'database/dbconnect.php';


if (isset($_GET['package_id']) && isset($_GET['travel_date'])) {
    $packageId = $_GET['package_id'];
    $travelDate = $_GET['travel_date'];

    // Query to fetch seat availability for the given package and date
    $query = "SELECT available_seats FROM package_availability WHERE package_id = ? AND travel_date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $packageId, $travelDate);
    $stmt->execute();
    $stmt->bind_result($availableSeats);

    $response = [];
    if ($stmt->fetch()) {
        if ($availableSeats > 0) {
            $response = [
                "status" => "success",
                "available_seats" => $availableSeats
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "Sorry, no seats available for the selected date."
            ];
        }
    } else {
        $response = [
            "status" => "error",
            "message" => "Invalid package or date."
        ];
    }

    echo json_encode($response);
}
?>
