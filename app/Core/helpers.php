<?php
// app/Core/helpers.php - fungsi bantu global

/** Escape output HTML (mencegah XSS). Pakai di SEMUA view. */
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/** Buat URL relatif ke front controller. */
function url(string $page = '', string $action = '', array $params = []): string {
    $q = [];
    if ($page !== '')   $q['page'] = $page;
    if ($action !== '') $q['action'] = $action;
    $q = array_merge($q, $params);
    return 'index.php' . ($q ? '?' . http_build_query($q) : '');
}

function redirect(string $to): void {
    header('Location: ' . $to);
    exit;
}

function flash(string $type, string $message): void {
    $_SESSION['flash_' . $type] = $message;
}

function isPost(): bool {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/** Token CSRF per-session. */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/** Verifikasi token CSRF untuk setiap request POST. */
function csrf_verify(): void {
    $sent = $_POST['csrf_token'] ?? '';
    if (!is_string($sent) || !hash_equals($_SESSION['csrf_token'] ?? '', $sent)) {
        http_response_code(419);
        flash('error', 'Sesi formulir tidak valid atau kedaluwarsa. Silakan ulangi.');
        redirect(isset($_SESSION['user_id']) ? url('dashboard') : url('login'));
    }
}

/** Render view dari app/Views/<name>.php dengan data yang dikirim. */
function view(string $name, array $data = []): void {
    extract($data);
    require __DIR__ . '/../Views/' . $name . '.php';
}
