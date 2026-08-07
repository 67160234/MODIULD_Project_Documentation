<?php
namespace App\Controllers;

use App\Helpers\JwtHelper;
use App\Config\Config;
use App\Config\Database;
use App\Models\User;

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // POST /api/register
    public function register(): void
    {
        $body = $this->getBody();

        // Validate required fields
        $required = ['username', 'email', 'password'];
        foreach ($required as $field) {
            if (empty($body[$field])) {
                $this->json(['success' => false, 'message' => "Field '$field' is required"], 422);
                return;
            }
        }

        $username = trim($body['username']);
        $email    = trim($body['email']);
        $password = $body['password'];
        $fullName = trim($body['full_name'] ?? '');

        // Validate username (3-50 chars, alphanumeric + underscore)
        if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
            $this->json(['success' => false, 'message' => 'Username must be 3-50 characters (letters, numbers, underscore only)'], 422);
            return;
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => 'Invalid email format'], 422);
            return;
        }

        // Validate password (min 8 chars, 1 uppercase, 1 number)
        if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $this->json(['success' => false, 'message' => 'Password must be at least 8 characters with 1 uppercase letter and 1 number'], 422);
            return;
        }

        // Check existing email
        if ($this->userModel->emailExists($email)) {
            $this->json(['success' => false, 'message' => 'Email already registered'], 409);
            return;
        }

        // Check existing username
        if ($this->userModel->usernameExists($username)) {
            $this->json(['success' => false, 'message' => 'Username already taken'], 409);
            return;
        }

        // Create user
        $userId = $this->userModel->create([
            'username'  => $username,
            'email'     => $email,
            'password'  => $password,
            'full_name' => $fullName ?: null,
        ]);

        $user = $this->userModel->findById($userId);

        $this->json([
            'success' => true,
            'message' => 'Registration successful',
            'data'    => ['user' => $user]
        ], 201);
    }

    // POST /api/login
    public function login(): void
    {
        $body = $this->getBody();

        if (empty($body['email']) || empty($body['password'])) {
            $this->json(['success' => false, 'message' => 'Email and password are required'], 422);
            return;
        }

        $user = $this->userModel->findByEmail(trim($body['email']));

        if (!$user || !$this->userModel->verifyPassword($body['password'], $user['password_hash'])) {
            $this->json(['success' => false, 'message' => 'Invalid email or password'], 401);
            return;
        }

        $now = time();
        $payload = [
            'iat'  => $now,
            'exp'  => $now + Config::$JWT_EXPIRE,
            'sub'  => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'role' => $user['role'],
        ];

        $token = JwtHelper::encode($payload, Config::$JWT_SECRET);

        $this->json([
            'success' => true,
            'message' => 'Login successful',
            'data'    => [
                'token'      => $token,
                'expires_in' => Config::$JWT_EXPIRE,
                'token_type' => 'Bearer',
                'user'       => [
                    'id'        => $user['id'],
                    'username'  => $user['username'],
                    'email'     => $user['email'],
                    'full_name' => $user['full_name'],
                    'role'      => $user['role'],
                ]
            ]
        ]);
    }

    // POST /api/logout
    public function logout(): void
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        if (!str_starts_with($authHeader, 'Bearer ')) {
            $this->json(['success' => false, 'message' => 'No token provided'], 400);
            return;
        }

        $token = substr($authHeader, 7);
        $tokenHash = hash('sha256', $token);

        try {
            // Decode to get expiry
            $decoded = JwtHelper::decode($token, Config::$JWT_SECRET);
            $expiresAt = date('Y-m-d H:i:s', $decoded->exp);

            $db = Database::getInstance();
            $stmt = $db->prepare('INSERT INTO token_blacklist (token_hash, user_id, expires_at) VALUES (?, ?, ?)');
            $stmt->execute([$tokenHash, $decoded->sub, $expiresAt]);

            // Clean expired blacklist entries
            $db->exec('DELETE FROM token_blacklist WHERE expires_at < NOW()');

        } catch (\Exception $e) {
            // Token already invalid, just return success
        }

        $this->json(['success' => true, 'message' => 'Logged out successfully']);
    }

    // POST /api/change-password
    public function changePassword(): void
    {
        $user = \App\Middleware\AuthMiddleware::handle();
        if (!$user) return;

        $body = $this->getBody();

        if (empty($body['current_password']) || empty($body['new_password'])) {
            $this->json(['success' => false, 'message' => 'current_password and new_password are required'], 422);
            return;
        }

        $dbUser = $this->userModel->findByEmail($user->email);
        if (!$dbUser || !$this->userModel->verifyPassword($body['current_password'], $dbUser['password_hash'])) {
            $this->json(['success' => false, 'message' => 'Current password is incorrect'], 401);
            return;
        }

        // Validate new password
        $newPassword = $body['new_password'];
        if (strlen($newPassword) < 8 || !preg_match('/[A-Z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword)) {
            $this->json(['success' => false, 'message' => 'New password must be at least 8 characters with 1 uppercase letter and 1 number'], 422);
            return;
        }

        $this->userModel->updatePassword((int)$user->sub, $newPassword);

        $this->json(['success' => true, 'message' => 'Password changed successfully']);
    }

    // ----- Helpers -----
    private function getBody(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            return json_decode(file_get_contents('php://input'), true) ?? [];
        }
        return $_POST;
    }

    private function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        echo json_encode($data);
    }
}
