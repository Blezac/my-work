<?php
require_once __DIR__ . '/../Database.php';

class Campaign {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll($userId, $filters = []) {
        $sql = "SELECT * FROM campaigns WHERE user_id = ?";
        $params = [$userId];

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        $sql .= " ORDER BY created_at DESC";
        return $this->db->select($sql, $params);
    }

    public function getById($id, $userId) {
        return $this->db->selectOne("SELECT * FROM campaigns WHERE id = ? AND user_id = ?", [$id, $userId]);
    }

    public function create($data, $userId) {
        return $this->db->insert(
            "INSERT INTO campaigns (user_id, name, subject, from_name, from_email, html_content, status) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $userId,
                $data['name'],
                $data['subject'],
                $data['from_name'],
                $data['from_email'],
                $data['html_content'],
                $data['status'] ?? 'draft'
            ]
        );
    }

    public function update($id, $data, $userId) {
        return $this->db->update(
            "UPDATE campaigns SET name = ?, subject = ?, from_name = ?, from_email = ?, html_content = ?, status = ? WHERE id = ? AND user_id = ?",
            [
                $data['name'],
                $data['subject'],
                $data['from_name'],
                $data['from_email'],
                $data['html_content'],
                $data['status'] ?? 'draft',
                $id,
                $userId
            ]
        );
    }

    public function delete($id, $userId) {
        return $this->db->delete("DELETE FROM campaigns WHERE id = ? AND user_id = ?", [$id, $userId]);
    }

    public function getStats($id, $userId) {
        $campaign = $this->getById($id, $userId);
        if (!$campaign) return null;

        return [
            'total_sent' => $campaign['total_sent'],
            'total_delivered' => $campaign['total_delivered'],
            'total_opens' => $campaign['total_opens'],
            'unique_opens' => $campaign['unique_opens'],
            'total_clicks' => $campaign['total_clicks'],
            'unique_clicks' => $campaign['unique_clicks'],
            'bounced' => $campaign['bounced'],
            'unsubscribed' => $campaign['unsubscribed'],
            'open_rate' => $campaign['open_rate'],
            'click_rate' => $campaign['click_rate']
        ];
    }
}
