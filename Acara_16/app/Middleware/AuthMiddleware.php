<?php
class AuthMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['login']) || $_SESSION['login'] !== true) {
            $base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
            header("Location: {$base}/login");
            exit;
        }
    }
}
