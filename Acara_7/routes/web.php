<?php
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Middleware/AuthMiddleware.php';

$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$requestUri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$uri = trim(substr($requestUri, strlen($basePath)), '/');

switch ($uri) {
    case '':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'login':
        $controller = new AuthController();
        $controller->loginForm();
        break;

    case 'login/process':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'dashboard':
        $middleware = new AuthMiddleware();
        $middleware->handle();

        $controller = new AuthController();
        $controller->dashboard();
        break;

    case 'mahasiswa':
        $middleware = new AuthMiddleware();
        $middleware->handle();

        $controller = new MahasiswaController();
        $controller->index();
        break;

    default:
        http_response_code(404);
        echo "404 - Halaman tidak ditemukan";
        break;
}
