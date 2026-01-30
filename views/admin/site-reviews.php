<?php
/**
 * Admin Site Reviews - Memora Movie
 */

$pageTitle = 'Depoimentos - Admin MEMORA MOVIE';
$pageHeading = 'Gerenciar Depoimentos';

try {
    require_once __DIR__ . '/../../api/config.sqlite.php';
    $reviews = $pdo->query("SELECT * FROM site_reviews ORDER BY display_order")->fetchAll();
} catch (Exception $e) {
    $reviews = [];
}

ob_start();
?>

<div class="mb-6 flex items-center justify-between">
    <a href="/admin/site" class="inline-flex items-center gap-2 text-gray-500 hover:text-memora-wine transition-colors">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
        Voltar
    </a>
    <button onclick="openModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-memora-wine text-white rounded-lg hover:bg-memora-wineLight">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
        Novo Depoimento
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($reviews as $review): ?>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <p class="text-gray-600 italic mb-4">"<?= htmlspecialchars(substr($review['text'], 0, 150)) ?>..."</p>
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-800"><?= htmlspecialchars($review['author']) ?></p>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($review['role'] ?? '') ?></p>
                </div>
                <div class="flex gap-2">
                    <button onclick="editReview(<?= $review['id'] ?>)" class="p-2 text-gray-500 hover:text-memora-wine hover:bg-gray-100 rounded">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                    </button>
                    <button onclick="deleteReview(<?= $review['id'] ?>)" class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg>
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div id="review-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg w-full max-w-lg">
            <div class="p-6 border-b"><h3 id="modal-title" class="text-lg font-semibold">Novo Depoimento</h3></div>
            <form id="review-form" onsubmit="saveReview(event)" class="p-6 space-y-4">
                <input type="hidden" id="review-id">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Texto</label>
                    <textarea id="review-text" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
                    <input type="text" id="review-author" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cargo/Filme</label>
                    <input type="text" id="review-role" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="flex-1 py-2 border border-gray-300 rounded-lg">Cancelar</button>
                    <button type="submit" class="flex-1 py-2 bg-memora-wine text-white rounded-lg">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const reviewsData = <?= json_encode($reviews) ?>;
let editingId = null;

function openModal() { editingId = null; document.getElementById('modal-title').textContent = 'Novo Depoimento'; document.getElementById('review-form').reset(); document.getElementById('review-modal').classList.remove('hidden'); }
function closeModal() { document.getElementById('review-modal').classList.add('hidden'); }

function editReview(id) {
    const r = reviewsData.find(x => x.id == id); if (!r) return;
    editingId = id;
    document.getElementById('modal-title').textContent = 'Editar Depoimento';
    document.getElementById('review-id').value = r.id;
    document.getElementById('review-text').value = r.text;
    document.getElementById('review-author').value = r.author;
    document.getElementById('review-role').value = r.role || '';
    document.getElementById('review-modal').classList.remove('hidden');
}

async function saveReview(e) {
    e.preventDefault();
    const data = { text: document.getElementById('review-text').value, author: document.getElementById('review-author').value, role: document.getElementById('review-role').value };
    try { if (editingId) { await api.put('/reviews/' + editingId, data); } else { await api.post('/reviews', data); } showToast('Salvo!'); setTimeout(() => location.reload(), 500); }
    catch (error) { showToast(error.message, 'error'); }
}

async function deleteReview(id) {
    if (!confirm('Excluir?')) return;
    try { await api.delete('/reviews/' + id); showToast('Excluído!'); setTimeout(() => location.reload(), 500); }
    catch (error) { showToast(error.message, 'error'); }
}
</script>

<?php $adminContent = ob_get_clean(); include __DIR__ . '/layout.php'; ?>
