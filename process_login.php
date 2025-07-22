<?php
// process_login.php - Handles login form submission for both students and admins

// Start a session to manage user login state across pages
session_start();

// Include the database connection file
require_once 'db_connect.php';

// Check if the form was submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username, password, and role from the login form
    $username = $_POST['username'];
    $password = $_POST['password']; // Raw password from the form
    $role = $_POST['role']; // 'student' or 'admin'

    // Basic validation: Check if required fields are not empty
    if (empty($username) || empty($password) || empty($role)) {
        echo "Error: Please enter both username and password, and select a role.";
        $conn->close();
        exit();
    }

    $table_name = "";
    $redirect_page = "";

    // Determine which table to query based on the selected role
    if ($role === 'student') {
        $table_name = "students";
        $redirect_page = "student_dashboard.php"; // You will create this page next
    } elseif ($role === 'admin') {
        $table_name = "admins";
        $redirect_page = "admin_dashboard.php"; // You already have a placeholder for this
    } else {
        echo "Invalid role selected.";
        $conn->close();
        exit();
    }

    // Prepare the SQL statement to fetch user/admin data
    // Using backticks for table and column names as a best practice
    $sql = "SELECT `id`, `username`, `password` FROM `" . $table_name . "` WHERE `username` = ?";

    $stmt = $conn->prepare($sql);

    // Check if statement preparation was successful
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        $conn->close();
        exit();
    }

    // Bind the username parameter
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a user/admin with that username exists
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashed_password_from_db = $row['password'];

        // Verify the password using password_verify() (for hashed passwords)
        if (password_verify($password, $hashed_password_from_db)) {
            // Password is correct!
            // Set session variables to mark the user as logged in
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $role; // Store the role in the session

            // Redirect to the appropriate dashboard
            header("Location: " . $redirect_page);
            exit(); // Always exit after a header redirect
        } else {
            // Incorrect password
            echo "Invalid username or password.";
        }
    } else {
        // Username not found
        echo "Invalid username or password.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

} else {
    // If the script is accessed directly without a POST request
    echo "Access Denied: This page should be accessed via a form submission.";
}
?>
