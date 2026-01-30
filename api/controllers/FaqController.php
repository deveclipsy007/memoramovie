<?php
/**
 * Controller de FAQs
 */

class FaqController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Listar todas as FAQs
     */
    public function list() {
        try {
            $stmt = $this->db->query("SELECT * FROM site_faqs ORDER BY display_order ASC, id ASC");
            jsonResponse(true, $stmt->fetchAll());
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar FAQs: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Criar nova FAQ
     */
    public function create() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['question']) || empty($input['answer'])) {
            jsonResponse(false, null, 'Pergunta e resposta são obrigatórias', 400);
        }

        try {
            $orderStmt = $this->db->query("SELECT COALESCE(MAX(display_order), 0) + 1 as next_order FROM site_faqs");
            $nextOrder = $orderStmt->fetch()['next_order'];

            $stmt = $this->db->prepare("INSERT INTO site_faqs (question, answer, display_order) VALUES (?, ?, ?)");
            $stmt->execute([
                $input['question'],
                $input['answer'],
                $input['display_order'] ?? $nextOrder
            ]);

            jsonResponse(true, ['message' => 'FAQ criada', 'id' => $this->db->lastInsertId()], null, 201);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao criar FAQ: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Atualizar FAQ
     */
    public function update($id) {
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            $fields = [];
            $values = [];

            if (isset($input['question'])) {
                $fields[] = 'question = ?';
                $values[] = $input['question'];
            }
            if (isset($input['answer'])) {
                $fields[] = 'answer = ?';
                $values[] = $input['answer'];
            }
            if (isset($input['display_order'])) {
                $fields[] = 'display_order = ?';
                $values[] = $input['display_order'];
            }

            if (!empty($fields)) {
                $values[] = $id;
                $sql = "UPDATE site_faqs SET " . implode(', ', $fields) . " WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute($values);
            }

            jsonResponse(true, ['message' => 'FAQ atualizada']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao atualizar FAQ: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Excluir FAQ
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM site_faqs WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                jsonResponse(false, null, 'FAQ não encontrada', 404);
            }

            jsonResponse(true, ['message' => 'FAQ excluída']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao excluir FAQ: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Reordenar FAQs
     */
    public function reorder() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['order']) || !is_array($input['order'])) {
            jsonResponse(false, null, 'Ordem é obrigatória', 400);
        }

        try {
            $this->db->beginTransaction();
            
            foreach ($input['order'] as $index => $id) {
                $stmt = $this->db->prepare("UPDATE site_faqs SET display_order = ? WHERE id = ?");
                $stmt->execute([$index + 1, $id]);
            }
            
            $this->db->commit();
            jsonResponse(true, ['message' => 'Ordem atualizada']);
        } catch (PDOException $e) {
            $this->db->rollBack();
            jsonResponse(false, null, 'Erro ao reordenar: ' . $e->getMessage(), 500);
        }
    }
}
