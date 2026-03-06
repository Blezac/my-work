<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Admin Login | Royal Ruby</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"/>
  <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/main.css"/>
  <style>
    body{background:linear-gradient(160deg,#5C0000,#0A0A0A);min-height:100vh;display:flex;align-items:center;justify-content:center;}
    .lcard{background:#fff;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.45);width:100%;max-width:430px;overflow:hidden;}
    .lhead{background:linear-gradient(135deg,#5C0000,#8B0000);padding:2.5rem 2rem;text-align:center;}
    .lhead h2{font-family:'Playfair Display',serif;color:#fff;font-size:1.55rem;margin:.5rem 0 .25rem;}
    .lhead p{color:rgba(255,255,255,.62);font-size:.72rem;letter-spacing:2.5px;text-transform:uppercase;margin:0;}
    .gold-bar{width:36px;height:2px;background:#C9A84C;margin:0 auto .65rem;}
    .lbody{padding:2.5rem 2rem;}
    .iwrap{position:relative;}
    .iwrap i{position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:#6B6B6B;font-size:.88rem;}
    .iwrap .form-control{padding-left:2.4rem;}
  </style>
</head>
<body>
<div class="lcard">
  <div class="lhead"><div class="gold-bar"></div><h2>Royal Ruby</h2><p>Admin Portal</p></div>
  <div class="lbody">
    <?php if (!empty($error)) : ?><div class="alert alert-danger py-2 mb-3" style="font-size:.84rem;"><i class="bi bi-exclamation-circle me-2"></i>Invalid email or password.</div><?php endif; ?>
    <?php if (!empty($timeout)) : ?><div class="alert alert-warning py-2 mb-3" style="font-size:.84rem;"><i class="bi bi-clock me-2"></i>Session expired. Please sign in again.</div><?php endif; ?>
    <p class="text-center mb-4" style="color:#6B6B6B;font-size:.88rem;">Sign in to manage your website</p>
    <form action="<?php echo APP_URL; ?>/admin/login" method="POST">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(isset($csrf)?$csrf:'',ENT_QUOTES,'UTF-8'); ?>"/>
      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <div class="iwrap"><i class="bi bi-envelope"></i>
          <input type="email" name="email" class="form-control" placeholder="admin@royalruby.com" required autocomplete="email"/>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label">Password</label>
        <div class="iwrap"><i class="bi bi-lock"></i>
          <input type="password" name="password" class="form-control" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required autocomplete="current-password"/>
        </div>
      </div>
      <button type="submit" class="btn-ruby w-100" style="justify-content:center;padding:.85rem;">Sign In <i class="bi bi-arrow-right ms-2"></i></button>
    </form>
    <p class="text-center mt-4 mb-0"><a href="<?php echo APP_URL; ?>/" style="color:#6B6B6B;font-size:.82rem;"><i class="bi bi-arrow-left me-1"></i>Back to Website</a></p>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body></html>
