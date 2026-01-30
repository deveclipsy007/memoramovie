<?php
/**
 * Admin Quiz - Memora Movie
 * Gerenciamento completo de perguntas e opções do quiz
 */

$pageTitle = 'Quiz - Admin MEMORA MOVIE';
$pageHeading = 'Gerenciar Quiz';

try {
    require_once __DIR__ . '/../../api/config.sqlite.php';
    
    // Buscar perguntas com suas opções
    $stmt = $pdo->query("SELECT * FROM quiz_questions ORDER BY display_order ASC, id ASC");
    $questions = $stmt->fetchAll();

    foreach ($questions as &$question) {
        $optStmt = $pdo->prepare("SELECT * FROM quiz_options WHERE question_id = ? ORDER BY id");
        $optStmt->execute([$question['id']]);
        $question['options'] = $optStmt->fetchAll();
    }
} catch (Exception $e) {
    $questions = [];
}

ob_start();
?>

<div class="mb-6 flex justify-between items-center">
    <p class="text-gray-600">O quiz é usado para recomendar o plano ideal para cada cliente.</p>
    <button onclick="openQuestionModal()" class="px-4 py-2 bg-memora-wine text-white rounded-lg flex items-center gap-2 hover:bg-memora-wineLight transition-colors shadow-sm">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        Nova Pergunta
    </button>
</div>

<div class="space-y-6">
    <?php foreach ($questions as $idx => $q): ?>
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-memora-wine text-white flex items-center justify-center text-sm font-bold">
                        <?= $q['display_order'] ?>
                    </span>
                    <h3 class="font-semibold text-gray-800 text-lg"><?= htmlspecialchars($q['question']) ?></h3>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick='editQuestion(<?= json_encode($q) ?>)' class="p-2 text-gray-500 hover:text-memora-wine hover:bg-white rounded-lg transition-colors border border-transparent hover:border-gray-200">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                    </button>
                    <button onclick="deleteQuestion(<?= $q['id'] ?>)" class="p-2 text-gray-500 hover:text-red-600 hover:bg-white rounded-lg transition-colors border border-transparent hover:border-gray-200">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                    </button>
                </div>
            </div>
            
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($q['options'] as $opt): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100 group">
                            <div class="flex-1 min-w-0 pr-4">
                                <p class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($opt['label']) ?></p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] uppercase tracking-wider text-gray-400">Peso:</span>
                                    <span class="text-xs font-semibold text-memora-wine"><?= $opt['score_weight'] ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($q['options'])): ?>
                        <p class="text-gray-400 text-sm italic col-span-full">Nenhuma opção cadastrada para esta pergunta.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
    <?php if (empty($questions)): ?>
        <div class="bg-white border border-dashed border-gray-300 rounded-xl p-12 text-center text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            <p class="text-lg font-medium">Nenhuma pergunta encontrada</p>
            <p class="mb-6">Comece criando a primeira pergunta do seu quiz interativo.</p>
            <button onclick="openQuestionModal()" class="px-6 py-3 bg-memora-wine text-white rounded-lg hover:bg-memora-wineLight transition-colors shadow-lg shadow-memora-wine/20 uppercase tracking-widest text-xs font-bold">
                Criar Primeira Pergunta
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- Question Modal -->
<div id="question-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeQuestionModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
                <h3 id="modal-title" class="text-xl font-bold text-gray-800">Nova Pergunta</h3>
                <button onclick="closeQuestionModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            
            <form id="question-form" onsubmit="saveQuestion(event)" class="overflow-y-auto p-6 space-y-6 flex-1">
                <input type="hidden" id="question-id">
                
                <div class="grid grid-cols-4 gap-6">
                    <div class="col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Texto da Pergunta</label>
                        <input type="text" id="question-text" required placeholder="Ex: Qual o objetivo principal do filme?" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-memora-wine/20 focus:border-memora-wine transition-all">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ordem</label>
                        <input type="number" id="question-order" required class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-memora-wine/20 focus:border-memora-wine transition-all text-center">
                    </div>
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-semibold text-gray-700">Opções de Resposta</label>
                        <button type="button" onclick="addOptionRow()" class="text-xs font-bold uppercase tracking-wider text-memora-wine hover:text-memora-wineLight flex items-center gap-1 transition-colors">
                            <span class="text-lg leading-none">+</span> Adicionar Opção
                        </button>
                    </div>
                    
                    <div id="options-container" class="space-y-3">
                        <!-- Options will be added here -->
                    </div>
                </div>
            </form>
            
            <div class="p-6 bg-gray-50 border-t border-gray-100 flex gap-3">
                <button type="button" onclick="closeQuestionModal()" class="flex-1 py-3 border border-gray-200 text-gray-600 font-semibold rounded-lg hover:bg-white transition-colors">
                    Cancelar
                </button>
                <button type="submit" form="question-form" class="flex-1 py-3 bg-memora-wine text-white font-semibold rounded-lg hover:bg-memora-wineLight shadow-lg shadow-memora-wine/20 transition-all active:scale-[0.98]">
                    Salvar Pergunta
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let editingQuestionId = null;

function openQuestionModal() {
    editingQuestionId = null;
    document.getElementById('modal-title').textContent = 'Nova Pergunta';
    document.getElementById('question-id').value = '';
    document.getElementById('question-text').value = '';
    document.getElementById('question-order').value = document.querySelectorAll('.bg-white.border.border-gray-200').length + 1;
    document.getElementById('options-container').innerHTML = '';
    
    // Iniciar com 3 opções vazias por padrão
    addOptionRow();
    addOptionRow();
    addOptionRow();
    
    document.getElementById('question-modal').classList.remove('hidden');
}

function closeQuestionModal() {
    document.getElementById('question-modal').classList.add('hidden');
}

function addOptionRow(label = '', weight = 1, id = '') {
    const container = document.getElementById('options-container');
    const row = document.createElement('div');
    row.className = 'flex items-center gap-3 animate-in fade-in slide-in-from-left-2 duration-300';
    row.innerHTML = `
        <input type="hidden" class="option-id" value="${id}">
        <div class="flex-1">
            <input type="text" class="option-label w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-memora-wine focus:border-memora-wine" placeholder="Texto da opção" required value="${label}">
        </div>
        <div class="w-24">
            <input type="number" class="option-weight w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-memora-wine focus:border-memora-wine text-center" placeholder="Peso" required value="${weight}">
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg>
        </button>
    `;
    container.appendChild(row);
}

function editQuestion(q) {
    editingQuestionId = q.id;
    document.getElementById('modal-title').textContent = 'Editar Pergunta';
    document.getElementById('question-id').value = q.id;
    document.getElementById('question-text').value = q.question;
    document.getElementById('question-order').value = q.display_order;
    
    const container = document.getElementById('options-container');
    container.innerHTML = '';
    q.options.forEach(opt => {
        addOptionRow(opt.label, opt.score_weight, opt.id);
    });
    
    if (q.options.length === 0) addOptionRow();
    
    document.getElementById('question-modal').classList.remove('hidden');
}

async function saveQuestion(e) {
    e.preventDefault();
    
    const options = Array.from(document.querySelectorAll('#options-container > div')).map(row => ({
        id: row.querySelector('.option-id').value,
        label: row.querySelector('.option-label').value,
        score_weight: parseInt(row.querySelector('.option-weight').value) || 1
    }));
    
    const data = {
        question: document.getElementById('question-text').value,
        display_order: parseInt(document.getElementById('question-order').value),
        options: options
    };
    
    try {
        if (editingQuestionId) {
            // No backend, o update de pergunta não lida com opções de forma automática
            // Vamos simplificar deletando e criando para manter as opções sincronizadas
            // Ou o correto seria ter uma API mais robusta. Para o MVP:
            await api.put('/quiz/' + editingQuestionId, data);
            
            // Sincronizar opções (deletar todas e criar novas é o mais simples)
            // Primeiro buscar pergunta atual para pegar IDs das opções antigas
            const currentQ = await api.get('/quiz/' + editingQuestionId);
            for (const opt of currentQ.data.options) {
                await api.delete('/quiz/options/' + opt.id);
            }
            // Adicionar novas
            for (const opt of options) {
                await api.post(`/quiz/${editingQuestionId}/options`, opt);
            }
        } else {
            await api.post('/quiz', data);
        }
        
        showToast('Pergunta salva com sucesso!');
        setTimeout(() => location.reload(), 500);
    } catch (error) {
        showToast('Erro ao salvar pergunta: ' + error.message, 'error');
    }
}

async function deleteQuestion(id) {
    if (!confirm('Tem certeza que deseja excluir esta pergunta e todas as suas opções?')) return;
    
    try {
        await api.delete('/quiz/' + id);
        showToast('Pergunta excluída!');
        setTimeout(() => location.reload(), 500);
    } catch (error) {
        showToast(error.message, 'error');
    }
}
</script>

<?php $adminContent = ob_get_clean(); include __DIR__ . '/layout.php'; ?>
