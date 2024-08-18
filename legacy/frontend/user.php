<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$servername = "localhost";
$username = "example_user";
$password = "password";
$dbname = "userinfo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch data from 'user' table
$sql = "SELECT * FROM user";
$result = $conn->query($sql);

// Initialize an array to hold the user data
$users = array();

if ($result) {
    if ($result->num_rows > 0) {
        // Fetch all rows as an associative array
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    } else {
        echo "0 results";
    }
} else {
    echo "Query error: " . $conn->error;
}



// Close the connection
$conn->close();
?>
