
<!-- Enquiry Modal -->
<div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enquire for <span id="packageName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="enquiryForm">
                    <input type="hidden" name="package_id" id="packageId">

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Your Name"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile No.</label>
                        <input type="text" class="form-control" id="mobile" name="mobile"
                            placeholder="+91 Mobile No." required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email ID</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Your E-Mail Address" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Passengers</label>
                        <div class="d-flex">
                            <div class="input-group me-3">
                                <span class="input-group-text">Adult</span>
                                <input type="number" class="form-control text-center" id="adult_count"
                                    name="adult_count" value="1" min="1">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Child</span>
                                <input type="number" class="form-control text-center" id="child_count"
                                    name="child_count" value="0" min="0">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Submit Enquiry</button>
                </form>
            </div>
        </div>
    </div>
</div>


                    <!-- Enquiry Now Button -->
                    <button class="btn btn-enquire" data-bs-toggle="modal" data-bs-target="#enquiryModal"
                        data-package-id="<?= htmlspecialchars($package['id'] ?? 0) ?>"
                        data-package-name="<?= htmlspecialchars($package['name'] ?? 'Package') ?>">
                        ENQUIRE NOW
                    </button>


<script>
        // Handle "Enquire Now" button clicks dynamically
        document.addEventListener("click", function (event) {
            if (event.target.classList.contains("btn-enquire")) {
                let packageId = event.target.getAttribute("data-package-id");
                let packageName = event.target.getAttribute("data-package-name");

                document.getElementById("packageId").value = packageId; // Set package ID
                document.getElementById("packageName").innerText = packageName; // Set package name
            }
        });

        // Handle Form Submission with AJAX
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("enquiryForm").addEventListener("submit", function (e) {
                e.preventDefault(); // Prevent page reload

                let formData = new FormData(this);

                fetch("submit_enquiry.php", {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.text()) // Get text response
                    .then(data => {
                        console.log("Response from server:", data.trim()); // Debugging

                        if (data.trim() === "success") {
                            // Show Bootstrap toast notification
                            let toastElement = document.getElementById("successToast");
                            let toast = new bootstrap.Toast(toastElement);
                            toast.show();

                            // Reset form fields
                            document.getElementById("enquiryForm").reset();

                            // Hide the modal after submission
                            let enquiryModal = bootstrap.Modal.getInstance(document.getElementById('enquiryModal'));
                            enquiryModal.hide();
                        } else {
                            alert("Error: " + data);
                        }
                    })
                    .catch(error => {
                        console.error("AJAX Error:", error);
                    });
            });
        });



    </script>
