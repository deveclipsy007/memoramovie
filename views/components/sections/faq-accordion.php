<?php
/**
 * FAQ Accordion Section - Memora Movie
 * Perguntas frequentes com accordion
 */

// Buscar FAQs do banco
try {
    require_once __DIR__ . '/../../../api/db.php';
    $stmt = $pdo->query("SELECT * FROM site_faqs ORDER BY display_order");
    $faqs = $stmt->fetchAll();
} catch (Exception $e) {
    $faqs = [
        ['id' => 1, 'question' => 'Como vocês criam emoção com arquivos "comuns"?', 'answer' => 'O segredo não está na qualidade da câmera, mas no olhar. Nossos editores buscam o "olhar" escondido, o toque de mão, o sorriso espontâneo. Usamos técnicas de cinema para transformar um vídeo tremido de celular em uma memória poética.'],
        ['id' => 2, 'question' => 'Posso enviar áudios de WhatsApp ou depoimentos?', 'answer' => 'Sim! A voz de um avô contando uma história, ou um áudio antigo de "eu te amo" pode ser a alma do filme. Nós mixamos esses áudios com a música para criar uma narrativa documental.'],
        ['id' => 3, 'question' => 'Quero fazer uma surpresa/reconquista. Vocês ajudam?', 'answer' => 'Com certeza. No momento do envio, você nos conta o objetivo. A direção criativa será totalmente focada em atingir esse objetivo emocional.'],
        ['id' => 4, 'question' => 'A música faz diferença?', 'answer' => 'A música é a alma. Escolhemos trilhas cinematográficas que crescem junto com a emoção do vídeo.'],
        ['id' => 5, 'question' => 'Meus arquivos são deletados depois?', 'answer' => 'Sua privacidade é sagrada. Após a entrega e sua aprovação final, tudo é deletado permanentemente dos nossos servidores em 7 dias.'],
        ['id' => 6, 'question' => 'Como recebo o filme?', 'answer' => 'Você recebe um link de uma "Sala de Cinema Digital" privada. É uma página linda, pronta para ser enviada como presente no WhatsApp ou e-mail.'],
    ];
}
?>

<section class="py-24 md:py-32 px-6 bg-white" id="faq">
    <div class="max-w-3xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <div class="inline-block mb-4 px-4 py-2 border border-memora-wine/20 rounded-full">
                <span class="text-[10px] uppercase tracking-[0.3em] text-memora-wine/60 font-medium">
                    Dúvidas
                </span>
            </div>
            <h2 class="font-serif text-4xl md:text-5xl text-memora-wine mb-4">
                Perguntas Frequentes
            </h2>
            <p class="text-memora-black/60">
                Tudo que você precisa saber antes de começar.
            </p>
        </div>
        
        <!-- Accordion -->
        <div class="space-y-4" id="faq-accordion">
            <?php foreach ($faqs as $idx => $faq): ?>
                <div class="faq-item border border-memora-wine/10 rounded-sm overflow-hidden transition-all duration-300 hover:border-memora-wine/30">
                    <button class="faq-trigger w-full px-6 py-5 flex items-center justify-between text-left transition-colors hover:bg-memora-cream/50"
                            onclick="toggleFaq(<?= $idx ?>)"
                            aria-expanded="false"
                            data-faq-idx="<?= $idx ?>">
                        <span class="font-medium text-memora-black pr-4"><?= htmlspecialchars($faq['question']) ?></span>
                        <svg class="faq-icon w-5 h-5 text-memora-wine flex-shrink-0 transition-transform duration-300" 
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300" data-faq-content="<?= $idx ?>">
                        <div class="px-6 pb-5 text-memora-black/60 leading-relaxed">
                            <?= htmlspecialchars($faq['answer']) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- CTA -->
        <div class="text-center mt-12 pt-8 border-t border-memora-wine/10">
            <p class="text-memora-black/60 mb-4">Ainda tem dúvidas?</p>
            <a href="mailto:hello@memora.com" class="inline-flex items-center gap-2 text-memora-wine hover:text-memora-wineLight transition-colors text-sm uppercase tracking-widest">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="16" x="2" y="4" rx="2"/>
                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>
                Fale Conosco
            </a>
        </div>
    </div>
</section>

<script>
// FAQ Accordion functionality
function toggleFaq(idx) {
    const trigger = document.querySelector(`[data-faq-idx="${idx}"]`);
    const content = document.querySelector(`[data-faq-content="${idx}"]`);
    const icon = trigger.querySelector('.faq-icon');
    const isOpen = trigger.getAttribute('aria-expanded') === 'true';
    
    // Close all other FAQs
    document.querySelectorAll('.faq-trigger').forEach((t, i) => {
        if (i !== idx) {
            t.setAttribute('aria-expanded', 'false');
            const c = document.querySelector(`[data-faq-content="${i}"]`);
            const ic = t.querySelector('.faq-icon');
            c.style.maxHeight = '0px';
            ic.style.transform = 'rotate(0deg)';
        }
    });
    
    // Toggle current
    if (isOpen) {
        trigger.setAttribute('aria-expanded', 'false');
        content.style.maxHeight = '0px';
        icon.style.transform = 'rotate(0deg)';
    } else {
        trigger.setAttribute('aria-expanded', 'true');
        content.style.maxHeight = content.scrollHeight + 'px';
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>
