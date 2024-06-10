<?php
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "air_guardian";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT mq5_value, mq6_value, mq7_value, mq135_value, timestamp FROM gas_read ORDER BY timestamp ASC";

// Perform the query
$result = $conn->query($sql);

// Check for errors
if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Fetch data
$sensorData = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $sensorData[] = $row;
    }
} else {
    echo "No rows found.";
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($sensorData);
?>