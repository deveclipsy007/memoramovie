<?php
/**
 * Footer Component - Memora Movie
 * Rodapé com links, newsletter e social
 */
?>

<footer class="bg-memora-wine text-memora-cream py-20 px-6 relative overflow-hidden">
    <!-- Texture overlay -->
    <div class="absolute inset-0 opacity-5 pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 relative z-10">
        
        <!-- Logo e descrição -->
        <div class="md:col-span-1">
            <div class="flex items-center gap-2 mb-6">
                <img
                    src="https://imagedelivery.net/mYdfeAeRRdkIXG5w7XJhtQ/18f53a7d-1f02-47e9-c486-61461b2bc000/public"
                    alt="Memora logo"
                    class="h-12 w-auto"
                    loading="lazy"
                >
                <span class="sr-only">Memora</span>
            </div>
            <p class="text-memora-cream/60 text-sm leading-relaxed mb-6">
                Transformando o caos da galeria em cinema.<br>
                Design editorial. Finalização humana.<br>
                Entregue em 48h.
            </p>
            <div class="flex gap-4">
                <!-- Instagram -->
                <a href="#" class="opacity-60 hover:opacity-100 transition-opacity">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                    </svg>
                </a>
                <!-- Email -->
                <a href="mailto:hello@memora.com" class="opacity-60 hover:opacity-100 transition-opacity">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="16" x="2" y="4" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Explorar -->
        <div>
            <h4 class="font-serif text-lg mb-6">Explorar</h4>
            <ul class="space-y-4 text-sm text-memora-cream/60">
                <li><a href="/capitulos" class="hover:text-white transition-colors">Catálogo</a></li>
                <li><a href="/como-funciona" class="hover:text-white transition-colors">Nossa Metodologia</a></li>
                <li><a href="/precos" class="hover:text-white transition-colors">Planos & Presentes</a></li>
            </ul>
        </div>

        <!-- Suporte -->
        <div>
            <h4 class="font-serif text-lg mb-6">Suporte</h4>
            <ul class="space-y-4 text-sm text-memora-cream/60">
                <li><a href="/faq" class="hover:text-white transition-colors">Dúvidas Frequentes</a></li>
                <li>
                    <a href="/privacidade" class="hover:text-white transition-colors flex items-center gap-2">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        Privacidade & Segurança
                    </a>
                </li>
                <li><a href="mailto:hello@memora.com" class="hover:text-white transition-colors">hello@memora.com</a></li>
            </ul>
        </div>

        <!-- Newsletter -->
        <div>
            <h4 class="font-serif text-lg mb-6">Newsletter</h4>
            <p class="text-xs text-memora-cream/60 mb-4">Receba dicas de como capturar melhores memórias.</p>
            <form class="flex gap-2" onsubmit="event.preventDefault(); alert('Obrigado por se inscrever!');">
                <input 
                    type="email" 
                    placeholder="Email" 
                    required
                    class="bg-white/10 border border-memora-cream/20 px-4 py-2 text-sm w-full focus:outline-none focus:border-memora-cream/40 text-white placeholder-memora-cream/30"
                >
                <button type="submit" class="text-xs uppercase tracking-widest border border-memora-cream px-4 hover:bg-memora-cream hover:text-memora-wine transition-colors">
                    Ok
                </button>
            </form>
        </div>
    </div>
    
    <!-- Bottom bar -->
    <div class="max-w-7xl mx-auto mt-20 pt-8 border-t border-memora-cream/10 flex flex-col md:flex-row justify-between items-center text-xs text-memora-cream/40">
        <p>© <?= date('Y') ?> Memora Movie Studios. All rights reserved.</p>
        <div class="flex items-center gap-6 mt-4 md:mt-0">
            <span>Visa</span>
            <span>Mastercard</span>
            <span>Apple Pay</span>
            <span>Pix</span>
        </div>
    </div>
</footer>
