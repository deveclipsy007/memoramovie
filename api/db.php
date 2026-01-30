<?php
/**
 * Centralizador de Conexão - Memora Movie
 * Este arquivo decide qual banco de dados usar e fornece informações de status.
 */

// Tenta carregar a configuração do MySQL por padrão
$configPath = __DIR__ . '/config.php';

if (file_exists($configPath)) {
    require_once $configPath;
} else {
    // Fallback para SQLite se o config.php não existir (útil para dev local rápido)
    require_once __DIR__ . '/config.sqlite.php';
}

/**
 * Retorna informações sobre a conexão atual para o indicador visual
 */
function getDbStatus() {
    return [
        'type' => defined('DB_TYPE') ? DB_TYPE : 'unknown',
        'name' => defined('DB_NAME') ? DB_NAME : 'N/A',
        'label' => (defined('DB_TYPE') && DB_TYPE === 'mysql') ? 'MySQL (Produção)' : 'SQLite (Local)',
        'color' => (defined('DB_TYPE') && DB_TYPE === 'mysql') ? 'bg-green-500' : 'bg-orange-500'
    ];
}
