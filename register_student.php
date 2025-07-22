<?php
// register_student.php - Handles student registration form submission and inserts data into the database

// Include the database connection file
require_once 'db_connect.php';

// Start a session to potentially store messages or redirect after successful registration
session_start();

// Check if the form was submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Retrieve form data using $_POST superglobal
    // Use isset() and ternary operator to handle optional fields gracefully
    // Ensure 'name' attributes in HTML match these keys
    $username = $_POST['username'];
    $password = $_POST['password']; // Raw password from the form
    $confirm_password = $_POST['confirm_password']; // Confirmation password
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    // Assuming phone_number and reason_for_course are now in your DB and form
    $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : NULL;
    $course_registered = isset($_POST['course_registered']) ? $_POST['course_registered'] : NULL;
    // Database column is now 'reason_for_course' (with underscore, no space)
    // The form field name is also 'reason_for_course' (with underscore)
    $reason_for_course = isset($_POST['reason_for_course']) ? $_POST['reason_for_course'] : NULL;
    $terms_agreed = isset($_POST['terms']) ? true : false; // Check if terms checkbox was ticked

    // 2. Server-side Validation
    // Basic validation: Check if required fields are not empty
    if (empty($username) || empty($password) || empty($confirm_password) || empty($first_name) || empty($last_name) || empty($email) || !$terms_agreed) {
        echo "Error: Please fill in all required fields and agree to terms.";
        $conn->close(); // Close connection before exiting
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Error: Passwords do not match.";
        $conn->close();
        exit();
    }

    // Basic email format validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Error: Invalid email format.";
        $conn->close();
        exit();
    }

    // 3. Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 4. Combine first name and last name for 'fullname' column
    $fullname = trim($first_name . " " . $last_name);

    // 5. Prepare SQL INSERT statement with the clean column name
    // No backticks needed for `reason_for_course` as it no longer has a space
    $sql = "INSERT INTO `students` (`username`, `password`, `email`, `fullname`, `phone_number`, `course_registered`, `reason_for_course`)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Check if the statement preparation was successful
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        $conn->close();
        exit();
    }

    // 6. Bind parameters to the placeholders
    // 'sssssss' indicates the data types of the parameters (all strings in this case)
    $stmt->bind_param("sssssss", $username, $hashed_password, $email, $fullname, $phone_number, $course_registered, $reason_for_course);

    // 7. Execute the statement
    if ($stmt->execute()) {
        echo "Registration successful! You can now log in.";
        // Optional: Redirect to a success page or login page
        // header("Location: login.html?registration=success");
        // exit();
    } else {
        // Handle errors during execution (e.g., duplicate username/email if UNIQUE constraint is violated)
        if ($conn->errno == 1062) {
            echo "Error: Username or Email already exists. Please choose a different one.";
        } else {
            echo "Error during registration: " . $stmt->error;
        }
    }

    // 8. Close the statement and database connection
    $stmt->close();
    $conn->close();

} else {
    // If the script is accessed directly without a POST request
    echo "Access Denied: This page should be accessed via a form submission.";
}
?>
