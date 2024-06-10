<?php
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "air_guardian";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$status = $_POST['status'];

$sql = "INSERT device (device_status) VALUES ('$status')"; 
if ($conn->query($sql) === TRUE) {
    echo "Device status updated successfully";
} else {
    echo "Error updating device status: " . $conn->error;
}

$conn->close();
?>
