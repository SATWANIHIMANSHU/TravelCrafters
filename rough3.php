<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Destination</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h4 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .btn-submit {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h4>Add New Destination</h4>
            <form id="destinationForm" action="insert_destination.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Destination Name</label>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Enter destination name">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required placeholder="Enter description"></textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Upload Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>

                <div class="mb-3">
                    <label for="display_type" class="form-label">Display Type</label>
                    <select class="form-control" id="display_type" name="display_type" required>
                        <option value="">Select Display Type</option>
                        <option value="Featured">Featured</option>
                        <option value="Popular">Popular</option>
                        <option value="Recommended">Recommended</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-submit">Submit</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $("#destinationForm").submit(function (e) {
                let image = $("#image").val();
                if (image === '') {
                    alert("Please upload an image");
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>
