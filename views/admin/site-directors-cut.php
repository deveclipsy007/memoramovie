<?php
/**
 * Admin Site Director's Cut - Memora Movie
 * Edição da seção Director's Cut
 */

$pageTitle = 'Editar Director\'s Cut - Admin MEMORA MOVIE';
$pageHeading = 'Editar Seção Director\'s Cut';

// Buscar conteúdo atual
try {
    require_once __DIR__ . '/../../api/db.php';
    $stmt = $pdo->query("SELECT * FROM site_content WHERE section = 'directors-cut'");
    $rows = $stmt->fetchAll();
    
    $dcContent = [];
    foreach ($rows as $row) {
        $dcContent[$row['id']] = $row['value'];
    }
} catch (Exception $e) {
    $dcContent = [];
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
        <form id="dc-form" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Título da Seção</label>
                <input type="text" id="dc_title" name="dc_title" 
                       value="<?= htmlspecialchars($dcContent['dc_title'] ?? 'Não é edição. É direção de arte.') ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine focus:border-memora-wine">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                <textarea id="dc_description" name="dc_description" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine focus:border-memora-wine"><?= htmlspecialchars($dcContent['dc_description'] ?? 'Cada filme Memora passa por um processo de pós-produção digno de cinema.') ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">URL do Vídeo (YouTube Embed)</label>
                <input type="url" id="directors_cut_video_url" name="directors_cut_video_url"
                       value="<?= htmlspecialchars($dcContent['directors_cut_video_url'] ?? 'https://www.youtube.com/embed/tfjtbAAuAUA') ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine focus:border-memora-wine"
                       placeholder="https://www.youtube.com/embed/...">
            </div>
            
            <button type="submit" 
                    class="w-full py-3 bg-memora-wine text-white font-medium rounded-lg hover:bg-memora-wineLight transition-colors">
                Salvar Alterações
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('dc-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const items = [
        { id: 'dc_title', section: 'directors-cut', content_type: 'text', value: document.getElementById('dc_title').value },
        { id: 'dc_description', section: 'directors-cut', content_type: 'text', value: document.getElementById('dc_description').value },
        { id: 'directors_cut_video_url', section: 'directors-cut', content_type: 'video', value: document.getElementById('directors_cut_video_url').value },
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
