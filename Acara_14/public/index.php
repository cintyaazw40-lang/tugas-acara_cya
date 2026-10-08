<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/Helpers/Logger.php';

try {
    require_once __DIR__ . '/../routes/web.php';
} catch (Throwable $e) {
    Logger::error('public/index.php', $e);
    http_response_code(500);
    echo 'Terjadi kesalahan pada sistem. Silakan coba lagi nanti.';
}