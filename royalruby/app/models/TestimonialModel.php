<?php
/**
 * Royal Ruby Exotic Place — TestimonialModel
 */
class TestimonialModel extends Model
{
    /** @var string */
    protected $table = 'testimonials';

    /**
     * All active testimonials ordered by sort_order
     * @return array
     */
    public function getActive()
    {
        return $this->query(
            "SELECT * FROM `testimonials`
             WHERE is_active = 1 AND deleted_at IS NULL
             ORDER BY sort_order ASC"
        )->fetchAll();
    }

    /**
     * Featured testimonials for homepage
     * @return array
     */
    public function getFeatured()
    {
        return $this->query(
            "SELECT * FROM `testimonials`
             WHERE is_featured = 1 AND is_active = 1 AND deleted_at IS NULL
             ORDER BY sort_order ASC"
        )->fetchAll();
    }

    /**
     * All testimonials including inactive (admin)
     * @return array
     */
    public function getAll()
    {
        return $this->query(
            "SELECT * FROM `testimonials` WHERE deleted_at IS NULL ORDER BY sort_order ASC"
        )->fetchAll();
    }
}