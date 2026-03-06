<?php require APP_ROOT . '/app/views/admin/layout-top.php'; ?>
<div class="row g-4">
  <div class="col-lg-4">
    <div class="admin-card">
      <div class="admin-card-header">Upload Image</div>
      <div class="admin-card-body">
        <form method="POST" action="<?php echo APP_URL; ?>/admin/gallery/store" enctype="multipart/form-data">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(isset($csrf)?$csrf:'',ENT_QUOTES,'UTF-8'); ?>"/>
          <div class="mb-3"><label class="form-label">Title</label><input type="text" name="title" class="form-control" placeholder="Image title"/></div>
          <div class="mb-3"><label class="form-label">Category</label>
            <select name="category" class="form-select">
              <option value="general">General</option>
              <option value="hospitality">Hospitality</option>
              <option value="events">Events</option>
              <option value="office">Office</option>
            </select></div>
          <div class="mb-3"><label class="form-label">Image *</label><input type="file" name="image" class="form-control" accept="image/*" required/></div>
          <button type="submit" class="btn-ruby w-100" style="justify-content:center;">Upload <i class="bi bi-cloud-upload ms-2"></i></button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="admin-card">
      <div class="admin-card-header">Gallery Images</div>
      <div class="admin-card-body">
        <?php if(empty($gallery)): ?><p style="color:var(--mid-grey);font-size:.88rem;">No images uploaded yet.</p>
        <?php else: ?>
        <div class="row g-3">
          <?php foreach($gallery as $g): ?>
          <div class="col-md-4">
            <div style="border-radius:8px;overflow:hidden;position:relative;border:1px solid #F0EAEA;">
              <img src="<?php echo APP_URL; ?>/storage/<?php echo htmlspecialchars($g['image']); ?>" alt="<?php echo htmlspecialchars($g['title']); ?>" style="width:100%;height:140px;object-fit:cover;"/>
              <div style="padding:.6rem;">
                <div style="font-size:.8rem;font-weight:600;"><?php echo htmlspecialchars($g['title']); ?></div>
                <div style="font-size:.72rem;color:var(--mid-grey);"><?php echo htmlspecialchars($g['category']); ?></div>
                <a href="<?php echo APP_URL; ?>/admin/gallery/<?php echo $g['id']; ?>/delete" class="btn btn-sm btn-outline-danger mt-1 w-100" data-confirm="Delete this image?"><i class="bi bi-trash me-1"></i>Delete</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php require APP_ROOT . '/app/views/admin/layout-bottom.php'; ?>
