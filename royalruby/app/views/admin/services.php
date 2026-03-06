<?php require APP_ROOT . '/app/views/admin/layout-top.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0" style="font-weight:700;">All Services</h5>
  <a href="<?php echo APP_URL; ?>/admin/services/create" class="btn-ruby" style="padding:.5rem 1.2rem;font-size:.8rem;"><i class="bi bi-plus-circle me-2"></i>Add Service</a>
</div>
<div class="admin-table"><table class="table">
  <thead><tr><th>#</th><th>Title</th><th>Icon</th><th>Featured</th><th>Active</th><th>Actions</th></tr></thead>
  <tbody>
    <?php if(empty($services)): ?><tr><td colspan="6" class="text-center py-4" style="color:var(--mid-grey);">No services yet</td></tr>
    <?php else: foreach($services as $s): ?>
    <tr>
      <td><?php echo (int)$s['id']; ?></td>
      <td><strong><?php echo htmlspecialchars($s['title']); ?></strong><br/><small style="color:var(--mid-grey);"><?php echo htmlspecialchars($s['slug']); ?></small></td>
      <td><i class="bi <?php echo htmlspecialchars($s['icon']); ?>" style="font-size:1.3rem;color:var(--ruby);"></i></td>
      <td><?php echo $s['is_featured']?'<span class="badge-gold" style="padding:.2rem .5rem;border-radius:4px;font-size:.68rem;">Yes</span>':'<span class="badge bg-light text-secondary" style="font-size:.68rem;">No</span>'; ?></td>
      <td><?php echo $s['is_active']?'<span class="badge-ruby" style="padding:.2rem .5rem;border-radius:4px;font-size:.68rem;">Active</span>':'<span class="badge bg-secondary" style="font-size:.68rem;">Off</span>'; ?></td>
      <td>
        <a href="<?php echo APP_URL; ?>/admin/services/<?php echo $s['id']; ?>/edit"   class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-pencil"></i></a>
        <a href="<?php echo APP_URL; ?>/admin/services/<?php echo $s['id']; ?>/delete" class="btn btn-sm btn-outline-danger" data-confirm="Delete this service?"><i class="bi bi-trash"></i></a>
      </td>
    </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table></div>
<?php require APP_ROOT . '/app/views/admin/layout-bottom.php'; ?>
