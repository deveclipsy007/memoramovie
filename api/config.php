<?php
/**
 * Configuração de Banco de Dados - Memora Movie
 * 
 * ATENÇÃO: Nunca suba as senhas reais para o Git.
 * Use o arquivo config.example.php como modelo.
 */

// Configurações de exibição de erro (Desativar em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Dados de conexão (Preencher com os dados da Hostinger/Local)
$db_host = 'localhost';
$db_name = 'u854567422_memorabanco';
$db_user = 'u854567422_memora';
$db_pass = 'Escher007.';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // Em caso de erro, retornar JSON padronizado
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'Falha na conexão com o banco de dados.'
    ]);
    exit;
}
