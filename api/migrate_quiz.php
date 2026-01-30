<?php
/**
 * Script de migração - Cria tabelas de Quiz
 * Acesse este arquivo uma vez pelo navegador para criar as tabelas
 */

require_once 'db.php';

try {
    // Criar tabela de perguntas
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS quiz_questions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            question TEXT NOT NULL,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Criar tabela de opções
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS quiz_options (
            id TEXT PRIMARY KEY,
            question_id INTEGER NOT NULL,
            label TEXT NOT NULL,
            score_weight INTEGER DEFAULT 1,
            FOREIGN KEY (question_id) REFERENCES quiz_questions(id) ON DELETE CASCADE
        )
    ");
    
    // Verificar se já existem dados
    $check = $pdo->query("SELECT COUNT(*) as c FROM quiz_questions")->fetch();
    
    if ($check['c'] == 0) {
        // Inserir perguntas
        $pdo->exec("
            INSERT INTO quiz_questions (id, question, display_order) VALUES
            (1, 'Qual categoria define melhor sua história?', 1),
            (2, 'Qual a duração ideal para o filme final?', 2),
            (3, 'Qual o tipo de animação e ritmo da edição?', 3),
            (4, 'Quantidade de arquivos para envio:', 4),
            (5, 'Qual o objetivo emocional principal?', 5)
        ");
        
        // Inserir opções
        $pdo->exec("
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
            ('dynamic', 3, 'Dinâmica e Viral (Cortes rápidos, batida forte)', 1),
            ('cinematic', 3, 'Cinematográfica (Ritmo narrativo, respiros)', 2),
            ('slow', 3, 'Contemplativa (Takes longos, foco no detalhe)', 3),
            ('small', 4, '20 a 50 arquivos (Seleção prévia)', 1),
            ('medium_files', 4, '50 a 150 arquivos (Galeria misturada)', 2),
            ('large', 4, 'Ilimitado (HDs, Nuvens, Drive Completo)', 3),
            ('joy', 5, 'Euforia e Diversão (Sorrisos)', 1),
            ('romance', 5, 'Paixão e Romance (Suspiros)', 2),
            ('emotion', 5, 'Saudade e Emoção Profunda (Lágrimas)', 3)
        ");
        
        echo "✅ Tabelas criadas e dados inseridos com sucesso!<br>";
        echo "5 perguntas e 18 opções foram adicionadas.";
    } else {
        echo "ℹ️ Tabelas já existem e contêm " . $check['c'] . " perguntas.";
    }
    
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage();
}
