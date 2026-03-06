<?php
/**
 * Royal Ruby Exotic Place
 * Base Model — PDO singleton, prepared statements only
 */
class Model
{
    /** @var PDO|null */
    protected static $pdo = null;

    /** @var string */
    protected $table = '';

    /** @var string */
    protected $primaryKey = 'id';

    public function __construct()
    {
        if (self::$pdo === null) {
            self::$pdo = $this->createConnection();
        }
    }

    /** @return PDO */
    private function createConnection()
    {
        $dsn     = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            return new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (defined('APP_DEBUG') && APP_DEBUG) {
                die('DB Error: ' . $e->getMessage());
            }
            die('A database error occurred. Please try again later.');
        }
    }

    /** @return PDO */
    protected function getDb()
    {
        return self::$pdo;
    }

    /**
     * Execute a prepared statement
     * @param  string $sql
     * @param  array  $params
     * @return PDOStatement
     */
    protected function query($sql, array $params = [])
    {
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Find row by primary key
     * @param  int        $id
     * @return array|null
     */
    public function findById($id)
    {
        $stmt = $this->query(
            "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ? AND `deleted_at` IS NULL LIMIT 1",
            [(int)$id]
        );
        $row = $stmt->fetch();
        return ($row !== false) ? $row : null;
    }

    /**
     * Find row by any column
     * @param  string     $column
     * @param  mixed      $value
     * @return array|null
     */
    public function findBy($column, $value)
    {
        $stmt = $this->query(
            "SELECT * FROM `{$this->table}` WHERE `{$column}` = ? AND `deleted_at` IS NULL LIMIT 1",
            [$value]
        );
        $row = $stmt->fetch();
        return ($row !== false) ? $row : null;
    }

    /**
     * Insert a row and return last insert ID
     * @param  array $data
     * @return int
     */
    public function insert(array $data)
    {
        $cols = implode(', ', array_map(function ($c) {
            return "`{$c}`";
        }, array_keys($data)));
        $ph = implode(', ', array_fill(0, count($data), '?'));
        $this->query("INSERT INTO `{$this->table}` ({$cols}) VALUES ({$ph})", array_values($data));
        return (int)self::$pdo->lastInsertId();
    }

    /**
     * Update row by primary key
     * @param  int   $id
     * @param  array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $sets   = implode(', ', array_map(function ($c) {
            return "`{$c}` = ?";
        }, array_keys($data)));
        $params   = array_values($data);
        $params[] = (int)$id;
        $stmt = $this->query(
            "UPDATE `{$this->table}` SET {$sets} WHERE `{$this->primaryKey}` = ?",
            $params
        );
        return $stmt->rowCount() > 0;
    }

    /**
     * Soft-delete (sets deleted_at)
     * @param  int  $id
     * @return bool
     */
    public function softDelete($id)
    {
        $stmt = $this->query(
            "UPDATE `{$this->table}` SET `deleted_at` = NOW() WHERE `{$this->primaryKey}` = ?",
            [(int)$id]
        );
        return $stmt->rowCount() > 0;
    }

    /**
     * Count rows with optional conditions
     * @param  array $conditions
     * @return int
     */
    public function count(array $conditions = [])
    {
        $sql    = "SELECT COUNT(*) FROM `{$this->table}` WHERE `deleted_at` IS NULL";
        $params = [];
        foreach ($conditions as $col => $val) {
            $sql     .= " AND `{$col}` = ?";
            $params[] = $val;
        }
        return (int)$this->query($sql, $params)->fetchColumn();
    }
}