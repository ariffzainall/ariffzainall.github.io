<?php

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "air_guardian";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sqlSensor = "SELECT mq5_value, mq6_value, mq7_value, mq135_value FROM gas_read ORDER BY timestamp DESC LIMIT 1";
$resultSensor = $conn->query($sqlSensor);
$sensorData = [
    'mq5' => 0,
    'mq6' => 0,
    'mq7' => 0,
    'mq135' => 0
];
if ($resultSensor->num_rows > 0) {
    $rowSensor = $resultSensor->fetch_assoc();
    $sensorData['mq5'] = $rowSensor['mq5_value'];
    $sensorData['mq6'] = $rowSensor['mq6_value'];
    $sensorData['mq7'] = $rowSensor['mq7_value'];
    $sensorData['mq135'] = $rowSensor['mq135_value'];
}

//$maxSensorValue = max($sensorData['mq5'], $sensorData['mq6'], $sensorData['mq7'], $sensorData['mq135']);
//$aqiReading = round(($maxSensorValue / 1023) * 100);
$aqiReading = ($sensorData['mq5'] + $sensorData['mq6'] + $sensorData['mq7'] + $sensorData['mq135']) / 4;

$conn->close();

$response = [
    'aqi' => $aqiReading,
    'mq5' => $sensorData['mq5'],
    'mq6' => $sensorData['mq6'],
    'mq7' => $sensorData['mq7'],
    'mq135' => $sensorData['mq135']
];

header('Content-Type: application/json');

echo json_encode($response);
?>


