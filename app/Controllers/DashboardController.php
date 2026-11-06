<?php
require_once __DIR__ . '/../Repositories/BukuRepository.php';
require_once __DIR__ . '/../Repositories/PeminjamanRepository.php';
require_once __DIR__ . '/../Repositories/UserRepository.php';

class DashboardController {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    /** /dashboard -> arahkan ke dashboard sesuai role */
    public function redirectByRole() {
        redirect(Auth::homeUrl());
    }

    public function admin() {
        $bukuRepo = new BukuRepository($this->database);
        $stats = $bukuRepo->stats();
        $stats['total_user'] = (new UserRepository($this->database))->countAll();
        $stats['pinjam_aktif'] = (new PeminjamanRepository($this->database))->countActive();
        $lowStock = $bukuRepo->lowStock(5);
        view('dashboard/admin', compact('stats', 'lowStock'));
    }

    public function user() {
        $bukuRepo = new BukuRepository($this->database);
        $stats = $bukuRepo->stats();
        $tersedia = $bukuRepo->availableBooks(6);
        view('dashboard/user', compact('stats', 'tersedia'));
    }
}
