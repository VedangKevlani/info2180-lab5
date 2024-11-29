<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *'); // Allow all origins
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Allowed HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allowed headers

$servername = "localhost";
$username = "lab5_user";
$port = '3307';
$password = "password123";
$dbname = "world";

try {
    // Create the PDO connection
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $country = isset($_GET['country']) ? $_GET['country'] : '';
    $lookup = isset($_GET['lookup']) ? $_GET['lookup'] : '';

    // Debugging: Check if lookup is set and country is provided
    if ($lookup === 'country' && !empty($country)) {
        // Query for country data
        $stmt = $conn->prepare("SELECT name, continent, independence_year, head_of_state FROM countries WHERE name LIKE :country");
        $stmt->bindValue(':country', '%' . $country . '%', PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($rows) {
            echo '<table border="1"><thead><tr><th>Name</th><th>Continent</th><th>Independence</th><th>Head of State</th></thead><tbody>';
            foreach ($rows as $row) {
                echo "<tr><td>" . htmlspecialchars($row['name'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['continent'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['independence_year'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['head_of_state'] ?? '') . "</td></tr>";
            }
            echo '</tbody></table>';
        } else {
            echo "<p>No countries found matching '$country'.</p>";
        }
    } elseif ($lookup === 'city' && !empty($country)) {
        // Query for cities in the country
        $stmt = $conn->prepare("
            SELECT c.name AS city, c.district, c.population 
            FROM cities c
            JOIN countries co ON c.country_code = co.code
            WHERE co.name LIKE :country
        ");
        $stmt->bindValue(':country', '%' . $country . '%', PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($rows) {
            echo '<table border="1"><thead><tr><th>City</th><th>District</th><th>Population</th></tr></thead><tbody>';
            foreach ($rows as $row) {
                echo "<tr><td>" . htmlspecialchars($row['city'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['district'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['population'] ?? '') . "</td></tr>";
            }
            echo '</tbody></table>';
        } else {
            echo "<p>No cities found for '$country'.</p>";
        }
    } else {
        echo "<p>No country specified or invalid lookup type.</p>";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
