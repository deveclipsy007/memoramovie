<?php
/**
 * Admin Site Plans - Memora Movie
 */

$pageTitle = 'Planos - Admin MEMORA MOVIE';
$pageHeading = 'Gerenciar Planos';

try {
    require_once __DIR__ . '/../../api/db.php';
    $plans = $pdo->query("SELECT * FROM plans ORDER BY id")->fetchAll();
} catch (Exception $e) {
    $plans = [];
}

ob_start();
?>

<div class="mb-6">
    <a href="/admin/site" class="inline-flex items-center gap-2 text-gray-500 hover:text-memora-wine transition-colors">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
        Voltar
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php foreach ($plans as $plan): ?>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-medium px-2 py-1 bg-memora-wine/10 text-memora-wine rounded">Plano <?= htmlspecialchars($plan['id']) ?></span>
                <button onclick="editPlan('<?= $plan['id'] ?>')" class="p-2 text-gray-500 hover:text-memora-wine hover:bg-gray-100 rounded">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                </button>
            </div>
            <h3 class="font-semibold text-gray-800 text-lg mb-2"><?= htmlspecialchars($plan['name']) ?></h3>
            <p class="text-3xl font-bold text-memora-wine mb-2">R$ <?= number_format($plan['price'], 2, ',', '.') ?></p>
            <p class="text-sm text-gray-500 mb-2"><?= htmlspecialchars($plan['duration']) ?></p>
            <p class="text-sm text-gray-600"><?= htmlspecialchars($plan['description']) ?></p>
            <p class="text-xs text-gray-400 mt-4">Entrega: <?= htmlspecialchars($plan['delivery_time']) ?></p>
        </div>
    <?php endforeach; ?>
</div>

<div id="plan-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg w-full max-w-lg">
            <div class="p-6 border-b"><h3 class="text-lg font-semibold">Editar Plano</h3></div>
            <form id="plan-form" onsubmit="savePlan(event)" class="p-6 space-y-4">
                <input type="hidden" id="plan-id">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                    <input type="text" id="plan-name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preço (R$)</label>
                    <input type="number" step="0.01" id="plan-price" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duração</label>
                    <input type="text" id="plan-duration" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                    <textarea id="plan-description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempo de Entrega</label>
                    <input type="text" id="plan-delivery" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
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
const plansData = <?= json_encode($plans) ?>;
let editingId = null;

function closeModal() { document.getElementById('plan-modal').classList.add('hidden'); }

function editPlan(id) {
    const p = plansData.find(x => x.id == id); if (!p) return;
    editingId = id;
    document.getElementById('plan-id').value = p.id;
    document.getElementById('plan-name').value = p.name;
    document.getElementById('plan-price').value = p.price;
    document.getElementById('plan-duration').value = p.duration || '';
    document.getElementById('plan-description').value = p.description || '';
    document.getElementById('plan-delivery').value = p.delivery_time || '';
    document.getElementById('plan-modal').classList.remove('hidden');
}

async function savePlan(e) {
    e.preventDefault();
    const data = {
        name: document.getElementById('plan-name').value,
        price: parseFloat(document.getElementById('plan-price').value),
        duration: document.getElementById('plan-duration').value,
        description: document.getElementById('plan-description').value,
        delivery_time: document.getElementById('plan-delivery').value
    };

    try {
        await api.put('/plans/' + editingId, data);
        showToast('Plano atualizado com sucesso!');
        setTimeout(() => location.reload(), 500);
    } catch (error) {
        showToast(error.message, 'error');
    }
}

</script>

<?php $adminContent = ob_get_clean(); include __DIR__ . '/layout.php'; ?>
