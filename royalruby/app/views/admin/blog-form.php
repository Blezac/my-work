<?php require APP_ROOT . '/app/views/admin/layout-top.php'; ?>
<div class="admin-card"><div class="admin-card-header"><?php echo (isset($post)&&$post)?'Edit Post':'New Blog Post'; ?></div>
<div class="admin-card-body">
  <?php $action=(isset($post)&&$post)?APP_URL.'/admin/blog/'.$post['id']. '/update':APP_URL.'/admin/blog/store'; ?>
  <form method="POST" action="<?php echo $action; ?>" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(isset($csrf)?$csrf:'',ENT_QUOTES,'UTF-8'); ?>"/>
    <div class="row g-3">
      <div class="col-md-9"><label class="form-label">Title *</label>
        <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars(isset($post['title'])?$post['title']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-3"><label class="form-label">Category</label>
        <input type="text" name="category" class="form-control" placeholder="Business" value="<?php echo htmlspecialchars(isset($post['category'])?$post['category']:'Business',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-6"><label class="form-label">Slug (auto if blank)</label>
        <input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars(isset($post['slug'])?$post['slug']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-3"><label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="draft" <?php echo (isset($post['status'])&&$post['status']==='draft')?'selected':''; ?>>Draft</option>
          <option value="published" <?php echo (isset($post['status'])&&$post['status']==='published')?'selected':''; ?>>Published</option>
        </select></div>
      <div class="col-md-3"><label class="form-label">Tags</label>
        <input type="text" name="tags" class="form-control" placeholder="tag1,tag2" value="<?php echo htmlspecialchars(isset($post['tags'])?$post['tags']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-12"><label class="form-label">Excerpt *</label>
        <textarea name="excerpt" class="form-control" rows="2" required><?php echo htmlspecialchars(isset($post['excerpt'])?$post['excerpt']:'',ENT_QUOTES,'UTF-8'); ?></textarea></div>
      <div class="col-12"><label class="form-label">Content *</label>
        <textarea name="content" class="form-control" rows="10" required><?php echo htmlspecialchars(isset($post['content'])?$post['content']:'',ENT_QUOTES,'UTF-8'); ?></textarea></div>
      <div class="col-12"><label class="form-label">Featured Image (max 5MB)</label>
        <input type="file" name="featured_image" class="form-control" accept="image/*"/></div>
      <div class="col-12 d-flex gap-3 mt-2">
        <button type="submit" class="btn-ruby">Save Post <i class="bi bi-check-circle ms-2"></i></button>
        <a href="<?php echo APP_URL; ?>/admin/blog" class="btn-outline-ruby">Cancel</a>
      </div>
    </div>
  </form>
</div></div>
<?php require APP_ROOT . '/app/views/admin/layout-bottom.php'; ?>
