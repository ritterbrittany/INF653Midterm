<?php
// Include your Database.php file
include_once 'Database.php';

// Create a new instance of the Database class
$database = new Database();
$conn = $database->connect();

// Check if the connection was successful
if ($conn) {
    echo "Connection successful!";
} else {
    echo "Connection failed.";
}
?>