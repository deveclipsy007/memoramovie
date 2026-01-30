<?php
/**
 * Admin Layout - Memora Movie
 * Layout base para páginas do painel admin
 */

session_start();

// Verificar autenticação (exceto na página de login)
$currentPage = basename($_SERVER['SCRIPT_NAME'], '.php');
$isLoginPage = strpos($_SERVER['REQUEST_URI'], '/admin/login') !== false || $_SERVER['REQUEST_URI'] === '/admin';

if (!$isLoginPage && !isset($_SESSION['admin_id'])) {
    header('Location: /admin');
    exit;
}

$adminUser = $_SESSION['admin_username'] ?? 'Admin';
$pageTitle = $pageTitle ?? 'Admin - MEMORA MOVIE';

// Menu items
$menuItems = [
    ['icon' => 'home', 'label' => 'Dashboard', 'href' => '/admin/dashboard'],
    ['icon' => 'film', 'label' => 'Capítulos', 'href' => '/admin/chapters'],
    ['icon' => 'users', 'label' => 'Leads', 'href' => '/admin/leads'],
    ['icon' => 'help-circle', 'label' => 'Quiz', 'href' => '/admin/quiz'],
    ['icon' => 'file-text', 'label' => 'Logs', 'href' => '/admin/logs'],
    ['icon' => 'globe', 'label' => 'Site', 'href' => '/admin/site'],
    ['icon' => 'settings', 'label' => 'Configurações', 'href' => '/admin/settings'],
];

$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Icons SVG
$icons = [
    'home' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
    'film' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 3v18"/><path d="M3 7.5h4"/><path d="M3 12h18"/><path d="M3 16.5h4"/><path d="M17 3v18"/><path d="M17 7.5h4"/><path d="M17 16.5h4"/></svg>',
    'users' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    'help-circle' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>',
    'file-text' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>',
    'globe' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>',
    'settings' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>',
    'logout' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>',
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        memora: {
                            cream: '#F6F2EE',
                            wine: '#5A0B18',
                            wineLight: '#8C2B3D',
                            black: '#1A1A1A',
                            gray: '#A8A29E',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-memora-black text-white flex flex-col fixed h-full">
            <!-- Logo -->
            <div class="p-6 border-b border-white/10">
                <a href="/admin/dashboard" class="flex items-center gap-3">
                    <?= $icons['film'] ?>
                    <span class="font-bold text-lg">MEMORA ADMIN</span>
                </a>
            </div>
            
            <!-- Menu -->
            <nav class="flex-1 py-6">
                <ul class="space-y-1 px-3">
                    <?php foreach ($menuItems as $item): ?>
                        <?php 
                        $isActive = strpos($currentPath, $item['href']) === 0;
                        $activeClass = $isActive ? 'bg-memora-wine text-white' : 'text-white/70 hover:bg-white/10 hover:text-white';
                        ?>
                        <li>
                            <a href="<?= $item['href'] ?>" 
                               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $activeClass ?>">
                                <?= $icons[$item['icon']] ?>
                                <span><?= $item['label'] ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            
            <!-- User & Logout -->
            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-memora-wine flex items-center justify-center text-white font-bold">
                        <?= substr($adminUser, 0, 1) ?>
                    </div>
                    <div>
                        <div class="text-sm font-medium"><?= htmlspecialchars($adminUser) ?></div>
                        <div class="text-xs text-white/50">Administrador</div>
                    </div>
                </div>
                <a href="/admin?logout=1" 
                   class="flex items-center gap-2 text-white/70 hover:text-white text-sm transition-colors">
                    <?= $icons['logout'] ?>
                    <span>Sair</span>
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 ml-64">
            <!-- Top bar -->
            <header class="bg-white border-b border-gray-200 px-8 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-semibold text-gray-800"><?= $pageHeading ?? 'Dashboard' ?></h1>
                    <a href="/" target="_blank" class="text-sm text-memora-wine hover:underline">
                        Ver Site →
                    </a>
                </div>
            </header>
            
            <!-- Page Content -->
            <div class="p-8">
                <?= $adminContent ?? '' ?>
            </div>
        </main>
    </div>
    
    <!-- Toast notifications -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>
    
    <script>
    // API helper
    const api = {
        async fetch(endpoint, options = {}) {
            const response = await fetch('/api' + endpoint, {
                ...options,
                headers: {
                    'Content-Type': 'application/json',
                    ...options.headers,
                },
            });
            const result = await response.json();
            if (!result.ok) {
                throw new Error(result.error || 'Erro na requisição');
            }
            return result.data;
        },
        get: (endpoint) => api.fetch(endpoint, { method: 'GET' }),
        post: (endpoint, body) => api.fetch(endpoint, { method: 'POST', body: JSON.stringify(body) }),
        put: (endpoint, body) => api.fetch(endpoint, { method: 'PUT', body: JSON.stringify(body) }),
        delete: (endpoint) => api.fetch(endpoint, { method: 'DELETE' }),
    };
    
    // Toast helper
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `px-6 py-3 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} transform transition-all duration-300 translate-x-full`;
        toast.textContent = message;
        container.appendChild(toast);
        
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full');
        });
        
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    </script>
</body>
</html>
