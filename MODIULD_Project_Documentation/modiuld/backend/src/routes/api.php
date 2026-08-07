<?php
// ======================================================
// MODIULD API Routes
// ======================================================

use App\Controllers\AuthController;
use App\Controllers\UserController;

$authController = new AuthController();
$userController = new UserController();

// Helper: route not found
$notFound = function () {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Route not found']);
    exit;
};

// Helper: method not allowed
$methodNotAllowed = function () {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
};

// ============================================================
// AUTH ROUTES
// ============================================================

// POST /api/register
if ($path === '/register') {
    if ($method === 'POST') {
        $authController->register();
    } else {
        $methodNotAllowed();
    }
    exit;
}

// POST /api/login
if ($path === '/login') {
    if ($method === 'POST') {
        $authController->login();
    } else {
        $methodNotAllowed();
    }
    exit;
}

// POST /api/logout
if ($path === '/logout') {
    if ($method === 'POST') {
        $authController->logout();
    } else {
        $methodNotAllowed();
    }
    exit;
}

// POST /api/change-password
if ($path === '/change-password') {
    if ($method === 'POST') {
        $authController->changePassword();
    } else {
        $methodNotAllowed();
    }
    exit;
}

// ============================================================
// USER ROUTES
// ============================================================

// GET /api/me
if ($path === '/me') {
    if ($method === 'GET') {
        $userController->me();
    } else {
        $methodNotAllowed();
    }
    exit;
}

// GET /api/users
if ($path === '/users') {
    if ($method === 'GET') {
        $userController->index();
    } else {
        $methodNotAllowed();
    }
    exit;
}

// GET /api/check-username/{name}
if (preg_match('#^/check-username/([^/]+)$#', $path, $matches)) {
    if ($method === 'GET') {
        $userController->checkUsername($matches[1]);
    } else {
        $methodNotAllowed();
    }
    exit;
}

// GET|PUT|DELETE /api/users/{id}
if (preg_match('#^/users/(\d+)$#', $path, $matches)) {
    $id = (int)$matches[1];
    match ($method) {
        'GET'    => $userController->show($id),
        'PUT'    => $userController->update($id),
        'DELETE' => $userController->destroy($id),
        default  => $methodNotAllowed(),
    };
    exit;
}

// ============================================================
// HEALTH CHECK
// ============================================================
if ($path === '/health' || $path === '/') {
    echo json_encode([
        'success' => true,
        'message' => 'MODIULD API is running',
        'version' => '1.0.0',
        'timestamp' => date('Y-m-d H:i:s'),
    ]);
    exit;
}

$notFound();
