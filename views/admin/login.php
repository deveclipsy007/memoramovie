<?php
/**
 * Admin Login - Memora Movie
 */

session_start();

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: /admin');
    exit;
}

// Se já logado, redirecionar
if (isset($_SESSION['admin_id'])) {
    header('Location: /admin/dashboard');
    exit;
}

$error = '';

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        require_once __DIR__ . '/../../api/config.sqlite.php';
        
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: /admin/dashboard');
            exit;
        } else {
            $error = 'Usuário ou senha incorretos';
        }
    } catch (Exception $e) {
        $error = 'Erro de conexão com o banco de dados';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Admin MEMORA MOVIE</title>
    
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
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-memora-black min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 text-white mb-4">
                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                    <path d="M7 3v18"/><path d="M3 7.5h4"/><path d="M3 12h18"/><path d="M3 16.5h4"/>
                    <path d="M17 3v18"/><path d="M17 7.5h4"/><path d="M17 16.5h4"/>
                </svg>
                <span class="font-bold text-2xl">MEMORA</span>
            </div>
            <p class="text-white/50 text-sm">Painel Administrativo</p>
        </div>
        
        <!-- Login Card -->
        <div class="bg-white rounded-lg p-8 shadow-2xl">
            <h1 class="text-xl font-semibold text-gray-800 mb-6 text-center">Entrar</h1>
            
            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Usuário</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           required
                           autocomplete="username"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine focus:border-memora-wine transition-colors"
                           placeholder="admin">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           autocomplete="current-password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine focus:border-memora-wine transition-colors"
                           placeholder="••••••••">
                </div>
                
                <button type="submit" 
                        class="w-full py-3 bg-memora-wine text-white font-medium rounded-lg hover:bg-memora-wineLight transition-colors">
                    Entrar
                </button>
            </form>
        </div>
        
        <!-- Back link -->
        <div class="text-center mt-6">
            <a href="/" class="text-white/50 hover:text-white text-sm transition-colors">
                ← Voltar ao site
            </a>
        </div>
    </div>
    
</body>
</html>
