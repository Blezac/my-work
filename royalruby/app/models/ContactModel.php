<?php
/**
 * Royal Ruby Exotic Place — ContactModel
 */
class ContactModel extends Model
{
    /** @var string */
    protected $table = 'contacts';

    /**
     * Save a contact form submission
     * @param  array $data
     * @return int   inserted ID
     */
    public function saveContact(array $data)
    {
        return $this->insert($data);
    }

    /**
     * All non-deleted messages, newest first
     * @return array
     */
    public function getAll()
    {
        return $this->query(
            "SELECT * FROM `contacts` WHERE deleted_at IS NULL ORDER BY created_at DESC"
        )->fetchAll();
    }

    /**
     * Count unread messages
     * @return int
     */
    public function countUnread()
    {
        return (int)$this->query(
            "SELECT COUNT(*) FROM `contacts` WHERE is_read = 0 AND deleted_at IS NULL"
        )->fetchColumn();
    }

    /**
     * Mark a message as read
     * @param  int  $id
     * @return void
     */
    public function markRead($id)
    {
        $this->query("UPDATE `contacts` SET is_read = 1 WHERE id = ?", [(int)$id]);
    }
}