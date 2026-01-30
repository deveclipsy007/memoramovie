-- ========================================
-- MIGRATION: Adicionar tabelas CMS
-- Data: 2026-01-21
-- Descrição: Cria tabelas site_content, site_faqs e site_reviews
-- ========================================

USE memora_db;

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

-- ========================================
-- FIM DA MIGRATION
-- ========================================
