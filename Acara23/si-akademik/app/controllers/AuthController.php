<?php
class AuthController {
    private User $model;

    public function __construct() {
        $this->model = new User();
    }

    public function register(): void {
        $data = $this->readJsonBody();
        $required = ['username', 'email', 'password'];
        $errors = [];

        foreach ($required as $field) {
            if (!isset($data[$field]) || !is_string($data[$field]) || trim($data[$field]) === '') {
                $errors[$field][] = "Field $field wajib diisi.";
            }
        }
        if ($errors) {
            Response::json(400, false, 'Data registrasi wajib dilengkapi.', null, $errors);
        }

        $username = trim($data['username']);
        $email = trim($data['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::json(422, false, 'Validasi data gagal.', null, [
                'email' => ['Format email tidak valid.'],
            ]);
        }
        if ($this->model->exists($username, $email)) {
            Response::json(409, false, 'Username atau email sudah digunakan.');
        }

        try {
            $id = $this->model->create([
                'username' => $username,
                'email' => $email,
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'role' => 'user',
            ]);
            Response::json(201, true, 'Registrasi berhasil.', ['id' => $id]);
        } catch (InvalidArgumentException $e) {
            Response::json(422, false, 'Validasi data gagal.', null, [
                'email' => [$e->getMessage()],
            ]);
        } catch (PDOException $e) {
            $errors = (defined('APP_ENV') && APP_ENV === 'development')
                ? ['database' => [$e->getMessage()]]
                : [];
            Response::json(500, false, 'Registrasi gagal.', null, $errors);
        }
    }

    public function login(): void {
        $data = $this->readJsonBody();
        $username = trim((string) ($data['username'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        if ($username === '' || $password === '') {
            Response::json(400, false, 'Username dan password wajib diisi.', null, [
                'username' => $username === '' ? ['Field username wajib diisi.'] : [],
                'password' => $password === '' ? ['Field password wajib diisi.'] : [],
            ]);
        }

        $user = $this->model->findByUsername($username);
        if (!$user || !password_verify($password, $user['password'])) {
            Response::json(401, false, 'Username atau password salah.');
        }

        $token = Auth::generateToken([
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
        ]);

        Response::json(200, true, 'Login berhasil.', [
            'token' => $token,
            'expires_in' => 300,
            'role' => $user['role'],
        ]);
    }

    private function readJsonBody(): array {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        if (!is_array($data) || json_last_error() !== JSON_ERROR_NONE) {
            Response::json(400, false, 'Body harus berisi JSON yang valid.');
        }
        return $data;
    }
}
