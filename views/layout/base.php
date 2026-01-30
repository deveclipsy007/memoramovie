<?php
/**
 * Template Base - Memora Movie
 * Layout principal para todas as páginas públicas
 */

// Configurações padrão
$pageTitle = $pageTitle ?? 'MEMORA MOVIE';
$pageDescription = $pageDescription ?? 'Transformamos seus momentos em filmes com alma de cinema.';
$bodyClass = $bodyClass ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    
    <!-- Tailwind CSS via CDN (dev) - substituir por build em produção -->
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
                    },
                    fontFamily: {
                        serif: ['"DM Serif Display"', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        marquee: 'marquee 60s linear infinite',
                        'marquee-slow': 'marquee 90s linear infinite',
                        grain: 'grain 8s steps(10) infinite',
                        float: 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        marquee: {
                            '0%': { transform: 'translateX(0%)' },
                            '100%': { transform: 'translateX(-50%)' },
                        },
                        grain: {
                            '0%, 100%': { transform: 'translate(0, 0)' },
                            '10%': { transform: 'translate(-5%, -10%)' },
                            '20%': { transform: 'translate(-15%, 5%)' },
                            '30%': { transform: 'translate(7%, -25%)' },
                            '40%': { transform: 'translate(-5%, 25%)' },
                            '50%': { transform: 'translate(-15%, 10%)' },
                            '60%': { transform: 'translate(15%, 0%)' },
                            '70%': { transform: 'translate(0%, 15%)' },
                            '80%': { transform: 'translate(3%, 35%)' },
                            '90%': { transform: 'translate(-10%, 10%)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Estilos customizados -->
    <style>
        body {
            background-color: #F6F2EE;
            color: #1A1A1A;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'DM Serif Display', serif;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Film Grain Overlay */
        .bg-grain {
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.05'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 9999;
            animation: grain 8s steps(10) infinite;
            opacity: 0.4;
        }
        
        /* Transition classes para animações */
        .transition-transform-scale {
            transition: transform 0.3s ease-out;
        }
        .transition-transform-scale:hover {
            transform: scale(1.02);
        }
        .transition-transform-scale:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body class="<?= htmlspecialchars($bodyClass) ?>">
    <div class="flex flex-col min-h-screen bg-memora-cream font-sans text-memora-black antialiased selection:bg-memora-wine selection:text-white">
        
        <?php include __DIR__ . '/navbar.php'; ?>
        
        <main class="flex-grow">
            <?= $content ?? '' ?>
        </main>
        
        <?php include __DIR__ . '/mobile-sticky-cta.php'; ?>
        <?php include __DIR__ . '/footer.php'; ?>
        
    </div>
    
    <!-- Film Grain Effect -->
    <div class="bg-grain"></div>
    
    <!-- JavaScript -->
    <script src="/public/assets/js/app.js" defer></script>
</body>
</html>
