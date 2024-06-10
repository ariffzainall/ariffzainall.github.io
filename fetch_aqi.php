<?php
include './include/config.php'; // Include your database connection settings

// Function to calculate AQI based on sensor values
function calculateAQI($mq5, $mq6, $mq7, $mq135) {
    // Normalize the values to percentage
    $normalized_mq5 = $mq5 / 1023 * 100;
    $normalized_mq6 = $mq6 / 1023 * 100;
    $normalized_mq7 = $mq7 / 1023 * 100;
    $normalized_mq135 = $mq135 / 1023 * 100;

    // Placeholder calculation; replace with actual AQI calculation logic if needed
    return ($normalized_mq5 + $normalized_mq6 + $normalized_mq7 + $normalized_mq135) / 4;
}

// Function to determine air type and action to be taken based on AQI
function getAirTypeAndAction($aqi) {
    if ($aqi <= 50) {
        return ['Good', 'No action needed'];
    } elseif ($aqi <= 100) {
        return ['Moderate', 'Sensitive individuals should avoid outdoor exertion'];
    } elseif ($aqi <= 150) {
        return ['Unhealthy for Sensitive Groups', 'Sensitive individuals should avoid outdoor exertion'];
    } elseif ($aqi <= 200) {
        return ['Unhealthy', 'Everyone should avoid outdoor exertion'];
    } elseif ($aqi <= 300) {
        return ['Very Unhealthy', 'Everyone should avoid all outdoor exertion'];
    } else {
        return ['Hazardous', 'Everyone should remain indoors'];
    }
}

// Fetch data grouped by day
$sql = "SELECT 
            DATE(timestamp) as date, 
            AVG(mq5_value) as avg_mq5, 
            AVG(mq6_value) as avg_mq6, 
            AVG(mq7_value) as avg_mq7, 
            AVG(mq135_value) as avg_mq135 
        FROM gas_read 
        GROUP BY DATE(timestamp)";

$result = $conn->query($sql);

$daily_data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $aqi = calculateAQI($row['avg_mq5'], $row['avg_mq6'], $row['avg_mq7'], $row['avg_mq135']);
        list($airType, $action) = getAirTypeAndAction($aqi);
        $daily_data[] = [
            'date' => $row['date'],
            'aqi' => round($aqi, 2),
            'airType' => $airType,
            'action' => $action
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($daily_data);

$conn->close();
?>
