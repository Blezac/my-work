<?php
/**
 * Royal Ruby Exotic Place — NewsletterModel
 */
class NewsletterModel extends Model
{
    /** @var string */
    protected $table = 'newsletter_subscribers';

    /**
     * Subscribe an email (upsert)
     * @param  string $email
     * @param  string $name
     * @return bool
     */
    public function subscribe($email, $name = '')
    {
        try {
            $token = bin2hex(random_bytes(32));
            $this->query(
                "INSERT INTO `newsletter_subscribers` (email, name, token)
                 VALUES (?, ?, ?)
                 ON DUPLICATE KEY UPDATE is_active = 1",
                [(string)$email, (string)$name, $token]
            );
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * All active subscribers
     * @return array
     */
    public function getAll()
    {
        return $this->query(
            "SELECT * FROM `newsletter_subscribers` WHERE is_active = 1 ORDER BY subscribed_at DESC"
        )->fetchAll();
    }

    /**
     * Count active subscribers
     * @return int
     */
    public function countActive()
    {
        return (int)$this->query(
            "SELECT COUNT(*) FROM `newsletter_subscribers` WHERE is_active = 1"
        )->fetchColumn();
    }
}