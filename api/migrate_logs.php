<?php
require_once __DIR__ . '/db.php';

try {
    echo "Criando tabela logs...\n";
    
    $sql = "CREATE TABLE IF NOT EXISTS logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        level VARCHAR(50),
        message TEXT,
        context TEXT,
        url VARCHAR(255),
        user_agent VARCHAR(255),
        ip_address VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    
    $pdo->exec($sql);
    echo "Tabela logs criada com sucesso!\n";
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
