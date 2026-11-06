<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../Entities/Buku.php';

class BukuRepository {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->connect();
    }

    public function all() {
        $stmt = $this->db->prepare("SELECT * FROM buku ORDER BY id_buku ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Pencarian berdasarkan kode buku, judul, penulis, kategori.
     * Memakai prepared statement (aman dari SQL Injection).
     */
    public function search($keyword = '', $kategori = '') {
        $sql = "SELECT * FROM buku WHERE 1=1";
        $params = [];

        if ($keyword !== '') {
            // escape karakter wildcard LIKE milik user
            $like = '%' . addcslashes($keyword, '\\%_') . '%';
            $sql .= " AND (id_buku LIKE :k1 OR judul LIKE :k2 OR penulis LIKE :k3 OR kategori LIKE :k4)";
            $params = ['k1' => $like, 'k2' => $like, 'k3' => $like, 'k4' => $like];
        }
        if ($kategori !== '') {
            $sql .= " AND kategori = :kat";
            $params['kat'] = $kategori;
        }
        $sql .= " ORDER BY id_buku ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM buku WHERE id_buku = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function kategoriList() {
        $stmt = $this->db->prepare("SELECT DISTINCT kategori FROM buku ORDER BY kategori ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function stats() {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total_judul,
                    COALESCE(SUM(stok), 0) AS total_stok,
                    COALESCE(SUM(status = 'Tersedia'), 0) AS tersedia,
                    COALESCE(SUM(status = 'Habis'), 0) AS habis
             FROM buku"
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function lowStock($limit = 5) {
        $stmt = $this->db->prepare("SELECT * FROM buku WHERE stok <= 2 ORDER BY stok ASC, id_buku ASC LIMIT " . (int)$limit);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function availableBooks($limit = 6) {
        $stmt = $this->db->prepare("SELECT * FROM buku WHERE stok > 0 ORDER BY id_buku DESC LIMIT " . (int)$limit);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(Buku $buku) {
        $query = "INSERT INTO buku (id_buku, judul, penulis, penerbit, tahun_terbit, kategori, stok, status)
                  VALUES (:id, :judul, :penulis, :penerbit, :tahun, :kategori, :stok, :status)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id'       => $buku->getIdBuku(),
            'judul'    => $buku->getJudul(),
            'penulis'  => $buku->getPenulis(),
            'penerbit' => $buku->getPenerbit(),
            'tahun'    => $buku->getTahunTerbit(),
            'kategori' => $buku->getKategori(),
            'stok'     => $buku->getStok(),
            'status'   => $buku->getStatus()
        ]);
    }

    public function update(Buku $buku) {
        $query = "UPDATE buku
                  SET judul = :judul, penulis = :penulis, penerbit = :penerbit, tahun_terbit = :tahun,
                      kategori = :kategori, stok = :stok, status = :status
                  WHERE id_buku = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id'       => $buku->getIdBuku(),
            'judul'    => $buku->getJudul(),
            'penulis'  => $buku->getPenulis(),
            'penerbit' => $buku->getPenerbit(),
            'tahun'    => $buku->getTahunTerbit(),
            'kategori' => $buku->getKategori(),
            'stok'     => $buku->getStok(),
            'status'   => $buku->getStatus()
        ]);
    }

    /** Ubah stok saja; status dihitung otomatis dari stok. */
    public function updateStok($id, $stok) {
        $status = ($stok > 0) ? 'Tersedia' : 'Habis';
        $stmt = $this->db->prepare("UPDATE buku SET stok = :stok, status = :status WHERE id_buku = :id");
        return $stmt->execute(['stok' => $stok, 'status' => $status, 'id' => $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM buku WHERE id_buku = :id");
        return $stmt->execute(['id' => $id]);
    }
}
