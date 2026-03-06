<?php
/**
 * Royal Ruby Exotic Place — AdminController
 * Handles all admin dashboard actions
 */
class AdminController extends Controller
{
    // ── PRIVATE HELPER ───────────────────────────────────────────
    /**
     * Render an admin view, ensuring auth and injecting common data
     * @param  string $view  relative to app/views/admin/
     * @param  array  $data
     * @return void
     */
    private function adminView($view, array $data = [])
    {
        $this->requireAuth();
        $this->loadSettings();

        require_once APP_ROOT . '/app/models/ContactModel.php';
        $contactModel = new ContactModel();

        $data['unreadMessages'] = $contactModel->countUnread();
        $data['adminUser']      = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin';

        $this->view('admin/' . $view, $data);
    }

    // ── AUTH ──────────────────────────────────────────────────────
    public function loginPage()
    {
        if (isset($_SESSION['admin_id'])) {
            $this->redirect(APP_URL . '/admin/dashboard');
            return;
        }
        $this->view('admin/login', [
            'csrf'    => $this->generateCsrf(),
            'timeout' => isset($_GET['timeout']),
            'error'   => isset($_GET['error']),
        ]);
    }

    public function loginPost()
    {
        if (!$this->isPost() || !$this->validateCsrf()) {
            $this->redirect(APP_URL . '/admin/login?error=1');
            return;
        }
        require_once APP_ROOT . '/app/models/UserModel.php';
        $userModel = new UserModel();
        $email     = filter_var($this->post('email'), FILTER_SANITIZE_EMAIL);
        $password  = $this->post('password');
        $user      = $userModel->findByEmail($email);

        if ($user && $userModel->verifyPassword($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']      = $user['id'];
            $_SESSION['admin_name']    = $user['name'];
            $_SESSION['admin_role']    = $user['role'];
            $_SESSION['last_activity'] = time();
            $userModel->updateLastLogin($user['id']);
            $this->redirect(APP_URL . '/admin/dashboard');
        } else {
            $this->redirect(APP_URL . '/admin/login?error=1');
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect(APP_URL . '/admin/login');
    }

    // ── DASHBOARD ─────────────────────────────────────────────────
    public function dashboard()
    {
        require_once APP_ROOT . '/app/models/BlogModel.php';
        require_once APP_ROOT . '/app/models/ContactModel.php';
        require_once APP_ROOT . '/app/models/ServiceModel.php';
        require_once APP_ROOT . '/app/models/NewsletterModel.php';
        require_once APP_ROOT . '/app/models/TestimonialModel.php';

        $blogModel        = new BlogModel();
        $contactModel     = new ContactModel();
        $serviceModel     = new ServiceModel();
        $newsletterModel  = new NewsletterModel();
        $testimonialModel = new TestimonialModel();

        $this->adminView('dashboard', [
            'pageTitle'          => 'Dashboard | Admin',
            'totalPosts'         => $blogModel->countPublished(),
            'totalMessages'      => $contactModel->count(),
            'unreadMsgs'         => $contactModel->countUnread(),
            'totalServices'      => $serviceModel->count(),
            'totalSubscribers'   => $newsletterModel->countActive(),
            'totalTestimonials'  => $testimonialModel->count(),
            'recentMessages'     => $contactModel->getAll(),
            'recentPosts'        => $blogModel->getPublished(5),
        ]);
    }

    // ── SERVICES CRUD ─────────────────────────────────────────────
    public function services()
    {
        require_once APP_ROOT . '/app/models/ServiceModel.php';
        $serviceModel = new ServiceModel();
        $this->adminView('services', [
            'pageTitle' => 'Services | Admin',
            'services'  => $serviceModel->getAll(),
        ]);
    }

    public function serviceCreate()
    {
        $this->adminView('service-form', [
            'pageTitle' => 'Add Service | Admin',
            'service'   => null,
            'csrf'      => $this->generateCsrf(),
        ]);
    }

    public function serviceStore()
    {
        if (!$this->isPost() || !$this->validateCsrf()) {
            $this->redirect(APP_URL . '/admin/services');
            return;
        }
        require_once APP_ROOT . '/app/models/ServiceModel.php';
        $model = new ServiceModel();
        $slug  = $this->post('slug') ?: $this->post('title');
        $slug  = $this->makeSlug($slug);
        if ($model->slugExists($slug)) {
            $slug .= '-' . time();
        }
        $image = $this->handleUpload('image', 'services');
        $model->insert([
            'title'       => $this->post('title'),
            'slug'        => $slug,
            'icon'        => $this->post('icon', 'bi-briefcase'),
            'short_desc'  => $this->post('short_desc'),
            'full_desc'   => $this->post('full_desc'),
            'benefits'    => $this->post('benefits'),
            'image'       => $image,
            'sort_order'  => (int)$this->post('sort_order', '0'),
            'is_featured' => (int)$this->post('is_featured', '0'),
            'is_active'   => 1,
        ]);
        $this->redirect(APP_URL . '/admin/services?saved=1');
    }

    public function serviceEdit($id)
    {
        require_once APP_ROOT . '/app/models/ServiceModel.php';
        $model   = new ServiceModel();
        $service = $model->findById((int)$id);
        if (!$service) {
            $this->redirect(APP_URL . '/admin/services');
            return;
        }
        $this->adminView('service-form', [
            'pageTitle' => 'Edit Service | Admin',
            'service'   => $service,
            'csrf'      => $this->generateCsrf(),
        ]);
    }

    public function serviceUpdate($id)
    {
        if (!$this->isPost() || !$this->validateCsrf()) {
            $this->redirect(APP_URL . '/admin/services');
            return;
        }
        require_once APP_ROOT . '/app/models/ServiceModel.php';
        $model = new ServiceModel();
        $data  = [
            'title'       => $this->post('title'),
            'icon'        => $this->post('icon', 'bi-briefcase'),
            'short_desc'  => $this->post('short_desc'),
            'full_desc'   => $this->post('full_desc'),
            'benefits'    => $this->post('benefits'),
            'sort_order'  => (int)$this->post('sort_order', '0'),
            'is_featured' => (int)$this->post('is_featured', '0'),
            'is_active'   => (int)$this->post('is_active', '1'),
        ];
        $image = $this->handleUpload('image', 'services');
        if ($image !== '') {
            $data['image'] = $image;
        }
        $model->update((int)$id, $data);
        $this->redirect(APP_URL . '/admin/services?saved=1');
    }

    public function serviceDelete($id)
    {
        require_once APP_ROOT . '/app/models/ServiceModel.php';
        $model = new ServiceModel();
        $model->softDelete((int)$id);
        $this->redirect(APP_URL . '/admin/services?deleted=1');
    }

    // ── BLOG CRUD ─────────────────────────────────────────────────
    public function blog()
    {
        require_once APP_ROOT . '/app/models/BlogModel.php';
        $model = new BlogModel();
        $this->adminView('blog', [
            'pageTitle' => 'Blog Posts | Admin',
            'posts'     => $model->getAll(),
        ]);
    }

    public function blogCreate()
    {
        $this->adminView('blog-form', [
            'pageTitle' => 'New Post | Admin',
            'post'      => null,
            'csrf'      => $this->generateCsrf(),
        ]);
    }

    public function blogStore()
    {
        if (!$this->isPost() || !$this->validateCsrf()) {
            $this->redirect(APP_URL . '/admin/blog');
            return;
        }
        require_once APP_ROOT . '/app/models/BlogModel.php';
        $model  = new BlogModel();
        $slug   = $this->post('slug') ?: $this->post('title');
        $slug   = $this->makeSlug($slug);
        if ($model->slugExists($slug)) {
            $slug .= '-' . time();
        }
        $status = $this->post('status', 'draft');
        $image  = $this->handleUpload('featured_image', 'blog');
        $model->insert([
            'user_id'        => isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : 1,
            'title'          => $this->post('title'),
            'slug'           => $slug,
            'excerpt'        => $this->post('excerpt'),
            'content'        => $this->post('content'),
            'featured_image' => $image,
            'category'       => $this->post('category', 'Business'),
            'tags'           => $this->post('tags'),
            'meta_title'     => $this->post('meta_title'),
            'meta_desc'      => $this->post('meta_desc'),
            'status'         => $status,
            'published_at'   => ($status === 'published') ? date('Y-m-d H:i:s') : null,
        ]);
        $this->redirect(APP_URL . '/admin/blog?saved=1');
    }

    public function blogEdit($id)
    {
        require_once APP_ROOT . '/app/models/BlogModel.php';
        $model = new BlogModel();
        $post  = $model->findById((int)$id);
        if (!$post) {
            $this->redirect(APP_URL . '/admin/blog');
            return;
        }
        $this->adminView('blog-form', [
            'pageTitle' => 'Edit Post | Admin',
            'post'      => $post,
            'csrf'      => $this->generateCsrf(),
        ]);
    }

    public function blogUpdate($id)
    {
        if (!$this->isPost() || !$this->validateCsrf()) {
            $this->redirect(APP_URL . '/admin/blog');
            return;
        }
        require_once APP_ROOT . '/app/models/BlogModel.php';
        $model  = new BlogModel();
        $status = $this->post('status', 'draft');
        $data   = [
            'title'      => $this->post('title'),
            'excerpt'    => $this->post('excerpt'),
            'content'    => $this->post('content'),
            'category'   => $this->post('category', 'Business'),
            'tags'       => $this->post('tags'),
            'meta_title' => $this->post('meta_title'),
            'meta_desc'  => $this->post('meta_desc'),
            'status'     => $status,
        ];
        if ($status === 'published') {
            $existing = $model->findById((int)$id);
            if ($existing && !$existing['published_at']) {
                $data['published_at'] = date('Y-m-d H:i:s');
            }
        }
        $image = $this->handleUpload('featured_image', 'blog');
        if ($image !== '') {
            $data['featured_image'] = $image;
        }
        $model->update((int)$id, $data);
        $this->redirect(APP_URL . '/admin/blog?saved=1');
    }

    public function blogDelete($id)
    {
        require_once APP_ROOT . '/app/models/BlogModel.php';
        $model = new BlogModel();
        $model->softDelete((int)$id);
        $this->redirect(APP_URL . '/admin/blog?deleted=1');
    }

    // ── TESTIMONIALS CRUD ─────────────────────────────────────────
    public function testimonials()
    {
        require_once APP_ROOT . '/app/models/TestimonialModel.php';
        $model = new TestimonialModel();
        $this->adminView('testimonials', [
            'pageTitle'    => 'Testimonials | Admin',
            'testimonials' => $model->getAll(),
        ]);
    }

    public function testimonialCreate()
    {
        $this->adminView('testimonial-form', [
            'pageTitle'   => 'Add Testimonial | Admin',
            'testimonial' => null,
            'csrf'        => $this->generateCsrf(),
        ]);
    }

    public function testimonialStore()
    {
        if (!$this->isPost() || !$this->validateCsrf()) {
            $this->redirect(APP_URL . '/admin/testimonials');
            return;
        }
        require_once APP_ROOT . '/app/models/TestimonialModel.php';
        $model = new TestimonialModel();
        $model->insert([
            'client_name'  => $this->post('client_name'),
            'client_title' => $this->post('client_title'),
            'company'      => $this->post('company'),
            'review'       => $this->post('review'),
            'rating'       => (int)$this->post('rating', '5'),
            'is_featured'  => (int)$this->post('is_featured', '0'),
            'is_active'    => 1,
            'sort_order'   => (int)$this->post('sort_order', '0'),
        ]);
        $this->redirect(APP_URL . '/admin/testimonials?saved=1');
    }

    public function testimonialEdit($id)
    {
        require_once APP_ROOT . '/app/models/TestimonialModel.php';
        $model       = new TestimonialModel();
        $testimonial = $model->findById((int)$id);
        if (!$testimonial) {
            $this->redirect(APP_URL . '/admin/testimonials');
            return;
        }
        $this->adminView('testimonial-form', [
            'pageTitle'   => 'Edit Testimonial | Admin',
            'testimonial' => $testimonial,
            'csrf'        => $this->generateCsrf(),
        ]);
    }

    public function testimonialUpdate($id)
    {
        if (!$this->isPost() || !$this->validateCsrf()) {
            $this->redirect(APP_URL . '/admin/testimonials');
            return;
        }
        require_once APP_ROOT . '/app/models/TestimonialModel.php';
        $model = new TestimonialModel();
        $model->update((int)$id, [
            'client_name'  => $this->post('client_name'),
            'client_title' => $this->post('client_title'),
            'company'      => $this->post('company'),
            'review'       => $this->post('review'),
            'rating'       => (int)$this->post('rating', '5'),
            'is_featured'  => (int)$this->post('is_featured', '0'),
            'is_active'    => (int)$this->post('is_active', '1'),
            'sort_order'   => (int)$this->post('sort_order', '0'),
        ]);
        $this->redirect(APP_URL . '/admin/testimonials?saved=1');
    }

    public function testimonialDelete($id)
    {
        require_once APP_ROOT . '/app/models/TestimonialModel.php';
        $model = new TestimonialModel();
        $model->softDelete((int)$id);
        $this->redirect(APP_URL . '/admin/testimonials?deleted=1');
    }

    // ── CONTACTS ──────────────────────────────────────────────────
    public function contacts()
    {
        require_once APP_ROOT . '/app/models/ContactModel.php';
        $model = new ContactModel();
        $this->adminView('contacts', [
            'pageTitle' => 'Messages | Admin',
            'contacts'  => $model->getAll(),
        ]);
    }

    public function contactView($id)
    {
        require_once APP_ROOT . '/app/models/ContactModel.php';
        $model   = new ContactModel();
        $contact = $model->findById((int)$id);
        if (!$contact) {
            $this->redirect(APP_URL . '/admin/contacts');
            return;
        }
        $model->markRead((int)$id);
        $this->adminView('contact-view', [
            'pageTitle' => 'View Message | Admin',
            'contact'   => $contact,
        ]);
    }

    public function contactDelete($id)
    {
        require_once APP_ROOT . '/app/models/ContactModel.php';
        $model = new ContactModel();
        $model->softDelete((int)$id);
        $this->redirect(APP_URL . '/admin/contacts?deleted=1');
    }

    // ── GALLERY ───────────────────────────────────────────────────
    public function gallery()
    {
        require_once APP_ROOT . '/app/models/GalleryModel.php';
        $model = new GalleryModel();
        $this->adminView('gallery', [
            'pageTitle' => 'Gallery | Admin',
            'gallery'   => $model->getAll(),
            'csrf'      => $this->generateCsrf(),
        ]);
    }

    public function galleryStore()
    {
        if (!$this->isPost() || !$this->validateCsrf()) {
            $this->redirect(APP_URL . '/admin/gallery');
            return;
        }
        $image = $this->handleUpload('image', 'gallery');
        if ($image !== '') {
            require_once APP_ROOT . '/app/models/GalleryModel.php';
            $model = new GalleryModel();
            $model->insert([
                'title'       => $this->post('title'),
                'description' => $this->post('description'),
                'image'       => $image,
                'category'    => $this->post('category', 'general'),
                'sort_order'  => (int)$this->post('sort_order', '0'),
                'is_active'   => 1,
            ]);
        }
        $this->redirect(APP_URL . '/admin/gallery?saved=1');
    }

    public function galleryDelete($id)
    {
        require_once APP_ROOT . '/app/models/GalleryModel.php';
        $model = new GalleryModel();
        $model->softDelete((int)$id);
        $this->redirect(APP_URL . '/admin/gallery?deleted=1');
    }

    // ── SETTINGS ─────────────────────────────────────────────────
    public function settings()
    {
        $this->adminView('settings', [
            'pageTitle' => 'Settings | Admin',
            'csrf'      => $this->generateCsrf(),
        ]);
    }

    public function settingsUpdate()
    {
        if (!$this->isPost() || !$this->validateCsrf()) {
            $this->redirect(APP_URL . '/admin/settings');
            return;
        }
        require_once APP_ROOT . '/app/models/SettingsModel.php';
        $model = new SettingsModel();
        $keys  = [
            'site_name', 'site_tagline', 'site_email', 'site_phone',
            'site_address', 'site_description', 'site_keywords',
            'hero_headline', 'hero_subheadline', 'about_intro',
            'facebook_url', 'instagram_url', 'linkedin_url', 'twitter_url',
        ];
        foreach ($keys as $key) {
            if (isset($_POST[$key])) {
                $model->set($key, strip_tags($this->post($key)));
            }
        }
        $this->redirect(APP_URL . '/admin/settings?saved=1');
    }

    // ── NEWSLETTER ────────────────────────────────────────────────
    public function newsletter()
    {
        require_once APP_ROOT . '/app/models/NewsletterModel.php';
        $model = new NewsletterModel();
        $this->adminView('newsletter', [
            'pageTitle'   => 'Newsletter Subscribers | Admin',
            'subscribers' => $model->getAll(),
        ]);
    }
}