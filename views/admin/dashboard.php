<?php
/**
 * Admin Dashboard - Memora Movie
 */

$pageTitle = 'Dashboard - Admin MEMORA MOVIE';
$pageHeading = 'Dashboard';

// Buscar estatísticas
try {
    require_once __DIR__ . '/../../api/config.sqlite.php';
    
    // Total de leads
    $totalLeads = $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
    $newLeads = $pdo->query("SELECT COUNT(*) FROM leads WHERE status = 'new'")->fetchColumn();
    
    // Leads por período
    $leadsToday = $pdo->query("SELECT COUNT(*) FROM leads WHERE DATE(created_at) = DATE('now')")->fetchColumn();
    $leadsWeek = $pdo->query("SELECT COUNT(*) FROM leads WHERE created_at >= DATE('now', '-7 days')")->fetchColumn();
    
    // Total de capítulos
    $totalChapters = $pdo->query("SELECT COUNT(*) FROM chapters")->fetchColumn();
    
    // Leads recentes
    $recentLeads = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC LIMIT 5")->fetchAll();
    
} catch (Exception $e) {
    $totalLeads = 0;
    $newLeads = 0;
    $leadsToday = 0;
    $leadsWeek = 0;
    $totalChapters = 0;
    $recentLeads = [];
}

ob_start();
?>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Leads -->
    <div class="bg-white rounded-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total de Leads</p>
                <p class="text-3xl font-bold text-gray-800"><?= $totalLeads ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- New Leads -->
    <div class="bg-white rounded-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Leads Novos</p>
                <p class="text-3xl font-bold text-green-600"><?= $newLeads ?></p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Leads Esta Semana -->
    <div class="bg-white rounded-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Leads (7 dias)</p>
                <p class="text-3xl font-bold text-purple-600"><?= $leadsWeek ?></p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M8 2v4"/>
                    <path d="M16 2v4"/>
                    <rect width="18" height="18" x="3" y="4" rx="2"/>
                    <path d="M3 10h18"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Capítulos -->
    <div class="bg-white rounded-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Capítulos Ativos</p>
                <p class="text-3xl font-bold text-memora-wine"><?= $totalChapters ?></p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-memora-wine" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                    <path d="M7 3v18"/><path d="M3 7.5h4"/><path d="M3 12h18"/><path d="M3 16.5h4"/>
                    <path d="M17 3v18"/><path d="M17 7.5h4"/><path d="M17 16.5h4"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Recent Leads -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg p-6 border border-gray-200">
        <h3 class="font-semibold text-gray-800 mb-4">Ações Rápidas</h3>
        <div class="space-y-3">
            <a href="/admin/leads" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                    </svg>
                </div>
                <span class="text-sm text-gray-700">Ver Leads</span>
            </a>
            <a href="/admin/chapters" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14"/><path d="M5 12h14"/>
                    </svg>
                </div>
                <span class="text-sm text-gray-700">Gerenciar Capítulos</span>
            </a>
            <a href="/admin/site" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/>
                        <path d="M2 12h20"/>
                    </svg>
                </div>
                <span class="text-sm text-gray-700">Editar Site</span>
            </a>
        </div>
    </div>
    
    <!-- Recent Leads -->
    <div class="lg:col-span-2 bg-white rounded-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Leads Recentes</h3>
            <a href="/admin/leads" class="text-sm text-memora-wine hover:underline">Ver todos</a>
        </div>
        
        <?php if (empty($recentLeads)): ?>
            <p class="text-gray-500 text-center py-8">Nenhum lead ainda</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($recentLeads as $lead): ?>
                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-memora-wine/10 flex items-center justify-center text-memora-wine font-medium">
                                <?= $lead['name'] ? strtoupper(substr($lead['name'], 0, 1)) : '?' ?>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800"><?= htmlspecialchars($lead['name'] ?: 'Sem nome') ?></p>
                                <p class="text-sm text-gray-500"><?= htmlspecialchars($lead['email'] ?: $lead['phone'] ?: 'Sem contato') ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                <?php 
                                switch ($lead['status']) {
                                    case 'new': echo 'bg-green-100 text-green-700'; break;
                                    case 'contacted': echo 'bg-blue-100 text-blue-700'; break;
                                    case 'converted': echo 'bg-purple-100 text-purple-700'; break;
                                    default: echo 'bg-gray-100 text-gray-700';
                                }
                                ?>">
                                <?= ucfirst($lead['status']) ?>
                            </span>
                            <p class="text-xs text-gray-400 mt-1"><?= date('d/m H:i', strtotime($lead['created_at'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$adminContent = ob_get_clean();
include __DIR__ . '/layout.php';
?>
