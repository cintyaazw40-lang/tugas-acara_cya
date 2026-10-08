<?php
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaApiController.php';
require_once __DIR__ . '/../app/Controllers/ProdiController.php';
require_once __DIR__ . '/../app/Controllers/MatakuliahController.php';
require_once __DIR__ . '/../app/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../app/Repositories/ProdiRepository.php';
require_once __DIR__ . '/../app/Services/MahasiswaService.php';

$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$requestUri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$uri = trim(substr($requestUri, strlen($basePath)), '/');
$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    ['GET',  '',                          'HomeController',       'index',   false],

    ['GET',  'login',                     'AuthController',       'loginForm', false],
    ['POST', 'login/process',             'AuthController',       'login',     false],
    ['GET',  'logout',                    'AuthController',       'logout',    false],
    ['GET',  'dashboard',                 'AuthController',       'dashboard', true],

    ['GET',  'mahasiswa',                 'MahasiswaController',  'index',   true],
    ['GET',  'mahasiswa/create',          'MahasiswaController',  'create',  true],
    ['POST', 'mahasiswa',                 'MahasiswaController',  'store',   true],
    ['GET',  'mahasiswa/{id}/edit',       'MahasiswaController',  'edit',    true],
    ['POST', 'mahasiswa/{id}/update',     'MahasiswaController',  'update',  true],
    ['POST', 'mahasiswa/{id}/delete',     'MahasiswaController',  'destroy', true],

    ['GET',  'api/mahasiswa',             'MahasiswaApiController', 'index', false],
    ['POST', 'api/mahasiswa',             'MahasiswaApiController', 'store', false],

    ['GET',  'prodi',                     'ProdiController',      'index',   true],
    ['GET',  'prodi/create',              'ProdiController',      'create',  true],
    ['POST', 'prodi',                     'ProdiController',      'store',   true],
    ['GET',  'prodi/{id}/edit',           'ProdiController',      'edit',    true],
    ['POST', 'prodi/{id}/update',         'ProdiController',      'update',  true],
    ['POST', 'prodi/{id}/delete',         'ProdiController',      'destroy', true],

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

    $quotedPattern = preg_quote($pattern, '#');
    $regex = '#^' . str_replace('\{id\}', '(\d+)', $quotedPattern) . '$#';

    if (preg_match($regex, $uri, $matches)) {
        if ($needsAuth) {
            $middleware = new AuthMiddleware();
            $middleware->handle();
        }

        if ($controllerName === 'MahasiswaController') {
            $database = Database::getInstance();
            $repository = new MahasiswaRepository($database);
            $prodiRepository = new ProdiRepository($database);
            $service = new MahasiswaService($repository, $prodiRepository);
            $controller = new MahasiswaController($repository, $service, $prodiRepository);
        } elseif ($controllerName === 'MahasiswaApiController') {
            try {
                $database = Database::getInstance();
                $repository = new MahasiswaRepository($database);
                $prodiRepository = new ProdiRepository($database);
                $service = new MahasiswaService($repository, $prodiRepository);
                $controller = new MahasiswaApiController($repository, $service);
            } catch (Throwable $e) {
                Logger::error('web.php::MahasiswaApiController', $e);
                http_response_code(500);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => false,
                    'message' => 'Terjadi kesalahan pada server',
                    'data' => null,
                ]);
                return;
            }
        } else {
            $controller = new $controllerName();
        }

        $params = array_slice($matches, 1);
        $params = array_map('intval', $params);

        call_user_func_array([$controller, $action], $params);
        return;
    }
}

http_response_code(404);
echo "404 - Halaman tidak ditemukan";