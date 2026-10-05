<?php
class AuthController
{
    private function base(): string
    {
        return str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    }

    public function loginForm()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === 'admin' && $password === '12345') {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $username;
            header("Location: {$this->base()}/dashboard?login=1");
            exit;
        }

        header("Location: {$this->base()}/login?error=1");
        exit;
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();

        header("Location: {$this->base()}/login?logout=1");
        exit;
    }

    public function dashboard()
    {
        // AuthMiddleware sudah memastikan user login sebelum sampai sini
        $username = $_SESSION['username'] ?? 'Admin';
        require_once __DIR__ . '/../Views/dashboard/index.php';
    }
}
