<?php
require_once __DIR__ . '/db.php';

try {
    echo "Adicionando conteúdo da seção Director's Cut...\n";
    
    $items = [
        ['dc_title', 'directors-cut', 'text', 'Não é edição. É direção de arte.'],
        ['dc_description', 'directors-cut', 'text', 'Cada filme Memora passa por um processo de pós-produção digno de cinema. Nossos editores são diretores criativos que tratam cada projeto como uma obra única.'],
        ['directors_cut_video_url', 'directors-cut', 'video', 'https://www.youtube.com/embed/tfjtbAAuAUA']
    ];
    
    foreach ($items as $item) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO site_content (id, section, content_type, value) VALUES (?, ?, ?, ?)");
        $stmt->execute($item);
    }

    // Atualizar vídeo do hero se já existir
    $stmt = $pdo->prepare("UPDATE site_content SET value = ? WHERE id = 'hero_video_url'");
    $stmt->execute(['https://www.youtube.com/embed/tfjtbAAuAUA']);
    
    echo "Migração concluída com sucesso!\n";
    
} catch (PDOException $e) {
    echo "Erro na migração: " . $e->getMessage() . "\n";
}
