<?php require APP_ROOT . '/app/views/admin/layout-top.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="mb-0" style="font-weight:700;">Blog Posts</h5>
  <a href="<?php echo APP_URL; ?>/admin/blog/create" class="btn-ruby" style="padding:.5rem 1.2rem;font-size:.8rem;"><i class="bi bi-plus-circle me-2"></i>New Post</a>
</div>
<div class="admin-table"><table class="table">
  <thead><tr><th>#</th><th>Title</th><th>Category</th><th>Status</th><th>Views</th><th>Date</th><th>Actions</th></tr></thead>
  <tbody>
    <?php if(empty($posts)): ?><tr><td colspan="7" class="text-center py-4" style="color:var(--mid-grey);">No posts yet</td></tr>
    <?php else: foreach($posts as $p): ?>
    <tr>
      <td><?php echo (int)$p['id']; ?></td>
      <td><a href="<?php echo APP_URL; ?>/blog/<?php echo htmlspecialchars($p['slug']); ?>" target="_blank" style="color:var(--charcoal);font-weight:600;"><?php echo htmlspecialchars(substr($p['title'],0,45)); ?>...</a></td>
      <td style="font-size:.83rem;"><?php echo htmlspecialchars($p['category']); ?></td>
      <td><span class="<?php echo $p['status']==='published'?'badge-ruby':'badge bg-secondary'; ?>" style="<?php echo $p['status']==='published'?'padding:.2rem .45rem;border-radius:4px;font-size:.67rem;':'font-size:.67rem;'; ?>"><?php echo ucfirst($p['status']); ?></span></td>
      <td style="font-size:.83rem;"><?php echo number_format($p['views']); ?></td>
      <td style="font-size:.78rem;color:var(--mid-grey);"><?php echo date('M j, Y',strtotime($p['created_at'])); ?></td>
      <td>
        <a href="<?php echo APP_URL; ?>/admin/blog/<?php echo $p['id']; ?>/edit"   class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-pencil"></i></a>
        <a href="<?php echo APP_URL; ?>/admin/blog/<?php echo $p['id']; ?>/delete" class="btn btn-sm btn-outline-danger" data-confirm="Delete this post?"><i class="bi bi-trash"></i></a>
      </td>
    </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table></div>
<?php require APP_ROOT . '/app/views/admin/layout-bottom.php'; ?>
