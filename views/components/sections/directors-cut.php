<?php
/**
 * Directors Cut Section - Memora Movie
 * Seção sobre qualidade cinematográfica
 */

try {
    require_once __DIR__ . '/../../../api/db.php';
    $stmt = $pdo->query("SELECT * FROM site_content WHERE section = 'directors-cut'");
    $rows = $stmt->fetchAll();
    
    $dcContent = [];
    foreach ($rows as $row) {
        $dcContent[$row['id']] = $row['value'];
    }
} catch (Exception $e) {
    $dcContent = [];
}

$features = [
    [
        'title' => 'Color Grading',
        'description' => 'Tratamento de cor cinematográfico frame a frame. Transformamos vídeos de celular em obras visuais.',
    ],
    [
        'title' => 'Sound Design',
        'description' => 'Mixagem de áudio profissional. Trilha sonora licenciada que cresce junto com a emoção.',
    ],
    [
        'title' => 'Narrativa',
        'description' => 'Estrutura de 3 atos. Começo, meio e fim que prendem atenção e tocam o coração.',
    ],
];
?>

<section class="py-24 md:py-32 px-6 bg-memora-black text-white relative overflow-hidden">
    <!-- Film grain overlay -->
    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/dark-leather.png')]"></div>
    
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            
            <!-- Text Content -->
            <div>
                <div class="inline-block mb-6 px-4 py-2 border border-white/20 rounded-full">
                    <span class="text-[10px] uppercase tracking-[0.3em] text-white/60 font-medium">
                        Director's Cut
                    </span>
                </div>
                
                <h2 class="font-serif text-4xl md:text-5xl text-white mb-6 leading-tight">
                    <?= $dcContent['dc_title'] ?? 'Não é edição. <br><span class="italic text-white/80">É direção de arte.</span>' ?>
                </h2>
                
                <p class="text-white/60 text-lg mb-10 leading-relaxed">
                    <?= htmlspecialchars($dcContent['dc_description'] ?? 'Cada filme Memora passa por um processo de pós-produção digno de cinema. Nossos editores são diretores criativos que tratam cada projeto como uma obra única.') ?>
                </p>
                
                <!-- Features -->
                <div class="space-y-6">
                    <?php foreach ($features as $feature): ?>
                        <div class="flex gap-4">
                            <div class="w-1 bg-memora-wine flex-shrink-0"></div>
                            <div>
                                <h3 class="text-white font-medium mb-1"><?= htmlspecialchars($feature['title']) ?></h3>
                                <p class="text-white/50 text-sm"><?= htmlspecialchars($feature['description']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Visual -->
            <div class="relative">
                <!-- Main image -->
                <div class="relative aspect-[4/3] overflow-hidden rounded-sm">
                    <img src="https://imagedelivery.net/mYdfeAeRRdkIXG5w7XJhtQ/8ebb07c7-6953-4c5c-25aa-c966a0f81800/public" 
                         alt="Cinema editing"
                         class="w-full h-full object-cover">
                    
                    <!-- Overlay gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-memora-black/60 to-transparent"></div>
                    
                    <!-- Play button overlay -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <button onclick="openVideoModal('<?= $dcContent['directors_cut_video_url'] ?? 'https://www.youtube.com/embed/tfjtbAAuAUA' ?>')" 
                                class="w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm border border-white/30 flex items-center justify-center hover:bg-white/20 transition-colors">
                            <svg class="w-8 h-8 text-white fill-current ml-1" viewBox="0 0 24 24">
                                <polygon points="5 3 19 12 5 21 5 3"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Floating stats -->
                <div class="absolute -bottom-6 -left-6 bg-memora-wine p-6 rounded-sm shadow-2xl">
                    <div class="text-4xl font-serif text-white mb-1">48h</div>
                    <div class="text-[10px] uppercase tracking-widest text-white/60">Entrega Média</div>
                </div>
                
                <div class="absolute -top-6 -right-6 bg-white text-memora-black p-6 rounded-sm shadow-2xl">
                    <div class="text-4xl font-serif text-memora-wine mb-1">4K</div>
                    <div class="text-[10px] uppercase tracking-widest text-memora-black/60">Qualidade Final</div>
                </div>
            </div>
        </div>
    </div>
</section>
