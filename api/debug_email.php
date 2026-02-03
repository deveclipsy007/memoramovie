<?php
// Arquivo: api/debug_email.php
// Objetivo: Testar envio de e-mail com logs detalhados na tela

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';
require_once 'services/SimpleSMTP.php';

echo "<h1>Teste de Diagnóstico de E-mail</h1>";
echo "<pre>";

echo "1. Verificando Configurações:\n";
echo "Host: " . SMTP_HOST . "\n";
echo "Port: " . SMTP_PORT . "\n";
echo "User: " . SMTP_USER . "\n";
echo "Secure: " . SMTP_SECURE . "\n";

echo "\n2. Testando Conexão SMTP...\n";

try {
    $socket = (SMTP_SECURE == 'ssl' ? 'ssl://' : '') . SMTP_HOST;
    echo "Conectando em $socket:" . SMTP_PORT . "...\n";
    
    $conn = fsockopen($socket, SMTP_PORT, $errno, $errstr, 15);

    if (!$conn) {
        throw new Exception("Falha na conexão: $errstr ($errno)");
    }
    echo "Conectado! Resposta do servidor:\n";
    echo fgets($conn, 512);

    echo "Enviando EHLO...\n";
    fwrite($conn, "EHLO " . SMTP_HOST . "\r\n");
    echo fgets($conn, 512);

    echo "Enviando AUTH LOGIN...\n";
    fwrite($conn, "AUTH LOGIN\r\n");
    echo fgets($conn, 512);

    echo "Enviando Usuário...\n";
    fwrite($conn, base64_encode(SMTP_USER) . "\r\n");
    echo fgets($conn, 512);

    echo "Enviando Senha...\n";
    fwrite($conn, base64_encode(SMTP_PASS) . "\r\n");
    $response = fgets($conn, 512);
    echo "Resposta da Autenticação: " . $response;

    if (strpos($response, '235') === false) {
        throw new Exception("Autenticação falhou! Verifique a senha.");
    }
    
    echo "\nSUCESSO: Credenciais corretas e conexão estabelecida!\n";
    echo "O problema não é senha nem conexão.\n";

    fwrite($conn, "QUIT\r\n");
    fclose($conn);

} catch (Exception $e) {
    echo "\nERRO CRÍTICO: " . $e->getMessage() . "\n";
}

echo "</pre>";
