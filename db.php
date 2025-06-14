<?php
$host = 'localhost';
$dbname = 'db9a6osacdj5tv';
$username = 'uxgukysg8xcbd';
$password = '6imcip8yfmic';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
