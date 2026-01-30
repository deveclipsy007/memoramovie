<?php
/**
 * Admin Settings - Memora Movie
 */

$pageTitle = 'Configurações - Admin MEMORA MOVIE';
$pageHeading = 'Configurações';

try {
    require_once __DIR__ . '/../../api/db.php';
    $stmt = $pdo->query("SELECT * FROM settings");
    $rows = $stmt->fetchAll();
    $settings = [];
    foreach ($rows as $row) { $settings[$row['key']] = $row['value']; }
} catch (Exception $e) {
    $settings = [];
}

ob_start();
?>

<div class="max-w-2xl">
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-800 mb-6">Configurações Gerais</h3>
        
        <form id="settings-form" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Título do Site</label>
                <input type="text" id="site_title" value="<?= htmlspecialchars($settings['site_title'] ?? 'Memora Movie') ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email de Contato</label>
                <input type="email" id="contact_email" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-memora-wine">
            </div>
            
            <button type="submit" class="w-full py-3 bg-memora-wine text-white font-medium rounded-lg hover:bg-memora-wineLight transition-colors">
                Salvar Configurações
            </button>
        </form>
    </div>
    
    <div class="bg-white rounded-lg border border-gray-200 p-6 mt-6">
        <h3 class="font-semibold text-gray-800 mb-4">Alterar Senha</h3>
        <p class="text-sm text-gray-500 mb-4">Use o terminal com o comando PHP para gerar novo hash de senha:</p>
        <code class="block bg-gray-100 p-3 rounded text-sm">php -r "echo password_hash('nova_senha', PASSWORD_DEFAULT);"</code>
    </div>
</div>

<script>
document.getElementById('settings-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    try {
        await api.post('/settings/site_title', { value: document.getElementById('site_title').value });
        await api.post('/settings/contact_email', { value: document.getElementById('contact_email').value });
        showToast('Configurações salvas!');
    } catch (error) { showToast(error.message, 'error'); }
});
</script>

<?php $adminContent = ob_get_clean(); include __DIR__ . '/layout.php'; ?>
