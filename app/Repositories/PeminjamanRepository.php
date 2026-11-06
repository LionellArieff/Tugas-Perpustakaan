<?php
require_once __DIR__ . '/../../config/Database.php';

class PeminjamanRepository {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->connect();
    }

    public function allWithBuku() {
        $query = "SELECT p.*, b.judul 
                  FROM peminjaman p 
                  JOIN buku b ON p.id_buku = b.id_buku 
                  ORDER BY p.tanggal_pinjam DESC, p.id_peminjaman DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($idPeminjaman) {
        $stmt = $this->db->prepare("SELECT * FROM peminjaman WHERE id_peminjaman = :id");
        $stmt->execute(['id' => $idPeminjaman]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countActiveByNim($nim) {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(jumlah), 0) FROM peminjaman WHERE nim = :nim AND status_peminjaman = 'Dipinjam'");
        $stmt->execute(['nim' => $nim]);
        return (int)$stmt->fetchColumn();
    }

    public function create($idPeminjaman, $nim, $idBuku, $jumlah, $tanggalPinjam) {
        $query = "INSERT INTO peminjaman (id_peminjaman, nim, id_buku, jumlah, tanggal_pinjam, status_peminjaman) 
                  VALUES (:id, :nim, :id_buku, :jumlah, :tgl, 'Dipinjam')";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id'      => $idPeminjaman,
            'nim'     => $nim,
            'id_buku' => $idBuku,
            'jumlah'  => $jumlah,
            'tgl'     => $tanggalPinjam
        ]);
    }

    public function updatePengembalian($idPeminjaman, $tanggalKembali) {
        $query = "UPDATE peminjaman 
                  SET tanggal_kembali = :tgl, status_peminjaman = 'Dikembalikan' 
                  WHERE id_peminjaman = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'tgl' => $tanggalKembali,
            'id'  => $idPeminjaman
        ]);
    }

    public function countActiveByBuku($idBuku) {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(jumlah), 0) FROM peminjaman WHERE id_buku = :id AND status_peminjaman = 'Dipinjam'");
        $stmt->execute(['id' => $idBuku]);
        return (int)$stmt->fetchColumn();
    }

    // Method baru untuk menghapus semua riwayat peminjaman berdasarkan ID buku
    public function deleteByBuku($idBuku) {
        $stmt = $this->db->prepare("DELETE FROM peminjaman WHERE id_buku = :id");
        return $stmt->execute(['id' => $idBuku]);
    }

    public function countActive() {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM peminjaman WHERE status_peminjaman = 'Dipinjam'");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
}
