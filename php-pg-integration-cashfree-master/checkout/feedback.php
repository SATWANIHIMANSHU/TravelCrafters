<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Feedback | TravelCrafters</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(to right, #74ebd5, #acb6e5);
      font-family: 'Poppins', sans-serif;
    }

    .feedback-container {
      max-width: 600px;
      margin: 60px auto;
      background: #fff;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
      transition: 0.3s;
    }

    .feedback-container:hover {
      transform: translateY(-5px);
    }

    h2 {
      font-weight: 600;
      color: #333;
    }

    .form-label {
      font-weight: 500;
    }

    .form-control {
      border-radius: 12px;
      transition: box-shadow 0.3s ease;
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
      border-color: #007bff;
    }

    .btn-submit {
      background: #007bff;
      border: none;
      font-size: 17px;
      font-weight: 600;
      border-radius: 30px;
      padding: 12px 0;
      transition: 0.3s;
    }

    .btn-submit:hover {
      background: #0056b3;
    }

    .btn-skip {
      background-color: transparent;
      color: #6c757d;
      border: none;
      font-weight: 500;
      text-decoration: underline;
      margin-top: 15px;
      display: block;
      text-align: center;
    }

    .btn-skip:hover {
      color: #343a40;
    }

    .rating-stars select {
      font-size: 1.1rem;
    }
  </style>
</head>
<body>
  <div class="container feedback-container">
    <h2 class="text-center mb-2">We Value Your Feedback 💬</h2>
    <p class="text-center text-muted mb-4">Your opinion helps us improve TravelCrafters!</p>

    <form action="submit_feedback.php" method="POST">
      <div class="mb-3">
        <label class="form-label">Your Name</label>
        <input type="text" class="form-control" name="name" placeholder="Enter your name" required />
      </div>

      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" class="form-control" name="email" placeholder="Enter your email" required />
      </div>

      <div class="mb-3 rating-stars">
        <label class="form-label">Rate Us</label>
        <select class="form-control" name="rating" required>
          <option value="">-- Select Rating --</option>
          <option value="5">⭐⭐⭐⭐⭐ - Excellent</option>
          <option value="4">⭐⭐⭐⭐ - Very Good</option>
          <option value="3">⭐⭐⭐ - Good</option>
          <option value="2">⭐⭐ - Fair</option>
          <option value="1">⭐ - Poor</option>
        </select>
      </div>

      <div class="mb-4">
        <label class="form-label">Feedback</label>
        <textarea class="form-control" name="message" rows="4" placeholder="Tell us how we did..." required></textarea>
      </div>

      <button type="submit" class="btn btn-submit w-100">Submit Feedback</button>
      <a href="submit_feedback.php?skip=true" class="btn-skip">Skip for now</a>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
