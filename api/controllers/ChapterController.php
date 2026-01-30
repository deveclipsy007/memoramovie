<?php
/**
 * Controller de Capítulos - CRUD Completo
 */

class ChapterController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Listar todos os capítulos
     */
    public function list() {
        try {
            $stmt = $this->db->query("SELECT * FROM chapters ORDER BY display_order ASC");
            $data = $stmt->fetchAll();
            jsonResponse(true, $data);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar capítulos: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Obter um capítulo por ID
     */
    public function get($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM chapters WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch();

            if (!$data) {
                jsonResponse(false, null, 'Capítulo não encontrado', 404);
            }

            jsonResponse(true, $data);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar capítulo: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Criar novo capítulo
     */
    public function create() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['id']) || empty($input['title'])) {
            jsonResponse(false, null, 'ID e título são obrigatórios', 400);
        }

        try {
            // Verificar se ID já existe
            $check = $this->db->prepare("SELECT id FROM chapters WHERE id = ?");
            $check->execute([$input['id']]);
            if ($check->fetch()) {
                jsonResponse(false, null, 'ID já existe', 409);
            }

            // Obter próxima ordem
            $orderStmt = $this->db->query("SELECT COALESCE(MAX(display_order), 0) + 1 as next_order FROM chapters");
            $nextOrder = $orderStmt->fetch()['next_order'];

            $stmt = $this->db->prepare("
                INSERT INTO chapters (id, title, subtitle, image_url, color, display_order) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $input['id'],
                $input['title'],
                $input['subtitle'] ?? null,
                $input['image_url'] ?? null,
                $input['color'] ?? '#5A0B18',
                $input['display_order'] ?? $nextOrder
            ]);

            jsonResponse(true, ['message' => 'Capítulo criado com sucesso', 'id' => $input['id']], null, 201);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao criar capítulo: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Atualizar capítulo existente
     */
    public function update($id) {
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            // Verificar se existe
            $check = $this->db->prepare("SELECT id FROM chapters WHERE id = ?");
            $check->execute([$id]);
            if (!$check->fetch()) {
                jsonResponse(false, null, 'Capítulo não encontrado', 404);
            }

            $fields = [];
            $values = [];

            if (isset($input['title'])) {
                $fields[] = 'title = ?';
                $values[] = $input['title'];
            }
            if (isset($input['subtitle'])) {
                $fields[] = 'subtitle = ?';
                $values[] = $input['subtitle'];
            }
            if (isset($input['image_url'])) {
                $fields[] = 'image_url = ?';
                $values[] = $input['image_url'];
            }
            if (isset($input['color'])) {
                $fields[] = 'color = ?';
                $values[] = $input['color'];
            }
            if (isset($input['display_order'])) {
                $fields[] = 'display_order = ?';
                $values[] = $input['display_order'];
            }

            if (empty($fields)) {
                jsonResponse(false, null, 'Nenhum campo para atualizar', 400);
            }

            $values[] = $id;
            $sql = "UPDATE chapters SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);

            jsonResponse(true, ['message' => 'Capítulo atualizado com sucesso']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao atualizar capítulo: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Excluir capítulo
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM chapters WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                jsonResponse(false, null, 'Capítulo não encontrado', 404);
            }

            jsonResponse(true, ['message' => 'Capítulo excluído com sucesso']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao excluir capítulo: ' . $e->getMessage(), 500);
        }
    }
}
