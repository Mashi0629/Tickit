<?php
// index.php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: public/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 selection:bg-indigo-500 selection:text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickit - Premium Help Desk & Support Platform</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        /* Smooth pulsating abstract glow background effect */
        @keyframes pulseGlow {
            0%, 100% { transform: scale(1) translate(0px, 0px); opacity: 0.4; }
            50% { transform: scale(1.15) translate(30px, -50px); opacity: 0.6; }
        }
        .animate-glow-1 { animation: pulseGlow 8s infinite ease-in-out; }
        .animate-glow-2 { animation: pulseGlow 12s infinite ease-in-out reverse; }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between text-slate-300 antialiased overflow-x-hidden relative">

    <div class="absolute top-0 left-1/4 -translate-x-1/2 w-[500px] h-[500px] bg-indigo-600/20 rounded-full blur-[120px] pointer-events-none animate-glow-1"></div>
    <div class="absolute top-20 right-1/4 translate-x-1/2 w-[400px] h-[400px] bg-violet-600/15 rounded-full blur-[100px] pointer-events-none animate-glow-2"></div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="absolute top-6 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4">
            <div class="p-4 bg-slate-900/90 border border-rose-500/30 text-rose-300 text-sm rounded-xl backdrop-blur-md shadow-2xl flex items-center justify-between">
                <span><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></span>
                <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-200 font-bold ml-2 cursor-pointer">&times;</button>
            </div>
        </div>
    <?php endif; ?>

    <header class="bg-slate-950/70 border-b border-slate-900 sticky top-0 z-40 backdrop-blur-md transition-all">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2 group">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-md shadow-indigo-500/20 group-hover:rotate-6 transition-transform">
                    <span class="text-white font-black text-sm">T</span>
                </div>
                <span class="text-xl font-bold tracking-tight text-white">Tickit<span class="text-indigo-500">.</span></span>
            </div>
            <div class="flex items-center space-x-6">
                <a href="public/login.php" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">Sign In</a>
                <a href="public/register.php" class="text-sm font-medium px-4 py-2 rounded-lg bg-white text-slate-950 hover:bg-slate-200 transition shadow-lg shadow-white/5 font-semibold">Get Started</a>
            </div>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center px-6 py-20 relative z-10">
        <div class="max-w-4xl text-center space-y-8">
            
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium bg-slate-900 border border-slate-800 text-slate-400 backdrop-blur-xs hover:border-indigo-500/30 transition-colors">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Production Environment Core Active v1.0</span>
            </div>
            
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white leading-[1.15]">
                Customer engineering operations, <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-indigo-400 via-violet-400 to-fuchsia-400 bg-clip-text text-transparent">streamlined into focus.</span>
            </h1>
            
            <p class="text-base sm:text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed font-light">
                Tickit unifies customer success metrics and raw terminal debugging. Track enterprise incident lifecycles through an elegant conversational ecosystem.
            </p>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-center gap-4 max-w-md mx-auto sm:max-w-none">
                <a href="public/register.php" class="w-full sm:w-auto text-center font-semibold bg-gradient-to-r from-indigo-500 to-violet-600 text-white px-8 py-3.5 rounded-xl hover:from-indigo-600 hover:to-violet-700 shadow-xl shadow-indigo-500/10 hover:shadow-indigo-500/20 active:scale-98 transition-all cursor-pointer">
                    Create Workspace Account
                </a>
                <a href="public/login.php" class="w-full sm:w-auto text-center font-medium bg-slate-900 hover:bg-slate-850 text-slate-300 border border-slate-800 px-8 py-3.5 rounded-xl active:scale-98 transition-all cursor-pointer backdrop-blur-xs">
                    Workspace Portal &rarr;
                </a>
                <a href="actions/demo_admin_process.php" class="w-full sm:w-auto text-center font-semibold bg-slate-900 hover:bg-amber-950/20 text-amber-400 border border-amber-500/20 px-8 py-3.5 rounded-xl active:scale-98 transition-all cursor-pointer backdrop-blur-xs flex items-center justify-center gap-2 group">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 group-hover:scale-125 transition-transform"></span>
                    Admin Console Log
                </a>
            </div>

            <div class="pt-12 max-w-5xl mx-auto relative group">
                <div class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-indigo-500/10 to-violet-500/10 opacity-30 blur-lg group-hover:opacity-50 transition duration-500"></div>
                <div class="relative bg-slate-900/60 border border-slate-800/80 rounded-2xl p-3 backdrop-blur-md shadow-2xl">
                    <div class="flex items-center gap-2 px-3 pb-3 border-b border-slate-800/60">
                        <div class="w-3 h-3 rounded-full bg-rose-500/40"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-500/40"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-500/40"></div>
                        <div class="bg-slate-950/60 text-[10px] text-slate-500 font-mono px-4 py-0.5 rounded-md ml-4 border border-slate-900">localhost/tickit/dashboard</div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 p-4 text-left">
                        <div class="col-span-3 sm:col-span-1 bg-slate-950/40 rounded-xl p-4 border border-slate-900/50 space-y-2">
                            <div class="h-3 bg-indigo-500/20 rounded w-1/2"></div>
                            <div class="h-6 bg-indigo-500/10 rounded w-1/3"></div>
                        </div>
                        <div class="col-span-3 sm:col-span-1 bg-slate-950/40 rounded-xl p-4 border border-slate-900/50 space-y-2">
                            <div class="h-3 bg-emerald-500/20 rounded w-2/3"></div>
                            <div class="h-6 bg-emerald-500/10 rounded w-1/4"></div>
                        </div>
                        <div class="col-span-3 sm:col-span-1 bg-slate-950/40 rounded-xl p-4 border border-slate-900/50 space-y-2">
                            <div class="h-3 bg-amber-500/20 rounded w-2/5"></div>
                            <div class="h-6 bg-amber-500/10 rounded w-1/3"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="bg-slate-950 border-t border-slate-900/60 py-6 text-center text-xs text-slate-500 relative z-10">
        <div class="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>&copy; <?php echo date('Y'); ?> Tickit Operations Inc. Running on Vanilla PHP.</div>
            <div class="flex space-x-4 text-slate-600">
                <span>Secure SSL</span>
                <span>&bull;</span>
                <span>PDO Driver Active</span>
            </div>
        </div>
    </footer>

</body>
</html>