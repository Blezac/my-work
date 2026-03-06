<?php require APP_ROOT . '/app/views/admin/layout-top.php'; ?>
<div class="admin-card"><div class="admin-card-header"><?php echo (isset($service)&&$service)?'Edit Service':'Add New Service'; ?></div>
<div class="admin-card-body">
  <?php $action=(isset($service)&&$service)?APP_URL.'/admin/services/'.$service['id']. '/update':APP_URL.'/admin/services/store'; ?>
  <form method="POST" action="<?php echo $action; ?>" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(isset($csrf)?$csrf:'',ENT_QUOTES,'UTF-8'); ?>"/>
    <div class="row g-3">
      <div class="col-md-8"><label class="form-label">Title *</label>
        <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars(isset($service['title'])?$service['title']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-4"><label class="form-label">Bootstrap Icon</label>
        <input type="text" name="icon" class="form-control" placeholder="bi-briefcase" value="<?php echo htmlspecialchars(isset($service['icon'])?$service['icon']:'bi-briefcase',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-6"><label class="form-label">Slug (auto if blank)</label>
        <input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars(isset($service['slug'])?$service['slug']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-3"><label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="<?php echo isset($service['sort_order'])?(int)$service['sort_order']:0; ?>"/></div>
      <div class="col-md-3 d-flex align-items-end gap-3 pb-1">
        <div class="form-check"><input class="form-check-input" type="checkbox" name="is_featured" value="1" <?php echo (isset($service['is_featured'])&&$service['is_featured'])?'checked':''; ?>><label class="form-check-label" style="font-size:.85rem;">Featured</label></div>
        <?php if(isset($service)&&$service): ?><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" <?php echo (isset($service['is_active'])&&$service['is_active'])?'checked':''; ?>><label class="form-check-label" style="font-size:.85rem;">Active</label></div><?php endif; ?>
      </div>
      <div class="col-12"><label class="form-label">Short Description *</label>
        <textarea name="short_desc" class="form-control" rows="2" required><?php echo htmlspecialchars(isset($service['short_desc'])?$service['short_desc']:'',ENT_QUOTES,'UTF-8'); ?></textarea></div>
      <div class="col-12"><label class="form-label">Full Description *</label>
        <textarea name="full_desc" class="form-control" rows="5" required><?php echo htmlspecialchars(isset($service['full_desc'])?$service['full_desc']:'',ENT_QUOTES,'UTF-8'); ?></textarea></div>
      <div class="col-12"><label class="form-label">Benefits (JSON e.g. ["Benefit 1","Benefit 2"])</label>
        <textarea name="benefits" class="form-control" rows="2" placeholder='["Benefit 1","Benefit 2"]'><?php echo htmlspecialchars(isset($service['benefits'])?$service['benefits']:'',ENT_QUOTES,'UTF-8'); ?></textarea></div>
      <div class="col-12"><label class="form-label">Service Image (max 5MB)</label>
        <input type="file" name="image" class="form-control" accept="image/*"/></div>
      <div class="col-12 d-flex gap-3 mt-2">
        <button type="submit" class="btn-ruby">Save Service <i class="bi bi-check-circle ms-2"></i></button>
        <a href="<?php echo APP_URL; ?>/admin/services" class="btn-outline-ruby">Cancel</a>
      </div>
    </div>
  </form>
</div></div>
<?php require APP_ROOT . '/app/views/admin/layout-bottom.php'; ?>
