<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Package</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .badge-custom {
            font-size: 14px;
            margin-right: 5px;
        }
        .card-inclusions { background: #e9f9e9; }
        .card-exclusions { background: #fbe9e9; }
        .booking-box {
            background: #eaf4ff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 350px;
            margin: auto;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .price-box {
            background: #fff;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .old-price {
            text-decoration: line-through;
            color: #888;
            font-size: 14px;
        }
        .new-price {
            font-size: 26px;
            font-weight: bold;
            color: #000;
        }
        .per-person {
            font-size: 14px;
            color: #555;
        }
        .btn-book {
            background: #ff7300;
            color: white;
            font-weight: bold;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 25px;
            font-size: 16px;
        }
        .btn-enquire {
            background: none;
            border: 2px solid #007bff;
            color: #007bff;
            font-weight: bold;
            width: 100%;
            padding: 12px;
            border-radius: 25px;
            font-size: 16px;
            margin-top: 10px;
        }
        .btn-enquire:hover {
            background: #007bff;
            color: white;
        }
        .carousel-inner img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <!-- Image Carousel -->
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="uploads/cambodia.webp" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="uploads/goa.jpg" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="uploads/maldivs.jpg" class="d-block w-100" alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>

                <!-- Package Details -->
                <div class="mt-4">
                    <h2>Andaman Island Trip Package - Snorkeling at Elephant Beach</h2>
                    <span class="badge bg-secondary badge-custom">7 Nights / 8 Days</span>
                    <span class="badge bg-primary badge-custom">Fully Customisable</span>
                    <span class="badge bg-success badge-custom">Expert Choice</span>
                    <h3 class="mt-3">Starting From ₹32,999 <span class="text-decoration-line-through text-muted">₹40,999</span> <span class="badge bg-success">19% Off</span></h3>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Booking Box -->
                <div class="booking-box">
                    <p class="text-muted mb-1">Starting from</p>
                    <div class="price-box">
                        <span class="old-price">₹48,999</span>
                        <br>
                        <span class="new-price">₹44,999</span> <span class="per-person">Per Person</span>
                    </div>
                    <button class="btn btn-book">BOOK NOW</button>
                    <button class="btn btn-enquire">ENQUIRE NOW</button>
                </div>
            </div>
        </div>
        
        <!-- Itinerary Section -->
        <div class="mt-4">
            <h4>Detailed Itinerary</h4>
            <div class="accordion" id="itineraryAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#day1">Day 1 - Arrive in Port Blair</button>
                    </h2>
                    <div id="day1" class="accordion-collapse collapse show" data-bs-parent="#itineraryAccordion">
                        <div class="accordion-body">Visit Corbyn’s Cove Beach</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#day2">Day 2 - Excursion to Ross Island</button>
                    </h2>
                    <div id="day2" class="accordion-collapse collapse" data-bs-parent="#itineraryAccordion">
                        <div class="accordion-body">Visit Viper Island & North Bay</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Inclusions & Exclusions -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card card-inclusions p-3">
                    <h5 class="text-success">✔ Inclusions</h5>
                    <ul>
                        <li>Accommodation on twin Sharing Basis</li>
                        <li>Breakfast and Dinner</li>
                        <li>Non-a/c vehicle for transfers & sightseeing</li>
                        <li>All permit fees & hotel taxes</li>
                        <li>Rates are valid for INDIAN NATIONALS only</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-exclusions p-3">
                    <h5 class="text-danger">✖ Exclusions</h5>
                    <ul>
                        <li>Natural calamities expenses</li>
                        <li>Increase in taxes or fuel prices</li>
                        <li>Room Heater Charges</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
