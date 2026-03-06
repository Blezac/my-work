<?php
/**
 * Royal Ruby Exotic Place — SettingsModel
 * Manages the key-value settings table
 */
class SettingsModel extends Model
{
    /** @var string */
    protected $table = 'settings';

    /**
     * Return all settings as key => value array
     * @return array
     */
    public function getAllKeyed()
    {
        $rows   = $this->query("SELECT key_name, key_value FROM `settings`")->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['key_name']] = $row['key_value'];
        }
        return $result;
    }

    /**
     * Upsert a single setting
     * @param string $key
     * @param string $value
     * @return void
     */
    public function set($key, $value)
    {
        $this->query(
            "INSERT INTO `settings` (key_name, key_value)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE key_value = VALUES(key_value)",
            [$key, $value]
        );
    }

    /**
     * Return settings for a specific group
     * @param  string $group
     * @return array
     */
    public function getByGroup($group)
    {
        return $this->query(
            "SELECT key_name, key_value FROM `settings` WHERE group_name = ?",
            [$group]
        )->fetchAll();
    }
}