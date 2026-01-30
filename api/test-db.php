<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    require_once 'config.php';
    echo json_encode([
        'ok' => true,
        'message' => 'Banco conectado com sucesso (MySQL)',
        'db_name' => $db_name
    ]);
} catch (Exception $e) {
    echo json_encode([
        'ok' => false,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
