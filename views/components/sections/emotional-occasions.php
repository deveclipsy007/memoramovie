<?php
/**
 * Emotional Occasions Section - Memora Movie
 * Grid de ocasiões/capítulos disponíveis
 */

// Buscar capítulos
try {
    require_once __DIR__ . '/../../../api/db.php';
    $stmt = $pdo->query("SELECT * FROM chapters ORDER BY display_order LIMIT 6");
    $chapters = $stmt->fetchAll();
} catch (Exception $e) {
    $chapters = [
        ['id' => 'love', 'title' => 'Love Story', 'subtitle' => 'Para casamentos, pedidos ou reconquistas.', 'color' => '#5A0B18', 'image_url' => 'https://images.unsplash.com/photo-1511285560982-1351cdeb9821?q=80&w=800'],
        ['id' => 'legacy', 'title' => 'The Legacy', 'subtitle' => 'A história dos seus pais ou avós.', 'color' => '#8B4513', 'image_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=800'],
        ['id' => 'newborn', 'title' => 'New Life', 'subtitle' => 'Do anúncio da gravidez ao primeiro ano.', 'color' => '#D4A5A5', 'image_url' => 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?q=80&w=800'],
        ['id' => 'travel', 'title' => 'Wanderlust', 'subtitle' => 'Aquela viagem que mudou quem você é.', 'color' => '#2C3E50', 'image_url' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=800'],
        ['id' => 'pet', 'title' => 'Soulmate', 'subtitle' => 'Uma homenagem ao seu melhor amigo.', 'color' => '#E67E22', 'image_url' => 'https://images.unsplash.com/photo-1530281700549-e82e7bf110d6?q=80&w=800'],
        ['id' => 'friendship', 'title' => 'Best Years', 'subtitle' => 'Amizades que o tempo não apaga.', 'color' => '#9B59B6', 'image_url' => 'https://images.unsplash.com/photo-1491438590914-bc09fcaaf77a?q=80&w=800'],
    ];
}

// Default images por ID
$defaultImages = [
    'love' => 'https://images.unsplash.com/photo-1511285560982-1351cdeb9821?q=80&w=800&auto=format&fit=crop',
    'legacy' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=800&auto=format&fit=crop',
    'newborn' => 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?q=80&w=800&auto=format&fit=crop',
    'travel' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=800&auto=format&fit=crop',
    'pet' => 'https://images.unsplash.com/photo-1530281700549-e82e7bf110d6?q=80&w=800&auto=format&fit=crop',
    'friendship' => 'https://images.unsplash.com/photo-1491438590914-bc09fcaaf77a?q=80&w=800&auto=format&fit=crop',
];
?>

<section class="py-24 md:py-32 px-6 bg-memora-cream">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <div class="inline-block mb-4 px-4 py-2 border border-memora-wine/20 rounded-full">
                <span class="text-[10px] uppercase tracking-[0.3em] text-memora-wine/60 font-medium">
                    Escolha sua história
                </span>
            </div>
            <h2 class="font-serif text-4xl md:text-5xl text-memora-wine mb-4">
                Ocasiões Emocionais
            </h2>
            <p class="text-memora-black/60 max-w-xl mx-auto">
                Cada momento merece uma direção de arte única. Escolha o capítulo que melhor representa sua história.
            </p>
        </div>
        
        <!-- Grid de Capítulos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($chapters as $chapter): ?>
                <?php 
                $imageUrl = $chapter['image_url'] ?? $defaultImages[$chapter['id']] ?? $defaultImages['love'];
                $color = $chapter['color'] ?? '#5A0B18';
                ?>
                <a href="/capitulos/<?= htmlspecialchars($chapter['id']) ?>" 
                   class="group relative aspect-[4/5] overflow-hidden rounded-xl cursor-pointer">
                    
                    <!-- Background Image -->
                    <img src="<?= htmlspecialchars($imageUrl) ?>" 
                         alt="<?= htmlspecialchars($chapter['title']) ?>"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    
                    <!-- Overlay Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    
                    <!-- Color accent on hover -->
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-30 transition-opacity duration-500"
                         style="background-color: <?= htmlspecialchars($color) ?>"></div>
                    
                    <!-- Content -->
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white z-10">
                        <!-- Color dot -->
                        <div class="w-2 h-2 rounded-full mb-4 transition-transform duration-300 group-hover:scale-150"
                             style="background-color: <?= htmlspecialchars($color) ?>"></div>
                        
                        <h3 class="font-serif text-2xl mb-2 transition-transform duration-300 group-hover:translate-x-2">
                            <?= htmlspecialchars($chapter['title']) ?>
                        </h3>
                        <p class="text-white/70 text-sm">
                            <?= htmlspecialchars($chapter['subtitle']) ?>
                        </p>
                        
                        <!-- Arrow on hover -->
                        <div class="mt-4 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-[-10px] group-hover:translate-x-0">
                            <span class="text-xs uppercase tracking-widest">Explorar</span>
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"/>
                                <path d="m12 5 7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        
        <!-- Ver todos -->
        <div class="text-center mt-12">
            <a href="/capitulos" class="inline-flex items-center gap-2 text-memora-wine hover:text-memora-wineLight transition-colors text-sm uppercase tracking-widest">
                Ver todos os capítulos
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/>
                    <path d="m12 5 7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>
