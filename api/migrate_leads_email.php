<?php
require_once __DIR__ . '/db.php';

try {
    echo "Atualizando tabela leads...\n";
    
    // Check if ai_message exists
    $stmt = $pdo->query("SHOW COLUMNS FROM leads LIKE 'ai_message'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE leads ADD COLUMN ai_message TEXT DEFAULT NULL AFTER plan_selected");
        echo "Coluna ai_message adicionada.\n";
    }
    
    // Check if email_sent exists
    $stmt = $pdo->query("SHOW COLUMNS FROM leads LIKE 'email_sent'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE leads ADD COLUMN email_sent TINYINT(1) DEFAULT 0 AFTER ai_message");
        echo "Coluna email_sent adicionada.\n";
    }
    
    echo "Migração concluída com sucesso!\n";
    
} catch (PDOException $e) {
    echo "Erro na migração: " . $e->getMessage() . "\n";
}
