<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <?php
  $base = APP_URL;
  foreach (array('/','/about','/services','/hospitality','/blog','/testimonials','/contact') as $path) { ?>
  <url><loc><?php echo htmlspecialchars($base.$path); ?></loc><changefreq>weekly</changefreq><priority><?php echo $path==='/'?'1.0':'0.8'; ?></priority></url>
  <?php }
  if (!empty($services)) foreach ($services as $s) { ?>
  <url><loc><?php echo htmlspecialchars($base.'/services/'.$s['slug']); ?></loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <?php }
  if (!empty($posts)) foreach ($posts as $p) { ?>
  <url><loc><?php echo htmlspecialchars($base.'/blog/'.$p['slug']); ?></loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
  <?php } ?>
</urlset>