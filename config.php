<?php
// Configuration details
$host = 'localhost';
$db_name = 'minecraft_shop';
$username = 'root';
$password = '';

try {
    // Create a secure connection using PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect to database. " . $e->getMessage());
}
?>
