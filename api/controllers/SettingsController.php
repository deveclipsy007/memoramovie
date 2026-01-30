<?php
/**
 * Controller de Configurações - Memora Movie
 */

require_once __DIR__ . '/../config.sqlite.php';
require_once __DIR__ . '/../helpers.php';

class SettingsController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Obter todas as configurações
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT `key`, `value` FROM settings");
            $settings = [];
            while ($row = $stmt->fetch()) {
                $settings[$row['key']] = $row['value'];
            }
            jsonResponse(true, $settings);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao buscar configurações', 500);
        }
    }

    /**
     * Atualizar uma configuração específica
     */
    public function update($key) {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['value'])) {
            jsonResponse(false, null, 'Valor é obrigatório', 400);
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = ?");
            $stmt->execute([$key, $input['value'], $input['value']]);
            jsonResponse(true, ['message' => 'Configuração atualizada']);
        } catch (PDOException $e) {
            jsonResponse(false, null, 'Erro ao atualizar configuração', 500);
        }
    }
}
