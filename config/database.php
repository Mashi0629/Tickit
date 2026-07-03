<?php
// config/database.php

$host = 'localhost';
$db   = 'support_system';
$user = 'root'; // Change to your DB username
$pass = '';     // Change to your DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throws errors if queries fail
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Returns arrays with column names
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Turns off emulation for safer prepared statements
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("Database connection failed: " . $e->getMessage());
}
?>