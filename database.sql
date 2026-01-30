-- Database: memora_db
-- Host: localhost (ou Hostinger MySQL)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Estrutura da tabela `chapters`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `chapters` (
  `id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `plans`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `plans` (
  `id` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `delivery_time` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Dados de exemplo (Seed)
-- --------------------------------------------------------

INSERT INTO `chapters` (`id`, `title`, `subtitle`, `color`, `display_order`) VALUES
('love', 'Love Story', 'Para casamentos, pedidos ou reconquistas.', '#5A0B18', 1),
('legacy', 'The Legacy', 'A história dos seus pais ou avós.', '#8B4513', 2),
('newborn', 'New Life', 'Do anúncio da gravidez ao primeiro ano.', '#D4A5A5', 3),
('travel', 'Wanderlust', 'Aquela viagem que mudou quem você é.', '#2C3E50', 4),
('pet', 'Soulmate', 'Uma homenagem ao seu melhor amigo.', '#E67E22', 5),
('friendship', 'Best Years', 'Amizades que o tempo não apaga.', '#9B59B6', 6);

INSERT INTO `plans` (`id`, `name`, `price`, `duration`, `description`, `delivery_time`) VALUES
('A', 'Memora Capsule', 448.70, '90s a 120s', 'Puro impacto. Um trailer vibrante editado para gerar retenção.', '24 a 48 horas'),
('B', 'Memora Feature', 754.80, '3 a 5 minutos', 'Cinema narrativo. Deixamos a história respirar.', 'Até 48 horas'),
('C', 'Memora Legacy', 1467.80, '8 a 15 minutos', 'A obra-prima. Construção documental profunda.', '3 a 5 dias úteis');

-- --------------------------------------------------------
-- Estrutura da tabela `settings` (Configurações do Site)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `settings` (
  `key` varchar(50) NOT NULL,
  `value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `admins` (Usuários Administrativos)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `metrics` (Rastreamento de Cliques)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `metrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(100) NOT NULL,
  `event_data` json DEFAULT NULL,
  `page_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_event_name` (`event_name`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `leads` (Respostas do Formulário)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `quiz_results` json DEFAULT NULL,
  `plan_selected` varchar(50) DEFAULT NULL,
  `status` enum('new','contacted','closed','converted') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Dados iniciais de settings
-- --------------------------------------------------------

INSERT INTO `settings` (`key`, `value`) VALUES
('hero_video_url', 'https://www.youtube.com/embed/dQw4w9WgXcQ'),
('site_title', 'Memora Movie'),
('contact_email', 'contato@memoramovie.com');

-- --------------------------------------------------------
-- Admin inicial (senha: admin123 - TROQUE DEPOIS!)
-- Hash gerado com password_hash('admin123', PASSWORD_DEFAULT)
-- --------------------------------------------------------

INSERT INTO `admins` (`username`, `password_hash`) VALUES
('admin', '$2y$10$fd9SjvVljiV.ISYOcnV1v.dssMSQltoqThfGxx/B86YDSIn9G8XsS');

-- --------------------------------------------------------
-- Estrutura da tabela `site_content` (Conteúdo CMS do Site)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `site_content` (
  `id` varchar(100) NOT NULL,
  `section` varchar(50) NOT NULL,
  `content_type` enum('text','html','video','image') DEFAULT 'text',
  `value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_section` (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `site_faqs` (FAQs do Site)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `site_faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `site_reviews` (Depoimentos do Site)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `site_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `author` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `quiz_questions`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `quiz_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `quiz_options`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `quiz_options` (
  `id` varchar(50) NOT NULL,
  `question_id` int(11) NOT NULL,
  `label` text NOT NULL,
  `score_weight` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  FOREIGN KEY (`question_id`) REFERENCES `quiz_questions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Dados iniciais do Quiz (migrados do constants.ts)
-- --------------------------------------------------------

INSERT INTO `quiz_questions` (`id`, `question`, `display_order`) VALUES
(1, 'Qual categoria define melhor sua história?', 1),
(2, 'Qual a duração ideal para o filme final?', 2),
(3, 'Qual o tipo de animação e ritmo da edição?', 3),
(4, 'Quantidade de arquivos para envio:', 4),
(5, 'Qual o objetivo emocional principal?', 5);

INSERT INTO `quiz_options` (`id`, `question_id`, `label`, `score_weight`) VALUES
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

-- --------------------------------------------------------
-- Dados iniciais do conteúdo do site (Hero Section)
-- --------------------------------------------------------

INSERT INTO `site_content` (`id`, `section`, `content_type`, `value`) VALUES
('hero_title', 'hero', 'text', 'Sua história. Como um filme.'),
('hero_subtitle', 'hero', 'text', 'Transformamos seus momentos em filmes com alma de cinema. Para emocionar. Para presentear. Para nunca esquecer.'),
('hero_video_url', 'hero', 'video', 'https://www.youtube.com/embed/dQw4w9WgXcQ'),
('hero_cta_text', 'hero', 'text', 'Eternizar meu Momento');

-- --------------------------------------------------------
-- Dados iniciais de FAQs
-- --------------------------------------------------------

INSERT INTO `site_faqs` (`question`, `answer`, `display_order`) VALUES
('Como vocês criam emoção com arquivos ''comuns''?', 'O segredo não está na qualidade da câmera, mas no olhar. Nossos editores buscam o ''olhar'' escondido, o toque de mão, o sorriso espontâneo. Usamos técnicas de cinema (câmera lenta, trilha sonora crescente, sound design) para transformar um vídeo tremido de celular em uma memória poética.', 1),
('Posso enviar áudios de WhatsApp ou depoimentos?', 'Sim! E nós encorajamos muito. A voz de um avô contando uma história, ou um áudio antigo de ''eu te amo'' pode ser a alma do filme. Nós mixamos esses áudios com a música para criar uma narrativa documental.', 2),
('Quero fazer uma surpresa/reconquista. Vocês ajudam?', 'Com certeza. No momento do envio, você nos conta o objetivo (ex: ''Quero pedir desculpas'', ''Quero pedir em casamento'', ''É aniversário de 50 anos''). A direção criativa será totalmente focada em atingir esse objetivo emocional.', 3),
('A música faz diferença?', 'A música é a alma. Não usamos músicas genéricas de fundo. Escolhemos trilhas cinematográficas que crescem junto com a emoção do vídeo. Você pode nos dizer o estilo (ex: ''Piano triste'', ''Indie Eufórico'') e nós cuidamos da magia.', 4),
('Meus arquivos são deletados depois?', 'Sua privacidade é sagrada. Após a entrega e sua aprovação final, tudo é deletado permanentemente dos nossos servidores em 7 dias. Suas memórias pertencem apenas a você.', 5),
('Como recebo o filme?', 'Você recebe um link de uma ''Sala de Cinema Digital'' privada. É uma página linda, pronta para ser enviada como presente no WhatsApp ou e-mail da pessoa amada. O efeito ''Uau'' começa na entrega.', 6);

-- --------------------------------------------------------
-- Dados iniciais de Reviews (Depoimentos)
-- --------------------------------------------------------

INSERT INTO `site_reviews` (`text`, `author`, `role`, `display_order`) VALUES
('Eu fiz para tentar reconquistar minha ex-esposa. Mandei o vídeo. Nós choramos juntos assistindo. Obrigado por salvarem minha família.', 'Ricardo M.', 'Trailer ''Love Story''', 1),
('Meu pai faleceu há 3 anos. Ver ele ''vivo'' de novo, sorrindo em câmera lenta, com a música certa... foi o melhor presente que já me dei.', 'Ana Clara T.', 'Filme ''Legacy''', 2),
('Não é sobre organizar fotos. É sobre ver sua vida e pensar: ''Nossa, a gente foi muito feliz''. Chorei do início ao fim.', 'M. Chen', 'Filme ''Travel''', 3);

COMMIT;
