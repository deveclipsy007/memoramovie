<?php
/**
 * Configuração EXEMPLO - Memora Movie
 * 
 * RENOMEIE ESTE ARQUIVO PARA config.php e preencha seus dados reais.
 * NUNCA SUBA O config.php COM SENHAS REAIS PARA O GITHUB.
 */

$db_host = 'SEU_HOST_AQUI'; // Geralmente 'localhost' na Hostinger
$db_name = 'SEU_NOME_DO_BANCO_AQUI';
$db_user = 'SEU_USUARIO_AQUI';
$db_pass = 'SUA_SENHA_AQUI';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Configuração pendente no servidor.']);
    exit;
}
