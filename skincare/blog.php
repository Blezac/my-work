<?php
$pageTitle = 'The Skin Journal';
require_once 'includes/header.php';

// Single post view
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
if ($slug) {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE slug = ? AND published = 1 LIMIT 1");
    $stmt->execute([$slug]);
    $post = $stmt->fetch();
    
    if (!$post) {
        redirect(SITE_URL . '/blog.php');
    }
    
    // Related posts
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id != ? AND published = 1 ORDER BY RAND() LIMIT 3");
    $stmt->execute([$post['id']]);
    $related = $stmt->fetchAll();
    
    // Single Post View
    ?>
    
    <!-- Single Post Header -->
    <section class="py-16 bg-cream border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-4">
                <a href="index.php" class="hover:text-primary">Home</a>
                <span>/</span>
                <a href="blog.php" class="hover:text-primary">Journal</a>
                <span>/</span>
                <span class="text-gray-700"><?= htmlspecialchars(substr($post['title'], 0, 30)) ?>...</span>
            </div>
            <span class="text-xs text-primary uppercase tracking-widest font-medium"><?= ucfirst(str_replace('-', ' ', $post['category'])) ?></span>
            <h1 class="font-display text-4xl md:text-5xl font-semibold mt-3 mb-4 leading-tight"><?= htmlspecialchars($post['title']) ?></h1>
            <div class="flex items-center gap-4 text-xs text-gray-400">
                <span><i class="fas fa-user mr-1 text-primary"></i><?= htmlspecialchars($post['author']) ?></span>
                <span><i class="fas fa-calendar mr-1 text-primary"></i><?= date('F j, Y', strtotime($post['created_at'])) ?></span>
            </div>
        </div>
    </section>
    
    <!-- Post Image -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="w-full bg-cream placeholder-img" style="height:400px;background:linear-gradient(135deg,#0a3d26,#1F7A5A,#2ecc8e);">
            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:5rem;"></div>
        </div>
    </div>
    
    <!-- Post Content -->
    <article class="max-w-4xl mx-auto px-4 pb-16">
        <div class="prose max-w-none text-gray-700 text-sm leading-relaxed" style="font-size:1rem;line-height:1.8;">
            <?= $post['content'] ?>
        </div>
        
        <!-- Share -->
        <div class="mt-10 pt-8 border-t border-gray-100 flex items-center gap-4 flex-wrap">
            <span class="text-xs text-gray-400 uppercase tracking-widest">Share:</span>
            <a href="https://twitter.com/share?url=<?= urlencode(SITE_URL . '/blog.php?slug=' . $post['slug']) ?>&text=<?= urlencode($post['title']) ?>" target="_blank" 
               class="text-xs text-gray-500 hover:text-primary flex items-center gap-1 transition-colors">
                <i class="fab fa-twitter"></i> Twitter
            </a>
            <a href="https://web.facebook.com/sharer.php?u=<?= urlencode(SITE_URL . '/blog.php?slug=' . $post['slug']) ?>" target="_blank"
               class="text-xs text-gray-500 hover:text-primary flex items-center gap-1 transition-colors">
                <i class="fab fa-facebook"></i> Facebook
            </a>
            <a href="https://wa.me/?text=<?= urlencode($post['title'] . ' ' . SITE_URL . '/blog.php?slug=' . $post['slug']) ?>" target="_blank"
               class="text-xs text-gray-500 hover:text-primary flex items-center gap-1 transition-colors">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
        </div>
    </article>
    
    <!-- Related Posts -->
    <?php if (!empty($related)): ?>
    <section class="bg-cream py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="font-display text-3xl font-semibold mb-8">You May Also Like</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($related as $rp): ?>
                <div class="blog-card group overflow-hidden bg-white">
                    <div class="overflow-hidden" style="height: 180px;">
                        <div class="w-full h-full blog-image placeholder-img" style="background:linear-gradient(135deg,#0a3d26,#1F7A5A);">
                            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:2.5rem;"></div>
                        </div>
                    </div>
                    <div class="p-4">
                        <a href="blog.php?slug=<?= urlencode($rp['slug']) ?>">
                            <h3 class="font-display text-base font-semibold group-hover:text-primary transition-colors leading-snug"><?= htmlspecialchars($rp['title']) ?></h3>
                        </a>
                        <p class="text-xs text-gray-400 mt-2"><?= date('M j, Y', strtotime($rp['created_at'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <?php
} else {
    // Blog listing
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE published = 1 ORDER BY created_at DESC");
    $stmt->execute();
    $posts = $stmt->fetchAll();
    ?>

<!-- Blog Header -->
<section class="py-20" style="background: linear-gradient(135deg, #0a3d26 0%, #1F7A5A 100%);">
    <div class="max-w-7xl mx-auto px-4 text-center text-white">
        <p class="text-white/60 text-xs tracking-[0.3em] uppercase mb-4">Knowledge & Glow</p>
        <h1 class="font-display text-5xl md:text-6xl font-semibold mb-4">The Skin Journal</h1>
        <p class="text-white/70 text-sm max-w-md mx-auto">Expert skincare tips, ingredient deep-dives, and everything you need to know about melanin-rich skin.</p>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <?php if (!empty($posts)): ?>
        
        <!-- Featured Post (first) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16 animate-on-scroll">
            <?php $featured = $posts[0]; ?>
            <div class="overflow-hidden" style="height: 360px;">
                <div class="w-full h-full blog-image placeholder-img" style="background: linear-gradient(135deg, #0a3d26, #1F7A5A, #2ecc8e);">
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:5rem;"></div>
                </div>
            </div>
            <div class="flex flex-col justify-center">
                <span class="text-xs text-primary uppercase tracking-widest font-medium mb-3">
                    <?= ucfirst(str_replace('-', ' ', $featured['category'])) ?> · Featured
                </span>
                <a href="blog.php?slug=<?= urlencode($featured['slug']) ?>">
                    <h2 class="font-display text-3xl md:text-4xl font-semibold mb-4 hover:text-primary transition-colors leading-snug"><?= htmlspecialchars($featured['title']) ?></h2>
                </a>
                <p class="text-gray-500 text-sm leading-relaxed mb-5"><?= htmlspecialchars($featured['excerpt']) ?></p>
                <div class="flex items-center gap-4 text-xs text-gray-400 mb-5">
                    <span><?= htmlspecialchars($featured['author']) ?></span>
                    <span>·</span>
                    <span><?= date('F j, Y', strtotime($featured['created_at'])) ?></span>
                </div>
                <a href="blog.php?slug=<?= urlencode($featured['slug']) ?>" class="btn-primary inline-block">Read Article →</a>
            </div>
        </div>
        
        <!-- Other Posts -->
        <?php $otherPosts = array_slice($posts, 1); ?>
        <?php if (!empty($otherPosts)): ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($otherPosts as $post): ?>
            <div class="blog-card group overflow-hidden animate-on-scroll">
                <div class="overflow-hidden" style="height:220px;background:linear-gradient(135deg,#0a3d26,#1F7A5A);">
                    <div class="w-full h-full blog-image placeholder-img">
                        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:3rem;"></div>
                    </div>
                </div>
                <div class="pt-5 pb-4">
                    <span class="text-xs text-primary uppercase tracking-widest font-medium"><?= ucfirst(str_replace('-', ' ', $post['category'])) ?></span>
                    <a href="blog.php?slug=<?= urlencode($post['slug']) ?>">
                        <h3 class="font-display text-xl font-semibold mt-2 mb-3 group-hover:text-primary transition-colors leading-snug"><?= htmlspecialchars($post['title']) ?></h3>
                    </a>
                    <p class="text-gray-500 text-sm leading-relaxed mb-3"><?= htmlspecialchars(substr($post['excerpt'], 0, 90)) ?>...</p>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400 text-xs"><?= date('M j, Y', strtotime($post['created_at'])) ?></span>
                        <a href="blog.php?slug=<?= urlencode($post['slug']) ?>" class="text-primary text-xs hover:underline">Read →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="text-center py-20">
            <div class="text-4xl mb-4"></div>
            <p class="text-gray-500">No articles yet. Check back soon!</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php } ?>

<a href="https://wa.me/2349012345678" target="_blank" class="whatsapp-float"><i class="fab fa-whatsapp"></i></a>

<?php require_once 'includes/footer.php'; ?>
