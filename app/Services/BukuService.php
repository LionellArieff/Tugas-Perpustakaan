<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../Repositories/BukuRepository.php';
require_once __DIR__ . '/../Repositories/PeminjamanRepository.php';
require_once __DIR__ . '/../Entities/Buku.php';
require_once __DIR__ . '/../Core/Logger.php';

class BukuService {
    private $bukuRepo;
    private $peminjamanRepo;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->bukuRepo = new BukuRepository($database);
        $this->peminjamanRepo = new PeminjamanRepository($database);
    }

    public function getAllBuku($keyword = '', $kategori = '') {
        return $this->bukuRepo->search(trim($keyword), trim($kategori));
    }

    public function getBuku($idBuku) {
        return $this->bukuRepo->find(trim($idBuku));
    }

    public function getKategoriList() {
        return $this->bukuRepo->kategoriList();
    }

    /** Validasi + normalisasi input buku. Data disimpan apa adanya, di-escape saat ditampilkan (e()). */
    private function validasi(array $in, $cekId = true) {
        $d = [
            'id'       => trim($in['id_buku'] ?? ''),
            'judul'    => trim($in['judul'] ?? ''),
            'penulis'  => trim($in['penulis'] ?? ''),
            'penerbit' => trim($in['penerbit'] ?? ''),
            'kategori' => trim($in['kategori'] ?? ''),
            'tahun'    => filter_var($in['tahun_terbit'] ?? '', FILTER_VALIDATE_INT),
            'stok'     => filter_var($in['stok'] ?? '', FILTER_VALIDATE_INT),
        ];

        if ($cekId && !preg_match('/^[A-Za-z0-9_-]{2,20}$/', $d['id'])) {
            throw new Exception("Kode buku 2-20 karakter (huruf, angka, - atau _).");
        }
        if ($d['judul'] === '' || mb_strlen($d['judul']) > 150) {
            throw new Exception("Judul wajib diisi (maksimal 150 karakter).");
        }
        if ($d['penulis'] === '' || mb_strlen($d['penulis']) > 100) {
            throw new Exception("Penulis wajib diisi (maksimal 100 karakter).");
        }
        if ($d['penerbit'] === '' || mb_strlen($d['penerbit']) > 100) {
            throw new Exception("Penerbit wajib diisi (maksimal 100 karakter).");
        }
        if ($d['kategori'] === '' || mb_strlen($d['kategori']) > 50) {
            throw new Exception("Kategori wajib diisi (maksimal 50 karakter).");
        }
        if ($d['tahun'] === false || $d['tahun'] < 1900 || $d['tahun'] > ((int)date('Y') + 1)) {
            throw new Exception("Tahun terbit tidak valid.");
        }
        if ($d['stok'] === false || $d['stok'] < 0 || $d['stok'] > 100000) {
            throw new Exception("Stok harus berupa angka 0 atau lebih.");
        }
        return $d;
    }

    public function createBuku(array $in) {
        $d = $this->validasi($in, true);

        if ($this->bukuRepo->find($d['id'])) {
            throw new Exception("Kode buku '{$d['id']}' sudah terdaftar.");
        }

        $status = ($d['stok'] > 0) ? 'Tersedia' : 'Habis';
        $buku = new Buku($d['id'], $d['judul'], $d['penulis'], $d['penerbit'], $d['tahun'], $d['kategori'], $d['stok'], $status);
        $this->bukuRepo->create($buku);
        Logger::log("BUKU DITAMBAH: [{$d['id']}] oleh [" . ($_SESSION['username'] ?? '?') . "]", 'INFO');
        return true;
    }

    public function updateBuku(array $in) {
        $d = $this->validasi($in, true);

        if (!$this->bukuRepo->find($d['id'])) {
            throw new Exception("Data buku tidak ditemukan.");
        }

        $status = ($d['stok'] > 0) ? 'Tersedia' : 'Habis';
        $buku = new Buku($d['id'], $d['judul'], $d['penulis'], $d['penerbit'], $d['tahun'], $d['kategori'], $d['stok'], $status);
        $this->bukuRepo->update($buku);
        Logger::log("BUKU DIUBAH: [{$d['id']}] oleh [" . ($_SESSION['username'] ?? '?') . "]", 'INFO');
        return true;
    }

    public function updateStok($idBuku, $stok) {
        $idBuku = trim($idBuku);
        $stok = filter_var($stok, FILTER_VALIDATE_INT);

        if ($stok === false || $stok < 0 || $stok > 100000) {
            throw new Exception("Stok harus berupa angka 0 atau lebih.");
        }
        if (!$this->bukuRepo->find($idBuku)) {
            throw new Exception("Data buku tidak ditemukan.");
        }

        $this->bukuRepo->updateStok($idBuku, $stok);
        Logger::log("STOK DIUBAH: buku [{$idBuku}] menjadi {$stok} oleh [" . ($_SESSION['username'] ?? '?') . "]", 'INFO');
        return true;
    }

    public function deleteBuku($idBuku) {
        $idBuku = trim($idBuku);

        if (!$this->bukuRepo->find($idBuku)) {
            throw new Exception("Data buku tidak ditemukan.");
        }
        // Buku yang sedang dipinjam tidak boleh dihapus
        if ($this->peminjamanRepo->countActiveByBuku($idBuku) > 0) {
            throw new Exception("Buku tidak dapat dihapus karena masih ada peminjaman yang aktif.");
        }

        try {
            $this->db->beginTransaction();
            $this->peminjamanRepo->deleteByBuku($idBuku);   // bersihkan riwayat (FK)
            $this->bukuRepo->delete($idBuku);
            $this->db->commit();
        } catch (Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw new Exception("Gagal menghapus buku.");
        }
        Logger::log("BUKU DIHAPUS: [{$idBuku}] oleh [" . ($_SESSION['username'] ?? '?') . "]", 'INFO');
        return true;
    }
}
