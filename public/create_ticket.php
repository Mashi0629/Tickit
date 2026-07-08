<?php
// public/create_ticket.php
session_start();

// Redirect if not logged in or if the user is an admin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['user_role'] !== 'customer') {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ticket - Tickit</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-full flex flex-col justify-between text-slate-800">

<header class="bg-white border-b border-slate-200">
    <div class="max-w-3xl mx-auto px-4 py-4 flex justify-between items-center">
        <a href="dashboard.php" class="text-2xl font-bold tracking-tight text-indigo-600">Tickit<span class="text-slate-400">.</span></a>
        <a href="dashboard.php" class="text-sm font-medium text-slate-600 hover:text-indigo-600">&larr; Back to Dashboard</a>
    </div>
</header>

<main class="max-w-3xl w-full mx-auto px-4 py-8 flex-grow">
    <div class="bg-white p-8 rounded-xl shadow-xs border border-slate-200">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Open a New Support Ticket</h1>
            <p class="text-sm text-slate-500 mt-1">Please provide clear details about your technical issue so our team can assist you quickly.</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-4 p-3 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-sm rounded-r-md">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="../actions/create_ticket_process.php" method="POST" class="space-y-5">
            <div>
                <label for="title" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">Ticket Subject / Title</label>
                <input type="text" name="title" id="title" required placeholder="e.g., Unable to access database cluster"
                       class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
            </div>

            <div>
                <label for="priority" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">Severity / Priority Level</label>
                <select name="priority" id="priority" required
                        class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-xs bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    <option value="low">Low - Minor issue / Question</option>
                    <option value="medium" selected>Medium - Normal production operational problem</option>
                    <option value="high">High - System blocking issue</option>
                </select>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">Detailed Description</label>
                <textarea name="description" id="description" rows="6" required placeholder="Describe what went wrong, steps to reproduce, or error messages..."
                          class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"></textarea>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" 
                        class="bg-indigo-600 text-white font-medium py-2 px-6 rounded-lg hover:bg-indigo-700 shadow-sm transition text-sm cursor-pointer">
                    Submit Ticket
                </button>
            </div>
        </form>
    </div>
</main>

<footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-400">
    &copy; <?php echo date('Y'); ?> Tickit Support System.
</footer>

</body>
</html>