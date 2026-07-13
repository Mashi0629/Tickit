<?php
// actions/demo_admin_process.php
session_start();
require_once '../config/database.php';

try {
    // Look for our dedicated system admin account
    $stmt = $pdo->prepare("SELECT id, name, role FROM users WHERE email = ? AND role = 'admin' LIMIT 1");
    $stmt->execute(['admin@tickit.com']);
    $admin = $stmt->fetch();

    if ($admin) {
        // Securely reset and regenerate session
        session_regenerate_id(true);

        // Inject Admin properties directly into the state session
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_name'] = $admin['name'];
        $_SESSION['user_role'] = $admin['role'];

        header("Location: ../public/dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Demo Admin account not found. Please run the SQL setup query.";
        header("Location: ../index.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Database connection issue.";
    header("Location: ../index.php");
    exit();
}