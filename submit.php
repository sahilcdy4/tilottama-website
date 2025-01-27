<?php
// Database credentials
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'tilottama_campus';

// Attempt to connect to MySQL database
$link = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$link) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $full_name = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $program = $_POST['program'];
    $additional_info = $_POST['message'];

    // Handle file upload (documents)
    $documents_path = '';
    if (isset($_FILES['documents']) && $_FILES['documents']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/'; // Directory to store uploaded files
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // Create the directory if it doesn't exist
        }
        $file_name = basename($_FILES['documents']['name']);
        $file_path = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['documents']['tmp_name'], $file_path)) {
            $documents_path = $file_path;
        }
    }

    // Insert data into the database
    $sql = "INSERT INTO admissions (full_name, email, phone, program, additional_info, documents_path) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssss", $full_name, $email, $phone, $program, $additional_info, $documents_path);
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Application submitted successfully!";
        } else {
            $error_message = "Error: " . mysqli_error($link);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_message = "Error: " . mysqli_error($link);
    }
}

// Close the database connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admissions - Tilottama Campus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .custom-border:hover {
          border-bottom: 2px solid black;
          width:auto;
        }
        .admission-section {
            background-color: #f8f9fa;
            padding: 50px 0;
        }
        .admission-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body style="background-color: #f2f2f2;">

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
          <a class="navbar-brand text-white" href="#">Tilottama Campus</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a class="nav-link active custom-border" aria-current="page" href="index.html">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active custom-border" href="#features">About Us</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active custom-border" href="admissions.html">Admissions</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active custom-border" href="#contact">Contact</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

    <!-- Admissions Section -->
    <section class="admission-section">
        <div class="container">
            <h2 class="text-center mb-4">Admissions</h2>
            <p class="text-center mb-5">Join Tilottama Campus and take the first step towards a bright future. Below you'll find all the information you need to apply.</p>

            <div class="row">
                <div class="col-md-6">
                    <h3>Admission Process</h3>
                    <p>Our admission process is designed to be simple and straightforward. Follow these steps to apply:</p>
                    <ol>
                        <li><strong>Step 1:</strong> Fill out the online application form below.</li>
                        <li><strong>Step 2:</strong> Submit the required documents (transcripts, ID, etc.).</li>
                        <li><strong>Step 3:</strong> Attend an interview (if required).</li>
                        <li><strong>Step 4:</strong> Receive your admission decision via email.</li>
                        <li><strong>Step 5:</strong> Complete enrollment by paying the admission fee.</li>
                    </ol>
                </div>
                <div class="col-md-6">
                    <h3>Admission Requirements</h3>
                    <p>To be eligible for admission, you must meet the following requirements:</p>
                    <ul>
                        <li>Completed application form.</li>
                        <li>High school diploma or equivalent.</li>
                        <li>Official transcripts from previous schools.</li>
                        <li>Valid identification (e.g., passport, citizenship certificate).</li>
                        <li>Passport-sized photographs.</li>
                    </ul>
                </div>
            </div>

            <!-- Admission Form -->
            <div class="row mt-5">
                <div class="col-md-8 offset-md-2">
                    <div class="admission-form">
                        <h3 class="text-center mb-4">Application Form</h3>
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Enter your full name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
                            </div>
                            <div class="mb-3">
                                <label for="program" class="form-label">Program of Interest</label>
                                <select class="form-select" id="program" name="program" required>
                                    <option value="">Select a program</option>
                                    <option value="Science">Science</option>
                                    <option value="Management">Management</option>
                                    <option value="BBA">BBA (Bachelor of Business Administration)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Additional Information</label>
                                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Any additional information or questions"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="documents" class="form-label">Upload Documents</label>
                                <input type="file" class="form-control" id="documents" name="documents" multiple>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">Submit Application</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; 2025 Tilottama Campus | All Rights Reserved</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>