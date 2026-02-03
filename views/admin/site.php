<?php
/**
 * Admin Site - Memora Movie
 * Menu de gerenciamento do site
 */

$pageTitle = 'Gerenciar Site - Admin MEMORA MOVIE';
$pageHeading = 'Gerenciar Site';

$siteLinks = [
    ['href' => '/admin/site/hero', 'title' => 'Hero', 'description' => 'Título, subtítulo e vídeo da seção principal', 'icon' => 'layout'],
    ['href' => '/admin/site/directors-cut', 'title' => 'Director\'s Cut', 'description' => 'Qualidade e vídeo da seção cinematográfica', 'icon' => 'video'],
    ['href' => '/admin/site/faqs', 'title' => 'FAQs', 'description' => 'Perguntas frequentes', 'icon' => 'help'],
    ['href' => '/admin/site/reviews', 'title' => 'Depoimentos', 'description' => 'Reviews e testimonials', 'icon' => 'star'],
    ['href' => '/admin/site/plans', 'title' => 'Planos', 'description' => 'Preços e pacotes', 'icon' => 'tag'],
];

ob_start();
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <?php foreach ($siteLinks as $link): ?>
        <a href="<?= $link['href'] ?>" 
           class="bg-white rounded-lg border border-gray-200 p-6 hover:border-memora-wine/30 hover:shadow-lg transition-all group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-memora-wine/10 rounded-lg flex items-center justify-center group-hover:bg-memora-wine/20 transition-colors">
                    <svg class="w-6 h-6 text-memora-wine" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <?php if ($link['icon'] === 'layout'): ?>
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                            <line x1="3" x2="21" y1="9" y2="9"/>
                        <?php elseif ($link['icon'] === 'video'): ?>
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                            <path d="m15 10 4.55-2.27A1 1 0 0 1 21 8.61v6.78a1 1 0 0 1-1.45.89L15 14"/>
                        <?php elseif ($link['icon'] === 'help'): ?>
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                            <path d="M12 17h.01"/>
                        <?php elseif ($link['icon'] === 'star'): ?>
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        <?php elseif ($link['icon'] === 'tag'): ?>
                            <path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/>
                            <path d="M7 7h.01"/>
                        <?php endif; ?>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800 mb-1 group-hover:text-memora-wine transition-colors"><?= $link['title'] ?></h3>
                    <p class="text-sm text-gray-500"><?= $link['description'] ?></p>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-memora-wine group-hover:translate-x-1 transition-all" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php
$adminContent = ob_get_clean();
include __DIR__ . '/layout.php';
?>
