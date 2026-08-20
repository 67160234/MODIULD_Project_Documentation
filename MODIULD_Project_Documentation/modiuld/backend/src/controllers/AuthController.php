<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/jwt.php';

class AuthController {

    public static function register(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        $email    = trim($data['email']    ?? '');
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');
        $confirm  = trim($data['confirm_password'] ?? '');
        $name     = trim($data['display_name'] ?? '');

        // Validate
        if (!$email || !$username || !$password || !$confirm) {
            self::json(['error' => 'Email, username, password and confirm_password are required.'], 422); return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            self::json(['error' => 'Invalid email address.'], 422); return;
        }
        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
            self::json(['error' => 'Username must be 3-20 characters long and contain only letters, numbers, and underscores.'], 422); return;
        }
        if ($password !== $confirm) {
            self::json(['error' => 'Passwords do not match.'], 422); return;
        }
        if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            self::json(['error' => 'Password must be at least 8 characters, include 1 uppercase letter and 1 number.'], 422); return;
        }

        $db = Database::getConnection();
        
        // Check email
        $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            self::json(['error' => 'An account with this email already exists.'], 409); return;
        }

        // Check username
        $stmt = $db->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            self::json(['error' => 'This username is already taken.'], 409); return;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $ins  = $db->prepare('INSERT INTO users (email, username, password_hash, display_name) VALUES (?, ?, ?, ?)');
        $ins->execute([$email, $username, $hash, $name ?: $username]);
        $userId = $db->lastInsertId();

        $token = JwtHelper::encode(['sub' => $userId, 'email' => $email, 'username' => $username]);
        self::json(['message' => 'Registration successful.', 'token' => $token, 'user' => ['id' => $userId, 'email' => $email, 'username' => $username, 'display_name' => $name ?: $username]], 201);
    }

    public static function login(): void {
        $data     = json_decode(file_get_contents('php://input'), true);
        $loginId  = trim($data['email']    ?? ''); // Can be email or username
        $password = trim($data['password'] ?? '');

        if (!$loginId || !$password) {
            self::json(['error' => 'Email/Username and password are required.'], 422); return;
        }

        $db   = Database::getConnection();
        
        if (strpos($loginId, '@') !== false) {
            $stmt = $db->prepare('SELECT id, email, username, password_hash, display_name FROM users WHERE email = ?');
        } else {
            $stmt = $db->prepare('SELECT id, email, username, password_hash, display_name FROM users WHERE username = ?');
        }
        
        $stmt->execute([$loginId]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            self::json(['error' => 'Invalid email/username or password.'], 401); return;
        }

        $token = JwtHelper::encode(['sub' => $user['id'], 'email' => $user['email'], 'username' => $user['username']]);
        self::json(['token' => $token, 'user' => ['id' => $user['id'], 'email' => $user['email'], 'username' => $user['username'], 'display_name' => $user['display_name']]]);
    }

    public static function logout(): void {
        // JWT is stateless; client must clear the token
        self::json(['message' => 'Logged out successfully.']);
    }

    public static function me(): void {
        $payload = JwtHelper::fromRequest();
        if (!$payload) { self::json(['error' => 'Unauthorized.'], 401); return; }

        $db   = Database::getConnection();
        $stmt = $db->prepare('SELECT id, email, username, display_name, role, created_at FROM users WHERE id = ?');
        $stmt->execute([$payload['sub']]);
        $user = $stmt->fetch();
        if (!$user) { self::json(['error' => 'User not found.'], 404); return; }
        self::json(['user' => $user]);
    }

    public static function changePassword(): void {
        $payload = JwtHelper::fromRequest();
        if (!$payload) { self::json(['error' => 'Unauthorized.'], 401); return; }

        $data        = json_decode(file_get_contents('php://input'), true);
        $oldPass     = trim($data['old_password'] ?? '');
        $newPass     = trim($data['new_password'] ?? '');
        $confirmPass = trim($data['confirm_password'] ?? '');

        if (!$oldPass || !$newPass || !$confirmPass) {
            self::json(['error' => 'All password fields are required.'], 422); return;
        }
        if ($newPass !== $confirmPass) {
            self::json(['error' => 'New passwords do not match.'], 422); return;
        }
        if (strlen($newPass) < 8 || !preg_match('/[A-Z]/', $newPass) || !preg_match('/[0-9]/', $newPass)) {
            self::json(['error' => 'New password must be at least 8 chars, 1 uppercase, 1 number.'], 422); return;
        }

        $db   = Database::getConnection();
        $stmt = $db->prepare('SELECT password_hash FROM users WHERE id = ?');
        $stmt->execute([$payload['sub']]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($oldPass, $user['password_hash'])) {
            self::json(['error' => 'Current password is incorrect.'], 401); return;
        }
        $hash = password_hash($newPass, PASSWORD_BCRYPT);
        $upd  = $db->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        $upd->execute([$hash, $payload['sub']]);
        self::json(['message' => 'Password updated successfully.']);
    }

    private static function json(array $data, int $status = 200): void {
        http_response_code($status);
        echo json_encode($data);
    }
}
