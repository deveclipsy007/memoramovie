<?php
/**
 * Migração CMS - Cria tabelas de conteúdo do site
 * Acesse este arquivo uma vez pelo navegador
 */

require_once 'db.php';

try {
    // Tabela de conteúdos gerais
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS site_content (
            id TEXT PRIMARY KEY,
            section TEXT NOT NULL,
            content_type TEXT DEFAULT 'text',
            value TEXT,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✅ Tabela site_content criada<br>";

    // Tabela de FAQs
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS site_faqs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            question TEXT NOT NULL,
            answer TEXT NOT NULL,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✅ Tabela site_faqs criada<br>";

    // Tabela de Reviews
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS site_reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            text TEXT NOT NULL,
            author TEXT NOT NULL,
            role TEXT,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✅ Tabela site_reviews criada<br>";

    // Tabela de Planos
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS site_plans (
            id TEXT PRIMARY KEY,
            name TEXT NOT NULL,
            price TEXT NOT NULL,
            duration TEXT,
            ideal_for TEXT,
            description TEXT,
            deliverables TEXT,
            client_sends TEXT,
            delivery_time TEXT,
            image_url TEXT,
            display_order INTEGER DEFAULT 0
        )
    ");
    echo "✅ Tabela site_plans criada<br>";

    // ========== DADOS INICIAIS ==========

    // Verificar se já tem dados em site_content
    $check = $pdo->query("SELECT COUNT(*) as c FROM site_content")->fetch();
    if ($check['c'] == 0) {
        // Conteúdos do Hero
        $pdo->exec("
            INSERT INTO site_content (id, section, content_type, value) VALUES
            ('hero_title', 'hero', 'text', 'Transforme memórias em legado.'),
            ('hero_subtitle', 'hero', 'text', 'Receba em 48h um filme cinematográfico feito com os vídeos e fotos que você já tem.'),
            ('hero_video_url', 'hero', 'video', 'https://www.youtube.com/embed/dQw4w9WgXcQ'),
            ('hero_cta_text', 'hero', 'text', 'Criar meu Filme'),
            ('manifesto_title', 'manifesto', 'text', 'Seus Arquivos Merecem Virar Legado'),
            ('manifesto_text', 'manifesto', 'text', 'A gente sabe que você tem gigabytes de memórias paradas na nuvem...'),
            ('guarantee_title', 'guarantee', 'text', 'Garantia Love Back'),
            ('guarantee_text', 'guarantee', 'text', 'Se você não chorar de emoção, devolvemos 100% do valor.')
        ");
        echo "✅ Conteúdos iniciais inseridos<br>";
    }

    // Verificar FAQs
    $checkFaq = $pdo->query("SELECT COUNT(*) as c FROM site_faqs")->fetch();
    if ($checkFaq['c'] == 0) {
        $pdo->exec("
            INSERT INTO site_faqs (question, answer, display_order) VALUES
            ('Como vocês criam emoção com arquivos comuns?', 'O segredo não está na qualidade da câmera, mas no olhar. Nossos editores buscam o olhar escondido, o toque de mão, o sorriso espontâneo.', 1),
            ('Posso enviar áudios de WhatsApp ou depoimentos?', 'Sim! E nós encorajamos muito. A voz de um avô contando uma história pode ser a alma do filme.', 2),
            ('Quero fazer uma surpresa/reconquista. Vocês ajudam?', 'Com certeza. No momento do envio, você nos conta o objetivo e a direção criativa será focada nisso.', 3),
            ('A música faz diferença?', 'A música é a alma. Não usamos músicas genéricas. Escolhemos trilhas cinematográficas que crescem junto com a emoção.', 4),
            ('Meus arquivos são deletados depois?', 'Sua privacidade é sagrada. Após a entrega, tudo é deletado em 7 dias.', 5),
            ('Como recebo o filme?', 'Você recebe um link de uma Sala de Cinema Digital privada, pronta para ser enviada como presente.', 6)
        ");
        echo "✅ FAQs inseridas<br>";
    }

    // Verificar Reviews
    $checkReview = $pdo->query("SELECT COUNT(*) as c FROM site_reviews")->fetch();
    if ($checkReview['c'] == 0) {
        $pdo->exec("
            INSERT INTO site_reviews (text, author, role, display_order) VALUES
            ('Eu fiz para tentar reconquistar minha ex-esposa. Mandei o vídeo. Nós choramos juntos assistindo. Obrigado por salvarem minha família.', 'Ricardo M.', 'Trailer Love Story', 1),
            ('Meu pai faleceu há 3 anos. Ver ele vivo de novo, sorrindo em câmera lenta, com a música certa... foi o melhor presente que já me dei.', 'Ana Clara T.', 'Filme Legacy', 2),
            ('Não é sobre organizar fotos. É sobre ver sua vida e pensar: Nossa, a gente foi muito feliz. Chorei do início ao fim.', 'M. Chen', 'Filme Travel', 3)
        ");
        echo "✅ Reviews inseridos<br>";
    }

    // Verificar Planos
    $checkPlans = $pdo->query("SELECT COUNT(*) as c FROM site_plans")->fetch();
    if ($checkPlans['c'] == 0) {
        $pdo->exec("
            INSERT INTO site_plans (id, name, price, duration, ideal_for, description, deliverables, client_sends, delivery_time, image_url, display_order) VALUES
            ('A', 'Memora Capsule', 'R$ 448,70', '90s a 120s', 'Redes Sociais, Reels, Highlights Rápidos', 'Puro impacto. Um trailer vibrante editado para gerar retenção e emoção imediata.', 'Edição Dinâmica|Formato Vertical ou Horizontal|1 Música Licenciada|Color Grading Pop', '20 a 50 melhores arquivos', '24 a 48 horas', 'https://images.unsplash.com/photo-1616469829718-0faf16324280?w=800', 1),
            ('B', 'Memora Feature', 'R$ 754,80', '3 a 5 minutos', 'Viagens, Histórias de Amor, Aniversários', 'Cinema narrativo. Deixamos a história respirar, criando arcos de começo, meio e fim.', 'Narrativa Cinematográfica|Mixagem de Som|Inclusão de Áudios/Vozes|Color Grading Editorial', 'Até 150 arquivos variados', 'Até 48 horas', 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=800', 2),
            ('C', 'Memora Legacy', 'R$ 1.467,80', '8 a 15 minutos', 'Homenagens, Documentários Biográficos', 'A obra-prima. Uma construção documental lenta e profunda.', 'Estrutura Documental|Restauração de Imagem|Storytelling com Entrevistas|Trilha Sonora Original', 'Arquivos Ilimitados (HD/Drive)', '3 a 5 dias úteis', 'https://images.unsplash.com/photo-1478720568477-152d9b164e63?w=800', 3)
        ");
        echo "✅ Planos inseridos<br>";
    }

    echo "<br>🎉 <strong>Migração concluída com sucesso!</strong>";

} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage();
}
