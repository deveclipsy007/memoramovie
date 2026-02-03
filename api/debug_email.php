<?php
// Arquivo: api/debug_email.php
// Objetivo: Testar envio de e-mail com logs detalhados na tela

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';
// Não vamos usar o SimpleSMTP aqui para mostrar o fluxo "cru", mas com a lógica corrigida

echo "<h1>Teste de Diagnóstico de E-mail (V2)</h1>";
echo "<pre>";

// Função auxiliar para ler resposta completa do SMTP
function read_smtp_response($conn) {
    $data = "";
    while ($str = fgets($conn, 512)) {
        $data .= $str;
        // SMTP termina a resposta quando o 4º caractere é espaço (ex: "250 OK")
        // Se for hífen (ex: "250-PIPELINING"), tem mais linhas vindo.
        if (substr($str, 3, 1) == " ") { break; }
    }
    return $data;
}

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
    
    echo "Conectado!\n";
    echo read_smtp_response($conn); // Ler banner inicial

    echo "Enviando EHLO...\n";
    fwrite($conn, "EHLO " . SMTP_HOST . "\r\n");
    $response = read_smtp_response($conn);
    echo $response;

    echo "Enviando AUTH LOGIN...\n";
    fwrite($conn, "AUTH LOGIN\r\n");
    echo read_smtp_response($conn); // Espera 334 VXNlcm5hbWU6

    echo "Enviando Usuário...\n";
    fwrite($conn, base64_encode(SMTP_USER) . "\r\n");
    echo read_smtp_response($conn); // Espera 334 UGFzc3dvcmQ6

    echo "Enviando Senha...\n";
    fwrite($conn, base64_encode(SMTP_PASS) . "\r\n");
    $response = read_smtp_response($conn);
    echo "Resposta da Autenticação: " . $response;

    if (strpos($response, '235') === false) {
        throw new Exception("Autenticação falhou! Resultado inesperado.");
    }
    
    echo "\nSUCESSO: Autenticação funcionou!\n";
    
    // Se quiser testar o envio real:
    echo "Enviando cabeçalho MAIL FROM...\n";
    fwrite($conn, "MAIL FROM: <" . SMTP_USER . ">\r\n");
    echo read_smtp_response($conn);
    
    echo "Enviando RCPT TO...\n";
    fwrite($conn, "RCPT TO: <" . SMTP_USER . ">\r\n"); // Envia para si mesmo
    echo read_smtp_response($conn);
    
    echo "Enviando DATA...\n";
    fwrite($conn, "DATA\r\n");
    echo read_smtp_response($conn);
    
    fwrite($conn, "Subject: Teste SMTP Hostinger\r\n\r\nTeste de envio OK.\r\n.\r\n");
    echo read_smtp_response($conn);
    
    echo "\n\nE-mail de teste enviado com sucesso!";

    fwrite($conn, "QUIT\r\n");
    fclose($conn);

} catch (Exception $e) {
    echo "\nERRO CRÍTICO: " . $e->getMessage() . "\n";
}

echo "</pre>";
