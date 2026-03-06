<?php
/**
 * Royal Ruby Exotic Place — ServiceModel
 */
class ServiceModel extends Model
{
    /** @var string */
    protected $table = 'services';

    /**
     * All active services ordered by sort_order
     * @return array
     */
    public function getActive()
    {
        return $this->query(
            "SELECT * FROM `services`
             WHERE is_active = 1 AND deleted_at IS NULL
             ORDER BY sort_order ASC"
        )->fetchAll();
    }

    /**
     * Featured services for homepage
     * @param  int   $limit
     * @return array
     */
    public function getFeatured($limit = 6)
    {
        return $this->query(
            "SELECT * FROM `services`
             WHERE is_featured = 1 AND is_active = 1 AND deleted_at IS NULL
             ORDER BY sort_order ASC
             LIMIT ?",
            [(int)$limit]
        )->fetchAll();
    }

    /**
     * Find service by slug
     * @param  string     $slug
     * @return array|null
     */
    public function getBySlug($slug)
    {
        $row = $this->query(
            "SELECT * FROM `services`
             WHERE slug = ? AND is_active = 1 AND deleted_at IS NULL
             LIMIT 1",
            [(string)$slug]
        )->fetch();
        return ($row !== false) ? $row : null;
    }

    /**
     * All services including inactive (admin)
     * @return array
     */
    public function getAll()
    {
        return $this->query(
            "SELECT * FROM `services` WHERE deleted_at IS NULL ORDER BY sort_order ASC"
        )->fetchAll();
    }

    /**
     * Check if slug exists (optionally excluding one ID)
     * @param  string $slug
     * @param  int    $excludeId
     * @return bool
     */
    public function slugExists($slug, $excludeId = 0)
    {
        $row = $this->query(
            "SELECT id FROM `services` WHERE slug = ? AND id != ?",
            [(string)$slug, (int)$excludeId]
        )->fetch();
        return ($row !== false);
    }
}