<?php
require_once __DIR__ . '/../Services/PeminjamanService.php';

// Seluruh halaman peminjaman hanya untuk Admin (dijaga di router).
class PeminjamanController {
    private $service;

    public function __construct() {
        $this->service = new PeminjamanService();
    }

    public function index() {
        $peminjaman = $this->service->getAllPeminjaman();
        $bukuList = $this->service->getAllBuku();
        view('peminjaman/index', compact('peminjaman', 'bukuList'));
    }

    public function store() {
        try {
            $this->service->pinjamBuku(
                $_POST['nim'] ?? '',
                $_POST['id_buku'] ?? '',
                $_POST['jumlah'] ?? 1,
                $_POST['tanggal_pinjam'] ?? ''
            );
            flash('success', 'Peminjaman buku berhasil dicatat!');
        } catch (Exception $e) {
            flash('error', $e->getMessage());
        }
        redirect(url('peminjaman'));
    }

    public function kembalikan() {
        try {
            $this->service->kembalikanBuku(
                $_POST['id_peminjaman'] ?? '',
                $_POST['tanggal_kembali'] ?? date('Y-m-d')
            );
            flash('success', 'Buku berhasil dikembalikan!');
        } catch (Exception $e) {
            flash('error', $e->getMessage());
        }
        redirect(url('peminjaman'));
    }
}
