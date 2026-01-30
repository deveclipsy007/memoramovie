<?php
/**
 * Controller de Quiz - Gerenciamento de Perguntas e Opções
 */

class QuizController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Listar todas as perguntas com suas opções
     */
    public function list() {
        try {
            $stmt = $this->db->query("SELECT * FROM quiz_questions ORDER BY display_order ASC, id ASC");
            $questions = $stmt->fetchAll();

            foreach ($questions as &$question) {
                $optStmt = $this->db->prepare("SELECT * FROM quiz_options WHERE question_id = ? ORDER BY id");
                $optStmt->execute([$question['id']]);
                $question['options'] = $optStmt->fetchAll();
            }

            jsonResponse(true, $questions);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar perguntas: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Obter uma pergunta por ID
     */
    public function get($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM quiz_questions WHERE id = ?");
            $stmt->execute([$id]);
            $question = $stmt->fetch();

            if (!$question) {
                jsonResponse(false, null, 'Pergunta não encontrada', 404);
            }

            $optStmt = $this->db->prepare("SELECT * FROM quiz_options WHERE question_id = ?");
            $optStmt->execute([$id]);
            $question['options'] = $optStmt->fetchAll();

            jsonResponse(true, $question);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar pergunta: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Criar nova pergunta
     */
    public function create() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['question'])) {
            jsonResponse(false, null, 'Texto da pergunta é obrigatório', 400);
        }

        try {
            // Obter próxima ordem
            $orderStmt = $this->db->query("SELECT COALESCE(MAX(display_order), 0) + 1 as next_order FROM quiz_questions");
            $nextOrder = $orderStmt->fetch()['next_order'];

            $stmt = $this->db->prepare("INSERT INTO quiz_questions (question, display_order) VALUES (?, ?)");
            $stmt->execute([$input['question'], $input['display_order'] ?? $nextOrder]);

            $questionId = $this->db->lastInsertId();

            // Criar opções se fornecidas
            if (!empty($input['options']) && is_array($input['options'])) {
                foreach ($input['options'] as $option) {
                    $optId = $option['id'] ?? uniqid('opt_');
                    $optStmt = $this->db->prepare("INSERT INTO quiz_options (id, question_id, label, score_weight) VALUES (?, ?, ?, ?)");
                    $optStmt->execute([
                        $optId,
                        $questionId,
                        $option['label'],
                        $option['score_weight'] ?? 1
                    ]);
                }
            }

            jsonResponse(true, ['message' => 'Pergunta criada', 'id' => $questionId], null, 201);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao criar pergunta: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Atualizar pergunta existente
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
            if (isset($input['display_order'])) {
                $fields[] = 'display_order = ?';
                $values[] = $input['display_order'];
            }

            if (!empty($fields)) {
                $values[] = $id;
                $sql = "UPDATE quiz_questions SET " . implode(', ', $fields) . " WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute($values);
            }

            jsonResponse(true, ['message' => 'Pergunta atualizada']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao atualizar pergunta: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Excluir pergunta (e suas opções)
     */
    public function delete($id) {
        try {
            // Excluir opções primeiro
            $this->db->prepare("DELETE FROM quiz_options WHERE question_id = ?")->execute([$id]);
            
            // Excluir pergunta
            $stmt = $this->db->prepare("DELETE FROM quiz_questions WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                jsonResponse(false, null, 'Pergunta não encontrada', 404);
            }

            jsonResponse(true, ['message' => 'Pergunta excluída']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao excluir pergunta: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Adicionar opção a uma pergunta
     */
    public function addOption($questionId) {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['label'])) {
            jsonResponse(false, null, 'Label da opção é obrigatório', 400);
        }

        try {
            $optId = $input['id'] ?? uniqid('opt_');
            $stmt = $this->db->prepare("INSERT INTO quiz_options (id, question_id, label, score_weight) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $optId,
                $questionId,
                $input['label'],
                $input['score_weight'] ?? 1
            ]);

            jsonResponse(true, ['message' => 'Opção adicionada', 'id' => $optId], null, 201);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao adicionar opção: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Atualizar opção
     */
    public function updateOption($optionId) {
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            $fields = [];
            $values = [];

            if (isset($input['label'])) {
                $fields[] = 'label = ?';
                $values[] = $input['label'];
            }
            if (isset($input['score_weight'])) {
                $fields[] = 'score_weight = ?';
                $values[] = $input['score_weight'];
            }

            if (!empty($fields)) {
                $values[] = $optionId;
                $sql = "UPDATE quiz_options SET " . implode(', ', $fields) . " WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute($values);
            }

            jsonResponse(true, ['message' => 'Opção atualizada']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao atualizar opção: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Excluir opção
     */
    public function deleteOption($optionId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM quiz_options WHERE id = ?");
            $stmt->execute([$optionId]);

            if ($stmt->rowCount() === 0) {
                jsonResponse(false, null, 'Opção não encontrada', 404);
            }

            jsonResponse(true, ['message' => 'Opção excluída']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao excluir opção: ' . $e->getMessage(), 500);
        }
    }
}
