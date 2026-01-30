<?php
/**
 * Capítulo Individual Page - Memora Movie
 * Página de ocasião específica (landing)
 */

$chapterId = $_GET['id'] ?? 'love';

// Buscar dados do capítulo
try {
    require_once __DIR__ . '/../../api/config.sqlite.php';
    $stmt = $pdo->prepare("SELECT * FROM chapters WHERE id = ?");
    $stmt->execute([$chapterId]);
    $chapter = $stmt->fetch();
} catch (Exception $e) {
    $chapter = null;
}

// Conteúdo default por ocasião
$occasionContent = [
    'love' => [
        'heroTitle' => 'Não deixe o "nós" virar apenas rotina.',
        'heroSubtitle' => 'Transforme vídeos soltos do casal em uma promessa de amor eterno.',
        'heroImage' => 'https://images.unsplash.com/photo-1516589178581-6cd7833ae3b2?q=80&w=1200&auto=format&fit=crop',
        'emotionalHook' => ['title' => 'A paixão merece replay.', 'text' => 'Com o tempo, a gente esquece como olhava um para o outro no começo.'],
        'ctaText' => 'Criar nossa História de Amor'
    ],
    'legacy' => [
        'heroTitle' => 'A voz dele não pode desaparecer.',
        'heroSubtitle' => 'Uma biografia visual para seus pais ou avós.',
        'heroImage' => 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?q=80&w=1200&auto=format&fit=crop',
        'emotionalHook' => ['title' => 'Honre quem veio antes.', 'text' => 'Transformamos arquivos empoeirados em um documentário digno de cinema.'],
        'ctaText' => 'Eternizar o Legado'
    ],
    'newborn' => [
        'heroTitle' => 'Eles crescem enquanto você pisca.',
        'heroSubtitle' => 'Do teste de gravidez aos primeiros passos.',
        'heroImage' => 'https://images.unsplash.com/photo-1519689680058-324335c77eba?q=80&w=1200&auto=format&fit=crop',
        'emotionalHook' => ['title' => 'O tempo é um ladrão.', 'text' => 'Organizamos o primeiro ano de vida em um filme que fará você chorar quando ele tiver 18 anos.'],
        'ctaText' => 'Criar o Filme do Bebê'
    ],
    'travel' => [
        'heroTitle' => 'A viagem acabou. A sensação não.',
        'heroSubtitle' => 'Transforme seus stories em um vlog cinematográfico.',
        'heroImage' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=1200&auto=format&fit=crop',
        'emotionalHook' => ['title' => 'Wanderlust em 4K.', 'text' => 'Criamos um filme para você assistir sempre que precisar fugir da rotina.'],
        'ctaText' => 'Reviver a Viagem'
    ],
    'pet' => [
        'heroTitle' => 'Para o amor mais puro do mundo.',
        'heroSubtitle' => 'Eles vivem pouco, mas amam muito.',
        'heroImage' => 'https://images.unsplash.com/photo-1530281700549-e82e7bf110d6?q=80&w=1200&auto=format&fit=crop',
        'emotionalHook' => ['title' => 'Melhores amigos merecem cinema.', 'text' => 'Um filme focado na personalidade única do seu bichinho.'],
        'ctaText' => 'Homenagear meu Pet'
    ],
    'friendship' => [
        'heroTitle' => 'Histórias que o tempo não apaga.',
        'heroSubtitle' => 'Despedidas de solteiro, formaturas ou anos de amizade.',
        'heroImage' => 'https://images.unsplash.com/photo-1491438590914-bc09fcaaf77a?q=80&w=1200&auto=format&fit=crop',
        'emotionalHook' => ['title' => 'A família que a gente escolhe.', 'text' => 'O presente perfeito para o aniversário daquele amigo(a) especial.'],
        'ctaText' => 'Celebrar a Amizade'
    ],
];

$content = $occasionContent[$chapterId] ?? $occasionContent['love'];
$chapterTitle = $chapter['title'] ?? ucfirst($chapterId);
$chapterColor = $chapter['color'] ?? '#5A0B18';

$pageTitle = $chapterTitle . ' - MEMORA MOVIE';
$pageDescription = $content['heroSubtitle'];

ob_start();
?>

<!-- Hero Section -->
<section class="relative min-h-[80vh] pt-24 flex items-center overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img src="<?= htmlspecialchars($content['heroImage']) ?>" 
             alt="<?= htmlspecialchars($chapterTitle) ?>"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
    </div>
    
    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-6 py-20">
        <div class="max-w-2xl">
            <!-- Label -->
            <div class="inline-block mb-6 px-4 py-2 border border-white/30 rounded-full">
                <span class="text-xs uppercase tracking-widest text-white/80 font-medium">
                    Capítulo: <?= htmlspecialchars($chapterTitle) ?>
                </span>
            </div>
            
            <h1 class="font-serif text-4xl md:text-6xl text-white leading-tight mb-6">
                <?= htmlspecialchars($content['heroTitle']) ?>
            </h1>
            
            <p class="text-white/80 text-xl mb-10 leading-relaxed">
                <?= htmlspecialchars($content['heroSubtitle']) ?>
            </p>
            
            <a href="/criar?chapter=<?= htmlspecialchars($chapterId) ?>" 
               class="inline-flex items-center justify-center px-10 py-4 text-sm font-medium tracking-widest uppercase transition-all duration-300 text-white hover:bg-white hover:text-memora-black"
               style="background-color: <?= htmlspecialchars($chapterColor) ?>">
                <?= htmlspecialchars($content['ctaText']) ?>
            </a>
        </div>
    </div>
</section>

<!-- Emotional Hook Section -->
<section class="py-20 px-6 bg-white">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="font-serif text-3xl md:text-4xl text-memora-wine mb-6">
            <?= htmlspecialchars($content['emotionalHook']['title']) ?>
        </h2>
        <p class="text-memora-black/60 text-lg leading-relaxed">
            <?= htmlspecialchars($content['emotionalHook']['text']) ?>
        </p>
    </div>
</section>

<!-- Process -->
<?php include __DIR__ . '/../components/sections/process-timeline.php'; ?>

<!-- Pricing -->
<?php include __DIR__ . '/../components/sections/pricing.php'; ?>

<!-- FAQ -->
<?php include __DIR__ . '/../components/sections/faq-accordion.php'; ?>

<!-- Final CTA -->
<?php include __DIR__ . '/../components/sections/final-cta.php'; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/base.php';
?>
