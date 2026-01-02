<?php
include '../database/dbconnect.php'; // Include database connection

$message = ""; // To store success or error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Capture package details
        $package_name = $_POST['package_name'];
        $destination_id = $_POST['destination_id'];
        $duration = $_POST['duration'];
        $original_price = $_POST['original_price'];
        $old_price = $_POST['old_price'];
        $is_customizable = $_POST['is_customizable'];
        $is_expert_choice = $_POST['is_expert_choice'];

        // Insert package details into `packages` table
        $stmt = $conn->prepare("INSERT INTO packages (name, destination_id, duration, original_price, old_price, is_customizable, is_expert_choice) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiddii", $package_name, $destination_id, $duration, $original_price, $old_price, $is_customizable, $is_expert_choice);
        $stmt->execute();
        $package_id = $stmt->insert_id; // Get newly inserted package ID
        $stmt->close();

        // Handle image uploads and insert into `package_images` table
        if (!empty($_FILES['package_images']['name'][0])) {
            foreach ($_FILES['package_images']['name'] as $key => $filename) {
                $file_tmp = $_FILES['package_images']['tmp_name'][$key];
                $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (in_array($file_ext, $allowed_types)) {
                    $new_filename = time() . "_" . basename($filename); // Store only image name
                    move_uploaded_file($file_tmp, "uploads/" . $new_filename); // Save image to uploads folder

                    // Insert only the image name into the `package_images` table
                    $stmt = $conn->prepare("INSERT INTO package_images (package_id, image_url) VALUES (?, ?)");
                    $stmt->bind_param("is", $package_id, $new_filename);
                    $stmt->execute();
                }
            }
        }

        // Insert itinerary details
        if (!empty($_POST['day_number'])) {
            $stmt = $conn->prepare("INSERT INTO itinerary (package_id, day_number, title, description) VALUES (?, ?, ?, ?)");
            foreach ($_POST['day_number'] as $key => $day_number) {
                $day_title = $_POST['day_title'][$key];
                $day_description = $_POST['day_description'][$key];
                $stmt->bind_param("iiss", $package_id, $day_number, $day_title, $day_description);
                $stmt->execute();
            }
            $stmt->close();
        }

        // Insert inclusions
        if (!empty($_POST['inclusion'])) {
            $stmt = $conn->prepare("INSERT INTO inclusions (package_id, description) VALUES (?, ?)");
            foreach ($_POST['inclusion'] as $inclusion) {
                if (!empty($inclusion)) {
                    $stmt->bind_param("is", $package_id, $inclusion);
                    $stmt->execute();
                }
            }
            $stmt->close();
        }

        // Insert exclusions
        if (!empty($_POST['exclusion'])) {
            $stmt = $conn->prepare("INSERT INTO exclusions (package_id, description) VALUES (?, ?)");
            foreach ($_POST['exclusion'] as $exclusion) {
                if (!empty($exclusion)) {
                    $stmt->bind_param("is", $package_id, $exclusion);
                    $stmt->execute();
                }
            }
            $stmt->close();
        }

        $message = "<div class='alert alert-success'>Package added successfully!</div>";
    } catch (Exception $e) {
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}


// Fetch destinations for the dropdown
$destQuery = $conn->query("SELECT id, name FROM destinations");
$destinations = $destQuery->fetch_all(MYSQLI_ASSOC);
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

              
                <?php echo $message; ?> <!-- Display success/error messages -->
                <form method="POST" action="" enctype="multipart/form-data">
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
                    <option value="">Select Destination</option>
                    <?php foreach ($destinations as $dest) { ?>
                        <option value="<?php echo $dest['id']; ?>"><?php echo $dest['name']; ?></option>
                    <?php } ?>
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
    newItem.className = 'itinerary-item mb-3 p-3 border rounded bg-light'; // Added border for visibility
    
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
            <div class="col-12 text-end">
                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeItinerary(this)">
                    <i class="fas fa-trash"></i> Remove Day
                </button>
            </div>
        </div>
    `;

    container.appendChild(newItem);
}

// ✅ Corrected removeItinerary function
function removeItinerary(button) {
    let parent = button.closest('.itinerary-item'); // Find the nearest .itinerary-item div
    if (parent) {
        parent.remove(); // Remove the entire itinerary block
    }
}




    // Function to add Image Upload with Remove Button
    function addImageUpload() {
        const container = document.getElementById('image-container');
        const newDiv = document.createElement('div');
        newDiv.className = 'mb-3 image-upload';
        newDiv.innerHTML = `
            <input type="file" class="form-control" name="package_images[]" accept="image/*">
            <button type="button" class="btn btn-remove mt-2" onclick="removeElement(this)">
                <i class="fas fa-trash"></i> Remove Image
            </button>
        `;
        container.appendChild(newDiv);
    }

    // Function to add Inclusion with Remove Button
    function addInclusion() {
        const container = document.getElementById('inclusion-container');
        const newDiv = document.createElement('div');
        newDiv.className = 'mb-3 inclusion-item';
        newDiv.innerHTML = `
            <input type="text" class="form-control" name="inclusion[]" placeholder="Enter inclusion">
            <button type="button" class="btn btn-remove mt-2" onclick="removeElement(this)">
                <i class="fas fa-trash"></i> Remove Inclusion
            </button>
        `;
        container.appendChild(newDiv);
    }

    // Function to add Exclusion with Remove Button
    function addExclusion() {
        const container = document.getElementById('exclusion-container');
        const newDiv = document.createElement('div');
        newDiv.className = 'mb-3 exclusion-item';
        newDiv.innerHTML = `
            <input type="text" class="form-control" name="exclusion[]" placeholder="Enter exclusion">
            <button type="button" class="btn btn-remove mt-2" onclick="removeElement(this)">
                <i class="fas fa-trash"></i> Remove Exclusion
            </button>
        `;
        container.appendChild(newDiv);
    }

    // General function to remove an element
    function removeElement(button) {
        button.parentElement.remove();
    }
</script>

</body>
</html>