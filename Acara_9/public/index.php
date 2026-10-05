<?php
// Session harus dimulai sebelum ada output apapun
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../routes/web.php';
