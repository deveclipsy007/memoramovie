<?php
/**
 * Helpers para Resposta JSON - Memora Movie
 */

/**
 * Função padronizada para retornos da API
 */
function jsonResponse($ok, $data = null, $error = null, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    
    // Configurar CORS básico (Dominio específico deve ser definido aqui)
    header("Access-Control-Allow-Origin: *"); 
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    echo json_encode([
        'ok' => (bool)$ok,
        'data' => $data,
        'error' => $error,
        'timestamp' => time()
    ]);
    exit;
}

/**
 * Registrar log no sistema
 */
function logEvent($pdo, $level, $message, $context = null) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO logs (level, message, context, url, user_agent, ip_address) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $level,
            $message,
            $context,
            $_SERVER['REQUEST_URI'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null,
            $_SERVER['REMOTE_ADDR'] ?? null
        ]);
    } catch (PDOException $e) {
        // Silenciosamente falhar para não quebrar a aplicação
        error_log("Erro ao registrar log: " . $e->getMessage());
    }
}
