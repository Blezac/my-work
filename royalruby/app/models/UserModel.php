<?php
/**
 * Royal Ruby Exotic Place — UserModel
 */
class UserModel extends Model
{
    /** @var string */
    protected $table = 'users';

    /**
     * Find an active user by email
     * @param  string     $email
     * @return array|null
     */
    public function findByEmail($email)
    {
        $row = $this->query(
            "SELECT * FROM `users` WHERE email = ? AND is_active = 1 LIMIT 1",
            [(string)$email]
        )->fetch();
        return ($row !== false) ? $row : null;
    }

    /**
     * Verify a plain-text password against a stored hash
     * @param  string $plain
     * @param  string $hash
     * @return bool
     */
    public function verifyPassword($plain, $hash)
    {
        return password_verify((string)$plain, (string)$hash);
    }

    /**
     * Record last login timestamp
     * @param  int  $id
     * @return void
     */
    public function updateLastLogin($id)
    {
        $this->query("UPDATE `users` SET last_login = NOW() WHERE id = ?", [(int)$id]);
    }

    /**
     * Change a user's password
     * @param  int    $id
     * @param  string $plain
     * @return void
     */
    public function changePassword($id, $plain)
    {
        $hash = password_hash((string)$plain, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->query("UPDATE `users` SET password = ? WHERE id = ?", [$hash, (int)$id]);
    }
}