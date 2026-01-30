<?php
/**
 * Preços Page - Memora Movie
 */

$pageTitle = 'Planos & Preços - MEMORA MOVIE';
$pageDescription = 'Descubra o plano perfeito para sua história.';

ob_start();
?>

<div class="pt-24 min-h-screen bg-memora-cream">
    <!-- Pricing Section -->
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
