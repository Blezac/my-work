<?php require APP_ROOT . '/app/views/admin/layout-top.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0" style="font-weight:700;">Testimonials</h5>
  <a href="<?php echo APP_URL; ?>/admin/testimonials/create" class="btn-ruby" style="padding:.5rem 1.2rem;font-size:.8rem;"><i class="bi bi-plus-circle me-2"></i>Add Testimonial</a>
</div>
<div class="admin-table"><table class="table">
  <thead><tr><th>#</th><th>Client</th><th>Company</th><th>Rating</th><th>Featured</th><th>Actions</th></tr></thead>
  <tbody>
    <?php if(empty($testimonials)): ?><tr><td colspan="6" class="text-center py-4" style="color:var(--mid-grey);">No testimonials yet</td></tr>
    <?php else: foreach($testimonials as $t): ?>
    <tr>
      <td><?php echo (int)$t['id']; ?></td>
      <td><strong><?php echo htmlspecialchars($t['client_name']); ?></strong><br/><small style="color:var(--mid-grey);"><?php echo htmlspecialchars(isset($t['client_title'])?$t['client_title']:''); ?></small></td>
      <td style="font-size:.85rem;"><?php echo htmlspecialchars(isset($t['company'])?$t['company']:''); ?></td>
      <td><?php for($s=0;$s<(int)$t['rating'];$s++) echo '<i class="bi bi-star-fill" style="color:#C9A84C;font-size:.75rem;"></i>'; ?></td>
      <td><?php echo $t['is_featured']?'<span class="badge-gold" style="padding:.2rem .5rem;border-radius:4px;font-size:.68rem;">Yes</span>':'<span class="badge bg-light text-secondary" style="font-size:.68rem;">No</span>'; ?></td>
      <td>
        <a href="<?php echo APP_URL; ?>/admin/testimonials/<?php echo $t['id']; ?>/edit"   class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-pencil"></i></a>
        <a href="<?php echo APP_URL; ?>/admin/testimonials/<?php echo $t['id']; ?>/delete" class="btn btn-sm btn-outline-danger" data-confirm="Delete this testimonial?"><i class="bi bi-trash"></i></a>
      </td>
    </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table></div>
<?php require APP_ROOT . '/app/views/admin/layout-bottom.php'; ?>
