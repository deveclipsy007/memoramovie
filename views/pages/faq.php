<?php
/**
 * FAQ Page - Memora Movie
 */

$pageTitle = 'Dúvidas Frequentes - MEMORA MOVIE';
$pageDescription = 'Tudo que você precisa saber sobre a Memora Movie.';

ob_start();
?>

<div class="pt-24 min-h-screen bg-memora-cream">
    <!-- Header -->
    <div class="py-12 text-center">
        <h1 class="font-serif text-5xl text-memora-wine mb-4">Ajuda</h1>
        <p class="text-memora-black/60">Encontre respostas para suas dúvidas.</p>
    </div>
    
    <!-- FAQ Accordion -->
    <?php include __DIR__ . '/../components/sections/faq-accordion.php'; ?>
    
    <!-- Contact Info -->
    <section class="py-20 px-6 bg-white">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="font-serif text-3xl text-memora-wine mb-6">Ainda precisa de ajuda?</h2>
            <p class="text-memora-black/60 mb-8">Nossa equipe está pronta para atender você.</p>
            <a href="mailto:hello@memora.com" 
               class="inline-flex items-center justify-center px-10 py-4 text-sm font-medium tracking-widest uppercase transition-all duration-300 bg-memora-wine text-white hover:bg-memora-wineLight">
                Fale Conosco
            </a>
        </div>
    </section>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/base.php';
?>
