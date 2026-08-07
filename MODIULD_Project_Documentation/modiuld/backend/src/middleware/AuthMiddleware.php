<?php
namespace App\Middleware;

use App\Helpers\JwtHelper;
use App\Config\Config;
use App\Config\Database;

class AuthMiddleware
{
    public static function handle(): ?object
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
            self::unauthorized('Missing or invalid Authorization header');
            return null;
        }

        $token = substr($authHeader, 7);

        // Check token blacklist
        if (self::isTokenBlacklisted($token)) {
            self::unauthorized('Token has been revoked');
            return null;
        }

        try {
            $decoded = JwtHelper::decode($token, Config::$JWT_SECRET);
            return $decoded;
        } catch (\RuntimeException $e) {
            // Distinguish expired vs invalid for better error messages
            self::unauthorized($e->getMessage());
            return null;
        }
    }

    private static function isTokenBlacklisted(string $token): bool
    {
        try {
            $db = Database::getInstance();
            $tokenHash = hash('sha256', $token);
            $stmt = $db->prepare('SELECT id FROM token_blacklist WHERE token_hash = ? AND expires_at > NOW()');
            $stmt->execute([$tokenHash]);
            return $stmt->fetch() !== false;
        } catch (\Exception $e) {
            return false;
        }
    }

    private static function unauthorized(string $message): void
    {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => $message]);
        exit;
    }
}
