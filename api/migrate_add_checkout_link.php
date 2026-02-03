<?php
require_once __DIR__ . '/db.php';

try {
    echo "Adicionando coluna checkout_link na tabela plans...\n";
    
    // Check if column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM plans LIKE 'checkout_link'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE plans ADD COLUMN checkout_link VARCHAR(500) DEFAULT NULL AFTER delivery_time");
        echo "Coluna checkout_link adicionada com sucesso!\n";
    } else {
        echo "Coluna checkout_link já existe.\n";
    }
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
