<?php
class Auth {
    private const SECRET = 'kunci_rahasia_workshop_2026';

    private static function base64UrlEncode(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $data): string {
        $padding = strlen($data) % 4;
        if ($padding) {
            $data .= str_repeat('=', 4 - $padding);
        }
        return base64_decode(strtr($data, '-_', '+/')) ?: '';
    }

    public static function generateToken(array $payload): string {
        $header = self::base64UrlEncode(json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT',
        ]));

        $payload['exp'] = time() + 300;
        $payloadEncoded = self::base64UrlEncode(json_encode($payload));
        $signature = self::base64UrlEncode(
            hash_hmac('sha256', "$header.$payloadEncoded", self::SECRET, true)
        );

        return "$header.$payloadEncoded.$signature";
    }

    public static function validateToken(string $token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        [$header, $payload, $signature] = $parts;
        $valid = self::base64UrlEncode(
            hash_hmac('sha256', "$header.$payload", self::SECRET, true)
        );
        if (!hash_equals($valid, $signature)) {
            return false;
        }

        $data = json_decode(self::base64UrlDecode($payload), true);
        if (!is_array($data) || !isset($data['exp']) || $data['exp'] < time()) {
            return false;
        }
        return $data;
    }

    public static function requireAuth(): array {
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $headers = array_change_key_case($headers, CASE_LOWER);
        $authHeader = $headers['authorization'] ?? '';

        if (!preg_match('/^Bearer\s+(\S+)$/i', trim($authHeader), $matches)) {
            Response::json(401, false, 'Token tidak ditemukan.');
        }

        $payload = self::validateToken($matches[1]);
        if (!$payload) {
            Response::json(401, false, 'Token tidak valid atau kedaluwarsa.');
        }
        return $payload;
    }

    public static function requireAdmin(): array {
        $payload = self::requireAuth();
        if (($payload['role'] ?? null) !== 'admin') {
            Response::json(403, false, 'Akses ditolak, hanya admin yang diizinkan.');
        }
        return $payload;
    }
}
