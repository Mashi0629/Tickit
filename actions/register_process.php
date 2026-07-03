<?php
// actions/register_process.php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim spaces and sanitize inputs
    $name = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Basic Validation
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../public/register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: ../public/register.php");
        exit();
    }

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $_SESSION['error'] = "This email is already registered.";
            header("Location: ../public/register.php");
            exit();
        }

        // Securely hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user (Default role is 'customer')
        $insert_stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'customer')");
        $insert_stmt->execute([$name, $email, $hashed_password]);

        $_SESSION['success'] = "Registration successful! Please log in.";
        header("Location: ../public/login.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Something went wrong. Please try again later.";
        header("Location: ../public/register.php");
        exit();
    }
} else {
    header("Location: ../public/register.php");
    exit();
}