<?php
/**
 * Privacidade Page - Memora Movie
 */

$pageTitle = 'Privacidade & Segurança - MEMORA MOVIE';
$pageDescription = 'Como protegemos suas memórias.';

ob_start();
?>

<div class="pt-24 min-h-screen bg-memora-cream">
    <div class="max-w-3xl mx-auto px-6 py-20">
        <h1 class="font-serif text-5xl text-memora-wine mb-8 text-center">Sua Privacidade</h1>
        
        <div class="bg-white p-8 rounded-sm border border-memora-wine/10 prose prose-lg max-w-none">
            <h2 class="font-serif text-2xl text-memora-wine">Suas memórias são sagradas</h2>
            <p class="text-memora-black/70">
                Na Memora Movie, entendemos que você está confiando a nós os momentos mais preciosos da sua vida. 
                Por isso, tratamos cada arquivo com o máximo cuidado e sigilo.
            </p>
            
            <h3 class="font-serif text-xl text-memora-wine mt-8">Como protegemos seus dados</h3>
            <ul class="text-memora-black/70 space-y-3">
                <li><strong>Criptografia:</strong> Todos os arquivos são transmitidos e armazenados com criptografia de ponta.</li>
                <li><strong>Acesso restrito:</strong> Apenas o editor designado para seu projeto tem acesso aos arquivos.</li>
                <li><strong>Exclusão automática:</strong> Após a entrega e aprovação, seus arquivos são deletados permanentemente em 7 dias.</li>
                <li><strong>Sem compartilhamento:</strong> Nunca compartilhamos, vendemos ou usamos seus arquivos para qualquer outro propósito.</li>
            </ul>
            
            <h3 class="font-serif text-xl text-memora-wine mt-8">Seus direitos</h3>
            <p class="text-memora-black/70">
                Você pode solicitar a exclusão imediata de seus arquivos a qualquer momento. 
                Basta entrar em contato conosco.
            </p>
            
            <h3 class="font-serif text-xl text-memora-wine mt-8">Contato</h3>
            <p class="text-memora-black/70">
                Para questões sobre privacidade: <a href="mailto:privacidade@memora.com" class="text-memora-wine hover:underline">privacidade@memora.com</a>
            </p>
        </div>
        
        <div class="text-center mt-12">
            <a href="/" class="inline-flex items-center gap-2 text-memora-wine hover:text-memora-wineLight transition-colors">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"/>
                    <path d="M19 12H5"/>
                </svg>
                Voltar ao Início
            </a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/base.php';
?>
