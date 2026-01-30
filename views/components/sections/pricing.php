<?php
/**
 * Pricing Section - Memora Movie
 * Quiz interativo com captura de lead e resultado impactante
 */

// Buscar planos do banco
try {
    require_once __DIR__ . '/../../../api/db.php';
    $stmt = $pdo->query("SELECT * FROM plans ORDER BY id");
    $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Buscar perguntas do quiz
    $stmtQuiz = $pdo->query("SELECT * FROM quiz_questions ORDER BY display_order");
    $quizData = $stmtQuiz->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatar para JSON
    $quizQuestions = [];
    foreach ($quizData as $q) {
        // Buscar opções para esta pergunta
        $stmtOptions = $pdo->prepare("SELECT id, label, score_weight FROM quiz_options WHERE question_id = ?");
        $stmtOptions->execute([$q['id']]);
        $optionsData = $stmtOptions->fetchAll(PDO::FETCH_ASSOC);
        
        $options = [];
        foreach ($optionsData as $opt) {
            $options[] = [
                'id' => $opt['id'],
                'label' => $opt['label'],
                'scoreWeight' => (int)$opt['score_weight']
            ];
        }

        $quizQuestions[] = [
            'id' => $q['id'],
            'question' => $q['question'],
            'options' => $options
        ];
    }
} catch (Exception $e) {
    // Fallback data
    $plans = [
        ['id' => 'A', 'name' => 'Memora Capsule', 'price' => 448.70, 'duration' => '90s a 120s', 'description' => 'Puro impacto. Um trailer vibrante que captura a essência do momento.', 'delivery_time' => '24 a 48 horas'],
        ['id' => 'B', 'name' => 'Memora Feature', 'price' => 754.80, 'duration' => '3 a 5 minutos', 'description' => 'Cinema narrativo. Uma história completa com começo, meio e fim emocionante.', 'delivery_time' => 'Até 48 horas'],
        ['id' => 'C', 'name' => 'Memora Legacy', 'price' => 1467.80, 'duration' => '8 a 15 minutos', 'description' => 'A obra-prima. Um documentário profundo que eterniza gerações.', 'delivery_time' => '3 a 5 dias úteis'],
    ];
    $quizQuestions = [];
}

// Plan images (alta qualidade)
$planImages = [
    'A' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?q=80&w=1200&auto=format&fit=crop',
    'B' => 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?q=80&w=1200&auto=format&fit=crop',
    'C' => 'https://images.unsplash.com/photo-1478720568477-152d9b164e63?q=80&w=1200&auto=format&fit=crop',
];

// Plan icons/badges
$planBadges = [
    'A' => ['icon' => '⚡', 'label' => 'Mais Rápido'],
    'B' => ['icon' => '⭐', 'label' => 'Mais Popular'],
    'C' => ['icon' => '👑', 'label' => 'Premium'],
];
?>

<section class="py-24 md:py-32 px-6 bg-memora-cream" id="precos">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <div class="inline-block mb-4 px-4 py-2 border border-memora-wine/20 rounded-full">
                <span class="text-[10px] uppercase tracking-[0.3em] text-memora-wine/60 font-medium">
                    Planos & Preços
                </span>
            </div>
            <h2 class="font-serif text-4xl md:text-5xl text-memora-wine mb-4">
                Descubra seu Plano Ideal
            </h2>
            <p class="text-memora-black/60 max-w-xl mx-auto">
                Responda algumas perguntas e encontraremos o plano perfeito para sua história.
            </p>
        </div>
        
        <!-- Quiz Container -->
        <div id="quiz-container" class="max-w-2xl mx-auto">
            
            <!-- Quiz Start -->
            <div id="quiz-start" class="text-center bg-white p-12 rounded-lg border border-memora-wine/10 shadow-lg">
                <img
                    src="https://imagedelivery.net/mYdfeAeRRdkIXG5w7XJhtQ/1ec259df-61e5-45d5-5e90-dc37c4d25000/public"
                    alt="Ícone do quiz Memora"
                    class="w-28 h-28 mx-auto mb-8 rounded-full object-cover shadow-xl shadow-memora-wine/30"
                    loading="lazy"
                >
                <h3 class="font-serif text-3xl text-memora-wine mb-4">Descubra seu Filme Perfeito</h3>
                <p class="text-memora-black/60 mb-8 text-lg">
                    Em apenas <span class="font-semibold text-memora-wine">5 perguntas</span>, vamos encontrar o plano ideal para eternizar sua história.
                </p>
                <button onclick="startQuiz()" 
                        class="inline-flex items-center justify-center gap-3 px-12 py-5 text-base font-medium tracking-widest uppercase transition-all duration-300 bg-memora-wine text-white hover:bg-memora-wineLight shadow-xl shadow-memora-wine/30 hover:shadow-2xl hover:shadow-memora-wine/40 hover:-translate-y-1 rounded-full">
                    <span>Começar Quiz</span>
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </button>
                <p class="text-xs text-memora-black/40 mt-6">⏱ Menos de 1 minuto</p>
            </div>
            
            <!-- Quiz Questions (hidden initially) -->
            <div id="quiz-questions" class="hidden">
                <!-- Questions will be rendered by JS -->
            </div>
            
            <!-- Lead Form (hidden initially) -->
            <div id="quiz-lead-form" class="hidden">
                <!-- Lead form will be rendered by JS -->
            </div>
            
            <!-- Quiz Result (hidden initially) -->
            <div id="quiz-result" class="hidden">
                <!-- Result will be rendered by JS -->
            </div>
        </div>
        

    </div>
</section>

<style>
/* Animações para o quiz */
@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes scaleIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

.quiz-animate-in {
    animation: slideUp 0.5s ease-out forwards;
}

.result-animate-in {
    animation: scaleIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}

.pulse-highlight {
    animation: pulse 2s ease-in-out infinite;
}

.shimmer-bg {
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
}

/* Confetti effect placeholder */
.confetti-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
}
</style>

<script>
// Quiz data from PHP
const quizQuestions = <?= json_encode($quizQuestions) ?>;
const plansData = <?= json_encode($plans) ?>;
const planImages = <?= json_encode($planImages) ?>;
const planBadges = <?= json_encode($planBadges) ?>;

let currentStep = 0;
let answers = {};
let calculatedPlan = null;
let leadData = { name: '', email: '', phone: '' };

function startQuiz() {
    document.getElementById('quiz-start').classList.add('hidden');
    document.getElementById('quiz-questions').classList.remove('hidden');
    renderQuestion();
}

function renderQuestion() {
    const container = document.getElementById('quiz-questions');
    const question = quizQuestions[currentStep];
    
    if (!question) {
        // Quiz finished, show lead form
        showLeadForm();
        return;
    }
    
    const progress = ((currentStep + 1) / quizQuestions.length) * 100;
    
    container.innerHTML = `
        <div class="bg-white p-8 md:p-10 rounded-lg border border-memora-wine/10 shadow-lg quiz-animate-in">
            <!-- Progress bar -->
            <div class="mb-10">
                <div class="flex justify-between text-sm text-memora-black/50 mb-3">
                    <span class="font-medium">Pergunta ${currentStep + 1} de ${quizQuestions.length}</span>
                    <span class="text-memora-wine font-semibold">${Math.round(progress)}%</span>
                </div>
                <div class="h-2 bg-memora-wine/10 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-memora-wine to-memora-wineLight transition-all duration-500 rounded-full" style="width: ${progress}%"></div>
                </div>
            </div>
            
            <!-- Question -->
            <h3 class="font-serif text-2xl md:text-3xl text-memora-wine mb-8 leading-tight">${question.question}</h3>
            
            <!-- Options -->
            <div class="space-y-4">
                ${question.options.map((opt, idx) => `
                    <button onclick="selectOption('${opt.id}', ${opt.scoreWeight})" 
                            class="w-full p-5 text-left border-2 border-memora-wine/10 rounded-lg hover:border-memora-wine hover:bg-memora-wine/5 transition-all duration-300 group flex items-center gap-4"
                            style="animation-delay: ${idx * 100}ms">
                        <span class="w-8 h-8 rounded-full border-2 border-memora-wine/30 group-hover:border-memora-wine group-hover:bg-memora-wine flex items-center justify-center text-sm font-medium text-memora-wine/50 group-hover:text-white transition-all duration-300">
                            ${String.fromCharCode(65 + idx)}
                        </span>
                        <span class="text-memora-black group-hover:text-memora-wine transition-colors text-lg">${opt.label}</span>
                    </button>
                `).join('')}
            </div>
        </div>
    `;
}

function selectOption(optionId, scoreWeight) {
    answers[currentStep] = { id: optionId, score: scoreWeight };
    currentStep++;
    renderQuestion();
}

function showLeadForm() {
    // Calculate plan first
    let totalScore = 0;
    Object.values(answers).forEach(a => totalScore += a.score);
    const avgScore = totalScore / Object.keys(answers).length;
    
    let recommendedPlanId = 'A';
    if (avgScore > 2.2) {
        recommendedPlanId = 'C';
    } else if (avgScore > 1.5) {
        recommendedPlanId = 'B';
    }
    
    calculatedPlan = plansData.find(p => p.id === recommendedPlanId) || plansData[0];
    
    // Hide questions, show lead form
    document.getElementById('quiz-questions').classList.add('hidden');
    const formContainer = document.getElementById('quiz-lead-form');
    formContainer.classList.remove('hidden');
    
    formContainer.innerHTML = `
        <div class="bg-white p-8 md:p-10 rounded-lg border border-memora-wine/10 shadow-lg quiz-animate-in">
            <!-- Icon -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/30">
                    <svg class="w-10 h-10 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <h3 class="font-serif text-2xl md:text-3xl text-memora-wine mb-3">Parabéns! 🎉</h3>
                <p class="text-memora-black/60 text-lg">
                    Encontramos o plano perfeito para você!<br>
                    <span class="text-memora-wine font-medium">Preencha seus dados para revelar:</span>
                </p>
            </div>
            
            <!-- Form -->
            <form id="lead-capture-form" onsubmit="submitLeadAndShowResult(event)" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-memora-black/70 mb-2">Seu nome</label>
                    <input type="text" 
                           id="lead-name" 
                           name="name" 
                           required
                           placeholder="Como podemos te chamar?"
                           class="w-full px-5 py-4 border-2 border-memora-wine/10 rounded-lg focus:border-memora-wine focus:ring-0 transition-colors text-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-memora-black/70 mb-2">Seu melhor e-mail</label>
                    <input type="email" 
                           id="lead-email" 
                           name="email" 
                           required
                           placeholder="email@exemplo.com"
                           class="w-full px-5 py-4 border-2 border-memora-wine/10 rounded-lg focus:border-memora-wine focus:ring-0 transition-colors text-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-memora-black/70 mb-2">WhatsApp</label>
                    <input type="tel" 
                           id="lead-phone" 
                           name="phone" 
                           required
                           placeholder="(11) 99999-9999"
                           class="w-full px-5 py-4 border-2 border-memora-wine/10 rounded-lg focus:border-memora-wine focus:ring-0 transition-colors text-lg">
                </div>
                
                <button type="submit" 
                        class="w-full py-5 text-lg font-medium tracking-wider uppercase transition-all duration-300 bg-gradient-to-r from-memora-wine to-memora-wineLight text-white hover:shadow-xl hover:shadow-memora-wine/30 rounded-lg flex items-center justify-center gap-3 mt-8">
                    <span>Revelar Meu Plano</span>
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </button>
                
                <p class="text-xs text-center text-memora-black/40 mt-4">
                    🔒 Seus dados estão seguros. Não enviamos spam.
                </p>
            </form>
        </div>
    `;
}

async function submitLeadAndShowResult(e) {
    e.preventDefault();
    
    // Collect lead data
    leadData.name = document.getElementById('lead-name').value;
    leadData.email = document.getElementById('lead-email').value;
    leadData.phone = document.getElementById('lead-phone').value;
    
    // Submit to API
    try {
        const response = await fetch('/api/leads', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                name: leadData.name,
                email: leadData.email,
                phone: leadData.phone,
                plan_selected: calculatedPlan.id,
                quiz_results: JSON.stringify(answers),
                source: 'quiz'
            })
        });
        
        const result = await response.json();
        console.log('Lead saved:', result);
    } catch (error) {
        console.error('Error saving lead:', error);
    }
    
    // Show result regardless of API success
    showImpactfulResult();
}

function showImpactfulResult() {
    // Hide form
    document.getElementById('quiz-lead-form').classList.add('hidden');
    
    const plan = calculatedPlan;
    const planImage = planImages[plan.id] || planImages['A'];
    const badge = planBadges[plan.id] || { icon: '✨', label: 'Recomendado' };
    
    const resultContainer = document.getElementById('quiz-result');
    resultContainer.classList.remove('hidden');
    
    resultContainer.innerHTML = `
        <div class="result-animate-in">
            <!-- Celebration Header -->
            <div class="text-center mb-8">
                <div class="inline-block px-6 py-2 bg-gradient-to-r from-amber-400 to-orange-500 text-white rounded-full text-sm font-medium mb-4 shadow-lg">
                    ${badge.icon} ${badge.label} para você, ${leadData.name.split(' ')[0]}!
                </div>
                <h3 class="font-serif text-3xl md:text-4xl text-memora-wine mb-2">Seu Plano Ideal</h3>
            </div>
            
            <!-- Plan Card - Impactful -->
            <div class="relative bg-white rounded-2xl overflow-hidden shadow-2xl shadow-memora-wine/20 border border-memora-wine/10">
                
                <!-- Hero Image -->
                <div class="relative h-64 md:h-80 overflow-hidden">
                    <img src="${planImage}" 
                         alt="${plan.name}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    
                    <!-- Shimmer effect -->
                    <div class="absolute inset-0 shimmer-bg"></div>
                    
                    <!-- Plan name overlay -->
                    <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-3xl">${badge.icon}</span>
                            <span class="text-sm uppercase tracking-widest opacity-80">${plan.duration}</span>
                        </div>
                        <h4 class="font-serif text-4xl md:text-5xl mb-2">${plan.name}</h4>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-8 md:p-10">
                    <p class="text-memora-black/70 text-lg mb-6 leading-relaxed">${plan.description}</p>
                    
                    <!-- Price highlight -->
                    <div class="bg-gradient-to-r from-memora-wine/5 to-memora-wineLight/5 rounded-xl p-6 mb-8 text-center pulse-highlight">
                        <p class="text-sm text-memora-wine/70 mb-2">Investimento especial</p>
                        <div class="flex items-baseline justify-center gap-2">
                            <span class="text-2xl text-memora-wine/70">R$</span>
                            <span class="text-5xl md:text-6xl font-serif text-memora-wine font-bold">
                                ${parseFloat(plan.price).toLocaleString('pt-BR', {minimumFractionDigits: 2})}
                            </span>
                        </div>
                        <p class="text-sm text-memora-black/50 mt-3 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            Entrega em ${plan.delivery_time}
                        </p>
                    </div>
                    
                    <!-- Benefits -->
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="flex items-center gap-3 text-sm text-memora-black/70">
                            <svg class="w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Color grading cinema</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-memora-black/70">
                            <svg class="w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Trilha sonora premium</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-memora-black/70">
                            <svg class="w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>1 revisão incluída</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-memora-black/70">
                            <svg class="w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Sala de cinema digital</span>
                        </div>
                    </div>
                    
                    <!-- CTAs -->
                    <div class="space-y-4">
                        <a href="/criar?plan=${plan.id}&name=${encodeURIComponent(leadData.name)}&email=${encodeURIComponent(leadData.email)}" 
                           class="block w-full py-5 text-center text-lg font-medium tracking-wider uppercase transition-all duration-300 bg-gradient-to-r from-memora-wine to-memora-wineLight text-white hover:shadow-xl hover:shadow-memora-wine/30 rounded-lg">
                            Quero Esse Plano! 🎬
                        </a>
                        <button onclick="resetQuiz()" 
                                class="block w-full py-4 text-center text-sm font-medium tracking-widest uppercase transition-all duration-300 border-2 border-memora-wine/20 text-memora-wine hover:bg-memora-wine hover:text-white rounded-lg">
                            Refazer Quiz
                        </button>
                    </div>
                    
                    <!-- Trust badges -->
                    <div class="flex items-center justify-center gap-6 mt-8 pt-6 border-t border-memora-wine/10">
                        <div class="flex items-center gap-2 text-xs text-memora-black/50">
                            <svg class="w-4 h-4 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Pagamento seguro
                        </div>
                        <div class="flex items-center gap-2 text-xs text-memora-black/50">
                            <svg class="w-4 h-4 text-memora-wine" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                            Garantia Love Back
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Scroll to result
    resultContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function resetQuiz() {
    currentStep = 0;
    answers = {};
    calculatedPlan = null;
    leadData = { name: '', email: '', phone: '' };
    
    document.getElementById('quiz-result').classList.add('hidden');
    document.getElementById('quiz-lead-form').classList.add('hidden');
    document.getElementById('quiz-questions').classList.add('hidden');
    document.getElementById('quiz-start').classList.remove('hidden');
    
    // Scroll to quiz
    document.getElementById('quiz-container').scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>
