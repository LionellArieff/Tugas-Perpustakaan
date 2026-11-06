<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../Core/Logger.php';
require_once __DIR__ . '/../Repositories/BukuRepository.php';
require_once __DIR__ . '/../Repositories/PeminjamanRepository.php';
require_once __DIR__ . '/../Entities/Buku.php';

class PeminjamanService {
    private $bukuRepo;
    private $peminjamanRepo;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->bukuRepo = new BukuRepository($database);
        $this->peminjamanRepo = new PeminjamanRepository($database);
    }

    public function getAllPeminjaman() {
        return $this->peminjamanRepo->allWithBuku();
    }

    public function getAllBuku() {
        return $this->bukuRepo->all();
    }

    public function pinjamBuku($nim, $idBuku, $jumlah, $tanggalPinjam) {
        $nim = trim($nim);
        $idBuku = trim($idBuku);
        $jumlah = (int)$jumlah;
        $tanggalPinjam = trim($tanggalPinjam);

        if (empty($nim) || empty($idBuku) || empty($tanggalPinjam) || $jumlah <= 0) {
            throw new Exception("NIM, ID Buku, Jumlah Pinjam, dan Tanggal Pinjam wajib diisi dengan benar.");
        }

        if (!preg_match('/^[A-Za-z0-9]{5,20}$/', $nim)) {
            throw new Exception("NIM harus 5-20 karakter huruf/angka.");
        }

        $buku = $this->bukuRepo->find($idBuku);
        if (!$buku) {
            throw new Exception("Data buku tidak ditemukan.");
        }

        // Exception Syarat: "Buku tidak tersedia."
        if ($buku['stok'] <= 0 || $buku['status'] === 'Habis') {
            throw new Exception("Buku tidak tersedia.");
        }

        if ($jumlah > $buku['stok']) {
            throw new Exception("Jumlah pinjam ({$jumlah}) melebihi stok yang tersedia ({$buku['stok']}).");
        }

        // Exception Syarat: "Mahasiswa telah mencapai batas maksimal peminjaman."
        $totalDipinjam = $this->peminjamanRepo->countActiveByNim($nim);
        if (($totalDipinjam + $jumlah) > 3) {
            throw new Exception("Mahasiswa telah mencapai batas maksimal peminjaman (Maksimal 3 buku). Saat ini sedang meminjam {$totalDipinjam} buku.");
        }

        try {
            $this->db->beginTransaction();

            $idPeminjaman = 'PJM-' . date('YmdHis') . rand(10, 99);
            $this->peminjamanRepo->create($idPeminjaman, $nim, $idBuku, $jumlah, $tanggalPinjam);

            // Ketentuan Stok Berkurang Sesuai Jumlah Pinjam
            $stokBaru = $buku['stok'] - $jumlah;
            $statusBaru = ($stokBaru == 0) ? 'Habis' : 'Tersedia';

            $this->bukuRepo->updateStok($buku['id_buku'], $stokBaru);

            $this->db->commit();

            Logger::log("PEMINJAMAN BERHASIL: NIM [{$nim}] meminjam {$jumlah} unit Buku [{$idBuku}] ID: {$idPeminjaman}", "INFO");
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            Logger::log("PEMINJAMAN GAGAL: NIM [{$nim}] - " . $e->getMessage(), "ERROR");
            throw $e;
        }
    }

    public function kembalikanBuku($idPeminjaman, $tanggalKembali) {
        $idPeminjaman = trim($idPeminjaman);
        $tanggalKembali = trim($tanggalKembali);

        if (empty($tanggalKembali)) {
            throw new Exception("Tanggal pengembalian wajib diisi.");
        }

        $peminjaman = $this->peminjamanRepo->find($idPeminjaman);
        if (!$peminjaman) {
            throw new Exception("Transaksi peminjaman tidak ditemukan.");
        }

        if ($peminjaman['status_peminjaman'] === 'Dikembalikan') {
            throw new Exception("Buku pada transaksi ini sudah dikembalikan sebelumnya.");
        }

        if (strtotime($tanggalKembali) < strtotime($peminjaman['tanggal_pinjam'])) {
            throw new Exception("Tanggal pengembalian tidak boleh lebih awal dari tanggal pinjam.");
        }

        try {
            $this->db->beginTransaction();

            $this->peminjamanRepo->updatePengembalian($idPeminjaman, $tanggalKembali);

            // Ketentuan Stok Bertambah Sesuai Jumlah Yang Dikembalikan
            $buku = $this->bukuRepo->find($peminjaman['id_buku']);
            if ($buku) {
                $jumlahKembali = (int)($peminjaman['jumlah'] ?? 1);
                $stokBaru = $buku['stok'] + $jumlahKembali;
                $this->bukuRepo->updateStok($buku['id_buku'], $stokBaru);
            }

            $this->db->commit();

            Logger::log("PENGEMBALIAN BERHASIL: ID Transaksi [{$idPeminjaman}] dikembalikan pada {$tanggalKembali}", "INFO");
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            Logger::log("PENGEMBALIAN GAGAL: ID Transaksi [{$idPeminjaman}] - " . $e->getMessage(), "ERROR");
            throw $e;
        }
    }
}
