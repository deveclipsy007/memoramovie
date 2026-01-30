-- Script para criar tabelas de Quiz no SQLite
-- Execute este arquivo no seu database.db local

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
  FOREIGN KEY (question_id) REFERENCES quiz_questions(id) ON DELETE CASCADE
);

-- Dados iniciais (migrados do constants.ts)
INSERT OR REPLACE INTO quiz_questions (id, question, display_order) VALUES
(1, 'Qual categoria define melhor sua história?', 1),
(2, 'Qual a duração ideal para o filme final?', 2),
(3, 'Qual o tipo de animação e ritmo da edição?', 3),
(4, 'Quantidade de arquivos para envio:', 4),
(5, 'Qual o objetivo emocional principal?', 5);

INSERT OR REPLACE INTO quiz_options (id, question_id, label, score_weight) VALUES
-- Pergunta 1: Categoria
('love', 1, 'Love Story (Casal/Casamento)', 2),
('legacy', 1, 'The Legacy (Pais/Avós)', 3),
('newborn', 1, 'New Life (Bebês/Crianças)', 2),
('travel', 1, 'Wanderlust (Viagens)', 2),
('pet', 1, 'Soulmate (Pets)', 1),
('friendship', 1, 'Best Years (Amizade/Festa)', 1),
-- Pergunta 2: Duração
('short', 2, 'Pílula (90s a 120s) - Foco em Redes Sociais', 1),
('medium', 2, 'Curta Metragem (3 a 5 min) - Narrativa Padrão', 2),
('long', 2, 'Feature Film (8 a 15 min) - Documentário Completo', 3),
-- Pergunta 3: Ritmo
('dynamic', 3, 'Dinâmica & Viral (Cortes rápidos, batida forte)', 1),
('cinematic', 3, 'Cinematográfica (Ritmo narrativo, respiros)', 2),
('slow', 3, 'Contemplativa (Takes longos, foco no detalhe)', 3),
-- Pergunta 4: Arquivos
('small', 4, '20 a 50 arquivos (Seleção prévia)', 1),
('medium_files', 4, '50 a 150 arquivos (Galeria misturada)', 2),
('large', 4, 'Ilimitado (HDs, Nuvens, Drive Completo)', 3),
-- Pergunta 5: Objetivo
('joy', 5, 'Euforia & Diversão (Sorrisos)', 1),
('romance', 5, 'Paixão & Romance (Suspiros)', 2),
('emotion', 5, 'Saudade & Emoção Profunda (Lágrimas)', 3);
