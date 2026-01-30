<?php
/**
 * Admin Leads - Memora Movie
 * Listagem e gestão de leads com detalhes das respostas do quiz
 */

$pageTitle = 'Leads - Admin MEMORA MOVIE';
$pageHeading = 'Gestão de Leads';

// Buscar leads
try {
    require_once __DIR__ . '/../../api/db.php';
    $leads = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC")->fetchAll();
    
    // Buscar perguntas e opções do quiz para mapear respostas
    $quizQuestions = [];
    $quizOptions = [];
    
    $stmtQuestions = $pdo->query("SELECT * FROM quiz_questions ORDER BY display_order");
    foreach ($stmtQuestions->fetchAll() as $q) {
        $quizQuestions[$q['id']] = $q['question'];
    }
    
    $stmtOptions = $pdo->query("SELECT * FROM quiz_options");
    foreach ($stmtOptions->fetchAll() as $o) {
        $quizOptions[$o['id']] = $o['label'];
    }
    
    // Buscar planos para mapear
    $plans = [];
    $stmtPlans = $pdo->query("SELECT * FROM plans");
    foreach ($stmtPlans->fetchAll() as $p) {
        $plans[$p['id']] = $p['name'];
    }
    
} catch (Exception $e) {
    $leads = [];
    $quizQuestions = [];
    $quizOptions = [];
    $plans = [];
}

$statusLabels = [
    'new' => ['label' => 'Novo', 'class' => 'bg-green-100 text-green-700'],
    'contacted' => ['label' => 'Contatado', 'class' => 'bg-blue-100 text-blue-700'],
    'closed' => ['label' => 'Fechado', 'class' => 'bg-gray-100 text-gray-700'],
    'converted' => ['label' => 'Convertido', 'class' => 'bg-purple-100 text-purple-700'],
];

ob_start();
?>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <?php 
    $counts = ['new' => 0, 'contacted' => 0, 'closed' => 0, 'converted' => 0];
    foreach ($leads as $lead) {
        if (isset($counts[$lead['status']])) {
            $counts[$lead['status']]++;
        }
    }
    ?>
    <?php foreach ($statusLabels as $status => $info): ?>
        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-sm text-gray-500"><?= $info['label'] ?></p>
            <p class="text-2xl font-bold text-gray-800"><?= $counts[$status] ?></p>
        </div>
    <?php endforeach; ?>
</div>

<!-- Leads Table -->
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contato</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plano</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (empty($leads)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Nenhum lead cadastrado ainda
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($leads as $lead): ?>
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="viewLead(<?= $lead['id'] ?>)">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-memora-wine/10 flex items-center justify-center text-memora-wine font-medium">
                                        <?= $lead['name'] ? strtoupper(substr($lead['name'], 0, 1)) : '?' ?>
                                    </div>
                                    <span class="font-medium text-gray-800"><?= htmlspecialchars($lead['name'] ?: 'Sem nome') ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">
                                    <?php if ($lead['email']): ?>
                                        <div><?= htmlspecialchars($lead['email']) ?></div>
                                    <?php endif; ?>
                                    <?php if ($lead['phone']): ?>
                                        <div class="text-gray-400"><?= htmlspecialchars($lead['phone']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                $planId = $lead['plan_selected'] ?? '';
                                $planName = $plans[$planId] ?? $planId;
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-memora-wine/10 text-memora-wine">
                                    <?= htmlspecialchars($planName ?: '-') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4" onclick="event.stopPropagation()">
                                <select onchange="updateStatus(<?= $lead['id'] ?>, this.value)" 
                                        class="text-xs font-medium px-2 py-1 rounded-full border-0 cursor-pointer <?= $statusLabels[$lead['status']]['class'] ?? 'bg-gray-100 text-gray-700' ?>">
                                    <?php foreach ($statusLabels as $status => $info): ?>
                                        <option value="<?= $status ?>" <?= ($lead['status'] ?? '') === $status ? 'selected' : '' ?>>
                                            <?= $info['label'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?= date('d/m/Y H:i', strtotime($lead['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                                <div class="flex justify-end gap-2">
                                    <button onclick="viewLead(<?= $lead['id'] ?>)" 
                                            title="Ver detalhes"
                                            class="p-2 text-gray-500 hover:text-memora-wine hover:bg-gray-100 rounded transition-colors">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>
                                    <a href="https://wa.me/55<?= preg_replace('/\D/', '', $lead['phone'] ?? '') ?>" 
                                       target="_blank"
                                       title="WhatsApp"
                                       class="p-2 text-gray-500 hover:text-green-600 hover:bg-gray-100 rounded transition-colors">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                    </a>
                                    <button onclick="deleteLead(<?= $lead['id'] ?>)" 
                                            title="Excluir"
                                            class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded transition-colors">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Lead Detail Modal -->
<div id="lead-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeLeadModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="sticky top-0 bg-white p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-800">Detalhes do Lead</h3>
                <button onclick="closeLeadModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
            <div id="lead-detail" class="p-6">
                <!-- Content loaded via JS -->
            </div>
        </div>
    </div>
</div>

<script>
const leadsData = <?= json_encode($leads) ?>;
const quizQuestions = <?= json_encode($quizQuestions) ?>;
const quizOptions = <?= json_encode($quizOptions) ?>;
const plansData = <?= json_encode($plans) ?>;

async function updateStatus(id, status) {
    try {
        await api.put('/leads/' + id + '/status', { status });
        showToast('Status atualizado!');
    } catch (error) {
        showToast(error.message, 'error');
        location.reload();
    }
}

function viewLead(id) {
    const lead = leadsData.find(l => l.id == id);
    if (!lead) return;
    
    // Parse quiz results - handle different formats
    let quizResultsHtml = '<p class="text-gray-500 italic">Sem respostas do quiz</p>';
    
    try {
        let results = lead.quiz_results;
        
        // Try to parse if it's a string
        if (typeof results === 'string') {
            // Handle double-encoded JSON
            try {
                results = JSON.parse(results);
                if (typeof results === 'string') {
                    results = JSON.parse(results);
                }
            } catch (e) {
                // If can't parse, show raw text
                quizResultsHtml = `<p class="text-gray-600 whitespace-pre-wrap">${lead.quiz_results}</p>`;
                results = null;
            }
        }
        
        if (results && typeof results === 'object' && Object.keys(results).length > 0) {
            const questionIds = Object.keys(quizQuestions);
            
            // Check if it's an array or object with answers
            const entries = Array.isArray(results) ? results.map((v, i) => [i, v]) : Object.entries(results);
            
            quizResultsHtml = `
                <div class="space-y-3">
                    ${entries.map(([stepIndex, answer], idx) => {
                        // Get question text
                        const questionId = questionIds[parseInt(stepIndex)];
                        const questionText = quizQuestions[questionId] || `Pergunta ${parseInt(stepIndex) + 1}`;
                        
                        // Get answer text - handle different formats
                        let answerText = '';
                        if (typeof answer === 'object' && answer !== null) {
                            // New format: {id: "opt1", score: 2}
                            const answerId = answer.id || answer.optionId || '';
                            answerText = quizOptions[answerId] || answerId || JSON.stringify(answer);
                        } else {
                            // Old format: just the option ID or text
                            answerText = quizOptions[answer] || answer || 'N/A';
                        }
                        
                        return `
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-memora-wine text-white flex items-center justify-center text-sm font-bold flex-shrink-0">
                                        ${parseInt(stepIndex) + 1}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-600 mb-1">${questionText}</p>
                                        <p class="font-medium text-memora-wine">${answerText}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            `;
        }
    } catch (e) {
        console.error('Error parsing quiz results:', e);
        if (lead.quiz_results) {
            quizResultsHtml = `<p class="text-gray-600 whitespace-pre-wrap">${lead.quiz_results}</p>`;
        }
    }
    
    // Get plan name
    const planName = plansData[lead.plan_selected] || lead.plan_selected || 'Não selecionado';
    
    document.getElementById('lead-detail').innerHTML = `
        <!-- Lead Info Header -->
        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-100">
            <div class="w-16 h-16 rounded-full bg-memora-wine/10 flex items-center justify-center text-memora-wine text-2xl font-bold">
                ${(lead.name || '?').charAt(0).toUpperCase()}
            </div>
            <div class="flex-1">
                <h4 class="text-xl font-semibold text-gray-800">${lead.name || 'Sem nome'}</h4>
                <p class="text-gray-500">Lead #${lead.id}</p>
            </div>
            <a href="https://wa.me/55${(lead.phone || '').replace(/\D/g, '')}" 
               target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                WhatsApp
            </a>
        </div>
        
        <!-- Contact Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gray-50 rounded-lg p-4">
                <label class="text-xs text-gray-500 uppercase tracking-wider block mb-1">Email</label>
                <p class="text-gray-800 font-medium">${lead.email || 'N/A'}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <label class="text-xs text-gray-500 uppercase tracking-wider block mb-1">Telefone</label>
                <p class="text-gray-800 font-medium">${lead.phone || 'N/A'}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <label class="text-xs text-gray-500 uppercase tracking-wider block mb-1">Plano Recomendado</label>
                <p class="text-memora-wine font-semibold text-lg">${planName}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <label class="text-xs text-gray-500 uppercase tracking-wider block mb-1">Data de Cadastro</label>
                <p class="text-gray-800 font-medium">${new Date(lead.created_at).toLocaleString('pt-BR')}</p>
            </div>
        </div>
        
        <!-- Quiz Responses -->
        <div class="mb-6">
            <h5 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-memora-wine" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 17h.01"/>
                </svg>
                Respostas do Quiz
            </h5>
            ${quizResultsHtml}
        </div>
        
        <!-- Source -->
        ${lead.source ? `
            <div class="text-xs text-gray-400 pt-4 border-t border-gray-100">
                Origem: ${lead.source}
            </div>
        ` : ''}
    `;
    
    document.getElementById('lead-modal').classList.remove('hidden');
}

function closeLeadModal() {
    document.getElementById('lead-modal').classList.add('hidden');
}

async function deleteLead(id) {
    if (!confirm('Tem certeza que deseja excluir este lead?')) return;
    
    try {
        await api.delete('/leads/' + id);
        showToast('Lead excluído!');
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
