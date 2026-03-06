<?php require APP_ROOT . '/app/views/admin/layout-top.php'; ?>
<div class="admin-card"><div class="admin-card-header"><i class="bi bi-gear me-2"></i>Site Settings</div>
<div class="admin-card-body">
  <form method="POST" action="<?php echo APP_URL; ?>/admin/settings/update">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(isset($csrf)?$csrf:'',ENT_QUOTES,'UTF-8'); ?>"/>
    <div class="row g-3">
      <div class="col-12"><h6 style="color:var(--ruby);font-size:.8rem;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #F0EAEA;padding-bottom:.5rem;margin-bottom:.5rem;">General</h6></div>
      <div class="col-md-6"><label class="form-label">Site Name</label><input type="text" name="site_name" class="form-control" value="<?php echo htmlspecialchars(isset($settings['site_name'])?$settings['site_name']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-6"><label class="form-label">Tagline</label><input type="text" name="site_tagline" class="form-control" value="<?php echo htmlspecialchars(isset($settings['site_tagline'])?$settings['site_tagline']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-12"><label class="form-label">Site Description (meta)</label><textarea name="site_description" class="form-control" rows="2"><?php echo htmlspecialchars(isset($settings['site_description'])?$settings['site_description']:'',ENT_QUOTES,'UTF-8'); ?></textarea></div>
      <div class="col-12"><h6 style="color:var(--ruby);font-size:.8rem;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #F0EAEA;padding-bottom:.5rem;margin-bottom:.5rem;margin-top:.5rem;">Contact Info</h6></div>
      <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="site_email" class="form-control" value="<?php echo htmlspecialchars(isset($settings['site_email'])?$settings['site_email']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-4"><label class="form-label">Phone</label><input type="text" name="site_phone" class="form-control" value="<?php echo htmlspecialchars(isset($settings['site_phone'])?$settings['site_phone']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-4"><label class="form-label">Address</label><input type="text" name="site_address" class="form-control" value="<?php echo htmlspecialchars(isset($settings['site_address'])?$settings['site_address']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-12"><h6 style="color:var(--ruby);font-size:.8rem;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #F0EAEA;padding-bottom:.5rem;margin-bottom:.5rem;margin-top:.5rem;">Homepage</h6></div>
      <div class="col-12"><label class="form-label">Hero Headline</label><input type="text" name="hero_headline" class="form-control" value="<?php echo htmlspecialchars(isset($settings['hero_headline'])?$settings['hero_headline']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-12"><label class="form-label">Hero Sub-headline</label><input type="text" name="hero_subheadline" class="form-control" value="<?php echo htmlspecialchars(isset($settings['hero_subheadline'])?$settings['hero_subheadline']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-12"><label class="form-label">About Intro</label><textarea name="about_intro" class="form-control" rows="3"><?php echo htmlspecialchars(isset($settings['about_intro'])?$settings['about_intro']:'',ENT_QUOTES,'UTF-8'); ?></textarea></div>
      <div class="col-12"><h6 style="color:var(--ruby);font-size:.8rem;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #F0EAEA;padding-bottom:.5rem;margin-bottom:.5rem;margin-top:.5rem;">Social Media</h6></div>
      <div class="col-md-3"><label class="form-label">Facebook URL</label><input type="url" name="facebook_url"  class="form-control" value="<?php echo htmlspecialchars(isset($settings['facebook_url'])?$settings['facebook_url']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-3"><label class="form-label">Instagram URL</label><input type="url" name="instagram_url" class="form-control" value="<?php echo htmlspecialchars(isset($settings['instagram_url'])?$settings['instagram_url']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-3"><label class="form-label">LinkedIn URL</label><input type="url" name="linkedin_url"  class="form-control" value="<?php echo htmlspecialchars(isset($settings['linkedin_url'])?$settings['linkedin_url']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-md-3"><label class="form-label">Twitter/X URL</label><input type="url" name="twitter_url"   class="form-control" value="<?php echo htmlspecialchars(isset($settings['twitter_url'])?$settings['twitter_url']:'',ENT_QUOTES,'UTF-8'); ?>"/></div>
      <div class="col-12 mt-2"><button type="submit" class="btn-ruby">Save Settings <i class="bi bi-check-circle ms-2"></i></button></div>
    </div>
  </form>
</div></div>
<?php require APP_ROOT . '/app/views/admin/layout-bottom.php'; ?>
