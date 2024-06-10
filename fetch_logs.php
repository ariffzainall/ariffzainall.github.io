<?php
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "air_guardian";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM device ORDER BY timestamp DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $status = ($row["device_status"] == 1) ? "ON" : "OFF";
        $timestamp = $row["timestamp"];
        echo "<tr><td>Switched $status the Sensor</td><td>$timestamp</td></tr>";
    }
} else {
    echo "<tr><td colspan='2'>No logs found</td></tr>";
}

$conn->close();
?>
