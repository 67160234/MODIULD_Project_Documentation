<?php
// ================================================================
// MODIULD API Router  — entry point for all /api/* requests
// ================================================================
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type, X-Guest-Token');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

// Autoload helpers
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/utils/jwt.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/LoadoutController.php';
require_once __DIR__ . '/controllers/ModuleController.php';

// Parse URI — strip query string and leading /api
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri    = preg_replace('#^/api#', '', $uri);
$uri    = rtrim($uri, '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

// ---- Route matching ----

// Status
if ($uri === '/status' && $method === 'GET') {
    echo json_encode(['status' => 'ok', 'version' => '1.0.0']); exit;
}

// Auth
if ($uri === '/register'        && $method === 'POST') { AuthController::register();       exit; }
if ($uri === '/login'           && $method === 'POST') { AuthController::login();          exit; }
if ($uri === '/logout'          && $method === 'POST') { AuthController::logout();         exit; }
if ($uri === '/me'              && $method === 'GET')  { AuthController::me();             exit; }
if ($uri === '/change-password' && $method === 'POST') { AuthController::changePassword(); exit; }
if ($uri === '/google/config'   && $method === 'GET')  { AuthController::googleConfig();    exit; }
if ($uri === '/google/sign-in'  && $method === 'POST') { AuthController::googleSignIn();    exit; }
if ($uri === '/google/complete' && $method === 'POST') { AuthController::googleComplete();  exit; }

// Modules
if ($uri === '/modules' && $method === 'GET') { ModuleController::index(); exit; }

// Loadouts
if ($uri === '/loadouts') {
    if ($method === 'GET')  { LoadoutController::index(); exit; }
    if ($method === 'POST') { LoadoutController::store(); exit; }
}
if (preg_match('#^/loadouts/(\d+)$#', $uri, $m)) {
    $id = (int)$m[1];
    if ($method === 'GET')    { LoadoutController::show($id);    exit; }
    if ($method === 'PUT')    { LoadoutController::update($id);  exit; }
    if ($method === 'DELETE') { LoadoutController::destroy($id); exit; }
}
if (preg_match('#^/loadouts/(\d+)/modules$#', $uri, $m)) {
    if ($method === 'GET') { ModuleController::loadoutModules((int)$m[1]); exit; }
}

// 404
http_response_code(404);
echo json_encode(['error' => 'Endpoint not found.']);
