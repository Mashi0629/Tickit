<?php
// public/register.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/header.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<div class="bg-white p-8 rounded-xl shadow-md border border-slate-100">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900">Create an account</h2>
        <p class="text-sm text-slate-500 mt-1">Get started with Tickit today.</p>
    </div>

    <form action="../actions/register_process.php" method="POST" class="space-y-4">
        <div>
            <label for="name" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">Full Name</label>
            <input type="text" name="name" id="name" required 
                   class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">Email Address</label>
            <input type="email" name="email" id="email" required 
                   class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">Password</label>
            <input type="password" name="password" id="password" required 
                   class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
        </div>

        <button type="submit" 
                class="w-full bg-indigo-600 text-white font-medium py-2 px-4 rounded-md hover:bg-indigo-700 transition text-sm cursor-pointer">
            Sign Up
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-slate-600">
        Already have an account? <a href="login.php" class="text-indigo-600 font-medium hover:underline">Log in</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>