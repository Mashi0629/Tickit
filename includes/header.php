<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickit - Support Ticket System</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="h-full flex flex-col justify-between text-slate-800">

<header class="bg-white shadow-xs border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <a href="../index.php" class="text-2xl font-bold tracking-tight text-indigo-600">Tickit<span class="text-slate-500">.</span></a>
        <nav class="space-x-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php" class="text-sm font-medium text-slate-600 hover:text-indigo-600">Dashboard</a>
                <a href="../actions/logout.php" class="text-sm font-medium text-rose-600 hover:text-rose-700">Logout</a>
            <?php else: ?>
                <a href="login.php" class="text-sm font-medium text-slate-600 hover:text-indigo-600">Login</a>
                <a href="register.php" class="text-sm font-medium px-3 py-1.5 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition">Sign Up</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="flex-grow flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Global Notification Alerts -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-4 p-3 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-sm rounded-r-md">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="mb-4 p-3 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm rounded-r-md">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>