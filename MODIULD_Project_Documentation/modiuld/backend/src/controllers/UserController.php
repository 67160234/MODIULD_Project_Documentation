<?php
namespace App\Controllers;

use App\Models\User;
use App\Middleware\AuthMiddleware;

class UserController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // GET /api/me  — real: reads from JWT
    public function me(): void
    {
        $auth = AuthMiddleware::handle();
        if (!$auth) return;

        $user = $this->userModel->findById((int)$auth->sub);
        if (!$user) {
            $this->json(['success' => false, 'message' => 'User not found'], 404);
            return;
        }

        $this->json(['success' => true, 'data' => ['user' => $user]]);
    }

    // GET /api/check-username/{name}  — real: checks DB
    public function checkUsername(string $username): void
    {
        $username = trim($username);
        if (empty($username)) {
            $this->json(['success' => false, 'message' => 'Username is required'], 422);
            return;
        }

        $exists = $this->userModel->usernameExists($username);
        $this->json([
            'success'   => true,
            'available' => !$exists,
            'message'   => $exists ? 'Username is already taken' : 'Username is available',
        ]);
    }

    // GET /api/users  — MOCK
    public function index(): void
    {
        $auth = AuthMiddleware::handle();
        if (!$auth) return;

        $page  = (int)($_GET['page'] ?? 1);
        $limit = min((int)($_GET['limit'] ?? 10), 100);

        // Return mock paginated user list
        $this->json([
            'success' => true,
            'message' => '[MOCK] User list',
            'data'    => [
                'items' => $this->mockUsers(),
                'total' => 25,
                'page'  => $page,
                'limit' => $limit,
                'total_pages' => 3,
            ]
        ]);
    }

    // GET /api/users/{id}  — MOCK
    public function show(int $id): void
    {
        $auth = AuthMiddleware::handle();
        if (!$auth) return;

        $mockUser = array_filter($this->mockUsers(), fn($u) => $u['id'] === $id);
        $user = array_values($mockUser)[0] ?? $this->mockUsers()[0];
        $user['id'] = $id;

        $this->json([
            'success' => true,
            'message' => '[MOCK] User data',
            'data'    => ['user' => $user]
        ]);
    }

    // PUT /api/users/{id}  — MOCK
    public function update(int $id): void
    {
        $auth = AuthMiddleware::handle();
        if (!$auth) return;

        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        $this->json([
            'success' => true,
            'message' => '[MOCK] User updated successfully',
            'data'    => [
                'user' => [
                    'id'        => $id,
                    'full_name' => $body['full_name'] ?? 'Updated Name',
                    'email'     => $body['email'] ?? 'user@example.com',
                    'updated_at'=> date('Y-m-d H:i:s'),
                ]
            ]
        ]);
    }

    // DELETE /api/users/{id}  — MOCK
    public function destroy(int $id): void
    {
        $auth = AuthMiddleware::handle();
        if (!$auth) return;

        $this->json([
            'success' => true,
            'message' => "[MOCK] User $id deleted successfully",
        ]);
    }

    // ----- Mock Data -----
    private function mockUsers(): array
    {
        return [
            ['id' => 1, 'username' => 'john_doe', 'email' => 'john@example.com', 'full_name' => 'John Doe', 'role' => 'user', 'created_at' => '2024-01-15 10:00:00'],
            ['id' => 2, 'username' => 'jane_smith', 'email' => 'jane@example.com', 'full_name' => 'Jane Smith', 'role' => 'admin', 'created_at' => '2024-01-16 11:00:00'],
            ['id' => 3, 'username' => 'bob_builder', 'email' => 'bob@example.com', 'full_name' => 'Bob Builder', 'role' => 'user', 'created_at' => '2024-01-17 12:00:00'],
            ['id' => 4, 'username' => 'alice_wonder', 'email' => 'alice@example.com', 'full_name' => 'Alice Wonder', 'role' => 'user', 'created_at' => '2024-01-18 13:00:00'],
            ['id' => 5, 'username' => 'charlie_biz', 'email' => 'charlie@example.com', 'full_name' => 'Charlie Biz', 'role' => 'user', 'created_at' => '2024-01-19 14:00:00'],
        ];
    }

    // ----- Helpers -----
    private function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        echo json_encode($data);
    }
}
