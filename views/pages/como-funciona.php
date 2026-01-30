<?php
/**
 * Como Funciona Page - Memora Movie
 */

$pageTitle = 'Como Criamos Magia - MEMORA MOVIE';
$pageDescription = 'Nosso processo de criação, do upload ao play.';

ob_start();
?>

<div class="pt-24 min-h-screen bg-memora-cream">
    <!-- Timeline -->
    <?php include __DIR__ . '/../components/sections/process-timeline.php'; ?>
    
    <!-- Directors Cut -->
    <?php include __DIR__ . '/../components/sections/directors-cut.php'; ?>
    
    <!-- Reviews -->
    <?php include __DIR__ . '/../components/sections/reaction-carousel.php'; ?>
    
    <!-- FAQ -->
    <?php include __DIR__ . '/../components/sections/faq-accordion.php'; ?>
    
    <!-- Final CTA -->
    <?php include __DIR__ . '/../components/sections/final-cta.php'; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/base.php';
?>
