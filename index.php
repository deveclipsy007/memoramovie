<?php
/**
 * Router Principal - Memora Movie
 * Roteador para páginas PHP (MPA)
 */

// Configurações
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obter URI limpa
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
if ($uri === '') $uri = '/';

// Rotas públicas
$publicRoutes = [
    '/'                => 'views/pages/home.php',
    '/capitulos'       => 'views/pages/capitulos.php',
    '/como-funciona'   => 'views/pages/como-funciona.php',
    '/precos'          => 'views/pages/precos.php',
    '/criar'           => 'views/pages/criar.php',
    '/faq'             => 'views/pages/faq.php',
    '/privacidade'     => 'views/pages/privacidade.php',
];

// Rotas admin
$adminRoutes = [
    '/admin'              => 'views/admin/login.php',
    '/admin/dashboard'    => 'views/admin/dashboard.php',
    '/admin/chapters'     => 'views/admin/chapters.php',
    '/admin/leads'        => 'views/admin/leads.php',
    '/admin/quiz'         => 'views/admin/quiz.php',
    '/admin/logs'         => 'views/admin/logs.php',
    '/admin/site'         => 'views/admin/site.php',
    '/admin/site/general' => 'views/admin/site-general.php',
    '/admin/site/hero'    => 'views/admin/site-hero.php',
    '/admin/site/faqs'    => 'views/admin/site-faqs.php',
    '/admin/site/reviews' => 'views/admin/site-reviews.php',
    '/admin/site/plans'   => 'views/admin/site-plans.php',
    '/admin/settings'     => 'views/admin/settings.php',
];

// Combinar todas as rotas
$allRoutes = array_merge($publicRoutes, $adminRoutes);

// Rota dinâmica: /capitulos/{id}
if (preg_match('/^\/capitulos\/([a-zA-Z0-9_-]+)$/', $uri, $matches)) {
    $_GET['id'] = $matches[1];
    require __DIR__ . '/views/pages/capitulo.php';
    exit;
}

// Match exato de rotas
if (array_key_exists($uri, $allRoutes)) {
    $file = __DIR__ . '/' . $allRoutes[$uri];
    if (file_exists($file)) {
        require $file;
        exit;
    }
}

// API - delegar para /api/index.php
if (strpos($uri, '/api') === 0) {
    // Ajustar REQUEST_URI para a API
    $_SERVER['REQUEST_URI'] = substr($uri, 4) ?: '/';
    require __DIR__ . '/api/index.php';
    exit;
}

// Assets estáticos em /public/
if (strpos($uri, '/public/') === 0) {
    $file = __DIR__ . $uri;
    if (file_exists($file) && is_file($file)) {
        // Determinar MIME type
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
        ];
        $mime = $mimeTypes[$ext] ?? 'application/octet-stream';
        header("Content-Type: $mime");
        readfile($file);
        exit;
    }
}

// 404 - Página não encontrada
http_response_code(404);
$pageTitle = 'Página não encontrada - MEMORA MOVIE';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?></title>
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
                    },
                    fontFamily: {
                        serif: ['"DM Serif Display"', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500&display=swap" rel="stylesheet">
</head>
<body class="bg-memora-cream font-sans min-h-screen flex items-center justify-center">
    <div class="text-center px-6">
        <h1 class="font-serif text-6xl text-memora-wine mb-4">404</h1>
        <p class="text-memora-black/60 mb-8">Oops! Página não encontrada.</p>
        <a href="/" class="inline-flex items-center justify-center px-8 py-3 text-sm font-medium tracking-widest uppercase bg-memora-wine text-white hover:bg-memora-wineLight transition-colors">
            Voltar ao Início
        </a>
    </div>
</body>
</html>
