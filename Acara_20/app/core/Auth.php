<?php
class Auth {
    const SECRET = 'kunci_rahasia_workshop_2026';
    // Tugas Mandiri 2: masa berlaku token diperpendek menjadi 5 menit (300 detik)
    const TOKEN_TTL = 300;

    private static function base64url(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64urlDecode(string $data): string {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public static function generateToken(array $payload): string {
        $header = self::base64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload['exp'] = time() + self::TOKEN_TTL;
        $payloadEnc = self::base64url(json_encode($payload));
        $signature  = self::base64url(hash_hmac('sha256', "$header.$payloadEnc", self::SECRET, true));
        return "$header.$payloadEnc.$signature";
    }

    public static function validateToken(string $token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;
        [$header, $payload, $signature] = $parts;
        $valid = self::base64url(hash_hmac('sha256', "$header.$payload", self::SECRET, true));
        if (!hash_equals($valid, $signature)) return false;
        $data = json_decode(self::base64urlDecode($payload), true);
        if (!is_array($data) || !isset($data['exp']) || $data['exp'] < time()) return false;
        return $data;
    }

    // Membaca header Authorization dari berbagai sumber (Apache/XAMPP kadang tidak meneruskannya)
    private static function authorizationHeader(): string {
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) return $_SERVER['HTTP_AUTHORIZATION'];
        if (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) return $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        if (function_exists('getallheaders')) {
            foreach (getallheaders() as $name => $value) {
                if (strtolower($name) === 'authorization') return $value;
            }
        }
        return '';
    }

    public static function requireAuth(): array {
        $authHeader = self::authorizationHeader();
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $m)) {
            Response::json(401, false, 'Token tidak ditemukan');
        }
        $payload = self::validateToken($m[1]);
        if (!$payload) {
            Response::json(401, false, 'Token tidak valid atau kedaluwarsa');
        }
        return $payload;
    }
}
