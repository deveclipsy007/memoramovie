<?php
/**
 * Admin Logs - Memora Movie
 */

$pageTitle = 'Logs - Admin MEMORA MOVIE';
$pageHeading = 'Logs do Sistema';

try {
    require_once __DIR__ . '/../../api/db.php';
    $logs = $pdo->query("SELECT * FROM logs ORDER BY created_at DESC LIMIT 100")->fetchAll();
} catch (Exception $e) {
    $logs = [];
}

ob_start();
?>

<div class="flex items-center justify-between mb-6">
    <p class="text-gray-600"><?= count($logs) ?> logs recentes</p>
    <button onclick="clearLogs()" class="px-4 py-2 text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors text-sm">
        Limpar Logs
    </button>
</div>

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nível</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mensagem</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">URL</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            <?php foreach ($logs as $log): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            <?= $log['level'] === 'error' ? 'bg-red-100 text-red-700' : ($log['level'] === 'warning' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') ?>">
                            <?= htmlspecialchars($log['level'] ?? 'info') ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-800 max-w-md truncate"><?= htmlspecialchars($log['message']) ?></td>
                    <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate"><?= htmlspecialchars($log['url'] ?? '-') ?></td>
                    <td class="px-4 py-3 text-sm text-gray-500"><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($logs)): ?>
                <tr><td colspan="4" class="px-4 py-12 text-center text-gray-500">Nenhum log registrado</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
async function clearLogs() {
    if (!confirm('Limpar todos os logs?')) return;
    try { await api.delete('/logs'); showToast('Logs limpos!'); setTimeout(() => location.reload(), 500); }
    catch (error) { showToast(error.message, 'error'); }
}
</script>

<?php $adminContent = ob_get_clean(); include __DIR__ . '/layout.php'; ?>
