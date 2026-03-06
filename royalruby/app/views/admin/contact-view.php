<?php require APP_ROOT . '/app/views/admin/layout-top.php'; ?>
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="admin-card">
      <div class="admin-card-header">
        <span>Message from <?php echo htmlspecialchars($contact['name']); ?></span>
        <a href="<?php echo APP_URL; ?>/admin/contacts" class="btn-outline-ruby" style="padding:.3rem .85rem;font-size:.74rem;"><i class="bi bi-arrow-left me-1"></i>Back</a>
      </div>
      <div class="admin-card-body">
        <div class="row g-3 mb-4">
          <?php $fields=array('Name'=>$contact['name'],'Email'=>$contact['email'],'Phone'=>isset($contact['phone'])?$contact['phone']:'-','Subject'=>isset($contact['subject'])?$contact['subject']:'-','Date'=>date('F j, Y g:i A',strtotime($contact['created_at'])));
          foreach($fields as $label=>$val): ?>
          <div class="col-md-6">
            <div style="font-size:.74rem;text-transform:uppercase;letter-spacing:.5px;color:var(--mid-grey);font-weight:700;margin-bottom:.25rem;"><?php echo $label; ?></div>
            <div style="font-size:.92rem;color:var(--charcoal);"><?php echo htmlspecialchars($val); ?></div>
          </div>
          <?php endforeach; ?>
        </div>
        <div style="font-size:.74rem;text-transform:uppercase;letter-spacing:.5px;color:var(--mid-grey);font-weight:700;margin-bottom:.5rem;">Message</div>
        <div style="background:var(--off-white);border-radius:8px;padding:1.25rem;font-size:.92rem;line-height:1.78;color:var(--charcoal);white-space:pre-wrap;"><?php echo htmlspecialchars($contact['message']); ?></div>
        <div class="d-flex gap-3 mt-4">
          <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>" class="btn-ruby" style="padding:.5rem 1.2rem;font-size:.82rem;"><i class="bi bi-envelope me-2"></i>Reply via Email</a>
          <a href="<?php echo APP_URL; ?>/admin/contacts/<?php echo $contact['id']; ?>/delete" class="btn-outline-ruby" style="padding:.5rem 1.2rem;font-size:.82rem;" data-confirm="Delete this message?"><i class="bi bi-trash me-2"></i>Delete</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require APP_ROOT . '/app/views/admin/layout-bottom.php'; ?>
