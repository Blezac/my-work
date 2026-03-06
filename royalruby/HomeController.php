<?php
/**
 * Royal Ruby Exotic Place — HomeController
 * Handles all public-facing pages
 */
class HomeController extends Controller
{
    public function index()
    {
        $this->loadSettings();
        require_once APP_ROOT . '/app/models/ServiceModel.php';
        require_once APP_ROOT . '/app/models/TestimonialModel.php';
        require_once APP_ROOT . '/app/models/BlogModel.php';
        require_once APP_ROOT . '/app/models/GalleryModel.php';

        $serviceModel     = new ServiceModel();
        $testimonialModel = new TestimonialModel();
        $blogModel        = new BlogModel();
        $galleryModel     = new GalleryModel();

        $this->view('pages/home', [
            'pageTitle'    => ($this->data['settings']['site_name'] ?? 'Royal Ruby Exotic Place'),
            'metaDesc'     => ($this->data['settings']['site_description'] ?? ''),
            'services'     => $serviceModel->getFeatured(5),
            'testimonials' => $testimonialModel->getFeatured(),
            'posts'        => $blogModel->getPublished(3),
            'gallery'      => $galleryModel->getActive('hospitality'),
        ]);
    }

    public function about()
    {
        $this->loadSettings();
        $this->view('pages/about', [
            'pageTitle' => 'About Us | Royal Ruby Exotic Place',
            'metaDesc'  => 'Learn about Royal Ruby Exotic Place — a premium Marketing and Business Consulting Firm.',
        ]);
    }

    public function services()
    {
        $this->loadSettings();
        require_once APP_ROOT . '/app/models/ServiceModel.php';
        $serviceModel = new ServiceModel();
        $this->view('pages/services', [
            'pageTitle' => 'Our Services | Royal Ruby Exotic Place',
            'metaDesc'  => 'Explore our marketing, business development, and hospitality consulting services.',
            'services'  => $serviceModel->getActive(),
        ]);
    }

    public function serviceDetail($slug)
    {
        $this->loadSettings();
        require_once APP_ROOT . '/app/models/ServiceModel.php';
        $serviceModel = new ServiceModel();
        $service = $serviceModel->getBySlug($slug);
        if (!$service) {
            http_response_code(404);
            $this->view('pages/404');
            return;
        }
        $this->view('pages/service-detail', [
            'pageTitle' => htmlspecialchars($service['title']) . ' | Royal Ruby',
            'metaDesc'  => $service['short_desc'],
            'service'   => $service,
        ]);
    }

    public function hospitality()
    {
        $this->loadSettings();
        require_once APP_ROOT . '/app/models/GalleryModel.php';
        $galleryModel = new GalleryModel();
        $this->view('pages/hospitality', [
            'pageTitle' => 'Hospitality | Royal Ruby Exotic Place',
            'metaDesc'  => 'Premium hospitality consulting and luxury experience management.',
            'gallery'   => $galleryModel->getActive(),
        ]);
    }

    public function testimonials()
    {
        $this->loadSettings();
        require_once APP_ROOT . '/app/models/TestimonialModel.php';
        $testimonialModel = new TestimonialModel();
        $this->view('pages/testimonials', [
            'pageTitle'    => 'Client Testimonials | Royal Ruby Exotic Place',
            'metaDesc'     => 'What our clients say about Royal Ruby Exotic Place.',
            'testimonials' => $testimonialModel->getActive(),
        ]);
    }

    public function blog()
    {
        $this->loadSettings();
        require_once APP_ROOT . '/app/models/BlogModel.php';
        $blogModel = new BlogModel();
        $page   = max(1, (int)$this->get('page', '1'));
        $limit  = 6;
        $offset = ($page - 1) * $limit;
        $this->view('pages/blog', [
            'pageTitle' => 'Blog | Royal Ruby Exotic Place',
            'metaDesc'  => 'Marketing insights, hospitality trends, and business growth strategies.',
            'posts'     => $blogModel->getPublished($limit, $offset),
            'total'     => $blogModel->countPublished(),
            'page'      => $page,
            'limit'     => $limit,
        ]);
    }

    public function blogPost($slug)
    {
        $this->loadSettings();
        require_once APP_ROOT . '/app/models/BlogModel.php';
        $blogModel = new BlogModel();
        $post = $blogModel->getBySlug($slug);
        if (!$post) {
            http_response_code(404);
            $this->view('pages/404');
            return;
        }
        $this->view('pages/blog-post', [
            'pageTitle' => htmlspecialchars($post['title']) . ' | Royal Ruby',
            'metaDesc'  => $post['excerpt'],
            'post'      => $post,
            'related'   => $blogModel->getPublished(3),
        ]);
    }

    public function contact()
    {
        $this->loadSettings();
        $this->view('pages/contact', [
            'pageTitle' => 'Contact Us | Royal Ruby Exotic Place',
            'metaDesc'  => 'Get in touch with Royal Ruby Exotic Place for a consultation.',
            'csrf'      => $this->generateCsrf(),
        ]);
    }

    public function submitContact()
    {
        if (!$this->isPost()) {
            $this->redirect(APP_URL . '/contact');
            return;
        }
        if (!$this->validateCsrf()) {
            $this->redirect(APP_URL . '/contact?error=csrf');
            return;
        }

        $name    = strip_tags($this->post('name'));
        $email   = filter_var($this->post('email'), FILTER_SANITIZE_EMAIL);
        $phone   = strip_tags($this->post('phone'));
        $subject = strip_tags($this->post('subject'));
        $message = strip_tags($this->post('message'));

        if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$message) {
            $this->redirect(APP_URL . '/contact?error=validation');
            return;
        }

        require_once APP_ROOT . '/app/models/ContactModel.php';
        $contactModel = new ContactModel();
        $contactModel->saveContact([
            'name'       => $name,
            'email'      => $email,
            'phone'      => $phone,
            'subject'    => $subject,
            'message'    => $message,
            'ip_address' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
        ]);

        $this->redirect(APP_URL . '/contact?success=1');
    }

    public function subscribe()
    {
        if (!$this->isPost()) {
            $this->json(['success' => false], 400);
            return;
        }
        $email = filter_var($this->post('email'), FILTER_SANITIZE_EMAIL);
        $name  = strip_tags($this->post('name'));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => 'Invalid email address.']);
            return;
        }
        require_once APP_ROOT . '/app/models/NewsletterModel.php';
        $model = new NewsletterModel();
        $model->subscribe($email, $name);
        $this->json(['success' => true, 'message' => 'Subscribed successfully!']);
    }

    public function sitemap()
    {
        header('Content-Type: application/xml; charset=utf-8');
        $this->loadSettings();
        require_once APP_ROOT . '/app/models/BlogModel.php';
        require_once APP_ROOT . '/app/models/ServiceModel.php';
        $blogModel    = new BlogModel();
        $serviceModel = new ServiceModel();
        $this->view('sitemap', [
            'posts'    => $blogModel->getPublished(100),
            'services' => $serviceModel->getActive(),
        ]);
    }
}