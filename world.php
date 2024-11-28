<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html; charset=utf-8");  // Change content type to HTML for rendering table
$host = 'localhost';
$port = '3307';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    // Establish PDO connection
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get the 'query' parameter from the request
    $query = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';  // Using 'query' as the key for better consistency

    // Default SQL query if no country is provided
    $sql = "SELECT name, continent, independence_year, head_of_state FROM countries";

    if ($query) {
        $sql .= " WHERE name LIKE :country";
    }

    $stmt = $conn->prepare($sql);

    // If there is a search query, bind the parameter
    if ($query) {
        $countryParam = "%$query%";  // Add wildcards for partial match
        $stmt->bindParam(':country', $countryParam);
    }

    $stmt->execute();

    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';
    echo '<thead><tr>';
    echo '<th>Country Name</th>';
    echo '<th>Continent</th>';
    echo '<th>Independence Year</th>';
    echo '<th>Head of State</th>';
    echo '</tr></thead>';
    echo '<tbody>';

    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['continent']) . '</td>';
        echo '<td>' . htmlspecialchars($row['independence_year']) . '</td>';
        echo '<td>' . htmlspecialchars($row['head_of_state']) . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';

} catch (PDOException $e) {
    // If there's a connection error, return a message
    echo '<p>Error: Connection failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>
