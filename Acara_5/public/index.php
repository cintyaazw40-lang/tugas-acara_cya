<?php
require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

use App\Controllers\HomeController;
use App\Controllers\MahasiswaController;

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Sesuaikan dengan lokasi folder project di htdocs
$base = '/web server/minggu 3/Acara_5/public';
if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'];

// Tugas Mandiri: dukungan parameter URL, misal /mahasiswa/5
if ($method === 'GET' && preg_match('#^/mahasiswa/(\d+)$#', $uri, $matches)) {
    $id = (int) $matches[1];
    $controller = new MahasiswaController();
    $controller->show($id);
    exit;
}

if (isset($routes[$method][$uri])) {
    [$controllerName, $action] = $routes[$method][$uri];
    $controllerClass = "App\\Controllers\\{$controllerName}";
    $controller = new $controllerClass();
    $controller->$action();
} else {
    http_response_code(404);
    echo "404 - Halaman tidak ditemukan";
}