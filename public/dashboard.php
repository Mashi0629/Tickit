<?php
// public/dashboard.php
require_once '../config/database.php';

// Check if user is logged in. If not, kick them out to login page.
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

try {
    // Determine query based on role
    if ($user_role === 'admin') {
        // Admins see all tickets + the creator's name
        $query = "SELECT t.*, u.name as customer_name 
                  FROM tickets t 
                  JOIN users u ON t.user_id = u.id 
                  ORDER BY t.created_at DESC";
        $stmt = $pdo->query($query);
        $tickets = $stmt->fetchAll();
    } else {
        // Customers only see their own tickets
        $query = "SELECT * FROM tickets WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id]);
        $tickets = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    die("Error fetching dashboard data: " . $e->getMessage());
}

// Custom function to color code our status badges beautifully
function getStatusClass($status) {
    return match($status) {
        'open' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
        'resolved' => 'bg-slate-100 text-slate-600 border-slate-200',
        default => 'bg-slate-50 text-slate-700 border-slate-200'
    };
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Tickit</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-full flex flex-col justify-between text-slate-800">

<header class="bg-white border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <span class="text-2xl font-bold tracking-tight text-indigo-600">Tickit<span class="text-slate-400">.</span></span>
        <div class="flex items-center space-x-4">
            <span class="text-sm text-slate-600">Hello, <strong><?php echo htmlspecialchars($user_name); ?></strong> (<?php echo ucfirst($user_role); ?>)</span>
            <a href="logout.php" class="text-sm font-medium text-rose-600 hover:text-rose-700">Logout</a>
        </div>
    </div>
</header>

<main class="max-w-6xl w-full mx-auto px-4 py-8 flex-grow">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Workspace Dashboard</h1>
            <p class="text-sm text-slate-500">Manage and track live support interactions.</p>
        </div>
        
        <?php if ($user_role === 'customer'): ?>
            <a href="create_ticket.php" class="inline-flex items-center bg-indigo-600 text-white font-medium px-4 py-2 rounded-lg hover:bg-indigo-700 shadow-sm transition text-sm cursor-pointer">
                + Create New Ticket
            </a>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h2 class="font-semibold text-slate-700">Active Technical Records (<?php echo count($tickets); ?>)</h2>
        </div>

        <?php if (empty($tickets)): ?>
            <div class="p-12 text-center">
                <p class="text-slate-400 text-sm">No tickets found in this workspace view.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Subject</th>
                            <?php if ($user_role === 'admin'): ?>
                                <th class="px-6 py-3">Submitted By</th>
                            <?php endif; ?>
                            <th class="px-6 py-3">Priority</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Created</th>
                            <th class="px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php foreach ($tickets as $ticket): ?>
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4 font-mono font-medium text-slate-400">#<?php echo $ticket['id']; ?></td>
                                <td class="px-6 py-4 font-medium text-slate-900">
                                    <?php echo htmlspecialchars($ticket['title']); ?>
                                </td>
                                <?php if ($user_role === 'admin'): ?>
                                    <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($ticket['customer_name']); ?></td>
                                <?php endif; ?>
                                <td class="px-6 py-4">
                                    <span class="text-xs capitalize font-medium">
                                        <?php echo $ticket['priority']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full border <?php echo getStatusClass($ticket['status']); ?>">
                                        <?php echo ucfirst($ticket['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">
                                    <?php echo date('M d, Y', strtotime($ticket['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="view_ticket.php?id=<?php echo $ticket['id']; ?>" class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1.5 rounded-md transition">
                                        Open View &rarr;
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-400">
    &copy; <?php echo date('Y'); ?> Tickit Support System.
</footer>

</body>
</html>