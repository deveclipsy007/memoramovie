<?php
/**
 * Centralizador de Conexão - Memora Movie
 * Este arquivo decide qual banco de dados usar e fornece informações de status.
 */

// Tenta carregar a configuração do MySQL por padrão
$mysqlConfig = __DIR__ . '/config.php';
$sqliteConfig = __DIR__ . '/config.sqlite.php';

if (file_exists($mysqlConfig)) {
    require_once $mysqlConfig;
} elseif (file_exists($sqliteConfig)) {
    // Fallback para SQLite se o config.php não existir
    require_once $sqliteConfig;
} else {
    // Se nenhum existir, não damos erro fatal aqui para permitir que o sistema mostre o erro de conexão depois
    // ou podemos definir um estado de erro.
    if (!defined('DB_TYPE')) define('DB_TYPE', 'none');
}

/**
 * Retorna informações sobre a conexão atual para o indicador visual
 */
function getDbStatus() {
    $type = defined('DB_TYPE') ? DB_TYPE : 'none';
    
    if ($type === 'mysql') {
        return [
            'type' => 'mysql',
            'name' => defined('DB_NAME') ? DB_NAME : 'N/A',
            'label' => 'MySQL (Produção)',
            'color' => 'bg-green-500'
        ];
    } elseif ($type === 'sqlite') {
        return [
            'type' => 'sqlite',
            'name' => defined('DB_NAME') ? DB_NAME : 'N/A',
            'label' => 'SQLite (Local)',
            'color' => 'bg-orange-500'
        ];
    } else {
        return [
            'type' => 'none',
            'name' => 'Faltando Config',
            'label' => 'Configuração Pendente',
            'color' => 'bg-red-500'
        ];
    }
}
