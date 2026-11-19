<?php
/**
 * Response envelope terstandar untuk seluruh endpoint REST API.
 *
 * Bentuk response (selalu sama, baik sukses maupun gagal):
 * {
 *   "success":   true|false,
 *   "message":   "pesan untuk manusia",
 *   "data":      ... | null,
 *   "errors":    { "field": ["pesan kesalahan", ...] }   // {} bila tidak ada
 *   "timestamp": "2026-01-01T10:00:00+07:00"
 * }
 */
class Response {
    /**
     * @param int         $httpCode Kode status HTTP (200, 201, 400, 404, 422, 500, ...)
     * @param bool        $success  Indikator keberhasilan proses
     * @param string      $message  Pesan ringkas hasil proses
     * @param mixed       $data     Data hasil (array/objek) atau null
     * @param array       $errors   Daftar kesalahan validasi per field,
     *                              contoh: ['nim' => ['Format NIM tidak valid.']]
     */
    public static function json(
        int $httpCode,
        bool $success,
        string $message,
        $data = null,
        array $errors = []
    ): void {
        http_response_code($httpCode);
        header('Content-Type: application/json; charset=utf-8');

        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        // Format rapi hanya pada mode pengembangan (Tugas Mandiri 2).
        if (defined('APP_ENV') && APP_ENV === 'development') {
            $flags |= JSON_PRETTY_PRINT;
        }

        $json = json_encode([
            'success'   => $success,
            'message'   => $message,
            'data'      => $data,
            // Selalu berupa objek ({}), agar tipe datanya konsisten bagi client.
            'errors'    => $errors === [] ? new stdClass() : $errors,
            'timestamp' => date('c'),
        ], $flags);

        echo $json !== false
            ? $json
            : '{"success":false,"message":"Gagal menyusun response JSON."}';
        exit;
    }
}
