<?php
require_once __DIR__ . '/../Database.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function register($data) {
        if (empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'Email and password required'];
        }

        $existing = $this->db->selectOne("SELECT id FROM users WHERE email = ?", [$data['email']]);
        if ($existing) {
            return ['success' => false, 'message' => 'Email already exists'];
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $userId = $this->db->insert(
            "INSERT INTO users (email, password, first_name, last_name, company, email_verified) VALUES (?, ?, ?, ?, ?, TRUE)",
            [$data['email'], $hashedPassword, $data['first_name'] ?? '', $data['last_name'] ?? '', $data['company'] ?? '']
        );

        return $userId ? ['success' => true, 'user_id' => $userId] : ['success' => false, 'message' => 'Registration failed'];
    }

    public function login($email, $password) {
        $user = $this->db->selectOne("SELECT * FROM users WHERE email = ?", [$email]);

        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        if (!$user['is_active']) {
            return ['success' => false, 'message' => 'Account inactive'];
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['logged_in'] = true;

        $this->db->update("UPDATE users SET last_login = NOW() WHERE id = ?", [$user['id']]);

        return ['success' => true, 'user' => [
            'id' => $user['id'],
            'email' => $user['email'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'role' => $user['role']
        ]];
    }

    public function logout() {
        session_destroy();
        return ['success' => true];
    }

    public function check() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function user() {
        if (!$this->check()) return null;
        return $this->db->selectOne("SELECT id, email, first_name, last_name, company, role FROM users WHERE id = ?", [$_SESSION['user_id']]);
    }

    public function requireAuth() {
        if (!$this->check()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
    }
}
