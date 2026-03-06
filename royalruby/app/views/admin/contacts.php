<?php require APP_ROOT . '/app/views/admin/layout-top.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0" style="font-weight:700;">Messages</h5>
</div>
<div class="admin-table"><table class="table">
  <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Subject</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
  <tbody>
    <?php if(empty($contacts)): ?><tr><td colspan="7" class="text-center py-4" style="color:var(--mid-grey);">No messages yet</td></tr>
    <?php else: foreach($contacts as $c): ?>
    <tr <?php echo !$c['is_read']?'style="font-weight:600;"':''; ?>>
      <td><?php echo (int)$c['id']; ?></td>
      <td><a href="<?php echo APP_URL; ?>/admin/contacts/<?php echo $c['id']; ?>" style="color:var(--charcoal);"><?php echo htmlspecialchars($c['name']); ?></a></td>
      <td style="font-size:.83rem;"><?php echo htmlspecialchars($c['email']); ?></td>
      <td style="font-size:.83rem;"><?php echo htmlspecialchars(isset($c['subject'])?substr($c['subject'],0,30):'(no subject)'); ?></td>
      <td style="font-size:.78rem;color:var(--mid-grey);"><?php echo date('M j, Y',strtotime($c['created_at'])); ?></td>
      <td><?php echo !$c['is_read']?'<span class="badge-ruby" style="padding:.2rem .45rem;border-radius:4px;font-size:.67rem;">New</span>':'<span class="badge bg-secondary" style="font-size:.67rem;">Read</span>'; ?></td>
      <td>
        <a href="<?php echo APP_URL; ?>/admin/contacts/<?php echo $c['id']; ?>"        class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-eye"></i></a>
        <a href="<?php echo APP_URL; ?>/admin/contacts/<?php echo $c['id']; ?>/delete" class="btn btn-sm btn-outline-danger" data-confirm="Delete this message?"><i class="bi bi-trash"></i></a>
      </td>
    </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table></div>
<?php require APP_ROOT . '/app/views/admin/layout-bottom.php'; ?>
