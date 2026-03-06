<?php require APP_ROOT . '/app/views/admin/layout-top.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0" style="font-weight:700;">Newsletter Subscribers</h5>
  <span class="badge-ruby" style="padding:.4rem .85rem;border-radius:6px;font-size:.8rem;"><?php echo count($subscribers); ?> total</span>
</div>
<div class="admin-table"><table class="table">
  <thead><tr><th>#</th><th>Email</th><th>Name</th><th>Date Subscribed</th></tr></thead>
  <tbody>
    <?php if(empty($subscribers)): ?><tr><td colspan="4" class="text-center py-4" style="color:var(--mid-grey);">No subscribers yet</td></tr>
    <?php else: foreach($subscribers as $i=>$s): ?>
    <tr>
      <td><?php echo $i+1; ?></td>
      <td><?php echo htmlspecialchars($s['email']); ?></td>
      <td style="font-size:.85rem;"><?php echo htmlspecialchars(isset($s['name'])?$s['name']: '-'); ?></td>
      <td style="font-size:.78rem;color:var(--mid-grey);"><?php echo date('M j, Y',strtotime($s['subscribed_at'])); ?></td>
    </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table></div>
<?php require APP_ROOT . '/app/views/admin/layout-bottom.php'; ?>
