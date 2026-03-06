<?php require APP_ROOT . '/app/views/admin/layout-top.php'; ?>
<div class="row g-3 mb-4">
  <?php
  $cards=array(
    array('bi-briefcase-fill','#8B0000','rgba(139,0,0,.1)',isset($totalServices)?(int)$totalServices:0,'Services'),
    array('bi-newspaper','#C9A84C','rgba(201,168,76,.1)',isset($totalPosts)?(int)$totalPosts:0,'Blog Posts'),
    array('bi-envelope-fill','#dc3545','rgba(220,53,69,.1)',isset($totalMessages)?(int)$totalMessages:0,'Messages'),
    array('bi-send-fill','#198754','rgba(25,135,84,.1)',isset($totalSubscribers)?(int)$totalSubscribers:0,'Subscribers'),
  );
  foreach($cards as $c): ?>
  <div class="col-lg-3 col-md-6">
    <div class="stat-card d-flex align-items-center gap-3">
      <div class="stat-icon" style="background:<?php echo $c[2]; ?>"><i class="bi <?php echo $c[0]; ?>" style="font-size:1.35rem;color:<?php echo $c[1]; ?>"></i></div>
      <div><div class="stat-num"><?php echo $c[3]; ?></div><div class="stat-label"><?php echo $c[4]; ?></div></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<div class="row g-3">
  <div class="col-lg-7">
    <div class="admin-card">
      <div class="admin-card-header"><span><i class="bi bi-envelope me-2 text-ruby"></i>Recent Messages</span><a href="<?php echo APP_URL; ?>/admin/contacts" class="btn-ruby" style="padding:.3rem .85rem;font-size:.74rem;">View All</a></div>
      <div style="overflow-x:auto;">
        <?php if(empty($recentMessages)): ?><p class="text-center py-4" style="color:var(--mid-grey);font-size:.88rem;">No messages yet.</p>
        <?php else: ?><table class="table mb-0"><thead><tr><th>Name</th><th>Subject</th><th>Date</th><th>Status</th></tr></thead><tbody>
          <?php foreach(array_slice($recentMessages,0,6) as $msg): ?>
          <tr>
            <td><a href="<?php echo APP_URL; ?>/admin/contacts/<?php echo (int)$msg['id']; ?>" style="color:var(--charcoal);font-weight:600;"><?php echo htmlspecialchars($msg['name']); ?></a></td>
            <td style="font-size:.82rem;color:var(--mid-grey);"><?php echo htmlspecialchars(substr(isset($msg['subject'])?$msg['subject']:'(no subject)',0,35)); ?></td>
            <td style="font-size:.78rem;color:var(--mid-grey);"><?php echo date('M j',strtotime($msg['created_at'])); ?></td>
            <td><?php echo !$msg['is_read']?'<span class="badge-ruby" style="padding:.2rem .45rem;border-radius:4px;font-size:.67rem;">New</span>':'<span class="badge bg-secondary" style="font-size:.67rem;">Read</span>'; ?></td>
          </tr>
          <?php endforeach; ?></tbody></table><?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="admin-card">
      <div class="admin-card-header"><span><i class="bi bi-newspaper me-2 text-ruby"></i>Recent Posts</span><a href="<?php echo APP_URL; ?>/admin/blog/create" class="btn-ruby" style="padding:.3rem .85rem;font-size:.74rem;">+ New</a></div>
      <div style="overflow-x:auto;">
        <?php if(empty($recentPosts)): ?><p class="text-center py-4" style="color:var(--mid-grey);font-size:.88rem;">No posts yet.</p>
        <?php else: ?><table class="table mb-0"><thead><tr><th>Title</th><th>Status</th><th>Views</th></tr></thead><tbody>
          <?php foreach($recentPosts as $p): ?>
          <tr>
            <td style="font-size:.82rem;"><a href="<?php echo APP_URL; ?>/admin/blog/<?php echo (int)$p['id']; ?>/edit" style="color:var(--charcoal);"><?php echo htmlspecialchars(substr($p['title'],0,30)); ?>...</a></td>
            <td><span class="<?php echo $p['status']==='published'?'badge-ruby':'badge bg-secondary'; ?>" style="<?php echo $p['status']==='published'?'padding:.2rem .45rem;border-radius:4px;font-size:.67rem;':'font-size:.67rem;'; ?>"><?php echo ucfirst($p['status']); ?></span></td>
            <td style="font-size:.82rem;color:var(--mid-grey);"><?php echo number_format($p['views']); ?></td>
          </tr>
          <?php endforeach; ?></tbody></table><?php endif; ?>
      </div>
    </div>
  </div>
</div>
<div class="row g-3 mt-1"><div class="col-12"><div class="admin-card">
  <div class="admin-card-header"><i class="bi bi-lightning-charge me-2 text-gold"></i>Quick Actions</div>
  <div class="admin-card-body"><div class="d-flex flex-wrap gap-3">
    <a href="<?php echo APP_URL; ?>/admin/blog/create"         class="btn-ruby"         style="padding:.5rem 1.2rem;font-size:.8rem;"><i class="bi bi-plus-circle me-2"></i>New Post</a>
    <a href="<?php echo APP_URL; ?>/admin/services/create"     class="btn-ruby"         style="padding:.5rem 1.2rem;font-size:.8rem;"><i class="bi bi-plus-circle me-2"></i>New Service</a>
    <a href="<?php echo APP_URL; ?>/admin/testimonials/create" class="btn-ruby"         style="padding:.5rem 1.2rem;font-size:.8rem;"><i class="bi bi-plus-circle me-2"></i>New Testimonial</a>
    <a href="<?php echo APP_URL; ?>/admin/settings"            class="btn-outline-ruby" style="padding:.5rem 1.2rem;font-size:.8rem;"><i class="bi bi-gear me-2"></i>Settings</a>
    <a href="<?php echo APP_URL; ?>/" target="_blank"          class="btn-outline-ruby" style="padding:.5rem 1.2rem;font-size:.8rem;"><i class="bi bi-eye me-2"></i>View Website</a>
  </div></div>
</div></div></div>
<?php require APP_ROOT . '/app/views/admin/layout-bottom.php'; ?>
