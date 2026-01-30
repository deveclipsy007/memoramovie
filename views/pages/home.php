<?php
/**
 * Home Page - Memora Movie
 * Página principal com todas as seções
 */

$pageTitle = 'MEMORA MOVIE - Sua história. Como um filme.';
$pageDescription = 'Transformamos seus momentos em filmes com alma de cinema. Para emocionar. Para presentear. Para nunca esquecer.';

// Capturar conteúdo
ob_start();
?>

<?php include __DIR__ . '/../components/sections/hero.php'; ?>
<?php include __DIR__ . '/../components/sections/chapter-marquee.php'; ?>
<?php include __DIR__ . '/../components/sections/manifesto.php'; ?>
<?php include __DIR__ . '/../components/sections/emotional-occasions.php'; ?>
<?php include __DIR__ . '/../components/sections/process-timeline.php'; ?>
<?php include __DIR__ . '/../components/sections/directors-cut.php'; ?>
<?php include __DIR__ . '/../components/sections/reaction-carousel.php'; ?>
<?php include __DIR__ . '/../components/sections/pricing.php'; ?>
<?php include __DIR__ . '/../components/sections/love-back-guarantee.php'; ?>
<?php include __DIR__ . '/../components/sections/faq-accordion.php'; ?>
<?php include __DIR__ . '/../components/sections/final-cta.php'; ?>

<?php
$content = ob_get_clean();

// Renderizar com layout base
include __DIR__ . '/../layout/base.php';
?>
