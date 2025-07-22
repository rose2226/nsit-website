<?php
// student_dashboard.php - Student's personalized dashboard

session_start(); // Start the session

// Check if the user is logged in AND if their role is 'student'
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'student') {
    // If not logged in or not a student, redirect to login page
    header("Location: login.html");
    exit();
}

// If logged in as a student, you can access their session data
$student_username = $_SESSION['username'];
$student_id = $_SESSION['user_id'];

// Here you would typically fetch more student-specific data from the database
// For example, their registered courses, grades, etc.
// require_once 'db_connect.php';
// $sql = "SELECT fullname, email, course_registered FROM students WHERE id = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $student_id);
// $stmt->execute();
// $result = $stmt->get_result();
// $student_data = $result->fetch_assoc();
// $stmt->close();
// $conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - NSIT</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .dashboard-container {
            min-height: calc(100vh - 120px); /* Adjust based on header/footer height */
            padding: 3rem 2rem;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .dashboard-card {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 800px;
            text-align: center;
        }
        .dashboard-card h1 {
            color: #2563eb;
            margin-bottom: 1.5rem;
            font-size: 2.2rem;
        }
        .dashboard-card p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 1rem;
        }
        .dashboard-card .btn {
            margin-top: 1.5rem;
            display: inline-block;
            padding: 12px 25px;
            background-color: #ef4444; /* Red for logout */
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .dashboard-card .btn:hover {
            background-color: #dc2626;
        }
    </style>
</head>
<body>
    <!-- Navigation (copied from your existing structure) -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <h1>NSIT</h1>
                <p>Nik Speed Institute</p>
            </div>
            <div class="nav-menu" id="nav-menu">
                <a href="index.html" class="nav-link">Home</a>
                <a href="about.html" class="nav-link">About</a>
                <a href="courses.html" class="nav-link">Courses</a>
                <a href="testimonials.html" class="nav-link">Success Stories</a>
            </div>
            <div class="nav-buttons">
                <a href="login.html" class="btn btn-primary">Login</a> 
                <a href="contact.html" class="btn btn-outline">Contact Us</a>
            </div>
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <section class="dashboard-container">
        <div class="dashboard-card">
            <h1>Hello, <?php echo htmlspecialchars($student_username); ?>!</h1>
            <p>Welcome to your student dashboard. Here you can view your registered courses, progress, and more.</p>
            <p>Your Student ID: <?php echo htmlspecialchars($student_id); ?></p>
            <!-- Add more student-specific content here later -->
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </section>

    <!-- Footer (copied from your existing structure) -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>NSIT</h3>
                    <p>Nik Speed Institute of Technology - Empowering communities through quality computer education.</p>
                    <div class="social-links">
                        <a href="#" class="social-link">Facebook</a>
                        <a href="#" class="social-link">Twitter</a>
                        <a href="#" class="social-link">LinkedIn</a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="about.html">About Us</a></li>
                        <li><a href="courses.html">Courses</a></li>
                        <li><a href="register.html">Register</a></li>
                        <li><a href="testimonials.html">Success Stories</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Popular Courses</h4>
                    <ul>
                        <li><a href="courses.html">Internet Basics</a></li>
                        <li><a href="courses.html">MS Office Suite</a></li>
                        <li><a href="courses.html">Social Media Marketing</a></li>
                        <li><a href="courses.html">Computer Hardware</a></li>
                        <li><a href="courses.html">AI & Cryptocurrency</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact Info</h4>
                    <p>Address: Hoima Road,Kasubi-Kampala,Uganda.</p>
                    <p>Phone: +256 701 574 837</p>
                    <p>Email: info@nsit.com</p>
                    <p> Mon-Fri: 8AM-6PM, Sat: 9AM-4PM</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Nik Speed Institute of Technology. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
