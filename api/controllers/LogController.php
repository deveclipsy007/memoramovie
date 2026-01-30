<?php
/**
 * Controller de Logs - Gerenciamento de Logs do Sistema
 */

class LogController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Listar todos os logs
     */
    public function list() {
        try {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
            $level = isset($_GET['level']) ? $_GET['level'] : null;

            $sql = "SELECT * FROM logs";
            $params = [];

            if ($level && in_array($level, ['error', 'warning', 'info', 'success'])) {
                $sql .= " WHERE level = ?";
                $params[] = $level;
            }

            $sql .= " ORDER BY created_at DESC LIMIT ?";
            $params[] = $limit;

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $data = $stmt->fetchAll();

            // Formatar timestamp para o frontend
            foreach ($data as &$log) {
                $log['timestamp'] = $log['created_at'];
            }

            jsonResponse(true, $data);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar logs: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Criar novo log
     */
    public function create() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['message'])) {
            jsonResponse(false, null, 'Mensagem é obrigatória', 400);
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO logs (level, message, context, url, user_agent, ip_address) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $input['level'] ?? 'info',
                $input['message'],
                $input['context'] ?? null,
                $input['url'] ?? $_SERVER['REQUEST_URI'] ?? null,
                $input['user_agent'] ?? $_SERVER['HTTP_USER_AGENT'] ?? null,
                $input['ip_address'] ?? $_SERVER['REMOTE_ADDR'] ?? null
            ]);

            jsonResponse(true, ['message' => 'Log criado', 'id' => $this->db->lastInsertId()], null, 201);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao criar log: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Limpar todos os logs
     */
    public function clear() {
        try {
            $this->db->exec("DELETE FROM logs");
            jsonResponse(true, ['message' => 'Logs limpos com sucesso']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao limpar logs: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Obter estatísticas dos logs
     */
    public function stats() {
        try {
            $stats = [
                'total' => $this->db->query("SELECT COUNT(*) FROM logs")->fetchColumn(),
                'error' => $this->db->query("SELECT COUNT(*) FROM logs WHERE level = 'error'")->fetchColumn(),
                'warning' => $this->db->query("SELECT COUNT(*) FROM logs WHERE level = 'warning'")->fetchColumn(),
                'info' => $this->db->query("SELECT COUNT(*) FROM logs WHERE level = 'info'")->fetchColumn(),
                'success' => $this->db->query("SELECT COUNT(*) FROM logs WHERE level = 'success'")->fetchColumn(),
                'last_24h' => $this->db->query("SELECT COUNT(*) FROM logs WHERE created_at >= datetime('now', '-24 hours')")->fetchColumn()
            ];

            jsonResponse(true, $stats);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar estatísticas: ' . $e->getMessage(), 500);
        }
    }
}
