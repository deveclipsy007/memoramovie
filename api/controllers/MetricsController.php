<?php
/**
 * Controller de Métricas - Memora Movie
 */

require_once __DIR__ . '/../config.sqlite.php';
require_once __DIR__ . '/../helpers.php';

class MetricsController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Registrar um evento (clique, visualização, etc)
     */
    public function track() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['event_name'])) {
            jsonResponse(false, null, 'Nome do evento é obrigatório', 400);
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO metrics (event_name, event_data, page_url) VALUES (?, ?, ?)");
            $stmt->execute([
                $input['event_name'],
                isset($input['event_data']) ? json_encode($input['event_data']) : null,
                $input['page_url'] ?? null
            ]);
            jsonResponse(true, ['message' => 'Evento registrado']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao registrar evento', 500);
        }
    }

    /**
     * Obter resumo de métricas
     */
    public function summary() {
        try {
            // Total de eventos por tipo
            $stmt = $this->db->query("
                SELECT event_name, COUNT(*) as total 
                FROM metrics 
                GROUP BY event_name 
                ORDER BY total DESC
            ");
            $byEvent = $stmt->fetchAll();

            // Total de eventos hoje
            $stmt = $this->db->query("
                SELECT COUNT(*) as total 
                FROM metrics 
                WHERE DATE(created_at) = DATE('now')
            ");
            $today = $stmt->fetch()['total'];

            // Total geral
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM metrics");
            $total = $stmt->fetch()['total'];

            jsonResponse(true, [
                'total' => (int)$total,
                'today' => (int)$today,
                'by_event' => $byEvent
            ]);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar métricas', 500);
        }
    }
}
