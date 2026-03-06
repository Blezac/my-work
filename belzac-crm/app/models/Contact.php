<?php
require_once __DIR__ . '/../Database.php';

class Contact {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll($userId, $filters = []) {
        $sql = "SELECT * FROM contacts WHERE user_id = ?";
        $params = [$userId];

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (email LIKE ? OR first_name LIKE ? OR last_name LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $sql .= " ORDER BY created_at DESC";
        
        if (isset($filters['limit'])) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = (int)$filters['limit'];
            $params[] = (int)($filters['offset'] ?? 0);
        }

        return $this->db->select($sql, $params);
    }

    public function getById($id, $userId) {
        return $this->db->selectOne("SELECT * FROM contacts WHERE id = ? AND user_id = ?", [$id, $userId]);
    }

    public function create($data, $userId) {
        return $this->db->insert(
            "INSERT INTO contacts (user_id, email, first_name, last_name, phone, company, city, country, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $userId,
                $data['email'],
                $data['first_name'] ?? '',
                $data['last_name'] ?? '',
                $data['phone'] ?? '',
                $data['company'] ?? '',
                $data['city'] ?? '',
                $data['country'] ?? '',
                $data['status'] ?? 'active'
            ]
        );
    }

    public function update($id, $data, $userId) {
        return $this->db->update(
            "UPDATE contacts SET first_name = ?, last_name = ?, phone = ?, company = ?, city = ?, country = ?, status = ? WHERE id = ? AND user_id = ?",
            [
                $data['first_name'] ?? '',
                $data['last_name'] ?? '',
                $data['phone'] ?? '',
                $data['company'] ?? '',
                $data['city'] ?? '',
                $data['country'] ?? '',
                $data['status'] ?? 'active',
                $id,
                $userId
            ]
        );
    }

    public function delete($id, $userId) {
        return $this->db->delete("DELETE FROM contacts WHERE id = ? AND user_id = ?", [$id, $userId]);
    }

    public function count($userId, $status = null) {
        if ($status) {
            $result = $this->db->selectOne("SELECT COUNT(*) as total FROM contacts WHERE user_id = ? AND status = ?", [$userId, $status]);
        } else {
            $result = $this->db->selectOne("SELECT COUNT(*) as total FROM contacts WHERE user_id = ?", [$userId]);
        }
        return $result['total'] ?? 0;
    }
}
