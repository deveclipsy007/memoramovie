<?php
/**
 * Criar Page - Memora Movie
 * Página do Quiz de criação
 */

$pageTitle = 'Criar meu Filme - MEMORA MOVIE';
$pageDescription = 'Comece a criar seu filme agora.';

$selectedPlan = $_GET['plan'] ?? null;
$selectedChapter = $_GET['chapter'] ?? null;

ob_start();
?>

<div class="pt-24 min-h-screen bg-memora-cream">
    <!-- Pricing with Quiz -->
    <?php include __DIR__ . '/../components/sections/pricing.php'; ?>
    
    <!-- Guarantee -->
    <?php include __DIR__ . '/../components/sections/love-back-guarantee.php'; ?>
    
    <!-- FAQ -->
    <?php include __DIR__ . '/../components/sections/faq-accordion.php'; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/base.php';
?>
