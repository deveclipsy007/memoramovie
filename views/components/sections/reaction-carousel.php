<?php
/**
 * Reaction Carousel Section - Memora Movie
 * Carrossel de depoimentos
 */

// Buscar reviews do banco
try {
    require_once __DIR__ . '/../../../api/config.sqlite.php';
    $stmt = $pdo->query("SELECT * FROM site_reviews ORDER BY display_order");
    $reviews = $stmt->fetchAll();
} catch (Exception $e) {
    $reviews = [
        ['id' => 1, 'text' => 'Eu fiz para tentar reconquistar minha ex-esposa. Mandei o vídeo. Nós choramos juntos assistindo. Obrigado por salvarem minha família.', 'author' => 'Ricardo M.', 'role' => "Trailer 'Love Story'"],
        ['id' => 2, 'text' => 'Meu pai faleceu há 3 anos. Ver ele "vivo" de novo, sorrindo em câmera lenta, com a música certa... foi o melhor presente que já me dei.', 'author' => 'Ana Clara T.', 'role' => "Filme 'Legacy'"],
        ['id' => 3, 'text' => 'Não é sobre organizar fotos. É sobre ver sua vida e pensar: "Nossa, a gente foi muito feliz". Chorei do início ao fim.', 'author' => 'M. Chen', 'role' => "Filme 'Travel'"],
    ];
}
?>

<section class="py-24 md:py-32 px-6 bg-memora-cream relative overflow-hidden">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <div class="inline-block mb-4 px-4 py-2 border border-memora-wine/20 rounded-full">
                <span class="text-[10px] uppercase tracking-[0.3em] text-memora-wine/60 font-medium">
                    Reações Reais
                </span>
            </div>
            <h2 class="font-serif text-4xl md:text-5xl text-memora-wine mb-4">
                O que eles sentiram
            </h2>
            <div class="mb-8 flex justify-center">
                <img
                    src="https://imagedelivery.net/mYdfeAeRRdkIXG5w7XJhtQ/50a7b9d0-0192-4a43-54c6-d5a779d2d400/public"
                    alt="Colagem de clientes da Memora emocionados"
                    class="w-full max-w-xl md:max-w-2xl rounded-3xl shadow-xl shadow-memora-wine/10"
                    loading="lazy"
                >
            </div>
            <p class="text-memora-black/60 max-w-xl mx-auto">
                Histórias verdadeiras de pessoas que transformaram memórias em filmes.
            </p>
        </div>
        
        <!-- Reviews Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($reviews as $review): ?>
                <div class="bg-white p-8 rounded-sm border border-memora-wine/10 hover:border-memora-wine/30 hover:shadow-xl hover:shadow-memora-wine/5 transition-all duration-300 group">
                    
                    <!-- Quote icon -->
                    <div class="text-memora-wine/20 mb-6">
                        <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.192 15.757c0-.88-.23-1.618-.69-2.217-.326-.412-.768-.683-1.327-.812-.55-.128-1.07-.137-1.54-.028-.16-.95.1-1.956.76-3.022.66-1.065 1.515-1.867 2.558-2.403L9.373 5c-.8.396-1.56.898-2.26 1.505-.71.607-1.34 1.305-1.9 2.094s-.98 1.68-1.25 2.69-.346 2.04-.217 3.1c.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.004zm9.124 0c0-.88-.23-1.618-.69-2.217-.326-.42-.77-.692-1.327-.817-.56-.124-1.074-.13-1.54-.022-.16-.94.09-1.95.75-3.02.66-1.06 1.514-1.86 2.557-2.4L18.49 5c-.8.396-1.555.898-2.26 1.505-.708.607-1.34 1.305-1.894 2.094-.556.79-.97 1.68-1.24 2.69-.273 1-.345 2.04-.217 3.1.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l-.007.004z"/>
                        </svg>
                    </div>
                    
                    <!-- Text -->
                    <blockquote class="text-memora-black/80 text-lg leading-relaxed mb-6 font-light italic">
                        "<?= htmlspecialchars($review['text']) ?>"
                    </blockquote>
                    
                    <!-- Author -->
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-memora-wine/10 flex items-center justify-center text-memora-wine font-serif text-xl">
                            <?= substr($review['author'], 0, 1) ?>
                        </div>
                        <div>
                            <div class="font-medium text-memora-wine"><?= htmlspecialchars($review['author']) ?></div>
                            <div class="text-xs text-memora-black/50"><?= htmlspecialchars($review['role']) ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
