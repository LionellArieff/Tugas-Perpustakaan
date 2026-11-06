<?php
class Buku {
    private $idBuku;
    private $judul;
    private $penulis;
    private $penerbit;
    private $tahunTerbit;
    private $kategori;
    private $stok;
    private $status;

    public function __construct($idBuku = null, $judul = null, $penulis = null, $penerbit = null, $tahunTerbit = null, $kategori = 'Umum', $stok = 0, $status = 'Tersedia') {
        $this->idBuku = $idBuku;
        $this->judul = $judul;
        $this->penulis = $penulis;
        $this->penerbit = $penerbit;
        $this->tahunTerbit = $tahunTerbit;
        $this->kategori = $kategori;
        $this->stok = $stok;
        $this->status = $status;
    }

    public function getIdBuku() { return $this->idBuku; }
    public function getJudul() { return $this->judul; }
    public function getPenulis() { return $this->penulis; }
    public function getPenerbit() { return $this->penerbit; }
    public function getTahunTerbit() { return $this->tahunTerbit; }
    public function getKategori() { return $this->kategori; }
    public function getStok() { return $this->stok; }
    public function getStatus() { return $this->status; }
}
