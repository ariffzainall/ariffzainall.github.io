<?php

//updated 6/9/24 || 8.54

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "air_guardian";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Use 'timestamp' instead of 'updated_at'
$sql = "SELECT device_status FROM device ORDER BY timestamp DESC LIMIT 1"; 
$result = $conn->query($sql);

if ($result === false) {
    die("Error: " . $conn->error);
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row["device_status"]; 
} else {
    echo "0"; 
}

$conn->close();
?>
