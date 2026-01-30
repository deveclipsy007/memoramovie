<?php
/**
 * Script de Migration - Executar UMA VEZ para criar tabelas CMS
 * Acesse: http://localhost/api/run_migration.php
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Migration - Memora DB</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1a1a1a;color:#0f0;}";
echo ".success{color:#0f0;}.error{color:#f00;}.info{color:#ff0;}</style></head><body>";

echo "<h1>🚀 Migration - Tabelas CMS</h1>";
echo "<p class='info'>Executando migration para criar tabelas site_content, site_faqs e site_reviews...</p><hr>";

try {
    // Verificar se as tabelas já existem
    $tables = ['site_content', 'site_faqs', 'site_reviews'];
    $existingTables = [];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            $existingTables[] = $table;
        }
    }
    
    if (!empty($existingTables)) {
        echo "<p class='info'>⚠️ Tabelas já existem: " . implode(', ', $existingTables) . "</p>";
        echo "<p class='info'>Pulando criação de tabelas existentes...</p>";
    }
    
    // Ler o arquivo SQL
    $sqlFile = __DIR__ . '/../migration_add_cms_tables.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("Arquivo migration_add_cms_tables.sql não encontrado!");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Remover comentários e linhas vazias
    $sql = preg_replace('/^--.*$/m', '', $sql);
    $sql = preg_replace('/^\s*$/m', '', $sql);
    
    // Dividir em statements individuais
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    $executed = 0;
    $skipped = 0;
    $errors = 0;
    
    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, 'USE ') === 0) {
            continue;
        }
        
        try {
            // Verificar se é INSERT e se já existe dados
            if (stripos($statement, 'INSERT INTO') === 0) {
                preg_match('/INSERT INTO `?(\w+)`?/i', $statement, $matches);
                $tableName = $matches[1] ?? '';
                
                if ($tableName) {
                    $checkStmt = $pdo->query("SELECT COUNT(*) as count FROM `$tableName`");
                    $count = $checkStmt->fetch()['count'];
                    
                    if ($count > 0) {
                        echo "<p class='info'>⏭️ Tabela '$tableName' já possui dados ($count registros). Pulando INSERT...</p>";
                        $skipped++;
                        continue;
                    }
                }
            }
            
            $pdo->exec($statement);
            $executed++;
            
            // Log do que foi executado
            $preview = substr($statement, 0, 80);
            echo "<p class='success'>✅ " . htmlspecialchars($preview) . "...</p>";
            
        } catch (PDOException $e) {
            // Ignorar erros de "tabela já existe"
            if (strpos($e->getMessage(), 'already exists') !== false) {
                $skipped++;
                echo "<p class='info'>⏭️ Tabela já existe, pulando...</p>";
            } else {
                $errors++;
                echo "<p class='error'>❌ Erro: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
    }
    
    echo "<hr>";
    echo "<h2 class='success'>✅ Migration Concluída!</h2>";
    echo "<p>Statements executados: <strong>$executed</strong></p>";
    echo "<p>Statements pulados: <strong>$skipped</strong></p>";
    echo "<p>Erros: <strong>$errors</strong></p>";
    
    // Verificar se as tabelas foram criadas
    echo "<hr><h3>Verificação das Tabelas:</h3>";
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            $countStmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
            $count = $countStmt->fetch()['count'];
            echo "<p class='success'>✅ Tabela '$table' existe com $count registros</p>";
        } else {
            echo "<p class='error'>❌ Tabela '$table' NÃO foi criada!</p>";
        }
    }
    
    echo "<hr>";
    echo "<p class='success'>🎉 Pronto! Agora você pode acessar o painel admin e gerenciar o conteúdo do site.</p>";
    echo "<p><a href='/admin' style='color:#0ff;'>Ir para o Admin</a></p>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ ERRO FATAL: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";
