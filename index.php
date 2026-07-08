<?php
// index.php
session_start();

// If the user is already authenticated, skip the landing page entirely
if (isset($_SESSION['user_id'])) {
    header("Location: public/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickit - Help Desk & Support Ticket System</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="h-full flex flex-col justify-between text-slate-800">

    <header class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <span class="text-2xl font-bold tracking-tight text-indigo-600">Tickit<span class="text-slate-400">.</span></span>
            <div class="space-x-4">
                <a href="public/login.php" class="text-sm font-medium text-slate-600 hover:text-indigo-600">Sign In</a>
                <a href="public/register.php" class="text-sm font-medium px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-xs">Get Started</a>
            </div>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center bg-radial from-indigo-50/50 to-slate-50 px-4 py-16">
        <div class="max-w-3xl text-center space-y-6">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                Now Live: Version 1.0 Built with PHP
            </span>
            
            <h1 class="text-4xl sm:text-5xl font-black tracking-tight text-slate-900 leading-tight">
                Customer support, <br class="sm:hidden">
                <span class="text-indigo-600">streamlined and simplified.</span>
            </h1>
            
            <p class="text-base sm:text-lg text-slate-600 max-w-xl mx-auto leading-relaxed">
                Tickit connects your development and support teams directly to client issues. Log tickets, track diagnostics, and maintain a seamless chat timeline in one place.
            </p>

            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="public/register.php" class="w-full sm:w-auto text-center font-medium bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 shadow-md transition-all cursor-pointer">
                    Create Free Account
                </a>
                <a href="public/login.php" class="w-full sm:w-auto text-center font-medium bg-white text-slate-700 border border-slate-200 px-8 py-3 rounded-lg hover:bg-slate-50 transition shadow-xs cursor-pointer">
                    Workspace Login &rarr;
                </a>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        &copy; <?php echo date('Y'); ?> Tickit Support System. Built with PHP & PDO.
    </footer>

</body>
</html>