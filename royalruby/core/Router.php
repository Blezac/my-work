<?php
/**
 * Royal Ruby Exotic Place
 * Router — maps clean URLs to Controller@action
 */
class Router
{
    /** @var array */
    private $routes = [];

    /** @var string */
    private $basePath;

    /**
     * @param string $basePath  e.g. /royalruby/public
     */
    public function __construct($basePath = '')
    {
        $this->basePath = rtrim((string)$basePath, '/');
    }

    /**
     * Register a GET route
     * @param string $path
     * @param string $controller
     * @param string $action
     */
    public function get($path, $controller, $action)
    {
        $this->routes['GET'][$path] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Register a POST route
     * @param string $path
     * @param string $controller
     * @param string $action
     */
    public function post($path, $controller, $action)
    {
        $this->routes['POST'][$path] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Dispatch the current request
     * @return void
     */
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri    = rtrim(str_replace($this->basePath, '', (string)$uri), '/');
        if ($uri === '') {
            $uri = '/';
        }

        // Exact match
        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            $this->execute($route['controller'], $route['action'], []);
            return;
        }

        // Pattern match for {param} segments
        $methodRoutes = isset($this->routes[$method]) ? $this->routes[$method] : [];
        foreach ($methodRoutes as $pattern => $route) {
            $regex = preg_replace('/\{[a-z_]+\}/', '([^/]+)', $pattern);
            $regex = '#^' . $regex . '$#';
            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches);
                $this->execute($route['controller'], $route['action'], $matches);
                return;
            }
        }

        // 404
        http_response_code(404);
        $file = APP_ROOT . '/app/views/pages/404.php';
        if (file_exists($file)) {
            require $file;
        } else {
            echo '<h1>404 — Page Not Found</h1>';
        }
    }

    /**
     * Instantiate controller and call action
     * @param  string $controllerName
     * @param  string $action
     * @param  array  $params
     * @return void
     */
    private function execute($controllerName, $action, array $params)
    {
        $file = APP_ROOT . '/app/controllers/' . $controllerName . '.php';
        if (!file_exists($file)) {
            die("Controller file not found: {$controllerName}.php");
        }
        require_once $file;
        if (!class_exists($controllerName)) {
            die("Controller class not found: {$controllerName}");
        }
        $controller = new $controllerName();
        if (!method_exists($controller, $action)) {
            die("Action not found: {$controllerName}::{$action}");
        }
        call_user_func_array([$controller, $action], $params);
    }
}