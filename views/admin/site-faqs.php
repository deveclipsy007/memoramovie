<?php
/**
 * Admin Site FAQs - Memora Movie
 */

$pageTitle = 'FAQs - Admin MEMORA MOVIE';
$pageHeading = 'Gerenciar FAQs';

try {
    require_once __DIR__ . '/../../api/db.php';
    $faqs = $pdo->query("SELECT * FROM site_faqs ORDER BY display_order")->fetchAll();
} catch (Exception $e) {
    $faqs = [];
}

ob_start();
?>

<div class="mb-6 flex items-center justify-between">
    <a href="/admin/site" class="inline-flex items-center gap-2 text-gray-500 hover:text-memora-wine transition-colors">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
        Voltar
    </a>
    <button onclick="openFaqModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-memora-wine text-white rounded-lg hover:bg-memora-wineLight transition-colors">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
        Nova FAQ
    </button>
</div>

<div class="bg-white rounded-lg border border-gray-200 divide-y divide-gray-200">
    <?php foreach ($faqs as $faq): ?>
        <div class="p-4 flex items-start gap-4">
            <div class="flex-1">
                <h4 class="font-medium text-gray-800"><?= htmlspecialchars($faq['question']) ?></h4>
                <p class="text-sm text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars($faq['answer']) ?></p>
            </div>
            <div class="flex gap-2">
                <button onclick="editFaq(<?= $faq['id'] ?>)" class="p-2 text-gray-500 hover:text-memora-wine hover:bg-gray-100 rounded">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                </button>
                <button onclick="deleteFaq(<?= $faq['id'] ?>)" class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                </button>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($faqs)): ?>
        <p class="p-8 text-center text-gray-500">Nenhuma FAQ cadastrada</p>
    <?php endif; ?>
</div>

<div id="faq-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeFaqModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg w-full max-w-lg">
            <div class="p-6 border-b"><h3 id="faq-modal-title" class="text-lg font-semibold">Nova FAQ</h3></div>
            <form id="faq-form" onsubmit="saveFaq(event)" class="p-6 space-y-4">
                <input type="hidden" id="faq-id">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pergunta</label>
                    <input type="text" id="faq-question" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Resposta</label>
                    <textarea id="faq-answer" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine"></textarea>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeFaqModal()" class="flex-1 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="flex-1 py-2 bg-memora-wine text-white rounded-lg hover:bg-memora-wineLight">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const faqsData = <?= json_encode($faqs) ?>;
let editingFaqId = null;

function openFaqModal() { editingFaqId = null; document.getElementById('faq-modal-title').textContent = 'Nova FAQ'; document.getElementById('faq-form').reset(); document.getElementById('faq-modal').classList.remove('hidden'); }
function closeFaqModal() { document.getElementById('faq-modal').classList.add('hidden'); }

function editFaq(id) {
    const faq = faqsData.find(f => f.id == id);
    if (!faq) return;
    editingFaqId = id;
    document.getElementById('faq-modal-title').textContent = 'Editar FAQ';
    document.getElementById('faq-id').value = faq.id;
    document.getElementById('faq-question').value = faq.question;
    document.getElementById('faq-answer').value = faq.answer;
    document.getElementById('faq-modal').classList.remove('hidden');
}

async function saveFaq(e) {
    e.preventDefault();
    const data = { question: document.getElementById('faq-question').value, answer: document.getElementById('faq-answer').value };
    try {
        if (editingFaqId) { await api.put('/faqs/' + editingFaqId, data); showToast('FAQ atualizada!'); }
        else { await api.post('/faqs', data); showToast('FAQ criada!'); }
        setTimeout(() => location.reload(), 500);
    } catch (error) { showToast(error.message, 'error'); }
}

async function deleteFaq(id) {
    if (!confirm('Excluir esta FAQ?')) return;
    try { await api.delete('/faqs/' + id); showToast('FAQ excluída!'); setTimeout(() => location.reload(), 500); }
    catch (error) { showToast(error.message, 'error'); }
}
</script>

<?php $adminContent = ob_get_clean(); include __DIR__ . '/layout.php'; ?>
