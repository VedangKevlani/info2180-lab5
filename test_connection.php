<?php
$servername = "localhost";
$username = "lab5_user"; // Use 'root' if using default XAMPP user
$password = "password123"; // Use '' if 'root' has no password
$dbname = "world";

try {
    // Create a new PDO instance and connect to the database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully!";
} catch (PDOException $e) {
    // If connection fails, print the error
    echo "Connection failed: " . $e->getMessage();
}
?>
