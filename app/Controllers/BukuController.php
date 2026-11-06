<?php
require_once __DIR__ . '/../Services/BukuService.php';

class BukuController {
    private $service;

    public function __construct() {
        $this->service = new BukuService();
    }

    /** Daftar buku + pencarian (judul, penulis, kategori, kode buku). Admin & User. */
    public function index() {
        $keyword  = trim($_GET['q'] ?? '');
        $kategori = trim($_GET['kategori'] ?? '');
        $bukuList = $this->service->getAllBuku($keyword, $kategori);
        $kategoriList = $this->service->getKategoriList();
        $isAdmin = Auth::isAdmin();
        view('buku/index', compact('bukuList', 'kategoriList', 'keyword', 'kategori', 'isAdmin'));
    }

    /** Detail buku. Admin & User. */
    public function detail() {
        $buku = $this->service->getBuku($_GET['id'] ?? '');
        if (!$buku) {
            flash('error', 'Buku tidak ditemukan.');
            redirect(url('buku'));
        }
        view('buku/detail', compact('buku'));
    }

    // ---------- Aksi di bawah ini hanya boleh dipanggil Admin (dijaga di router) ----------

    public function store() {
        try {
            $this->service->createBuku($_POST);
            flash('success', 'Data buku berhasil ditambahkan!');
        } catch (Exception $e) {
            flash('error', 'Gagal menambah buku: ' . $e->getMessage());
        }
        redirect(url('buku'));
    }

    public function update() {
        try {
            $this->service->updateBuku($_POST);
            flash('success', 'Data buku berhasil diperbarui!');
        } catch (Exception $e) {
            flash('error', 'Gagal memperbarui buku: ' . $e->getMessage());
        }
        redirect(url('buku'));
    }

    public function stok() {
        try {
            $this->service->updateStok($_POST['id_buku'] ?? '', $_POST['stok'] ?? '');
            flash('success', 'Stok buku berhasil diperbarui!');
        } catch (Exception $e) {
            flash('error', 'Gagal mengubah stok: ' . $e->getMessage());
        }
        redirect(url('buku'));
    }

    public function delete() {
        try {
            $this->service->deleteBuku($_POST['id_buku'] ?? '');
            flash('success', 'Data buku berhasil dihapus!');
        } catch (Exception $e) {
            flash('error', $e->getMessage());
        }
        redirect(url('buku'));
    }
}
