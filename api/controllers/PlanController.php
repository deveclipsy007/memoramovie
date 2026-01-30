<?php
/**
 * Controller de Planos - Gerenciamento de Planos de Preço
 */

class PlanController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Listar todos os planos
     */
    public function list() {
        try {
            $stmt = $this->db->query("SELECT * FROM plans ORDER BY price ASC");
            $plans = $stmt->fetchAll();
            jsonResponse(true, $plans);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar planos: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Obter um plano por ID
     */
    public function get($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM plans WHERE id = ?");
            $stmt->execute([$id]);
            $plan = $stmt->fetch();

            if (!$plan) {
                jsonResponse(false, null, 'Plano não encontrado', 404);
            }

            jsonResponse(true, $plan);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar plano: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Criar novo plano
     */
    public function create() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['id']) || empty($input['name']) || !isset($input['price'])) {
            jsonResponse(false, null, 'ID, Nome e Preço são obrigatórios', 400);
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO plans (id, name, price, duration, description, delivery_time) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $input['id'],
                $input['name'],
                $input['price'],
                $input['duration'] ?? '',
                $input['description'] ?? '',
                $input['delivery_time'] ?? ''
            ]);

            jsonResponse(true, ['message' => 'Plano criado'], null, 201);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao criar plano: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Atualizar plano existente
     */
    public function update($id) {
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            $fields = [];
            $values = [];

            $allowedFields = ['name', 'price', 'duration', 'description', 'delivery_time'];
            foreach ($allowedFields as $field) {
                if (isset($input[$field])) {
                    $fields[] = "$field = ?";
                    $values[] = $input[$field];
                }
            }

            if (empty($fields)) {
                jsonResponse(false, null, 'Nenhum campo para atualizar', 400);
            }

            $values[] = $id;
            $sql = "UPDATE plans SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);

            jsonResponse(true, ['message' => 'Plano atualizado']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao atualizar plano: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Excluir plano
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM plans WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                jsonResponse(false, null, 'Plano não encontrado', 404);
            }

            jsonResponse(true, ['message' => 'Plano excluído']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao excluir plano: ' . $e->getMessage(), 500);
        }
    }
}
