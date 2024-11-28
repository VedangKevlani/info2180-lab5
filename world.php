<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *'); // Allow all origins
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Allowed HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allowed headers

$servername = "localhost";
$username = "lab5_user";  // Change this to 'root' if using root
$password = "password123";  // Empty string for 'root' user in XAMPP
$dbname = "world";

try {
    // Establish the PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the country parameter from URL
    $country = isset($_GET['country']) ? $_GET['country'] : '';

    // Prepare SQL query using prepared statements (to prevent SQL injection)
    if ($country) {
        $sql = "SELECT name, continent, year_of_independence, head_of_state FROM countries WHERE name LIKE :country";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':country' => '%' . $country . '%']); // Bind the country parameter

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            // Output the data in an HTML table
            echo "<table border='1'>";
            echo "<tr><th>Country</th><th>Continent</th><th>Year of Independence</th><th>Head of State</th></tr>";
            foreach ($results as $row) {
                echo "<tr><td>" . htmlspecialchars($row['name']) . "</td><td>" . htmlspecialchars($row['continent']) . "</td><td>" . htmlspecialchars($row['year_of_independence']) . "</td><td>" . htmlspecialchars($row['head_of_state']) . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No results found for '$country'.";
        }
    } else {
        echo "No country parameter provided.";
    }

} catch (PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
}

// Close the connection
$conn = null;
?>
