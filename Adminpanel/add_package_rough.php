<?php
session_start(); // Start session to manage form submissions

include '../database/dbconnect.php';

// Set error reporting and display
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$package_id = null;
$error_message = null;
$destinations_result = null;

// Fetch destinations for dropdown
try {
    $destinations_query = "SELECT id, name FROM destinations ORDER BY name";
    $destinations_result = $conn->query($destinations_query);

    if (!$destinations_result) {
        throw new Exception("Failed to fetch destinations: " . $conn->error);
    }
} catch (Exception $e) {
    $error_message = $e->getMessage();
}

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if this is a duplicate submission
    if (isset($_SESSION['last_submission']) && 
        (time() - $_SESSION['last_submission']) < 5) {
        $error_message = "Please wait a few seconds before submitting again.";
    } else {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Sanitize and validate input data
            $package_name = $conn->real_escape_string($_POST['package_name']);
            $image_name = $conn->real_escape_string($_POST['package_name']);
            $destination_id = intval($_POST['destination_id']);
            $duration = intval($_POST['duration']);
            $original_price = floatval($_POST['original_price']);
            $old_price = floatval($_POST['old_price']);
            $is_customizable = intval($_POST['is_customizable']);
            $is_expert_choice = intval($_POST['is_expert_choice']);

            // Validate required fields
            if (empty($package_name) || $destination_id <= 0 || $duration <= 0) {
                throw new Exception("Missing or invalid required fields");
            }

            // Prepare Package Insertion
            // Handle Image Upload
$image_name = null; // Default to null in case no image is uploaded

if (!empty($_FILES['package_images']['name'][0])) {
    $original_filename = basename($_FILES['package_images']['name'][0]);
    $file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

    // Validate file type
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (in_array($file_extension, $allowed_types)) {
        // Move uploaded file
        if (move_uploaded_file($_FILES['package_images']['tmp_name'][0], "uploads/" . $original_filename)) {
            $image_name = $original_filename; // Store only image name
        } else {
            throw new Exception("Failed to upload image.");
        }
    } else {
        throw new Exception("Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.");
    }
}

// Insert package details into `packages` table along with the image name
$stmt_package = $conn->prepare("INSERT INTO packages 
    (name, destination_id, duration, original_price, old_price, is_customizable, is_expert_choice, image) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

$stmt_package->bind_param(
    "siiddiis", 
    $package_name, 
    $destination_id, 
    $duration, 
    $original_price,
    $old_price,  
    $is_customizable, 
    $is_expert_choice,
    $image_name // Insert image name in `packages` table
);

$stmt_package->execute();
$package_id = $stmt_package->insert_id;
// $stmt_package->close();


            // Itinerary Insertion
            $stmt_itinerary = $conn->prepare("INSERT INTO itinerary 
                (package_id, day_number, title, description) 
                VALUES (?, ?, ?, ?)");
            
            if (isset($_POST['day_number']) && is_array($_POST['day_number'])) {
                foreach ($_POST['day_number'] as $index => $day_number) {
                    // Sanitize itinerary inputs
                    $day_number = intval($day_number);
                    $day_title = $conn->real_escape_string($_POST['day_title'][$index]);
                    $day_description = $conn->real_escape_string($_POST['day_description'][$index]);

                    $stmt_itinerary->bind_param(
                        "iiss", 
                        $package_id, 
                        $day_number, 
                        $day_title, 
                        $day_description
                    );
                    $stmt_itinerary->execute();
                }
            } else {
                throw new Exception("At least one day in the itinerary is required");
            }

            // Package Images Insertion
            $stmt_image = $conn->prepare("INSERT INTO package_images (package_id, image_name) VALUES (?, ?)");

if (!empty($_FILES['package_images']['name'][0])) {
    // Create upload directory if not exists
    $upload_dir = 'uploads/packages/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    foreach ($_FILES['package_images']['tmp_name'] as $index => $tmp_name) {
        // Validate file upload
        if ($_FILES['package_images']['error'][$index] !== UPLOAD_ERR_OK) {
            throw new Exception("File upload error: " . $_FILES['package_images']['error'][$index]);
        }

        // Extract only the filename
        $original_filename = basename($_FILES['package_images']['name'][$index]);
        $file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

        // Validate file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($file_extension, $allowed_types)) {
            throw new Exception("Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.");
        }

        // Move uploaded file
        $new_filename = uniqid() . '_' . $original_filename;
        if (move_uploaded_file($tmp_name, $upload_dir . $new_filename)) {
            // Store only the image name in the database
            $stmt_image->bind_param("is", $package_id, $new_filename);
            $stmt_image->execute();
        } else {
            throw new Exception("Failed to move uploaded file");
        }
    }
}


            // Inclusions Insertion
            $stmt_inclusion = $conn->prepare("INSERT INTO inclusions 
                (package_id, description) 
                VALUES (?, ?)");
            
            if (isset($_POST['inclusion']) && is_array($_POST['inclusion'])) {
                foreach ($_POST['inclusion'] as $inclusion) {
                    // Sanitize inclusion
                    $inclusion = $conn->real_escape_string(trim($inclusion));
                    
                    // Skip empty inclusions
                    if (!empty($inclusion)) {
                        $stmt_inclusion->bind_param(
                            "is", 
                            $package_id, 
                            $inclusion
                        );
                        $stmt_inclusion->execute();
                    }
                }
            }

            // Exclusions Insertion
            $stmt_exclusion = $conn->prepare("INSERT INTO exclusions 
                (package_id, description) 
                VALUES (?, ?)");
            
            if (isset($_POST['exclusion']) && is_array($_POST['exclusion'])) {
                foreach ($_POST['exclusion'] as $exclusion) {
                    // Sanitize exclusion
                    $exclusion = $conn->real_escape_string(trim($exclusion));
                    
                    // Skip empty exclusions
                    if (!empty($exclusion)) {
                        $stmt_exclusion->bind_param(
                            "is", 
                            $package_id, 
                            $exclusion
                        );
                        $stmt_exclusion->execute();
                    }
                }
            }

            // Commit transaction
            $conn->commit();

            // Set last submission time
            $_SESSION['last_submission'] = time();

            // Redirect to prevent form resubmission
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit();

        } catch (Exception $e) {
            // Rollback transaction
            $conn->rollback();

            // Log the error
            error_log("Package Creation Error: " . $e->getMessage());

            // Set error message for display
            $error_message = $e->getMessage();

            // Optional: Delete any partially uploaded files
            if ($package_id) {
                // Implement cleanup logic if needed
            }
        }

        // Close prepared statements
        if (isset($stmt_package)) $stmt_package->close();
        if (isset($stmt_itinerary)) $stmt_itinerary->close();
        if (isset($stmt_image)) $stmt_image->close();
        if (isset($stmt_inclusion)) $stmt_inclusion->close();
        if (isset($stmt_exclusion)) $stmt_exclusion->close();
    }
}

// Check for successful submission via GET parameter
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $package_id = true; // To show success message
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Travel Package</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
        .form-section { 
            background: white; 
            border-radius: 8px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 25px;
        }
        .btn-add {
            margin-top: 10px;
            background-color: #28a745;
            color: white;
        }
        .btn-remove {
            margin-top: 10px;
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="text-center mb-4">
                    <i class="fas fa-plane"></i> Create New Travel Package
                </h1>

                <?php 
                // Display success or error messages with Bootstrap alerts
                if (isset($package_id)) {
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Success!</strong> Package added successfully. Package ID: $package_id
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                          </div>";
                }
                if (isset($error_message)) {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            <strong>Error!</strong> " . htmlspecialchars($error_message) . "
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                          </div>";
                }
                ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-section">
                        <h2 class="h4 mb-3"><i class="fas fa-info-circle"></i> Package Details</h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Package Name</label>
                                <input type="text" class="form-control" name="package_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Destination</label>
                                <select class="form-select" name="destination_id" required>
                                    <?php 
                                    if ($destinations_result->num_rows > 0) {
                                        while($row = $destinations_result->fetch_assoc()) {
                                            echo "<option value='".$row['id']."'>".$row['name']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Duration (Days)</label>
                                <input type="number" class="form-control" name="duration" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">New Price</label>
                                <input type="number" step="0.01" class="form-control" name="original_price" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Old Price</label>
                                <input type="number" step="0.01" class="form-control" name="old_price" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customizable</label>
                                <select class="form-select" name="is_customizable">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Expert Choice</label>
                                <select class="form-select" name="is_expert_choice">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div id="package-image-container">
        <div class="mb-3">
            <label class="form-label">Upload Images</label>
            <input type="file" class="form-control" name="package_images[]" accept="image/*" multiple>
        </div>
    </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="h4 mb-3"><i class="fas fa-route"></i> Itinerary</h2>
                        <div id="itinerary-container">
                            <div class="itinerary-item mb-3">
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label">Day Number</label>
                                        <input type="number" class="form-control" name="day_number[]" required>
                                    </div>
                                    <div class="col-md-8 mb-2">
                                        <label class="form-label">Day Title</label>
                                        <input type="text" class="form-control" name="day_title[]" required>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label class="form-label">Day Description</label>
                                        <textarea class="form-control" name="day_description[]" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-add" onclick="addItineraryDay()">
                            <i class="fas fa-plus"></i> Add Another Day
                        </button>
                    </div>

                    <div class="form-section">
    <h2 class="h4 mb-3"><i class="fas fa-image"></i> Package Images</h2>
    <div id="image-container">
        <div class="mb-3">
            <input type="file" class="form-control" name="package_images[]" accept="image/*">
        </div>
    </div>
    <button type="button" class="btn btn-add" onclick="addImageUpload()">
        <i class="fas fa-plus"></i> Add Another Image
    </button>
</div>


                    <div class="form-section">
                        <h2 class="h4 mb-3"><i class="fas fa-check-circle"></i> Inclusions</h2>
                        <div id="inclusion-container">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="inclusion[]" placeholder="Enter inclusion">
                            </div>
                        </div>
                        <button type="button" class="btn btn-add" onclick="addInclusion()">
                            <i class="fas fa-plus"></i> Add Inclusion
                        </button>
                    </div>

                    <div class="form-section">
                        <h2 class="h4 mb-3"><i class="fas fa-times-circle"></i> Exclusions</h2>
                        <div id="exclusion-container">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="exclusion[]" placeholder="Enter exclusion">
                            </div>
                        </div>
                        <button type="button" class="btn btn-add" onclick="addExclusion()">
                            <i class="fas fa-plus"></i> Add Exclusion
                        </button>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Create Package
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addItineraryDay() {
            const container = document.getElementById('itinerary-container');
            const newItem = document.createElement('div');
            newItem.className = 'itinerary-item mb-3';
            newItem.innerHTML = `
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Day Number</label>
                        <input type="number" class="form-control" name="day_number[]" required>
                    </div>
                    <div class="col-md-8 mb-2">
                        <label class="form-label">Day Title</label>
                        <input type="text" class="form-control" name="day_title[]" required>
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label">Day Description</label>
                        <textarea class="form-control" name="day_description[]" rows="3" required></textarea>
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-remove" onclick="this.closest('.itinerary-item').remove()">
                            <i class="fas fa-trash"></i> Remove Day
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
        }

        function addImageUpload() {
    const container = document.getElementById('image-container');
    const newDiv = document.createElement('div');
    newDiv.className = 'mb-3';
    
    const newInput = document.createElement('input');
    newInput.type = 'file';
    newInput.className = 'form-control';
    newInput.name = 'package_images[]';
    newInput.accept = 'image/*';

    newDiv.appendChild(newInput);
    container.appendChild(newDiv);
}


        function addInclusion() {
            const container = document.getElementById('inclusion-container');
            const newDiv = document.createElement('div');
            newDiv.className = 'mb-3';
            const newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.className = 'form-control';
            newInput.name = 'inclusion[]';
            newInput.placeholder = 'Enter inclusion';
            newDiv.appendChild(newInput);
            container.appendChild(newDiv);
        }

        function addExclusion() {
            const container = document.getElementById('exclusion-container');
            const newDiv = document.createElement('div');
            newDiv.className = 'mb-3';
            const newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.className = 'form-control';
            newInput.name = 'exclusion[]';
            newInput.placeholder = 'Enter exclusion';
            newDiv.appendChild(newInput);
            container.appendChild(newDiv);
        }
    </script>
</body>
</html>