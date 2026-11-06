<?php
// public/index.php - FRONT CONTROLLER + ROUTER + PROTEKSI AKSES
date_default_timezone_set('Asia/Jakarta');

// ---- Konfigurasi session yang aman ----
ini_set('session.use_strict_mode', '1');      // tolak session ID buatan sendiri
ini_set('session.use_only_cookies', '1');
session_set_cookie_params([
    'lifetime' => 0,                           // hilang saat browser ditutup
    'path'     => '/',
    'httponly' => true,                        // tidak bisa dibaca JavaScript
    'samesite' => 'Lax',
]);
session_start();

// ---- Cegah halaman tersimpan di cache (tombol Back setelah logout) ----
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');

// ---- Load file ----
require_once __DIR__ . '/../app/Core/helpers.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/Core/Logger.php';
require_once __DIR__ . '/../app/Core/Auth.php';
require_once __DIR__ . '/../app/Entities/User.php';
require_once __DIR__ . '/../app/Repositories/UserRepository.php';
require_once __DIR__ . '/../app/Services/AuthService.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/BukuController.php';
require_once __DIR__ . '/../app/Controllers/PeminjamanController.php';
require_once __DIR__ . '/../app/Controllers/UserController.php';

$page   = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? '';
$key    = $action === '' ? $page : $page . '/' . $action;

// ---- TABEL ROUTE ----
// 'rute' => [Controller, method, HTTP method, level akses]
// Level akses: guest (belum login) | auth (semua user login) | admin | user
$routes = [
    // Authentication
    'login'            => ['AuthController', 'loginForm',       'GET',  'guest'],
    'login-process'    => ['AuthController', 'loginProcess',    'POST', 'guest'],
    'register'         => ['AuthController', 'registerForm',    'GET',  'guest'],
    'register-process' => ['AuthController', 'registerProcess', 'POST', 'guest'],
    'logout'           => ['AuthController', 'logout',          'POST', 'auth'],

    // Dashboard
    'dashboard'        => ['DashboardController', 'redirectByRole', 'GET', 'auth'],
    'admin-dashboard'  => ['DashboardController', 'admin',          'GET', 'admin'],
    'user-dashboard'   => ['DashboardController', 'user',           'GET', 'user'],

    // Buku: baca (Admin & User)
    'buku'             => ['BukuController', 'index',  'GET',  'auth'],
    'buku/detail'      => ['BukuController', 'detail', 'GET',  'auth'],
    // Buku: kelola (HANYA Admin)
    'buku/store'       => ['BukuController', 'store',  'POST', 'admin'],
    'buku/update'      => ['BukuController', 'update', 'POST', 'admin'],
    'buku/stok'        => ['BukuController', 'stok',   'POST', 'admin'],
    'buku/delete'      => ['BukuController', 'delete', 'POST', 'admin'],

    // Peminjaman & manajemen pengguna (HANYA Admin)
    'peminjaman'            => ['PeminjamanController', 'index',      'GET',  'admin'],
    'peminjaman/store'      => ['PeminjamanController', 'store',      'POST', 'admin'],
    'peminjaman/kembalikan' => ['PeminjamanController', 'kembalikan', 'POST', 'admin'],
    'pengguna'              => ['UserController', 'index', 'GET', 'admin'],
];

// ---- Dispatcher ----
if (!isset($routes[$key])) {
    http_response_code(404);
    view('errors/404');
    exit;
}

[$controllerName, $method, $httpMethod, $access] = $routes[$key];

if ($_SERVER['REQUEST_METHOD'] !== $httpMethod) {
    // Mis. membuka URL POST-only (logout/hapus) lewat address bar -> kembalikan ke halaman aman
    redirect(Auth::check() ? Auth::homeUrl() : url('login'));
}

// 1) Proteksi berdasarkan session & role
switch ($access) {
    case 'guest':
        if (Auth::check()) redirect(Auth::homeUrl());   // sudah login -> tidak perlu form login
        break;
    case 'auth':
        Auth::requireLogin();
        break;
    case 'admin':
        Auth::requireRole('admin');
        break;
    case 'user':
        Auth::requireRole('user');
        break;
}

// 2) Proteksi CSRF untuk semua request POST
if ($httpMethod === 'POST') {
    csrf_verify();
}

// 3) Jalankan controller
if ($controllerName === 'AuthController') {
    $controller = new AuthController(new AuthService(new UserRepository(new Database())));
} else {
    $controller = new $controllerName();
}
$controller->$method();
