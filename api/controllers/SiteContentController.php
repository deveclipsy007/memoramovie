<?php
/**
 * Controller de Conteúdo do Site - CMS
 */

class SiteContentController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Listar todos os conteúdos
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM site_content ORDER BY section, id");
            $contents = $stmt->fetchAll();
            
            // Agrupar por seção
            $grouped = [];
            foreach ($contents as $item) {
                $grouped[$item['section']][$item['id']] = $item['value'];
            }
            
            jsonResponse(true, $grouped);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar conteúdos: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Obter conteúdos de uma seção específica
     */
    public function getSection($section) {
        try {
            $stmt = $this->db->prepare("SELECT id, value, content_type FROM site_content WHERE section = ?");
            $stmt->execute([$section]);
            $contents = $stmt->fetchAll();
            
            $result = [];
            foreach ($contents as $item) {
                $result[$item['id']] = $item['value'];
            }
            
            jsonResponse(true, $result);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar seção: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Atualizar um conteúdo
     */
    public function update($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['value'])) {
            jsonResponse(false, null, 'Valor é obrigatório', 400);
        }

        try {
            // Verificar se existe
            $check = $this->db->prepare("SELECT id FROM site_content WHERE id = ?");
            $check->execute([$id]);
            
            if ($check->fetch()) {
                // Update
                $stmt = $this->db->prepare("UPDATE site_content SET value = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->execute([$input['value'], $id]);
            } else {
                // Insert
                $stmt = $this->db->prepare("INSERT INTO site_content (id, section, content_type, value) VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $id,
                    $input['section'] ?? 'general',
                    $input['content_type'] ?? 'text',
                    $input['value']
                ]);
            }
            
            jsonResponse(true, ['message' => 'Conteúdo atualizado']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao atualizar: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Atualizar múltiplos conteúdos de uma vez
     */
    public function updateBatch() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['items']) || !is_array($input['items'])) {
            jsonResponse(false, null, 'Items é obrigatório e deve ser um array', 400);
        }

        try {
            $this->db->beginTransaction();
            
            foreach ($input['items'] as $item) {
                if (!isset($item['id']) || !isset($item['value'])) continue;
                
                $check = $this->db->prepare("SELECT id FROM site_content WHERE id = ?");
                $check->execute([$item['id']]);
                
                if ($check->fetch()) {
                    $stmt = $this->db->prepare("UPDATE site_content SET value = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                    $stmt->execute([$item['value'], $item['id']]);
                } else {
                    $stmt = $this->db->prepare("INSERT INTO site_content (id, section, content_type, value) VALUES (?, ?, ?, ?)");
                    $stmt->execute([
                        $item['id'],
                        $item['section'] ?? 'general',
                        $item['content_type'] ?? 'text',
                        $item['value']
                    ]);
                }
            }
            
            $this->db->commit();
            jsonResponse(true, ['message' => 'Conteúdos atualizados']);
        } catch (PDOException $e) {
            $this->db->rollBack();
            jsonResponse(false, null, 'Erro ao atualizar: ' . $e->getMessage(), 500);
        }
    }
}
