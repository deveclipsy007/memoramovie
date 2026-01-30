<?php
/**
 * Process Timeline Section - Memora Movie
 * Nova versão, mais detalhada e visual.
 */

$processSteps = [
    [
        'number' => '01',
        'title' => 'Imersão e Descoberta',
        'description' => 'O primeiro passo é entender a alma da sua história. Você nos envia suas memórias brutas – fotos, vídeos, áudios, textos – de qualquer fonte. Em paralelo, você preenche nosso Questionário Emocional, um briefing criativo que nos ajuda a capturar a essência, o tom e o objetivo do seu filme.',
        'input' => 'Fotos, vídeos, áudios e o Questionário Emocional preenchido.',
        'output' => 'Um profundo entendimento do seu universo e da narrativa desejada.',
        'image' => 'https://imagedelivery.net/mYdfeAeRRdkIXG5w7XJhtQ/b568b4c8-a776-493b-f734-85a1cc68ec00/public',
    ],
    [
        'number' => '02',
        'title' => 'Roteiro e Curadoria',
        'description' => 'Nossa equipe de roteiristas e curadores assiste e analisa todo o material. Eles identificam os momentos-chave, os sorrisos, os olhares e as falas que formarão a espinha dorsal do filme. Um roteiro é estruturado em 3 atos (começo, meio e fim) para criar uma jornada emocional coesa e impactante.',
        'input' => 'Todo o material bruto e as respostas do questionário.',
        'output' => 'Um roteiro cinematográfico e uma seleção criteriosa dos melhores momentos.',
        'image' => 'https://imagedelivery.net/mYdfeAeRRdkIXG5w7XJhtQ/d91f8917-ab52-4107-ae42-362887feda00/public',
    ],
    [
        'number' => '03',
        'title' => 'Direção de Arte e Edição',
        'description' => 'É aqui que a magia acontece. Nossos diretores de arte transformam o material bruto em cinema. Isso inclui a montagem (edição), a escolha e sincronização da trilha sonora, o tratamento de cor (Color Grading) para dar um look de filme, e o design de som para uma experiência imersiva.',
        'input' => 'O roteiro e a curadoria de momentos.',
        'output' => 'A primeira versão do seu filme, já com uma cara de cinema.',
        'image' => 'https://imagedelivery.net/mYdfeAeRRdkIXG5w7XJhtQ/8b500673-315e-4d3e-c112-c21e050db900/public',
    ],
    [
        'number' => '04',
        'title' => 'Finalização e Entrega',
        'description' => 'O filme passa por um rigoroso controle de qualidade. Ajustes finos são feitos no áudio e no vídeo. Finalmente, seu filme é renderizado em 4K e entregue em uma Sala de Cinema Digital exclusiva, pronta para você assistir, baixar e compartilhar com quem ama. Tudo isso em até 48 horas.',
        'input' => 'A versão editada do filme.',
        'output' => 'O filme finalizado em 4K, uma página de exibição e a emoção de reviver suas memórias.',
        'image' => 'https://imagedelivery.net/mYdfeAeRRdkIXG5w7XJhtQ/af15183d-d78c-4a47-981c-623c05d85e00/public',
    ],
];
?>

<section class="bg-white py-24 md:py-32 px-4 sm:px-6" id="como-funciona">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12 md:mb-24">
            <div class="inline-block mb-4 px-4 py-2 border border-memora-wine/20 rounded-full">
                <span class="text-[10px] uppercase tracking-[0.3em] text-memora-wine/60 font-medium">
                    Nosso Processo
                </span>
            </div>
            <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-memora-wine mb-4">
                Como Criamos Magia
            </h2>
            <p class="text-memora-black/60 max-w-2xl mx-auto text-lg">
                Transformar memórias em filmes é uma arte. Aqui, revelamos cada passo do nosso processo meticuloso, que combina sua história pessoal com nossa expertise cinematográfica.
            </p>
        </div>

        <!-- Vertical Timeline -->
        <div class="max-w-4xl mx-auto">
            <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-24">
                <!-- A linha vertical que conecta os pontos -->
                <div class="hidden lg:block absolute top-0 left-1/2 w-px h-full bg-memora-wine/10 -translate-x-1/2"></div>

                <?php foreach ($processSteps as $idx => $step): ?>
                    <!-- Lado do Texto -->
                    <div class="flex flex-col justify-center <?= $idx % 2 === 0 ? 'lg:order-1' : 'lg:order-2' ?>">
                        <div class="bg-memora-cream/40 border border-memora-wine/10 rounded-lg p-6 shadow-sm hover:shadow-xl hover:border-memora-wine/20 transition-all duration-300">
                            <span class="font-serif text-6xl text-memora-wine/10 block mb-4"><?= $step['number'] ?></span>
                            <h3 class="font-serif text-2xl text-memora-wine mb-4"><?= htmlspecialchars($step['title']) ?></h3>
                            <p class="text-memora-black/70 mb-6 leading-relaxed"><?= htmlspecialchars($step['description']) ?></p>
                            
                            <div class="text-sm space-y-3 pt-4 border-t border-memora-wine/10">
                                <div>
                                    <strong class="block text-memora-wine/80">O que precisamos de você:</strong>
                                    <p class="text-memora-black/60"><?= htmlspecialchars($step['input']) ?></p>
                                </div>
                                <div>
                                    <strong class="block text-memora-wine/80">O que entregamos a você:</strong>
                                    <p class="text-memora-black/60"><?= htmlspecialchars($step['output']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lado da Imagem (com o ponto na linha) -->
                    <div class="relative flex items-center justify-center <?= $idx % 2 === 0 ? 'lg:order-2' : 'lg:order-1' ?>">
                        <!-- O ponto na linha -->
                        <div class="hidden lg:block absolute top-1/2 left-1/2 w-4 h-4 bg-memora-cream border-2 border-memora-wine rounded-full -translate-x-1/2 -translate-y-1/2 z-10"></div>
                        
                        <div class="w-11/12 mx-auto aspect-[4/5] relative">
                            <img src="<?= htmlspecialchars($step['image']) ?>" 
                                 alt="<?= htmlspecialchars($step['title']) ?>" 
                                 class="w-full h-full object-cover rounded-xl shadow-2xl shadow-memora-wine/10">
                            <div class="absolute inset-0 rounded-xl bg-gradient-to-t from-black/20 to-transparent"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- CTA -->
        <div class="text-center mt-24">
            <a href="/criar" class="inline-flex items-center justify-center px-10 py-4 text-base font-medium tracking-widest uppercase transition-all duration-300 bg-memora-wine text-white hover:bg-memora-wineLight shadow-xl shadow-memora-wine/20 rounded-full">
                Transformar Minhas Memórias
            </a>
        </div>
    </div>
</section>
