<?php
/**
 * Controller de Leads - Gerenciamento de Respostas do Formulário
 */

class LeadController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Listar todos os leads com filtros opcionais
     */
    public function list() {
        try {
            $status = $_GET['status'] ?? null;
            $limit = min((int)($_GET['limit'] ?? 50), 100);
            $offset = (int)($_GET['offset'] ?? 0);

            $sql = "SELECT * FROM leads";
            $params = [];

            if ($status) {
                $sql .= " WHERE status = ?";
                $params[] = $status;
            }

            $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $leads = $stmt->fetchAll();

            // Decodificar quiz_results para cada lead
            foreach ($leads as &$lead) {
                if (isset($lead['quiz_results']) && is_string($lead['quiz_results'])) {
                    $lead['quiz_results'] = json_decode($lead['quiz_results'], true);
                }
            }

            // Total count
            $countSql = "SELECT COUNT(*) as total FROM leads";
            if ($status) {
                $countSql .= " WHERE status = ?";
                $countStmt = $this->db->prepare($countSql);
                $countStmt->execute([$status]);
            } else {
                $countStmt = $this->db->query($countSql);
            }
            $total = $countStmt->fetch()['total'];

            jsonResponse(true, [
                'leads' => $leads,
                'total' => (int)$total,
                'limit' => $limit,
                'offset' => $offset
            ]);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar leads: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Obter um lead por ID
     */
    public function get($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM leads WHERE id = ?");
            $stmt->execute([$id]);
            $lead = $stmt->fetch();

            if (!$lead) {
                jsonResponse(false, null, 'Lead não encontrado', 404);
            }

            // Decodificar quiz_results se for JSON
            if (isset($lead['quiz_results']) && is_string($lead['quiz_results'])) {
                $lead['quiz_results'] = json_decode($lead['quiz_results'], true);
            }

            jsonResponse(true, $lead);
        } catch (PDOException $e) {
            logEvent($this->db, 'error', 'Erro ao buscar lead', $e->getMessage());
            jsonResponse(false, null, 'Erro ao buscar lead: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Criar novo lead
     */
    public function create() {
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            $stmt = $this->db->prepare("
                INSERT INTO leads (name, email, phone, quiz_results, plan_selected, status) 
                VALUES (?, ?, ?, ?, ?, 'new')
            ");
            
            $stmt->execute([
                $input['name'] ?? null,
                $input['email'] ?? null,
                $input['phone'] ?? null,
                json_encode($input['quiz_results'] ?? []),
                $input['plan_selected'] ?? null
            ]);

            $leadId = $this->db->lastInsertId();

            logEvent($this->db, 'success', 'Novo lead cadastrado', 'ID: ' . $leadId . ' | Email: ' . ($input['email'] ?? 'N/A') . ' | Plano: ' . ($input['plan_selected'] ?? 'N/A'));

            // Disparar automação (AI + Email)
            // IMPORTANTE: Fazer isso ANTES do jsonResponse pois ele encerra o script com exit;
            if (!empty($input['email'])) {
                $this->processAutomation($leadId, $input['name'] ?? 'Cliente', $input['email'], $input['plan_selected'] ?? '');
            }

            jsonResponse(true, [
                'message' => 'Lead criado com sucesso',
                'id' => $leadId
            ], null, 201);
        } catch (PDOException $e) {
            logEvent($this->db, 'error', 'Erro ao criar lead', $e->getMessage());
            jsonResponse(false, null, 'Erro ao criar lead: ' . $e->getMessage(), 500);
        }
    }
    
    // Método auxiliar para processar automação (pode ser movido para job queue no futuro)
    private function processAutomation($leadId, $name, $email, $planId) {
        require_once __DIR__ . '/../services/AIService.php';
        require_once __DIR__ . '/../services/SimpleSMTP.php';
        
        try {
            // 1. Gerar mensagem personalizada (AI)
            $planNames = [
                'A' => 'Memora Capsule',
                'B' => 'Memora Feature',
                'C' => 'Memora Legacy'
            ];
            $planName = $planNames[$planId] ?? 'Memora Movie';
            
            $ai = new AIService();
            $emailBody = $ai->generateWelcomeMessage($name, $planName);
            
            // 2. Atualizar lead com a mensagem gerada
            $stmt = $this->db->prepare("UPDATE leads SET ai_message = ? WHERE id = ?");
            $stmt->execute([$emailBody, $leadId]);
            
            // 3. Enviar e-mail (SMTP)
            $smtp = new SimpleSMTP();
            $subject = "Bem-vindo ao universo Memora Movie, $name";
            $sent = $smtp->send($email, $subject, $emailBody);
            
            // 4. Atualizar status de envio
            if ($sent) {
                $stmt = $this->db->prepare("UPDATE leads SET email_sent = 1 WHERE id = ?");
                $stmt->execute([$leadId]);
                logEvent($this->db, 'success', 'Email enviado para lead', "ID: $leadId | Destino: $email");
            } else {
                logEvent($this->db, 'warning', 'Falha no envio de email', "ID: $leadId | Destino: $email");
            }
            
        } catch (Exception $e) {
            logEvent($this->db, 'error', 'Erro na automação do lead', $e->getMessage());
        }
    }

    /**
     * Atualizar status de um lead
     */
    public function update($id) {
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            $fields = [];
            $values = [];

            if (isset($input['status'])) {
                $fields[] = 'status = ?';
                $values[] = $input['status'];
            }
            if (isset($input['name'])) {
                $fields[] = 'name = ?';
                $values[] = $input['name'];
            }
            if (isset($input['email'])) {
                $fields[] = 'email = ?';
                $values[] = $input['email'];
            }
            if (isset($input['phone'])) {
                $fields[] = 'phone = ?';
                $values[] = $input['phone'];
            }

            if (empty($fields)) {
                jsonResponse(false, null, 'Nenhum campo para atualizar', 400);
            }

            $values[] = $id;
            $sql = "UPDATE leads SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);

            if ($stmt->rowCount() === 0) {
                jsonResponse(false, null, 'Lead não encontrado', 404);
            }

            logEvent($this->db, 'info', 'Lead atualizado', 'ID: ' . $id . ' | Status: ' . ($input['status'] ?? 'N/A'));

            jsonResponse(true, ['message' => 'Lead atualizado']);
        } catch (PDOException $e) {
            logEvent($this->db, 'error', 'Erro ao atualizar lead', $e->getMessage());
            jsonResponse(false, null, 'Erro ao atualizar lead: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Excluir lead
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM leads WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                jsonResponse(false, null, 'Lead não encontrado', 404);
            }

            logEvent($this->db, 'info', 'Lead excluído', 'ID: ' . $id);

            jsonResponse(true, ['message' => 'Lead excluído com sucesso']);
        } catch (PDOException $e) {
            logEvent($this->db, 'error', 'Erro ao excluir lead', $e->getMessage());
            jsonResponse(false, null, 'Erro ao excluir lead: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Estatísticas rápidas de leads
     */
    public function stats() {
        try {
            $stats = [];

            // Total por status
            $stmt = $this->db->query("
                SELECT status, COUNT(*) as count 
                FROM leads 
                GROUP BY status
            ");
            $byStatus = $stmt->fetchAll();
            
            $stats['by_status'] = [];
            $stats['total'] = 0;
            foreach ($byStatus as $row) {
                $stats['by_status'][$row['status']] = (int)$row['count'];
                $stats['total'] += (int)$row['count'];
            }

            // Leads hoje
            $todayStmt = $this->db->query("
                SELECT COUNT(*) as count 
                FROM leads 
                WHERE DATE(created_at) = DATE('now')
            ");
            $stats['today'] = (int)$todayStmt->fetch()['count'];

            jsonResponse(true, $stats);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar estatísticas: ' . $e->getMessage(), 500);
        }
    }
}
