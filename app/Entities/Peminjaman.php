<?php
class Peminjaman {
    private $idPeminjaman;
    private $nim;
    private $idBuku;
    private $tanggalPinjam;
    private $tanggalKembali;
    private $statusPeminjaman;

    public function __construct($idPeminjaman = null, $nim = null, $idBuku = null, $tanggalPinjam = null, $tanggalKembali = null, $statusPeminjaman = 'Dipinjam') {
        $this->idPeminjaman = $idPeminjaman;
        $this->nim = $nim;
        $this->idBuku = $idBuku;
        $this->tanggalPinjam = $tanggalPinjam;
        $this->tanggalKembali = $tanggalKembali;
        $this->statusPeminjaman = $statusPeminjaman;
    }

    public function getIdPeminjaman() { return $this->idPeminjaman; }
    public function getNim() { return $this->nim; }
    public function getIdBuku() { return $this->idBuku; }
    public function getTanggalPinjam() { return $this->tanggalPinjam; }
    public function getTanggalKembali() { return $this->tanggalKembali; }
    public function getStatusPeminjaman() { return $this->statusPeminjaman; }
}