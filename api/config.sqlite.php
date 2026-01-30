<?php
/**
 * Configuração SQLite - Memora Movie (Desenvolvimento)
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db_path = __DIR__ . '/../storage/memora.db';
$db_dir = dirname($db_path);

if (!is_dir($db_dir)) {
    mkdir($db_dir, 0755, true);
}

try {
    $pdo = new PDO("sqlite:$db_path", null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    // Criar tabelas se não existirem
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS chapters (
            id TEXT PRIMARY KEY,
            title TEXT NOT NULL,
            subtitle TEXT,
            image_url TEXT,
            color TEXT,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS plans (
            id TEXT PRIMARY KEY,
            name TEXT NOT NULL,
            price REAL NOT NULL,
            duration TEXT,
            description TEXT,
            delivery_time TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS settings (
            key TEXT PRIMARY KEY,
            value TEXT,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password_hash TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS metrics (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            event_name TEXT NOT NULL,
            event_data TEXT,
            page_url TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS leads (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT,
            email TEXT,
            phone TEXT,
            quiz_results TEXT,
            plan_selected TEXT,
            status TEXT DEFAULT 'new',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS quiz_questions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            question TEXT NOT NULL,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS quiz_options (
            id TEXT PRIMARY KEY,
            question_id INTEGER NOT NULL,
            label TEXT NOT NULL,
            score_weight INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (question_id) REFERENCES quiz_questions(id) ON DELETE CASCADE
        );
        
        CREATE TABLE IF NOT EXISTS logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            level TEXT NOT NULL DEFAULT 'info',
            message TEXT NOT NULL,
            context TEXT,
            url TEXT,
            user_agent TEXT,
            ip_address TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS site_content (
            id TEXT PRIMARY KEY,
            section TEXT NOT NULL,
            content_type TEXT DEFAULT 'text',
            value TEXT,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS site_faqs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            question TEXT NOT NULL,
            answer TEXT NOT NULL,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS site_reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            text TEXT NOT NULL,
            author TEXT NOT NULL,
            role TEXT,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");
    
    // Seed inicial se tabelas estiverem vazias
    $count = $pdo->query("SELECT COUNT(*) FROM chapters")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("
            INSERT INTO chapters (id, title, subtitle, color, display_order) VALUES
            ('love', 'Love Story', 'Para casamentos, pedidos ou reconquistas.', '#5A0B18', 1),
            ('legacy', 'The Legacy', 'A história dos seus pais ou avós.', '#8B4513', 2),
            ('newborn', 'New Life', 'Do anúncio da gravidez ao primeiro ano.', '#D4A5A5', 3),
            ('travel', 'Wanderlust', 'Aquela viagem que mudou quem você é.', '#2C3E50', 4),
            ('pet', 'Soulmate', 'Uma homenagem ao seu melhor amigo.', '#E67E22', 5),
            ('friendship', 'Best Years', 'Amizades que o tempo não apaga.', '#9B59B6', 6);
            
            INSERT INTO plans (id, name, price, duration, description, delivery_time) VALUES
            ('A', 'Memora Capsule', 448.70, '90s a 120s', 'Puro impacto. Um trailer vibrante editado para gerar retenção.', '24 a 48 horas'),
            ('B', 'Memora Feature', 754.80, '3 a 5 minutos', 'Cinema narrativo. Deixamos a história respirar.', 'Até 48 horas'),
            ('C', 'Memora Legacy', 1467.80, '8 a 15 minutos', 'A obra-prima. Construção documental profunda.', '3 a 5 dias úteis');
            
            INSERT INTO settings (key, value) VALUES
            ('hero_video_url', 'https://www.youtube.com/embed/dQw4w9WgXcQ'),
            ('site_title', 'Memora Movie'),
            ('contact_email', 'contato@memoramovie.com');
            
            INSERT INTO admins (username, password_hash) VALUES
            ('admin', '\$2y\$10\$fd9SjvVljiV.ISYOcnV1v.dssMSQltoqThfGxx/B86YDSIn9G8XsS');
        ");
    }
    
    // Seed de conteúdo do site (Hero)
    $contentCount = $pdo->query("SELECT COUNT(*) FROM site_content")->fetchColumn();
    if ($contentCount == 0) {
        $pdo->exec("
            INSERT INTO site_content (id, section, content_type, value) VALUES
            ('hero_title', 'hero', 'text', 'Sua história. Como um filme.'),
            ('hero_subtitle', 'hero', 'text', 'Transformamos seus momentos em filmes com alma de cinema. Para emocionar. Para presentear. Para nunca esquecer.'),
            ('hero_video_url', 'hero', 'video', 'https://www.youtube.com/embed/dQw4w9WgXcQ'),
            ('hero_cta_text', 'hero', 'text', 'Eternizar meu Momento');
        ");
    }
    
    // Seed de FAQs
    $faqCount = $pdo->query("SELECT COUNT(*) FROM site_faqs")->fetchColumn();
    if ($faqCount == 0) {
        $pdo->exec("
            INSERT INTO site_faqs (question, answer, display_order) VALUES
            ('Como vocês criam emoção com arquivos ''comuns''?', 'O segredo não está na qualidade da câmera, mas no olhar. Nossos editores buscam o ''olhar'' escondido, o toque de mão, o sorriso espontâneo. Usamos técnicas de cinema (câmera lenta, trilha sonora crescente, sound design) para transformar um vídeo tremido de celular em uma memória poética.', 1),
            ('Posso enviar áudios de WhatsApp ou depoimentos?', 'Sim! E nós encorajamos muito. A voz de um avô contando uma história, ou um áudio antigo de ''eu te amo'' pode ser a alma do filme. Nós mixamos esses áudios com a música para criar uma narrativa documental.', 2),
            ('Quero fazer uma surpresa/reconquista. Vocês ajudam?', 'Com certeza. No momento do envio, você nos conta o objetivo (ex: ''Quero pedir desculpas'', ''Quero pedir em casamento'', ''É aniversário de 50 anos''). A direção criativa será totalmente focada em atingir esse objetivo emocional.', 3),
            ('A música faz diferença?', 'A música é a alma. Não usamos músicas genéricas de fundo. Escolhemos trilhas cinematográficas que crescem junto com a emoção do vídeo. Você pode nos dizer o estilo (ex: ''Piano triste'', ''Indie Eufórico'') e nós cuidamos da magia.', 4),
            ('Meus arquivos são deletados depois?', 'Sua privacidade é sagrada. Após a entrega e sua aprovação final, tudo é deletado permanentemente dos nossos servidores em 7 dias. Suas memórias pertencem apenas a você.', 5),
            ('Como recebo o filme?', 'Você recebe um link de uma ''Sala de Cinema Digital'' privada. É uma página linda, pronta para ser enviada como presente no WhatsApp ou e-mail da pessoa amada. O efeito ''Uau'' começa na entrega.', 6);
        ");
    }
    
    // Seed de Reviews
    $reviewCount = $pdo->query("SELECT COUNT(*) FROM site_reviews")->fetchColumn();
    if ($reviewCount == 0) {
        $pdo->exec("
            INSERT INTO site_reviews (text, author, role, display_order) VALUES
            ('Eu fiz para tentar reconquistar minha ex-esposa. Mandei o vídeo. Nós choramos juntos assistindo. Obrigado por salvarem minha família.', 'Ricardo M.', 'Trailer ''Love Story''', 1),
            ('Meu pai faleceu há 3 anos. Ver ele ''vivo'' de novo, sorrindo em câmera lenta, com a música certa... foi o melhor presente que já me dei.', 'Ana Clara T.', 'Filme ''Legacy''', 2),
            ('Não é sobre organizar fotos. É sobre ver sua vida e pensar: ''Nossa, a gente foi muito feliz''. Chorei do início ao fim.', 'M. Chen', 'Filme ''Travel''', 3);
        ");
    }
    
    // Seed de perguntas do quiz
    $quizCount = $pdo->query("SELECT COUNT(*) FROM quiz_questions")->fetchColumn();
    if ($quizCount == 0) {
        $pdo->exec("
            INSERT INTO quiz_questions (id, question, display_order) VALUES
            (1, 'Qual categoria define melhor sua história?', 1),
            (2, 'Qual a duração ideal para o filme final?', 2),
            (3, 'Qual o tipo de animação e ritmo da edição?', 3),
            (4, 'Quantidade de arquivos para envio:', 4),
            (5, 'Qual o objetivo emocional principal?', 5);
            
            INSERT INTO quiz_options (id, question_id, label, score_weight) VALUES
            ('love', 1, 'Love Story (Casal/Casamento)', 2),
            ('legacy', 1, 'The Legacy (Pais/Avós)', 3),
            ('newborn', 1, 'New Life (Bebês/Crianças)', 2),
            ('travel', 1, 'Wanderlust (Viagens)', 2),
            ('pet', 1, 'Soulmate (Pets)', 1),
            ('friendship', 1, 'Best Years (Amizade/Festa)', 1),
            
            ('short', 2, 'Pílula (90s a 120s) - Foco em Redes Sociais', 1),
            ('medium', 2, 'Curta Metragem (3 a 5 min) - Narrativa Padrão', 2),
            ('long', 2, 'Feature Film (8 a 15 min) - Documentário Completo', 3),
            
            ('dynamic', 3, 'Dinâmica & Viral (Cortes rápidos, batida forte)', 1),
            ('cinematic', 3, 'Cinematográfica (Ritmo narrativo, respiros)', 2),
            ('slow', 3, 'Contemplativa (Takes longos, foco no detalhe)', 3),
            
            ('small', 4, '20 a 50 arquivos (Seleção prévia)', 1),
            ('medium_files', 4, '50 a 150 arquivos (Galeria misturada)', 2),
            ('large', 4, 'Ilimitado (HDs, Nuvens, Drive Completo)', 3),
            
            ('joy', 5, 'Euforia & Diversão (Sorrisos)', 1),
            ('romance', 5, 'Paixão & Romance (Suspiros)', 2),
            ('emotion', 5, 'Saudade & Emoção Profunda (Lágrimas)', 3);
        ");
    }
    
} catch (PDOException $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'Falha na conexão com o banco de dados: ' . $e->getMessage()
    ]);
    exit;
}
