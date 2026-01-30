<?php
/**
 * Hero Section - Memora Movie
 * Seção principal da home page
 */

// Buscar capítulos do banco para usar as imagens das categorias
try {
    require_once __DIR__ . '/../../../api/db.php';
    $stmt = $pdo->query("SELECT image_url FROM chapters WHERE image_url IS NOT NULL AND image_url != '' ORDER BY display_order");
    $chaptersWithImages = $stmt->fetchAll();
    
    // Extrair URLs das imagens
    $heroImages = [];
    foreach ($chaptersWithImages as $chapter) {
        if (!empty($chapter['image_url'])) {
            $heroImages[] = $chapter['image_url'];
        }
    }
    
    // Se não houver imagens no banco, usar fallback
    if (empty($heroImages)) {
        $heroImages = [
            "https://images.unsplash.com/photo-1511285560982-1351cdeb9821?q=80&w=1000&auto=format&fit=crop", // Wedding/Love
            "https://images.unsplash.com/photo-1519689680058-324335c77eba?q=80&w=1000&auto=format&fit=crop", // Family/Baby
            "https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?q=80&w=1000&auto=format&fit=crop", // Travel
            "https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=1000&auto=format&fit=crop"  // Portraits/Legacy
        ];
    }
} catch (Exception $e) {
    // Fallback data em caso de erro
    $heroImages = [
        "https://images.unsplash.com/photo-1511285560982-1351cdeb9821?q=80&w=1000&auto=format&fit=crop", // Wedding/Love
        "https://images.unsplash.com/photo-1519689680058-324335c77eba?q=80&w=1000&auto=format&fit=crop", // Family/Baby
        "https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?q=80&w=1000&auto=format&fit=crop", // Travel
        "https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=1000&auto=format&fit=crop"  // Portraits/Legacy
    ];
}

// Marcas de imprensa
$pressBrands = ['VOGUE', 'GQ', 'ARCHDIGEST', 'Kinfolk', 'Cereal'];
?>

<section class="relative min-h-[100dvh] pt-24 md:pt-32 pb-12 px-6 flex flex-col items-center justify-center overflow-hidden">

    <!-- Background Decorative Elements -->
    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-memora-wine/20 to-transparent"></div>

    <!-- Main Content -->
    <div class="max-w-6xl w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center mb-12">

        <!-- Text Column -->
        <div class="lg:col-span-5 text-center lg:text-left z-10 animate-fade-in-up">
            <div class="inline-block mb-6 px-3 py-1 border border-memora-wine/30 rounded-full">
                <span class="text-[10px] uppercase tracking-[0.2em] text-memora-wine font-semibold">
                    Ateliê de Histórias
                </span>
            </div>

            <h1 class="font-serif text-4xl sm:text-5xl md:text-7xl lg:text-8xl text-memora-wine leading-[0.95] mb-6 md:mb-8">
                Sua história. <br class="hidden sm:block">
                <span class="italic font-light opacity-80">Como um filme.</span>
            </h1>

            <p class="text-memora-black/70 text-lg md:text-xl leading-relaxed mb-10 max-w-md mx-auto lg:mx-0 font-light">
                Transformamos seus momentos em filmes com alma de cinema. <br>
                Para emocionar. Para presentear. <br>
                <span class="font-medium text-memora-wine">Para nunca esquecer.</span>
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                <a href="/criar" class="inline-flex items-center justify-center px-8 py-3 text-sm font-medium tracking-widest uppercase transition-all duration-300 bg-memora-wine text-white hover:bg-memora-wineLight shadow-lg shadow-memora-wine/20 w-full sm:w-auto rounded-lg">
                    Eternizar meu Momento
                </a>
                <button onclick="openVideoModal('https://www.youtube.com/embed/dQw4w9WgXcQ')" class="inline-flex items-center justify-center px-8 py-3 text-sm font-medium tracking-widest uppercase transition-all duração-300 bg-transparent text-memora-wine border border-memora-wine hover:bg-memora-wine hover:text-white w-full sm:w-auto rounded-lg">
                    Assistir Exemplo
                </button>
            </div>
        </div>

        <!-- Visual Column - The Frame -->
        <div class="lg:col-span-7 relative flex justify-center lg:justify-end">
            
            <!-- The Cinematic Frame with Carousel -->
            <div id="hero-frame" 
                 onclick="openVideoModal('https://www.youtube.com/embed/dQw4w9WgXcQ')"
                 class="relative w-full max-w-sm lg:max-w-lg aspect-[4/5] bg-memora-black overflow-hidden shadow-2xl cursor-pointer group rounded-sm animate-clip-reveal">
                
                <!-- Frame Markers -->
                <div class="absolute top-4 left-4 w-4 h-4 border-t border-l border-white/50 z-20"></div>
                <div class="absolute top-4 right-4 w-4 h-4 border-t border-r border-white/50 z-20"></div>
                <div class="absolute bottom-4 left-4 w-4 h-4 border-b border-l border-white/50 z-20"></div>
                <div class="absolute bottom-4 right-4 w-4 h-4 border-b border-r border-white/50 z-20"></div>

                <!-- Image Carousel -->
                <div id="hero-carousel" class="w-full h-full relative overflow-hidden">
                    <?php foreach ($heroImages as $idx => $img): ?>
                        <img 
                            src="<?= $img ?>" 
                            alt="Cinematic Memory <?= $idx + 1 ?>"
                            class="hero-slide absolute inset-0 w-full h-full object-cover transition-all duration-1000 group-hover:scale-105 <?= $idx === 0 ? 'opacity-90' : 'opacity-0' ?>"
                            data-index="<?= $idx ?>"
                        >
                    <?php endforeach; ?>
                    <!-- Vignette -->
                    <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-black/40"></div>
                </div>

                <!-- Play Overlay -->
                <div class="absolute inset-0 flex items-center justify-center z-10">
                    <button class="w-16 h-16 rounded-full bg-white/10 backdrop-blur-sm border border-white/30 flex items-center justify-center text-white pl-1 group-hover:bg-white/20 transition-colors">
                        <!-- Play icon -->
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <polygon points="5 3 19 12 5 21 5 3"/>
                        </svg>
                    </button>
                </div>

                <div class="absolute bottom-8 left-8 z-20">
                    <p class="text-white/80 font-serif text-2xl italic">"O melhor presente que já ganhei."</p>
                </div>
            </div>
        </div>
    </div>

</section>

<!-- Modal Component -->
<?php include __DIR__ . '/../ui/modal.php'; ?>

<style>
/* Animações customizadas */
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes scale-in {
    from {
        opacity: 0;
        transform: scale(0);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes clip-reveal {
    from {
        clip-path: inset(45% 0 45% 0);
        opacity: 0;
        transform: scale(1.1);
    }
    to {
        clip-path: inset(0% 0 0% 0);
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out forwards;
    animation-delay: 0.3s;
    opacity: 0;
}

.animate-fade-in {
    animation: fade-in 1s ease-out forwards;
    opacity: 0;
}

.animate-scale-in {
    animation: scale-in 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    opacity: 0;
}

.animate-clip-reveal {
    animation: clip-reveal 1.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    animation-delay: 0.2s;
    opacity: 0;
}
</style>

<script>
// Hero Image Carousel
(function() {
    const slides = document.querySelectorAll('.hero-slide');
    let currentIdx = 0;
    const totalSlides = slides.length;
    
    if (totalSlides <= 1) return;
    
    setInterval(() => {
        // Fade out current
        slides[currentIdx].classList.remove('opacity-90');
        slides[currentIdx].classList.add('opacity-0');
        
        // Move to next
        currentIdx = (currentIdx + 1) % totalSlides;
        
        // Fade in next
        slides[currentIdx].classList.remove('opacity-0');
        slides[currentIdx].classList.add('opacity-90');
    }, 5000);
})();
</script>
