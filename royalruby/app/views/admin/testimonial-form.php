<?php require APP_ROOT . '/app/views/admin/layout-top.php'; ?>
<div class="admin-card"><div class="admin-card-header"><?php echo (isset($testimonial)&&$testimonial)?'Edit Testimonial':'Add Testimonial'; ?></div>
<div class="admin-card-body">
  <?php $action=(isset($testimonial)&&$testimonial)?APP_URL.'/admin/testimonials/'.$testimonial['id']. '/update':APP_URL.'/admin/testimonials/store'; ?>
  <form method="POST" action="<?php echo $action; ?>">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(isset($csrf)?$csrf:'',ENT_QUOTES,'UTF-8'); ?>"/>
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Client Name *</label>
        <input type="text" name="client_name" class="form-control" required value="<?php echo htmlspecialchars(isset($testimonial['client_name'])?$testimonial['client_name']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-6"><label class="form-label">Job Title</label>
        <input type="text" name="client_title" class="form-control" value="<?php echo htmlspecialchars(isset($testimonial['client_title'])?$testimonial['client_title']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-6"><label class="form-label">Company</label>
        <input type="text" name="company" class="form-control" value="<?php echo htmlspecialchars(isset($testimonial['company'])?$testimonial['company']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-3"><label class="form-label">Rating (1-5)</label>
        <select name="rating" class="form-select">
          <?php for($r=5;$r>=1;$r--): ?><option value="<?php echo $r; ?>" <?php echo (isset($testimonial['rating'])&&(int)$testimonial['rating']==$r)?'selected':''; ?>><?php echo $r; ?> Stars</option><?php endfor; ?>
        </select></div>
      <div class="col-md-3"><label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="<?php echo isset($testimonial['sort_order'])?(int)$testimonial['sort_order']:0; ?>"/></div>
      <div class="col-12"><label class="form-label">Review *</label>
        <textarea name="review" class="form-control" rows="4" required><?php echo htmlspecialchars(isset($testimonial['review'])?$testimonial['review']:'',ENT_QUOTES,'UTF-8'); ?></textarea></div>
      <div class="col-12 d-flex gap-3">
        <div class="form-check"><input class="form-check-input" type="checkbox" name="is_featured" value="1" <?php echo (isset($testimonial['is_featured'])&&$testimonial['is_featured'])?'checked':''; ?>><label class="form-check-label" style="font-size:.85rem;">Featured on homepage</label></div>
        <?php if(isset($testimonial)&&$testimonial): ?><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" <?php echo (isset($testimonial['is_active'])&&$testimonial['is_active'])?'checked':''; ?>><label class="form-check-label" style="font-size:.85rem;">Active</label></div><?php endif; ?>
      </div>
      <div class="col-12 d-flex gap-3 mt-2">
        <button type="submit" class="btn-ruby">Save Testimonial <i class="bi bi-check-circle ms-2"></i></button>
        <a href="<?php echo APP_URL; ?>/admin/testimonials" class="btn-outline-ruby">Cancel</a>
      </div>
    </div>
  </form>
</div></div>
<?php require APP_ROOT . '/app/views/admin/layout-bottom.php'; ?>
