<?php
namespace App\Helpers;

/**
 * Lightweight JWT Helper — HS256 only
 * No external dependencies required.
 */
class JwtHelper
{
    private static string $algo = 'sha256';

    /**
     * Encode payload into a JWT string.
     */
    public static function encode(array $payload, string $secret): string
    {
        $header  = self::base64UrlEncode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
        $payload = self::base64UrlEncode(json_encode($payload));
        $sig     = self::base64UrlEncode(
            hash_hmac(self::$algo, "$header.$payload", $secret, true)
        );
        return "$header.$payload.$sig";
    }

    /**
     * Decode and verify a JWT string.
     * Throws \RuntimeException on failure.
     *
     * @return object Decoded payload
     */
    public static function decode(string $token, string $secret): object
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new \RuntimeException('Invalid token format');
        }

        [$headerB64, $payloadB64, $sigB64] = $parts;

        // Verify signature
        $expectedSig = self::base64UrlEncode(
            hash_hmac(self::$algo, "$headerB64.$payloadB64", $secret, true)
        );
        if (!hash_equals($expectedSig, $sigB64)) {
            throw new \RuntimeException('Invalid token signature');
        }

        // Decode payload
        $payload = json_decode(self::base64UrlDecode($payloadB64));
        if ($payload === null) {
            throw new \RuntimeException('Invalid token payload');
        }

        // Check expiry
        if (isset($payload->exp) && $payload->exp < time()) {
            throw new \RuntimeException('Token has expired');
        }

        return $payload;
    }

    // ─── Helpers ─────────────────────────────────────────────────

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
