<?php
/**
 * Royal Ruby Exotic Place — GalleryModel
 */
class GalleryModel extends Model
{
    /** @var string */
    protected $table = 'gallery';

    /**
     * Active gallery items, optionally filtered by category
     * @param  string $category  leave empty for all
     * @return array
     */
    public function getActive($category = '')
    {
        if ($category !== '') {
            return $this->query(
                "SELECT * FROM `gallery`
                 WHERE is_active = 1 AND category = ? AND deleted_at IS NULL
                 ORDER BY sort_order ASC",
                [(string)$category]
            )->fetchAll();
        }
        return $this->query(
            "SELECT * FROM `gallery`
             WHERE is_active = 1 AND deleted_at IS NULL
             ORDER BY sort_order ASC"
        )->fetchAll();
    }

    /**
     * All items including inactive (admin)
     * @return array
     */
    public function getAll()
    {
        return $this->query(
            "SELECT * FROM `gallery` WHERE deleted_at IS NULL ORDER BY sort_order ASC"
        )->fetchAll();
    }
}