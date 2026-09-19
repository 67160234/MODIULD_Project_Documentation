<?php
class JwtHelper {
    private static string $secret = '';

    private static function getSecret(): string {
        if (self::$secret === '') {
            self::$secret = getenv('JWT_SECRET') ?: 'modiuld_super_secret_jwt_key_2026';
        }
        return self::$secret;
    }

    public static function encode(array $payload): string {
        $header  = base64_encode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
        $payload['iat'] = time();
        $payload['exp'] = $payload['exp'] ?? time() + 86400 * 7; // 7 days by default
        $body    = base64_encode(json_encode($payload));
        $sig     = base64_encode(hash_hmac('sha256', "$header.$body", self::getSecret(), true));
        return "$header.$body.$sig";
    }

    public static function decode(string $token): ?array {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;
        [$header, $body, $sig] = $parts;
        $expected = base64_encode(hash_hmac('sha256', "$header.$body", self::getSecret(), true));
        if (!hash_equals($expected, $sig)) return null;
        $payload = json_decode(base64_decode($body), true);
        if (!$payload || $payload['exp'] < time()) return null;
        return $payload;
    }

    public static function fromRequest(): ?array {
        $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s+(.+)/i', $auth, $m)) {
            return self::decode($m[1]);
        }
        return null;
    }
}
