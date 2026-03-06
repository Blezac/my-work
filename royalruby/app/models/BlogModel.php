<?php
/**
 * Royal Ruby Exotic Place — BlogModel
 */
class BlogModel extends Model
{
    /** @var string */
    protected $table = 'blog_posts';

    /**
     * Published posts, newest first
     * @param  int   $limit
     * @param  int   $offset
     * @return array
     */
    public function getPublished($limit = 10, $offset = 0)
    {
        return $this->query(
            "SELECT * FROM `blog_posts`
             WHERE status = 'published' AND deleted_at IS NULL
             ORDER BY published_at DESC
             LIMIT ? OFFSET ?",
            [(int)$limit, (int)$offset]
        )->fetchAll();
    }

    /**
     * Find published post by slug and increment views
     * @param  string     $slug
     * @return array|null
     */
    public function getBySlug($slug)
    {
        $row = $this->query(
            "SELECT * FROM `blog_posts`
             WHERE slug = ? AND status = 'published' AND deleted_at IS NULL
             LIMIT 1",
            [(string)$slug]
        )->fetch();
        if ($row !== false) {
            $this->query("UPDATE `blog_posts` SET views = views + 1 WHERE id = ?", [$row['id']]);
            return $row;
        }
        return null;
    }

    /**
     * Count published posts
     * @return int
     */
    public function countPublished()
    {
        return (int)$this->query(
            "SELECT COUNT(*) FROM `blog_posts` WHERE status = 'published' AND deleted_at IS NULL"
        )->fetchColumn();
    }

    /**
     * All posts with author name (admin)
     * @return array
     */
    public function getAll()
    {
        return $this->query(
            "SELECT b.*, u.name AS author
             FROM `blog_posts` b
             LEFT JOIN `users` u ON b.user_id = u.id
             WHERE b.deleted_at IS NULL
             ORDER BY b.created_at DESC"
        )->fetchAll();
    }

    /**
     * @param  string $slug
     * @param  int    $excludeId
     * @return bool
     */
    public function slugExists($slug, $excludeId = 0)
    {
        $row = $this->query(
            "SELECT id FROM `blog_posts` WHERE slug = ? AND id != ?",
            [(string)$slug, (int)$excludeId]
        )->fetch();
        return ($row !== false);
    }
}