<?php
/**
 * Navbar Component - Memora Movie
 * Navegação principal com suporte a mobile
 */

// Definir links de navegação
$navLinks = [
    ['name' => 'Capítulos', 'path' => '/capitulos'],
    ['name' => 'Como Funciona', 'path' => '/como-funciona'],
    ['name' => 'Preços', 'path' => '/precos'],
];

// Obter rota atual
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>

<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 bg-transparent py-6">
    <div class="max-w-7xl mx-auto px-6 md:px-12 flex items-center justify-between">

        <!-- Logo -->
        <a href="/" class="flex items-center gap-2 group">
            <img 
                src="https://imagedelivery.net/mYdfeAeRRdkIXG5w7XJhtQ/829bc6e2-29b7-4d46-d5bc-d19b1caea000/public"
                alt="Memora logo"
                class="h-12 w-auto transition-transform duration-500 group-hover:scale-105"
                loading="lazy"
            >
            <span class="sr-only">Memora</span>
        </a>

        <!-- Desktop Links -->
        <div class="hidden md:flex items-center gap-8">
            <?php foreach ($navLinks as $link): ?>
                <?php 
                $isActive = $currentPath === $link['path'];
                $activeClass = $isActive ? 'text-memora-wine font-semibold' : 'text-memora-black/60 hover:text-memora-wine';
                $underlineClass = $isActive ? 'w-full' : '';
                ?>
                <a href="<?= $link['path'] ?>" 
                   class="text-sm tracking-widest uppercase transition-colors duration-300 relative group <?= $activeClass ?>">
                    <?= $link['name'] ?>
                    <span class="absolute -bottom-1 left-0 w-0 h-px bg-memora-wine transition-all duration-300 group-hover:w-full <?= $underlineClass ?>"></span>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- CTA Desktop -->
        <div class="hidden md:block">
            <a href="/criar" 
               class="inline-flex items-center justify-center px-6 py-2 text-xs font-medium tracking-widest uppercase transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-offset-2 bg-memora-wine text-white hover:bg-memora-wineLight focus:ring-memora-wine border border-transparent shadow-lg shadow-memora-wine/20 transition-transform-scale rounded-lg">
                Criar Capítulo
            </a>
        </div>

        <!-- Mobile Menu Toggle -->
        <button id="mobile-menu-toggle" class="md:hidden text-memora-wine" aria-label="Menu">
            <!-- Ícone Menu (hamburger) -->
            <svg id="menu-icon-open" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="4" x2="20" y1="12" y2="12"/>
                <line x1="4" x2="20" y1="6" y2="6"/>
                <line x1="4" x2="20" y1="18" y2="18"/>
            </svg>
            <!-- Ícone X (close) - hidden por padrão -->
            <svg id="menu-icon-close" class="w-6 h-6 hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"/>
                <path d="m6 6 12 12"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" class="md:hidden fixed top-0 left-0 right-0 bg-memora-cream z-[60] overflow-hidden transition-all duration-300 h-0 opacity-0">
        <div class="flex flex-col items-center justify-center h-full gap-8 px-6">
            <!-- Close Button in Overlay -->
            <button id="mobile-menu-close" class="absolute top-6 right-6 text-memora-wine">
                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </button>

            <?php foreach ($navLinks as $link): ?>
                <a href="<?= $link['path'] ?>" 
                   class="font-serif text-4xl text-memora-wine hover:opacity-70 transition-opacity mobile-menu-link">
                    <?= $link['name'] ?>
                </a>
            <?php endforeach; ?>
            
            <a href="/criar" class="w-full max-w-xs mobile-menu-link">
                <button class="w-full py-4 text-lg inline-flex items-center justify-center font-medium tracking-widest uppercase transition-all duration-300 ease-out bg-memora-wine text-white hover:bg-memora-wineLight shadow-lg shadow-memora-wine/20">
                    Começar Agora
                </button>
            </a>
        </div>
    </div>
</nav>

<script>
// Navbar scroll behavior
(function() {
    const navbar = document.getElementById('navbar');
    let lastScrollY = 0;
    
    window.addEventListener('scroll', function() {
        const scrollY = window.scrollY;
        
        if (scrollY > 20) {
            navbar.classList.remove('bg-transparent', 'py-6');
            navbar.classList.add('bg-memora-cream/90', 'backdrop-blur-md', 'border-b', 'border-memora-wine/10', 'py-3');
        } else {
            navbar.classList.add('bg-transparent', 'py-6');
            navbar.classList.remove('bg-memora-cream/90', 'backdrop-blur-md', 'border-b', 'border-memora-wine/10', 'py-3');
        }
        
        lastScrollY = scrollY;
    });
    
    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIconOpen = document.getElementById('menu-icon-open');
    const menuIconClose = document.getElementById('menu-icon-close');
    const mobileMenuLinks = document.querySelectorAll('.mobile-menu-link');
    
    function openMobileMenu() {
        mobileMenu.classList.remove('h-0', 'opacity-0');
        mobileMenu.classList.add('h-screen', 'opacity-100');
        menuIconOpen.classList.add('hidden');
        menuIconClose.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMobileMenu() {
        mobileMenu.classList.add('h-0', 'opacity-0');
        mobileMenu.classList.remove('h-screen', 'opacity-100');
        menuIconOpen.classList.remove('hidden');
        menuIconClose.classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    mobileMenuToggle.addEventListener('click', function() {
        if (mobileMenu.classList.contains('h-0')) {
            openMobileMenu();
        } else {
            closeMobileMenu();
        }
    });
    
    mobileMenuClose.addEventListener('click', closeMobileMenu);
    
    // Fechar menu ao clicar em links
    mobileMenuLinks.forEach(function(link) {
        link.addEventListener('click', closeMobileMenu);
    });
})();
</script>
