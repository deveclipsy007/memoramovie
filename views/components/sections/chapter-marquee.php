<?php
/**
 * Chapter Marquee Section - Memora Movie
 * Marquee infinito com capítulos
 */

// Buscar capítulos do banco ou usar defaults
try {
    require_once __DIR__ . '/../../../api/config.sqlite.php';
    $stmt = $pdo->query("SELECT * FROM chapters ORDER BY display_order");
    $chapters = $stmt->fetchAll();
} catch (Exception $e) {
    // Fallback data
    $chapters = [
        ['id' => 'love', 'title' => 'Love Story', 'subtitle' => 'Para casamentos', 'color' => '#5A0B18'],
        ['id' => 'legacy', 'title' => 'The Legacy', 'subtitle' => 'Seus pais e avós', 'color' => '#8B4513'],
        ['id' => 'newborn', 'title' => 'New Life', 'subtitle' => 'Bebês e crianças', 'color' => '#D4A5A5'],
        ['id' => 'travel', 'title' => 'Wanderlust', 'subtitle' => 'Viagens inesquecíveis', 'color' => '#2C3E50'],
        ['id' => 'pet', 'title' => 'Soulmate', 'subtitle' => 'Seu melhor amigo', 'color' => '#E67E22'],
        ['id' => 'friendship', 'title' => 'Best Years', 'subtitle' => 'Amizades eternas', 'color' => '#9B59B6'],
    ];
}

// Duplicar para efeito infinito
$chaptersDouble = array_merge($chapters, $chapters);
?>

<section class="py-12 bg-memora-cream overflow-hidden border-y border-memora-wine/10">
    <div class="relative">
        <!-- Fade edges -->
        <div class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-memora-cream to-transparent z-10 pointer-events-none"></div>
        <div class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-memora-cream to-transparent z-10 pointer-events-none"></div>
        
        <!-- Marquee container -->
        <div class="flex animate-marquee hover:[animation-play-state:paused]">
            <?php foreach ($chaptersDouble as $chapter): ?>
                <a href="/capitulos/<?= htmlspecialchars($chapter['id']) ?>" 
                   class="flex-shrink-0 group px-8 py-4">
                    <div class="flex items-center gap-4">
                        <!-- Dot indicator -->
                        <div class="w-3 h-3 rounded-full transition-transform duration-300 group-hover:scale-150"
                             style="background-color: <?= htmlspecialchars($chapter['color'] ?? '#5A0B18') ?>">
                        </div>
                        
                        <!-- Title -->
                        <span class="font-serif text-2xl md:text-3xl text-memora-wine whitespace-nowrap transition-colors duration-300 group-hover:text-memora-wineLight">
                            <?= htmlspecialchars($chapter['title']) ?>
                        </span>
                        
                        <!-- Subtitle on hover -->
                        <span class="text-xs uppercase tracking-widest text-memora-wine/40 whitespace-nowrap hidden md:inline opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <?= htmlspecialchars($chapter['subtitle']) ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
