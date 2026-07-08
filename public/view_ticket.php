<?php
// public/view_ticket.php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
$ticket_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$ticket_id) {
    header("Location: dashboard.php");
    exit();
}

// 1. FETCH TICKET DATA
try {
    $stmt = $pdo->prepare("SELECT t.*, u.name as customer_name FROM tickets t JOIN users u ON t.user_id = u.id WHERE t.id = ?");
    $stmt->execute([$ticket_id]);
    $ticket = $stmt->fetch();

    if (!$ticket) {
        die("Ticket not found.");
    }

    // Security Gate: Customers cannot peek at other customers' tickets
    if ($user_role !== 'admin' && $ticket['user_id'] !== $user_id) {
        die("Unauthorized access to this technical record.");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// 2. PROCESS INCOMING FORM ACTIONS (Status Update or New Reply)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Action A: Admin Updates Status
    if (isset($_POST['action']) && $_POST['action'] === 'update_status' && $user_role === 'admin') {
        $new_status = $_POST['status'];
        if (in_array($new_status, ['open', 'pending', 'resolved'])) {
            $update_stmt = $pdo->prepare("UPDATE tickets SET status = ? WHERE id = ?");
            $update_stmt->execute([$new_status, $ticket_id]);
            header("Location: view_ticket.php?id=" . $ticket_id);
            exit();
        }
    }

    // Action B: Someone posts a reply message
    if (isset($_POST['action']) && $_POST['action'] === 'add_reply') {
        $message = trim($_POST['message']);
        if (!empty($message)) {
            $reply_stmt = $pdo->prepare("INSERT INTO replies (ticket_id, user_id, message) VALUES (?, ?, ?)");
            $reply_stmt->execute([$ticket_id, $user_id, $message]);
            
            // If ticket was resolved, reopen it automatically if the client responds
            if ($ticket['status'] === 'resolved' && $user_role === 'customer') {
                $reopen_stmt = $pdo->prepare("UPDATE tickets SET status = 'open' WHERE id = ?");
                $reopen_stmt->execute([$ticket_id]);
            }

            header("Location: view_ticket.php?id=" . $ticket_id);
            exit();
        }
    }
}

// 3. FETCH EXISTING REPLIES FOR THE CONVERSATION THREAD
$replies_stmt = $pdo->prepare("SELECT r.*, u.name, u.role FROM replies r JOIN users u ON r.user_id = u.id WHERE r.ticket_id = ? ORDER BY r.created_at ASC");
$replies_stmt->execute([$ticket_id]);
$replies = $replies_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>#<?php echo $ticket['id']; ?> - <?php echo htmlspecialchars($ticket['title']); ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-full flex flex-col bg-slate-50 text-slate-800">

<header class="bg-white border-b border-slate-200 sticky top-0 z-10">
    <div class="max-w-4xl mx-auto px-4 py-4 flex justify-between items-center">
        <a href="dashboard.php" class="text-xl font-bold tracking-tight text-indigo-600">Tickit<span class="text-slate-400">.</span></a>
        <a href="dashboard.php" class="text-sm font-medium text-slate-600 hover:text-indigo-600">&larr; Dashboard</a>
    </div>
</header>

<main class="max-w-4xl w-full mx-auto px-4 py-8 flex-grow space-y-6">
    
    <div class="bg-white p-6 rounded-xl shadow-xs border border-slate-200">
        <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
            <div>
                <span class="text-xs font-mono text-slate-400 block mb-1">RECORD ID #<?php echo $ticket['id']; ?></span>
                <h1 class="text-2xl font-bold text-slate-900"><?php echo htmlspecialchars($ticket['title']); ?></h1>
                <p class="text-xs text-slate-500 mt-1">Submitted by <strong><?php echo htmlspecialchars($ticket['customer_name']); ?></strong> on <?php echo date('F j, Y, g:i a', strtotime($ticket['created_at'])); ?></p>
            </div>
            
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 text-xs font-semibold uppercase tracking-wider rounded-md bg-slate-100 border border-slate-200">
                    Priority: <?php echo $ticket['priority']; ?>
                </span>
                <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-md border <?php echo ($ticket['status'] === 'open' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($ticket['status'] === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-200 text-slate-700 border-slate-300')); ?>">
                    Status: <?php echo $ticket['status']; ?>
                </span>
            </div>
        </div>
        <hr class="border-slate-100 my-4">
        <p class="text-slate-700 whitespace-pre-wrap text-sm leading-relaxed"><?php echo htmlspecialchars($ticket['description']); ?></p>
    </div>

    <?php if ($user_role === 'admin'): ?>
        <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-semibold text-indigo-900">Administrative Actions</h3>
                <p class="text-xs text-indigo-700">Update this operational record's lifecycle state.</p>
            </div>
            <form action="" method="POST" class="flex gap-2 w-full sm:w-auto">
                <input type="hidden" name="action" value="update_status">
                <select name="status" class="bg-white border border-indigo-200 rounded-md text-xs px-3 py-1.5 focus:outline-none shadow-xs text-slate-700">
                    <option value="open" <?php if($ticket['status'] === 'open') echo 'selected'; ?>>Set Open</option>
                    <option value="pending" <?php if($ticket['status'] === 'pending') echo 'selected'; ?>>Set Pending</option>
                    <option value="resolved" <?php if($ticket['status'] === 'resolved') echo 'selected'; ?>>Set Resolved</option>
                </select>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs px-4 py-1.5 rounded-md shadow-xs transition cursor-pointer">Update</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="space-y-4">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Activity Thread</h3>
        
        <?php if (empty($replies)): ?>
            <p class="text-sm text-slate-400 italic bg-white p-6 rounded-xl border border-slate-200 text-center">No messages have been logged yet regarding this incident.</p>
        <?php else: ?>
            <?php foreach ($replies as $reply): 
                $isAdminReply = $reply['role'] === 'admin';
            ?>
                <div class="flex flex-col <?php echo $isAdminReply ? 'items-end' : 'items-start'; ?>">
                    <div class="max-w-[85%] rounded-xl p-4 shadow-xs border <?php echo $isAdminReply ? 'bg-indigo-600 text-white border-indigo-700' : 'bg-white text-slate-800 border-slate-200'; ?>">
                        <div class="flex items-center justify-between gap-8 mb-1.5">
                            <span class="text-xs font-bold <?php echo $isAdminReply ? 'text-indigo-200' : 'text-slate-600'; ?>">
                                <?php echo htmlspecialchars($reply['name']); ?> <?php echo $isAdminReply ? '(Staff)' : ''; ?>
                            </span>
                            <span class="text-[10px] <?php echo $isAdminReply ? 'text-indigo-200/80' : 'text-slate-400'; ?>">
                                <?php echo date('M d, g:i a', strtotime($reply['created_at'])); ?>
                            </span>
                        </div>
                        <p class="text-sm whitespace-pre-wrap leading-relaxed"><?php echo htmlspecialchars($reply['message']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-xs border border-slate-200">
        <h3 class="text-sm font-bold text-slate-900 mb-3">Add Message Response</h3>
        <form action="" method="POST" class="space-y-4">
            <input type="hidden" name="action" value="add_reply">
            <textarea name="message" rows="4" required placeholder="Type instructions, diagnostic updates, or questions here..."
                      class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"></textarea>
            <div class="flex justify-end">
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-medium py-2 px-5 rounded-md text-sm transition cursor-pointer shadow-sm">
                    Send Message
                </button>
            </div>
        </form>
    </div>
</main>

<footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-400 mt-12">
    &copy; <?php echo date('Y'); ?> Tickit Support System.
</footer>

</body>
</html>