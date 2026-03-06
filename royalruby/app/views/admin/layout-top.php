<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title><?php echo htmlspecialchars(isset($pageTitle)?$pageTitle:'Admin',ENT_QUOTES,'UTF-8'); ?> | Royal Ruby</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"/>
  <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/main.css"/>
</head>
<body>
<div class="admin-wrapper">
  <aside class="admin-sidebar">
    <div class="sidebar-brand"><span>Royal Ruby</span><small>Admin Panel</small></div>
    <nav style="padding:.5rem 0;">
      <?php
      $cur = $_SERVER['REQUEST_URI'];
      function navLink($url,$icon,$label,$badge=0){
        global $cur;
        $p = parse_url($url,PHP_URL_PATH);
        $active = ($p && strpos($cur,$p)!==false)?'active':'';
        echo '<a href="'.$url.'" class="admin-nav-link '.$active.'">';
        echo '<i class="bi '.$icon.'"></i>'.htmlspecialchars($label);
        if($badge>0) echo '<span style="margin-left:auto;background:var(--ruby);color:#fff;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:.62rem;font-weight:700;">'.$badge.'</span>';
        echo '</a>';
      }
      $unread = isset($unreadMessages)?(int)$unreadMessages:0;
      ?>
      <div class="admin-nav-section">Main</div>
      <?php navLink(APP_URL.'/admin/dashboard','bi-grid-1x2-fill','Dashboard'); ?>
      <div class="admin-nav-section">Content</div>
      <?php navLink(APP_URL.'/admin/services','bi-briefcase-fill','Services'); ?>
      <?php navLink(APP_URL.'/admin/blog','bi-newspaper','Blog Posts'); ?>
      <?php navLink(APP_URL.'/admin/testimonials','bi-chat-quote-fill','Testimonials'); ?>
      <?php navLink(APP_URL.'/admin/gallery','bi-images','Gallery'); ?>
      <div class="admin-nav-section">Inbox</div>
      <?php navLink(APP_URL.'/admin/contacts','bi-envelope-fill','Messages',$unread); ?>
      <?php navLink(APP_URL.'/admin/newsletter','bi-send-fill','Newsletter'); ?>
      <div class="admin-nav-section">System</div>
      <?php navLink(APP_URL.'/admin/settings','bi-gear-fill','Settings'); ?>
      <a href="<?php echo APP_URL; ?>/" target="_blank" class="admin-nav-link"><i class="bi bi-box-arrow-up-right"></i>View Website</a>
      <a href="<?php echo APP_URL; ?>/admin/logout" class="admin-nav-link" style="color:rgba(255,90,90,.75);"><i class="bi bi-box-arrow-left"></i>Logout</a>
    </nav>
  </aside>
  <div class="admin-main">
    <div class="admin-topbar">
      <div>
        <div style="font-weight:700;font-size:.92rem;"><?php echo htmlspecialchars(isset($pageTitle)?$pageTitle:'',ENT_QUOTES,'UTF-8'); ?></div>
        <small style="color:var(--mid-grey);font-size:.74rem;"><?php echo date('l, F j, Y'); ?></small>
      </div>
      <div class="d-flex align-items-center gap-3">
        <?php if(isset($unreadMessages)&&(int)$unreadMessages>0): ?><a href="<?php echo APP_URL; ?>/admin/contacts" style="color:var(--ruby);font-size:.84rem;"><i class="bi bi-envelope-fill me-1"></i><?php echo (int)$unreadMessages; ?> unread</a><?php endif; ?>
        <span style="font-size:.84rem;"><i class="bi bi-person-circle me-1" style="color:var(--ruby);"></i><?php echo htmlspecialchars(isset($adminUser)?$adminUser:'Admin',ENT_QUOTES,'UTF-8'); ?></span>
      </div>
    </div>
    <div class="px-3 pt-3">
      <?php if(isset($_GET['saved'])): ?><div class="alert alert-success alert-dismissible fade show py-2" style="font-size:.84rem;"><i class="bi bi-check-circle me-2"></i>Saved successfully! <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button></div><?php endif; ?>
      <?php if(isset($_GET['deleted'])): ?><div class="alert alert-warning alert-dismissible fade show py-2" style="font-size:.84rem;"><i class="bi bi-trash me-2"></i>Item deleted. <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button></div><?php endif; ?>
    </div>
    <div class="admin-content">
