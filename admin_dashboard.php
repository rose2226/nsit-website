<?php
// admin_dashboard.php - Admin's dashboard to view registered students

session_start(); // Start the session

// Check if the user is logged in AND if their role is 'admin'
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    // If not logged in or not an admin, redirect to login page
    header("Location: login.html");
    exit();
}

// Include the database connection file
require_once 'db_connect.php';

// Get admin username from session
$admin_username = $_SESSION['username'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NSIT</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Dashboard Container Styling */
        .dashboard-container {
            min-height: calc(100vh - 120px); /* Adjust based on header/footer height */
            padding: 3rem 2rem;
            background-color: #f0f2f5; /* Lighter, subtle background */
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        /* Dashboard Card Styling */
        .dashboard-card {
            background: linear-gradient(145deg, #ffffff, #f0f0f0); /* Subtle gradient */
            padding: 3rem; /* Increased padding */
            border-radius: 15px; /* More rounded corners */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); /* Softer, deeper shadow */
            width: 100%;
            max-width: 1200px; /* Wider for better table display */
            text-align: center;
            border: 1px solid #e0e0e0; /* Light border */
        }

        .dashboard-card h1 {
            color: #1c3d70; /* Deeper blue for headings */
            margin-bottom: 1.5rem;
            font-size: 2.5rem; /* Larger heading */
            font-weight: 700;
        }

        .dashboard-card p {
            font-size: 1.15rem; /* Slightly larger text */
            color: #555;
            margin-bottom: 2rem; /* More space below paragraph */
        }

        /* Table Styling for Professional Look */
        .table-responsive {
            overflow-x: auto; /* Ensures table is scrollable on small screens */
            margin-top: 2.5rem; /* Space above table */
            border-radius: 10px; /* Rounded corners for the table container */
            border: 1px solid #d0d0d0; /* Light border around the table */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); /* Subtle shadow for the table */
        }

        table {
            width: 100%;
            border-collapse: collapse; /* Removes double borders */
            min-width: 700px; /* Ensures table doesn't get too squished on small screens */
        }

        th, td {
            padding: 15px 20px; /* More padding for spaciousness */
            text-align: left;
            border-bottom: 1px solid #e0e0e0; /* Only bottom border for cleaner lines */
        }

        th {
            background-color: #2563eb; /* Primary blue for header background */
            color: white; /* White text for contrast */
            font-weight: 600;
            text-transform: uppercase; /* Uppercase for headers */
            font-size: 0.9rem;
            letter-spacing: 0.05em;
        }

        /* Rounded corners for table headers */
        th:first-child {
            border-top-left-radius: 10px;
        }
        th:last-child {
            border-top-right-radius: 10px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa; /* Lighter shade for even rows */
        }

        tr:hover {
            background-color: #e6f0ff; /* Light blue on hover for interaction */
            transition: background-color 0.2s ease-in-out;
        }

        /* Logout Button Styling */
        .dashboard-card .btn {
            margin-top: 3rem; /* More space above logout button */
            padding: 14px 30px; /* Larger button */
            background-color: #dc2626; /* Stronger red */
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(220, 38, 38, 0.3); /* Shadow for button */
        }
        .dashboard-card .btn:hover {
            background-color: #b91c1c; /* Darker red on hover */
            transform: translateY(-1px); /* Slight lift effect */
            box-shadow: 0 6px 15px rgba(220, 38, 38, 0.4);
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
                <!-- This link will now point to the new login.html -->
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
            <h1>Welcome, Admin <?php echo htmlspecialchars($admin_username); ?>!</h1>
            <p>Here is a comprehensive list of all registered students:</p>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Course Registered</th>
                            <th>Motivation</th>
                            <th>Registration Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch student data from the database
                        // Ensure column names here exactly match your 'students' table structure
                        // Using backticks for all column names as a best practice
                        $sql = "SELECT `id`, `username`, `fullname`, `email`, `phone_number`, `course_registered`, `reason_for_course`, `registration_date` FROM `students` ORDER BY `registration_date` DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["fullname"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["phone_number"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["course_registered"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["reason_for_course"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["registration_date"]) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' style='text-align: center; padding: 20px;'>No students registered yet.</td></tr>";
                        }
                        $conn->close(); // Close connection after fetching data
                        ?>
                    </tbody>
                </table>
            </div> <!-- End table-responsive -->
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
