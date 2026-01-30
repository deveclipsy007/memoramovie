<?php
/**
 * Mobile Sticky CTA Component - Memora Movie
 * Botão fixo no mobile para conversão
 */
?>

<!-- CTA fixo no mobile -->
<div id="mobile-sticky-cta" class="md:hidden fixed bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-memora-cream via-memora-cream to-transparent z-40 transition-all duration-300 translate-y-full opacity-0">
    <a href="/criar" 
       class="block w-full py-4 text-center text-sm font-medium tracking-widest uppercase transition-all duration-300 bg-memora-wine text-white hover:bg-memora-wineLight shadow-xl shadow-memora-wine/30">
        Criar meu Filme
    </a>
</div>

<script>
// Mostrar CTA após scroll
(function() {
    const stickyCta = document.getElementById('mobile-sticky-cta');
    let hasShown = false;
    
    window.addEventListener('scroll', function() {
        // Mostrar após 300px de scroll
        if (window.scrollY > 300) {
            if (!hasShown) {
                stickyCta.classList.remove('translate-y-full', 'opacity-0');
                stickyCta.classList.add('translate-y-0', 'opacity-100');
                hasShown = true;
            }
        }
        
        // Esconder próximo do final (footer)
        const scrollHeight = document.documentElement.scrollHeight;
        const clientHeight = document.documentElement.clientHeight;
        const scrollTop = window.scrollY;
        
        if (scrollTop + clientHeight > scrollHeight - 200) {
            stickyCta.classList.add('translate-y-full', 'opacity-0');
            stickyCta.classList.remove('translate-y-0', 'opacity-100');
        } else if (scrollTop > 300) {
            stickyCta.classList.remove('translate-y-full', 'opacity-0');
            stickyCta.classList.add('translate-y-0', 'opacity-100');
        }
    });
})();
</script>
