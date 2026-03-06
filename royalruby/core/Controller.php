<?php
/**
 * Royal Ruby Exotic Place
 * Base Controller — view rendering, helpers, auth
 */
class Controller
{
    /** @var array */
    protected $data = [];

    /**
     * Load site settings into $this->data['settings']
     * Requires SettingsModel to already be included.
     * @return void
     */
    protected function loadSettings()
    {
        /** @var string $modelFile */
        $modelFile = APP_ROOT . '/app/models/SettingsModel.php';
        if (!class_exists('SettingsModel')) {
            require_once $modelFile;
        }
        $settingsModel = new SettingsModel();
        $this->data['settings'] = $settingsModel->getAllKeyed();
    }

    /**
     * Render a view file, injecting $data into scope
     * @param  string $viewPath  relative to app/views/, no .php
     * @param  array  $data
     * @return void
     */
    protected function view($viewPath, array $data = [])
    {
        $data = array_merge($this->data, $data);
        extract($data, EXTR_SKIP);
        $file = APP_ROOT . '/app/views/' . $viewPath . '.php';
        if (!file_exists($file)) {
            http_response_code(404);
            echo "<h1>View not found: {$viewPath}</h1>";
            return;
        }
        require $file;
    }

    /**
     * HTTP redirect
     * @param  string $url
     * @return void
     */
    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Send JSON response and exit
     * @param  array $data
     * @param  int   $code
     * @return void
     */
    protected function json(array $data, $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Generate (or return existing) CSRF token
     * @return string
     */
    protected function generateCsrf()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate CSRF token submitted via POST
     * @return bool
     */
    protected function validateCsrf()
    {
        $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
        return !empty($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * HTML-encode a string for safe output
     * @param  string $str
     * @return string
     */
    protected function escape($str)
    {
        return htmlspecialchars((string)$str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Redirect to login if no admin session, enforce timeout
     * @return void
     */
    protected function requireAuth()
    {
        if (!isset($_SESSION['admin_id'])) {
            $this->redirect(APP_URL . '/admin/login');
        }
        $lifetime = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600;
        if (isset($_SESSION['last_activity'])
            && (time() - (int)$_SESSION['last_activity']) > $lifetime
        ) {
            session_unset();
            session_destroy();
            $this->redirect(APP_URL . '/admin/login?timeout=1');
        }
        $_SESSION['last_activity'] = time();
    }

    /**
     * @return bool
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Get trimmed POST value
     * @param  string $key
     * @param  string $default
     * @return string
     */
    protected function post($key, $default = '')
    {
        return isset($_POST[$key]) ? trim((string)$_POST[$key]) : $default;
    }

    /**
     * Get trimmed GET value
     * @param  string $key
     * @param  string $default
     * @return string
     */
    protected function get($key, $default = '')
    {
        return isset($_GET[$key]) ? trim((string)$_GET[$key]) : $default;
    }

    /**
     * Generate a URL-safe slug from a string
     * @param  string $str
     * @return string
     */
    protected function makeSlug($str)
    {
        $str = strtolower(trim((string)$str));
        $str = preg_replace('/[^a-z0-9\s\-]/', '', $str);
        $str = preg_replace('/[\s\-]+/', '-', $str);
        return trim($str, '-');
    }

    /**
     * Handle a file upload, return relative storage path or ''
     * @param  string $field   $_FILES key
     * @param  string $folder  subfolder under UPLOAD_DIR
     * @return string
     */
    protected function handleUpload($field, $folder)
    {
        if (empty($_FILES[$field]['tmp_name'])) {
            return '';
        }
        $file    = $_FILES[$field];
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowed, true)) {
            return '';
        }
        if ($file['size'] > 5 * 1024 * 1024) {
            return '';
        }
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $name = uniqid('img_', true) . '.' . $ext;
        $dir  = UPLOAD_DIR . $folder . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        if (move_uploaded_file($file['tmp_name'], $dir . $name)) {
            return 'uploads/' . $folder . '/' . $name;
        }
        return '';
    }
}