<?php
/**
 * Admin Site Hero - Memora Movie
 * Edição da seção Hero
 */

$pageTitle = 'Editar Hero - Admin MEMORA MOVIE';
$pageHeading = 'Editar Seção Hero';

// Buscar conteúdo atual
try {
    require_once __DIR__ . '/../../api/config.sqlite.php';
    $stmt = $pdo->query("SELECT * FROM site_content WHERE section = 'hero'");
    $rows = $stmt->fetchAll();
    
    $heroContent = [];
    foreach ($rows as $row) {
        $heroContent[$row['id']] = $row['value'];
    }
} catch (Exception $e) {
    $heroContent = [];
}

ob_start();
?>

<div class="max-w-2xl">
    <a href="/admin/site" class="inline-flex items-center gap-2 text-gray-500 hover:text-memora-wine mb-6 transition-colors">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
        </svg>
        Voltar
    </a>
    
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <form id="hero-form" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Título Principal</label>
                <input type="text" id="hero_title" name="hero_title" 
                       value="<?= htmlspecialchars($heroContent['hero_title'] ?? '') ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine focus:border-memora-wine"
                       placeholder="Sua história. Como um filme.">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Subtítulo</label>
                <textarea id="hero_subtitle" name="hero_subtitle" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine focus:border-memora-wine"
                          placeholder="Transformamos seus momentos..."><?= htmlspecialchars($heroContent['hero_subtitle'] ?? '') ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">URL do Vídeo (YouTube Embed)</label>
                <input type="url" id="hero_video_url" name="hero_video_url"
                       value="<?= htmlspecialchars($heroContent['hero_video_url'] ?? '') ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine focus:border-memora-wine"
                       placeholder="https://www.youtube.com/embed/...">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Texto do Botão CTA</label>
                <input type="text" id="hero_cta_text" name="hero_cta_text"
                       value="<?= htmlspecialchars($heroContent['hero_cta_text'] ?? 'Eternizar meu Momento') ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine focus:border-memora-wine"
                       placeholder="Eternizar meu Momento">
            </div>
            
            <button type="submit" 
                    class="w-full py-3 bg-memora-wine text-white font-medium rounded-lg hover:bg-memora-wineLight transition-colors">
                Salvar Alterações
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('hero-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const items = [
        { id: 'hero_title', section: 'hero', content_type: 'text', value: document.getElementById('hero_title').value },
        { id: 'hero_subtitle', section: 'hero', content_type: 'text', value: document.getElementById('hero_subtitle').value },
        { id: 'hero_video_url', section: 'hero', content_type: 'video', value: document.getElementById('hero_video_url').value },
        { id: 'hero_cta_text', section: 'hero', content_type: 'text', value: document.getElementById('hero_cta_text').value },
    ];
    
    try {
        await api.post('/site/content', { items });
        showToast('Conteúdo salvo com sucesso!');
    } catch (error) {
        showToast(error.message, 'error');
    }
});
</script>

<?php
$adminContent = ob_get_clean();
include __DIR__ . '/layout.php';
?>
