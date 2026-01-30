<?php
/**
 * Controller de Reviews/Depoimentos
 */

class ReviewController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Listar todos os reviews
     */
    public function list() {
        try {
            $stmt = $this->db->query("SELECT * FROM site_reviews ORDER BY display_order ASC, id ASC");
            jsonResponse(true, $stmt->fetchAll());
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar reviews: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Criar novo review
     */
    public function create() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['text']) || empty($input['author'])) {
            jsonResponse(false, null, 'Texto e autor são obrigatórios', 400);
        }

        try {
            $orderStmt = $this->db->query("SELECT COALESCE(MAX(display_order), 0) + 1 as next_order FROM site_reviews");
            $nextOrder = $orderStmt->fetch()['next_order'];

            $stmt = $this->db->prepare("INSERT INTO site_reviews (text, author, role, display_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $input['text'],
                $input['author'],
                $input['role'] ?? '',
                $input['display_order'] ?? $nextOrder
            ]);

            jsonResponse(true, ['message' => 'Review criado', 'id' => $this->db->lastInsertId()], null, 201);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao criar review: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Atualizar review
     */
    public function update($id) {
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            $fields = [];
            $values = [];

            if (isset($input['text'])) {
                $fields[] = 'text = ?';
                $values[] = $input['text'];
            }
            if (isset($input['author'])) {
                $fields[] = 'author = ?';
                $values[] = $input['author'];
            }
            if (isset($input['role'])) {
                $fields[] = 'role = ?';
                $values[] = $input['role'];
            }
            if (isset($input['display_order'])) {
                $fields[] = 'display_order = ?';
                $values[] = $input['display_order'];
            }

            if (!empty($fields)) {
                $values[] = $id;
                $sql = "UPDATE site_reviews SET " . implode(', ', $fields) . " WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute($values);
            }

            jsonResponse(true, ['message' => 'Review atualizado']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao atualizar review: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Excluir review
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM site_reviews WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                jsonResponse(false, null, 'Review não encontrado', 404);
            }

            jsonResponse(true, ['message' => 'Review excluído']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao excluir review: ' . $e->getMessage(), 500);
        }
    }
}
