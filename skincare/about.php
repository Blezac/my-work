<?php
$pageTitle = 'Our Story';
require_once 'includes/header.php';
?>

<!-- Hero -->
<section class="relative py-32 overflow-hidden" style="background: linear-gradient(135deg, #0a3d26 0%, #1F7A5A 60%, #2ecc8e 100%);">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 70% 30%, white 0%, transparent 60%);"></div>
    <div class="max-w-7xl mx-auto px-4 relative z-10 text-center text-white">
        <p class="text-white/60 text-xs tracking-[0.3em] uppercase mb-4">Est. 2019, Lagos Nigeria</p>
        <h1 class="font-display text-5xl md:text-7xl font-semibold mb-6">Our Story</h1>
        <p class="text-white/80 text-lg max-w-2xl mx-auto">Rooted in African tradition. Backed by science. Made for you.</p>
    </div>
</section>

<!-- Founder Story -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="animate-on-scroll">
                <div class="relative">
                    <div class="aspect-square max-w-md placeholder-img" style="background: linear-gradient(135deg, #1F7A5A, #2ecc8e, #d4f5e7); min-height: 440px;">
                        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:1rem;padding:3rem;text-align:center;">
                            <div style="font-size:5rem;">👩🏾‍🔬</div>
                            <p style="font-family:'Playfair Display',serif;font-size:1.3rem;color:white;font-style:italic;">"Nara Adeyemi<br>Founder & Formulator"</p>
                        </div>
                    </div>
                    <div class="absolute -bottom-5 -right-5 bg-primary text-white p-5 hidden md:block">
                        <p class="font-display text-2xl font-bold">2019</p>
                        <p class="text-xs text-white/70">Founded in Lagos</p>
                    </div>
                </div>
            </div>
            
            <div class="animate-on-scroll">
                <p class="text-primary text-xs tracking-[0.3em] uppercase mb-4">The Beginning</p>
                <h2 class="font-display text-4xl md:text-5xl font-semibold mb-6 leading-tight">
                    From a Kitchen<br>
                    <em class="italic font-normal text-primary">to 50,000 Customers</em>
                </h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                    Nara Adeyemi spent years frustrated with global skincare brands that simply didn't understand her skin. The products either left white residue, caused breakouts, or darkened her skin tone further. As a biochemist and skincare enthusiast, she decided to take matters into her own hands.
                </p>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                    In 2019, working from her kitchen in Lagos, she began experimenting with formulations that combined the botanical ingredients her grandmother used with modern dermatological actives like Vitamin C, niacinamide, and hyaluronic acid.
                </p>
                <p class="text-gray-600 text-sm leading-relaxed mb-8">
                    What started as jars shared with friends and family grew into Glōw by Nara — a brand trusted by over 50,000 customers across Nigeria and the diaspora.
                </p>
                <blockquote class="border-l-4 border-primary pl-5 py-2 italic text-gray-700 text-sm">
                    "Our skin tells our story. It carries centuries of melanin, strength, and beauty. It deserves skincare that understands and celebrates that."<br>
                    <span class="text-gray-400 not-italic mt-2 block text-xs">— Nara Adeyemi, Founder</span>
                </blockquote>
            </div>
        </div>
    </div>
</section>

<!-- Values -->
<section class="py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12 animate-on-scroll">
            <p class="text-primary text-xs tracking-[0.3em] uppercase mb-3">What We Stand For</p>
            <h2 class="font-display text-4xl font-semibold">Our Values</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $values = [
                ['icon' => '', 'title' => 'Clean Formulations', 'desc' => 'We never use parabens, mercury, hydroquinone, or harmful bleaching agents. Every ingredient serves a purpose.'],
                ['icon' => '', 'title' => 'Science-Backed', 'desc' => 'Our formulations are developed by biochemists and tested by dermatologists who specialize in melanin-rich skin.'],
                ['icon' => '', 'title' => 'Proudly African', 'desc' => 'We source over 60% of our botanical ingredients from Nigerian and African suppliers, supporting local communities.'],
                ['icon' => '', 'title' => 'Cruelty-Free', 'desc' => 'No animal testing. Ever. Our products are certified cruelty-free and we are committed to ethical beauty.'],
                ['icon' => '', 'title' => 'Sustainable', 'desc' => 'Our packaging is designed with sustainability in mind. We use recyclable materials and minimal plastic.'],
                ['icon' => '', 'title' => 'Skin Education', 'desc' => 'We believe informed customers get better results. Our journal and social channels are dedicated to real skin education.'],
            ];
            foreach ($values as $val):
            ?>
            <div class="bg-white p-6 animate-on-scroll border border-gray-50 hover:border-primary transition-colors group">
                <div class="text-3xl mb-4"><?= $val['icon'] ?></div>
                <h3 class="font-display text-lg font-semibold mb-2 group-hover:text-primary transition-colors"><?= $val['title'] ?></h3>
                <p class="text-gray-500 text-sm leading-relaxed"><?= $val['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Timeline -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-12 animate-on-scroll">
            <p class="text-primary text-xs tracking-[0.3em] uppercase mb-3">Our Journey</p>
            <h2 class="font-display text-4xl font-semibold">Milestones</h2>
        </div>
        
        <div class="relative">
            <div class="absolute left-1/2 -translate-x-px top-0 bottom-0 w-px bg-gray-200 hidden md:block"></div>
            
            <?php
            $milestones = [
                ['year' => '2019', 'title' => 'The Beginning', 'desc' => 'Nara begins formulating from her Lagos kitchen. First product: the Radiance Glow Serum.', 'side' => 'left'],
                ['year' => '2020', 'title' => 'First 1,000 Customers', 'desc' => 'Word-of-mouth growth leads to our first 1,000 customers. We move to a proper lab.', 'side' => 'right'],
                ['year' => '2021', 'title' => 'Dermatologist Partnership', 'desc' => 'We partner with dermatologists specializing in melanin-rich skin to validate and improve formulas.', 'side' => 'left'],
                ['year' => '2022', 'title' => '10,000 Customers', 'desc' => 'Social media growth drives us to 10,000 customers. We launch 4 new products.', 'side' => 'right'],
                ['year' => '2023', 'title' => 'NAFDAC Certified', 'desc' => 'All products receive NAFDAC certification, confirming our quality and safety standards.', 'side' => 'left'],
                ['year' => '2024', 'title' => '50,000+ Customers', 'desc' => 'We reach 50,000 customers and launch our full product line. The glow continues.', 'side' => 'right'],
            ];
            foreach ($milestones as $m):
            ?>
            <div class="relative grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 animate-on-scroll">
                <?php if ($m['side'] === 'left'): ?>
                <div class="md:text-right md:pr-8">
                    <span class="text-primary font-bold font-display text-xl"><?= $m['year'] ?></span>
                    <h3 class="font-medium text-sm mt-1 mb-1"><?= $m['title'] ?></h3>
                    <p class="text-gray-500 text-sm"><?= $m['desc'] ?></p>
                </div>
                <div class="hidden md:flex items-start justify-start pl-8">
                    <div class="w-3 h-3 bg-primary rounded-full mt-1.5 -translate-x-[calc(50%+0.5px)] relative" style="left:calc(-1rem - 0.5px)"></div>
                </div>
                <?php else: ?>
                <div class="hidden md:flex items-start justify-end pr-8">
                    <div class="w-3 h-3 bg-primary rounded-full mt-1.5 translate-x-[calc(50%+0.5px)] relative" style="right:calc(-1rem - 0.5px)"></div>
                </div>
                <div class="md:pl-8">
                    <span class="text-primary font-bold font-display text-xl"><?= $m['year'] ?></span>
                    <h3 class="font-medium text-sm mt-1 mb-1"><?= $m['title'] ?></h3>
                    <p class="text-gray-500 text-sm"><?= $m['desc'] ?></p>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Ingredients section -->
<section id="ingredients" class="py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12 animate-on-scroll">
            <p class="text-primary text-xs tracking-[0.3em] uppercase mb-3">What Goes Inside</p>
            <h2 class="font-display text-4xl font-semibold mb-4">Our Hero Ingredients</h2>
            <p class="text-gray-500 text-sm max-w-md mx-auto">Every ingredient is intentional. Here are some of the key actives that power our formulations.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <?php
            $ings = [
                ['icon' => '', 'name' => 'Shea Butter', 'from' => 'Northern Nigeria', 'desc' => 'Rich in fatty acids and vitamins A, E & F. Deeply moisturizes without clogging pores.'],
                ['icon' => '', 'name' => 'Vitamin C', 'from' => 'Botanical', 'desc' => 'Powerful antioxidant that inhibits melanin production and protects against UV damage.'],
                ['icon' => '', 'name' => 'Turmeric', 'from' => 'West Africa', 'desc' => 'Anti-inflammatory and brightening. Used in African beauty rituals for centuries.'],
                ['icon' => '', 'name' => 'Niacinamide', 'from' => 'Science-backed', 'desc' => 'Reduces dark spots, minimizes pores, and strengthens the skin barrier. A true multitasker.'],
                ['icon' => '', 'name' => 'Marula Oil', 'from' => 'Sub-Saharan Africa', 'desc' => 'Lightweight oil with exceptional skin absorption. Rich in oleic acid for deep nourishment.'],
                ['icon' => '', 'name' => 'Aloe Vera', 'from' => 'Nigeria', 'desc' => 'Soothes inflammation, hydrates, and aids healing. Perfect for sensitive and acne-prone skin.'],
                ['icon' => '', 'name' => 'Green Tea', 'from' => 'Botanical', 'desc' => 'Packed with antioxidants (EGCG) that protect against environmental damage and premature aging.'],
                ['icon' => '', 'name' => 'Hyaluronic Acid', 'from' => 'Biotechnology', 'desc' => 'Holds 1000x its weight in water. Plumps skin and reduces the appearance of fine lines.'],
            ];
            foreach ($ings as $ing):
            ?>
            <div class="bg-white p-5 border border-gray-50 hover:border-primary transition-all group animate-on-scroll">
                <div class="text-2xl mb-3"><?= $ing['icon'] ?></div>
                <h3 class="font-medium text-sm mb-1 group-hover:text-primary transition-colors"><?= $ing['name'] ?></h3>
                <p class="text-primary text-xs mb-2"><?= $ing['from'] ?></p>
                <p class="text-gray-500 text-xs leading-relaxed"><?= $ing['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="gradient-green py-20 text-center text-white">
    <div class="max-w-2xl mx-auto px-4 animate-on-scroll">
        <h2 class="font-display text-4xl font-semibold mb-4">Start Your Glow Journey</h2>
        <p class="text-white/70 text-sm mb-8">Explore our collection of premium skincare formulated just for you.</p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="shop.php" class="btn-primary bg-white text-primary hover:bg-gray-100" style="background:white;color:#1F7A5A;">Shop Now</a>
            <a href="contact.php" class="btn-outline" style="border-color:white;color:white;" onmouseover="this.style.background='white';this.style.color='#1F7A5A';" onmouseout="this.style.background='transparent';this.style.color='white';">Contact Us</a>
        </div>
    </div>
</section>

<a href="https://wa.me/2349012345678" target="_blank" class="whatsapp-float"><i class="fab fa-whatsapp"></i></a>

<?php require_once 'includes/footer.php'; ?>
