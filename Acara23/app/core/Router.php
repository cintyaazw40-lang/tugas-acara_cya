<?php

class Router
{
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'buku' => 'BukuController',
        'register' => 'AuthController',
        'login' => 'AuthController' // BARU Acara 20
    ];

    public function dispatch(): void
    {
        // Ambil path dari URL, abaikan query string
        $uri = rawurldecode(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/'
        );

        // Path dasar folder public, contoh: /code/Acara19/public
        $base = rtrim(
            str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])),
            '/'
        );

        if ($base === '.' || $base === '/') {
            $base = '';
        }

        // Buang path dasar (tidak peka huruf besar/kecil)
        if ($base !== '' && stripos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
        }

        // Buang /index.php jika ikut terbawa di URL
        if (stripos($uri, '/index.php') === 0) {
            $uri = substr($uri, strlen('/index.php'));
        }

        $segments = array_values(array_filter(
            explode('/', trim($uri, '/')),
            fn($segment) => $segment !== ''
        ));

        $resource = strtolower($segments[0] ?? 'mahasiswa');

        if (!isset($this->map[$resource])) {
            $this->json(404, [
                'success' => false,
                'message' => 'Endpoint tidak ditemukan'
            ]);
            return;
        }

        $class = $this->map[$resource];
        $controller = new $class();
        $method = $_SERVER['REQUEST_METHOD'];

        // BARU Acara 20: penanganan khusus resource login
        if ($resource === 'login') {
            if ($method !== 'POST') {
                header('Allow: POST');
                $this->json(405, [
                    'success' => false,
                    'message' => 'Gunakan metode POST untuk login'
                ]);
                return;
            }
            $controller->login();
            return;
        }

        // Penanganan khusus resource register (bukan CRUD biasa)
        if ($resource === 'register') {
            if ($method !== 'POST') {
                header('Allow: POST');
                $this->json(405, [
                    'success' => false,
                    'message' => 'Gunakan metode POST untuk register'
                ]);
                return;
            }
            $controller->register();
            return;
        }

        $id = $_GET['id'] ?? null;

        if ($id !== null &&
            (!ctype_digit((string) $id) || (int) $id < 1)) {
            $this->json(400, [
                'success' => false,
                'message' => 'ID tidak valid'
            ]);
            return;
        }

        switch ($method) {
            case 'GET':
                $controller->index();
                break;

            case 'POST':
                if ($id !== null) {
                    $this->json(400, [
                        'success' => false,
                        'message' => 'POST tidak memerlukan ID'
                    ]);
                    return;
                }
                $controller->store();
                break;

            case 'PUT':
                if ($id === null) {
                    $this->json(400, [
                        'success' => false,
                        'message' => 'ID wajib untuk PUT'
                    ]);
                    return;
                }
                $controller->update((int) $id);
                break;

            case 'DELETE':
                if ($id === null) {
                    $this->json(400, [
                        'success' => false,
                        'message' => 'ID wajib untuk DELETE'
                    ]);
                    return;
                }
                $controller->destroy((int) $id);
                break;

            default:
                header('Allow: GET, POST, PUT, DELETE');
                $this->json(405, [
                    'success' => false,
                    'message' => 'Metode HTTP tidak diizinkan'
                ]);
        }
    }

    private function json(int $status, array $data): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}