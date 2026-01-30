<?php
/**
 * Controller de Autenticação - Memora Movie
 */

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

class AuthController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Login do Administrador
     */
    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['username']) || empty($input['password'])) {
            jsonResponse(false, null, 'Usuário e senha são obrigatórios', 400);
        }

        try {
            $stmt = $this->db->prepare("SELECT * FROM admins WHERE username = ?");
            $stmt->execute([$input['username']]);
            $admin = $stmt->fetch();

            if (!$admin || !password_verify($input['password'], $admin['password_hash'])) {
                logEvent($this->db, 'warning', 'Tentativa de login com credenciais inválidas', 'Username: ' . $input['username']);
                jsonResponse(false, null, 'Credenciais inválidas', 401);
            }

            // Criar token de sessão simples
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            logEvent($this->db, 'success', 'Login realizado com sucesso', 'Admin: ' . $admin['username']);

            jsonResponse(true, [
                'message' => 'Login realizado com sucesso',
                'username' => $admin['username']
            ]);

        } catch (PDOException $e) {
            logEvent($this->db, 'error', 'Erro no processo de login', $e->getMessage());
            jsonResponse(false, null, 'Erro no servidor', 500);
        }
    }

    /**
     * Logout do Administrador
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $username = $_SESSION['admin_username'] ?? 'Desconhecido';
        session_destroy();
        logEvent($this->db, 'info', 'Logout realizado', 'Admin: ' . $username);
        jsonResponse(true, ['message' => 'Logout realizado']);
    }

    /**
     * Verificar se está autenticado
     */
    public function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['admin_id'])) {
            jsonResponse(true, ['authenticated' => true, 'username' => $_SESSION['admin_username']]);
        } else {
            jsonResponse(false, ['authenticated' => false], null, 401);
        }
    }
}
