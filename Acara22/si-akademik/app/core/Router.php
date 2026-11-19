<?php
class Router {
    // Peta sederhana: nama resource pada URL => nama class Controller
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'register' => 'AuthController',
        'login' => 'AuthController',
    ];

    public function dispatch(): void {
        try {
            $this->route();
        } catch (Throwable $e) {
            // Jaring pengaman: error tak terduga (mis. kegagalan query) tetap
            // dijawab dengan JSON berformat seragam, bukan halaman error PHP.
            $errors = (defined('APP_ENV') && APP_ENV === 'development')
                ? ['server' => [$e->getMessage()]]
                : [];
            Response::json(500, false, 'Terjadi kesalahan pada server.', null, $errors);
        }
    }

    private function route(): void {
        $uri      = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $uri);
        $resource = end($segments) ?: 'mahasiswa';

        $controllerName = $this->map[$resource] ?? null;
        if (!$controllerName) {
            Response::json(404, false, 'Endpoint tidak ditemukan.');
        }

        $controller = new $controllerName();

        if ($resource === 'register') {
            $controller->register();
            return;
        }
        if ($resource === 'login') {
            $controller->login();
            return;
        }

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':    $controller->index(); break;
            case 'POST':   $controller->store(); break;
            case 'PUT':    $controller->update($_GET['id'] ?? null); break;
            case 'DELETE': $controller->destroy($_GET['id'] ?? null); break;
            default:
                header('Allow: GET, POST, PUT, DELETE');
                Response::json(405, false, 'Method tidak diizinkan.');
        }
    }
}
