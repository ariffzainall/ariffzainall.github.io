<?php
$host = 'localhost';
$db = 'air_guardian';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Query to get all phone numbers
$query = "SELECT phonenumber FROM student";
$stmt = $pdo->query($query);
$results = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($results);
?>
