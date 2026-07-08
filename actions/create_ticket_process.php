<?php
// actions/create_ticket_process.php
session_start();
require_once '../config/database.php';

// Route enforcement: Must be logged in as a customer via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../public/dashboard.php");
    exit();
}

// Extract and sanitize text parameters
$title = trim($_POST['title']);
$priority = $_POST['priority'];
$description = trim($_POST['description']);
$user_id = $_SESSION['user_id'];

// Check valid dropdown inputs
$allowed_priorities = ['low', 'medium', 'high'];
if (!in_array($priority, $allowed_priorities)) {
    $priority = 'medium'; 
}

// Basic Form validation 
if (empty($title) || empty($description)) {
    $_SESSION['error'] = "Please fill out all fields completely.";
    header("Location: ../public/create_ticket.php");
    exit();
}

try {
    // Insert values using safe PDO bindings
    $stmt = $pdo->prepare("INSERT INTO tickets (user_id, title, description, priority, status) VALUES (?, ?, ?, ?, 'open')");
    $stmt->execute([$user_id, $title, $description, $priority]);

    // Send the user back to the main dashboard panel on success
    header("Location: ../public/dashboard.php");
    exit();

} catch (PDOException $e) {
    $_SESSION['error'] = "System failure when saving ticket records. Try again later.";
    header("Location: ../public/create_ticket.php");
    exit();
}