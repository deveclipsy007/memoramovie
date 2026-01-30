<?php
/**
 * Admin Chapters - Memora Movie
 * CRUD de capítulos
 */

$pageTitle = 'Capítulos - Admin MEMORA MOVIE';
$pageHeading = 'Gerenciar Capítulos';

// Buscar capítulos
try {
    require_once __DIR__ . '/../../api/db.php';
    $chapters = $pdo->query("SELECT * FROM chapters ORDER BY display_order")->fetchAll();
} catch (Exception $e) {
    $chapters = [];
}

ob_start();
?>

<!-- Header Actions -->
<div class="flex items-center justify-between mb-6">
    <p class="text-gray-600"><?= count($chapters) ?> capítulos cadastrados</p>
    <button onclick="openModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-memora-wine text-white rounded-lg hover:bg-memora-wineLight transition-colors">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
        Novo Capítulo
    </button>
</div>

<!-- Chapters Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($chapters as $chapter): ?>
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden group">
            <!-- Preview -->
            <div class="h-40 bg-gray-100 relative overflow-hidden">
                <?php if ($chapter['image_url']): ?>
                    <img src="<?= htmlspecialchars($chapter['image_url']) ?>" 
                         alt="<?= htmlspecialchars($chapter['title']) ?>"
                         class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-12 h-12" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                            <circle cx="9" cy="9" r="2"/>
                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                        </svg>
                    </div>
                <?php endif; ?>
                
                <!-- Color indicator -->
                <div class="absolute top-3 right-3 w-6 h-6 rounded-full border-2 border-white shadow-md"
                     style="background-color: <?= htmlspecialchars($chapter['color'] ?? '#5A0B18') ?>"></div>
            </div>
            
            <!-- Content -->
            <div class="p-4">
                <h3 class="font-semibold text-gray-800 mb-1"><?= htmlspecialchars($chapter['title']) ?></h3>
                <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars($chapter['subtitle'] ?? '') ?></p>
                
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400">ID: <?= htmlspecialchars($chapter['id']) ?></span>
                    <div class="flex gap-2">
                        <button onclick="editChapter('<?= htmlspecialchars($chapter['id']) ?>')" 
                                class="p-2 text-gray-500 hover:text-memora-wine hover:bg-gray-100 rounded transition-colors">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                            </svg>
                        </button>
                        <button onclick="deleteChapter('<?= htmlspecialchars($chapter['id']) ?>')" 
                                class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded transition-colors">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal -->
<div id="chapter-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <h3 id="modal-title" class="text-lg font-semibold text-gray-800">Novo Capítulo</h3>
            </div>
            <form id="chapter-form" onsubmit="saveChapter(event)" class="p-6 space-y-4">
                <input type="hidden" id="chapter-id" name="id">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID (slug)</label>
                    <input type="text" id="chapter-id-input" name="id_input" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine focus:border-memora-wine"
                           placeholder="love, travel, legacy...">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                    <input type="text" id="chapter-title" name="title" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine focus:border-memora-wine"
                           placeholder="Love Story">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtítulo</label>
                    <input type="text" id="chapter-subtitle" name="subtitle"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine focus:border-memora-wine"
                           placeholder="Para casamentos, pedidos ou reconquistas.">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL da Imagem</label>
                    <input type="url" id="chapter-image" name="image_url"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine focus:border-memora-wine"
                           placeholder="https://...">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cor</label>
                    <input type="color" id="chapter-color" name="color" value="#5A0B18"
                           class="w-full h-10 border border-gray-300 rounded-lg cursor-pointer">
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal()" 
                            class="flex-1 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 py-2 bg-memora-wine text-white rounded-lg hover:bg-memora-wineLight transition-colors">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let editingId = null;

function openModal() {
    editingId = null;
    document.getElementById('modal-title').textContent = 'Novo Capítulo';
    document.getElementById('chapter-form').reset();
    document.getElementById('chapter-id-input').disabled = false;
    document.getElementById('chapter-modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('chapter-modal').classList.add('hidden');
}

async function editChapter(id) {
    try {
        const chapter = await api.get('/chapters/' + id);
        editingId = id;
        
        document.getElementById('modal-title').textContent = 'Editar Capítulo';
        document.getElementById('chapter-id').value = chapter.id;
        document.getElementById('chapter-id-input').value = chapter.id;
        document.getElementById('chapter-id-input').disabled = true;
        document.getElementById('chapter-title').value = chapter.title;
        document.getElementById('chapter-subtitle').value = chapter.subtitle || '';
        document.getElementById('chapter-image').value = chapter.image_url || '';
        document.getElementById('chapter-color').value = chapter.color || '#5A0B18';
        
        document.getElementById('chapter-modal').classList.remove('hidden');
    } catch (error) {
        showToast(error.message, 'error');
    }
}

async function saveChapter(e) {
    e.preventDefault();
    
    const data = {
        id: document.getElementById('chapter-id-input').value,
        title: document.getElementById('chapter-title').value,
        subtitle: document.getElementById('chapter-subtitle').value,
        image_url: document.getElementById('chapter-image').value,
        color: document.getElementById('chapter-color').value,
    };
    
    try {
        if (editingId) {
            await api.put('/chapters/' + editingId, data);
            showToast('Capítulo atualizado!');
        } else {
            await api.post('/chapters', data);
            showToast('Capítulo criado!');
        }
        
        setTimeout(() => location.reload(), 500);
    } catch (error) {
        showToast(error.message, 'error');
    }
}

async function deleteChapter(id) {
    if (!confirm('Tem certeza que deseja excluir este capítulo?')) return;
    
    try {
        await api.delete('/chapters/' + id);
        showToast('Capítulo excluído!');
        setTimeout(() => location.reload(), 500);
    } catch (error) {
        showToast(error.message, 'error');
    }
}
</script>

<?php
$adminContent = ob_get_clean();
include __DIR__ . '/layout.php';
?>
