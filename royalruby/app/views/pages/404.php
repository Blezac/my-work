<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>404 | Royal Ruby Exotic Place</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"/>
  <?php if (defined('APP_URL')) : ?><link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/main.css"/><?php endif; ?>
</head>
<body>
<div style="min-height:100vh;background:linear-gradient(160deg,#5C0000,#0A0A0A);display:flex;align-items:center;justify-content:center;text-align:center;padding:2rem;">
  <div>
    <div style="font-family:Georgia,serif;font-size:7rem;font-weight:700;color:#C9A84C;line-height:1;">404</div>
    <h2 style="color:#fff;margin:1rem 0;">Page Not Found</h2>
    <p style="color:rgba(255,255,255,.62);max-width:400px;margin:0 auto 2rem;">The page you're looking for doesn't exist or has been moved.</p>
    <a href="<?php echo defined('APP_URL') ? APP_URL : '/'; ?>/" style="display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#9C7D2A,#C9A84C);color:#000;padding:.75rem 2rem;border-radius:6px;font-weight:700;font-size:.88rem;text-transform:uppercase;letter-spacing:1px;text-decoration:none;">
      Return Home <i class="bi bi-arrow-right"></i>
    </a>
  </div>
</div>
</body>
</html>