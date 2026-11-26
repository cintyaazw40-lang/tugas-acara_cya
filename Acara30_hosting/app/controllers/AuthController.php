<?php
class AuthController {
    private User $model;

    public function __construct() {
        $this->model = new User();
    }

    public function register(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            Response::json(400, false, 'Username, email, dan password wajib diisi');
            return;
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        try {
            $id = $this->model->create($data);
            Response::json(201, true, 'Registrasi berhasil', ['id' => $id]);
        } catch (InvalidArgumentException $e) {
            Response::json(400, false, $e->getMessage());
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                Response::json(409, false, 'Username atau email sudah terdaftar');
            } else {
                Response::json(500, false, 'Terjadi kesalahan server');
            }
        }
    }

    // BARU Acara 20
    public function login(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        if (!is_string($username) || !is_string($password) ||
            $username === '' || $password === '') {
            Response::json(400, false, 'Username dan password wajib diisi');
            return;
        }

        try {
            $user = $this->model->findByUsername($username);
        } catch (PDOException $e) {
            Response::json(500, false, 'Terjadi kesalahan server');
            return;
        }

        if (!$user || !password_verify($password, $user['password'])) {
            Response::json(401, false, 'Username atau password salah');
            return;
        }

        $token = Auth::generateToken(['id' => $user['id'], 'role' => $user['role']]);

        Response::json(200, true, 'Login berhasil', [
            'token' => $token,
            'role'  => $user['role']
        ]);
    }
}