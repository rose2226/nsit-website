<?php
// db_connect.php - Establishes a connection to the MySQL database

$servername = "localhost"; // The server name (usually 'localhost' for XAMPP)
$username = "root";      // The default MySQL username for XAMPP
$password = "";          // The default MySQL password for XAMPP (it's empty by default)
$dbname = "nsit_student_portal"; // !!! IMPORTANT: This MUST match the database name you created in phpMyAdmin

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    // If connection fails, stop script execution and display an error message
    die("Connection failed: " . $conn->connect_error);
}

// Optional: You can uncomment the line below for testing the connection.
// If you see "Database Connected successfully!" in your browser when accessing this file, it works.
// echo "Database Connected successfully!";

// IMPORTANT: Do NOT close the connection here ($conn->close();)
// The connection should remain open for other scripts that include this file
// and will be closed after all database operations are completed in those scripts.
?>
