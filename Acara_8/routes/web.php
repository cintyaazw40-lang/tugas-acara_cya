<?php
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/ProdiController.php';
require_once __DIR__ . '/../app/Controllers/MatakuliahController.php';
require_once __DIR__ . '/../app/Middleware/AuthMiddleware.php';

$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$requestUri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$uri = trim(substr($requestUri, strlen($basePath)), '/');
$method = $_SERVER['REQUEST_METHOD'];

// Format tiap route: [METHOD, pola URL, Controller, method, perlu login?]
// {id} akan ditangkap sebagai parameter dan dikirim ke method Controller
$routes = [
    ['GET',  '',                          'HomeController',       'index',   false],

    ['GET',  'login',                     'AuthController',       'loginForm', false],
    ['POST', 'login/process',             'AuthController',       'login',     false],
    ['GET',  'logout',                    'AuthController',       'logout',    false],
    ['GET',  'dashboard',                 'AuthController',       'dashboard', true],

    // Mahasiswa
    ['GET',  'mahasiswa',                 'MahasiswaController',  'index',   true],
    ['GET',  'mahasiswa/create',          'MahasiswaController',  'create',  true],
    ['POST', 'mahasiswa',                 'MahasiswaController',  'store',   true],
    ['GET',  'mahasiswa/{id}/edit',       'MahasiswaController',  'edit',    true],
    ['POST', 'mahasiswa/{id}/update',     'MahasiswaController',  'update',  true],
    ['POST', 'mahasiswa/{id}/delete',     'MahasiswaController',  'destroy', true],

    // Prodi
    ['GET',  'prodi',                     'ProdiController',      'index',   true],
    ['GET',  'prodi/create',              'ProdiController',      'create',  true],
    ['POST', 'prodi',                     'ProdiController',      'store',   true],
    ['GET',  'prodi/{id}/edit',           'ProdiController',      'edit',    true],
    ['POST', 'prodi/{id}/update',         'ProdiController',      'update',  true],
    ['POST', 'prodi/{id}/delete',         'ProdiController',      'destroy', true],

    // Mata Kuliah
    ['GET',  'matakuliah',                'MatakuliahController', 'index',   true],
    ['GET',  'matakuliah/create',         'MatakuliahController', 'create',  true],
    ['POST', 'matakuliah',                'MatakuliahController', 'store',   true],
    ['GET',  'matakuliah/{id}/edit',      'MatakuliahController', 'edit',    true],
    ['POST', 'matakuliah/{id}/update',    'MatakuliahController', 'update',  true],
    ['POST', 'matakuliah/{id}/delete',    'MatakuliahController', 'destroy', true],
];

foreach ($routes as [$routeMethod, $pattern, $controllerName, $action, $needsAuth]) {
    if ($routeMethod !== $method) {
        continue;
    }

    // Ubah {id} jadi regex penangkap angka (quote dulu, baru ganti placeholder)
    $quotedPattern = preg_quote($pattern, '#');
    $regex = '#^' . str_replace('\{id\}', '(\d+)', $quotedPattern) . '$#';

    if (preg_match($regex, $uri, $matches)) {
        if ($needsAuth) {
            $middleware = new AuthMiddleware();
            $middleware->handle();
        }

        $controller = new $controllerName();
        $params = array_slice($matches, 1);
        $params = array_map('intval', $params);

        call_user_func_array([$controller, $action], $params);
        return;
    }
}

http_response_code(404);
echo "404 - Halaman tidak ditemukan";
