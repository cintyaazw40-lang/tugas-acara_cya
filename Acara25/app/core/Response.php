<?php

class Response
{
    public static function json(
        int $httpCode,
        bool $success,
        string $message,
        $data = null,
        array $errors = []
    ): void {
        http_response_code($httpCode);
        header('Content-Type: application/json; charset=utf-8');

        $body = [
            'success'   => $success,
            'message'   => $message,
            'data'      => $data,
        ];

        // Field errors hanya muncul kalau memang ada kesalahan validasi
        if (!empty($errors)) {
            $body['errors'] = $errors;
        }

        $body['timestamp'] = date('c');

        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

        // JSON_PRETTY_PRINT hanya pada mode pengembangan
        if (defined('APP_ENV') && APP_ENV === 'development') {
            $flags |= JSON_PRETTY_PRINT;
        }

        echo json_encode($body, $flags);
        exit;
    }
}