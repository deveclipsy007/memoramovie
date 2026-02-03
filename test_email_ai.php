<?php
// Script de Teste Rápido
require_once __DIR__ . '/api/config.php';
require_once __DIR__ . '/api/services/SimpleSMTP.php';
require_once __DIR__ . '/api/services/AIService.php';

echo "=== Testando Automação ===\n";

// 1. Teste de AI
echo "\n[1] Gerando mensagem com GPT-4o-mini...\n";
$ai = new AIService();
$msg = $ai->generateWelcomeMessage('Teste Developer', 'Memora Feature');
echo "Mensagem Gerada:\n---\n$msg\n---\n";

if (strlen($msg) > 50) {
    echo "OK: AI parece estar funcionando.\n";
} else {
    echo "ERRO: Mensagem muito curta, verifique a API Key.\n";
}

// 2. Teste de Email (comente se não quiser enviar real)
echo "\n[2] Testando envio de E-mail (SMTP)...\n";
echo "Para qual email enviar o teste? (mock: admin@example.com)\n";
$to = 'admin@example.com'; // Altere se rodar manualmente e quiser receber

$smtp = new SimpleSMTP();
// Não vamos enviar de verdade no script automático para não travar, 
// apenas instanciar pra ver se não explode erro de sintaxe.
// Se quiser testar real, descomente abaixo:
// $sent = $smtp->send($to, 'Teste de Automação', $msg);
// echo $sent ? "OK: Email enviado.\n" : "ERRO: Falha no envio.\n";

echo "Teste concluído (Envio real comentado por segurança).\n";
