<?php
// Database connection details
$host = 'localhost'; // Usually 'localhost'
$dbname = 'lan_iwt'; // Name of the database
$username = 'root'; // Your MySQL username (default is 'root')
$password = ''; // Your MySQL password (default is empty for localhost)

// Create a new connection to the MySQL database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    //echo "Successfully connected to the database.";
}
?>
