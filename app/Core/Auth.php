<?php
// app/Core/Auth.php
// Middleware sederhana: proteksi halaman berdasarkan session & role.

require_once __DIR__ . '/Logger.php';

class Auth {
    public static function check(): bool {
        return isset($_SESSION['user_id']);
    }

    public static function role(): ?string {
        return $_SESSION['role'] ?? null;
    }

    public static function isAdmin(): bool {
        return self::role() === 'admin';
    }

    public static function homeUrl(): string {
        return self::isAdmin() ? url('admin-dashboard') : url('user-dashboard');
    }

    /** Wajib login. Jika belum -> ke halaman login. */
    public static function requireLogin(): void {
        if (!self::check()) {
            flash('error', 'Silakan login terlebih dahulu untuk mengakses halaman tersebut.');
            redirect(url('login'));
        }
    }

    /** Wajib login DAN memiliki role tertentu. Jika tidak -> HTTP 403. */
    public static function requireRole(string $role): void {
        self::requireLogin();
        if (self::role() !== $role) {
            Logger::log("AKSES DITOLAK: user [" . ($_SESSION['username'] ?? '?') . "] role [" . self::role() . "] mencoba membuka halaman khusus [{$role}]: " . ($_SERVER['REQUEST_URI'] ?? ''), 'WARNING');
            http_response_code(403);
            view('errors/403');
            exit;
        }
    }
}
