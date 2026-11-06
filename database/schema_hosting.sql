-- ============================================================
-- SCHEMA UNTUK HOSTING (InfinityFree / hosting bersama)
-- Tanpa CREATE DATABASE & USE, karena database dibuat lewat panel hosting.
-- Pilih database Anda di phpMyAdmin lebih dulu, lalu tab Import.
-- ============================================================

CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100) NOT NULL,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,              -- hash bcrypt, BUKAN plaintext
    role       ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS buku (
    id_buku      VARCHAR(20)  PRIMARY KEY,         -- kode buku
    judul        VARCHAR(150) NOT NULL,
    penulis      VARCHAR(100) NOT NULL,
    penerbit     VARCHAR(100) NOT NULL,
    tahun_terbit INT          NOT NULL,
    kategori     VARCHAR(50)  NOT NULL DEFAULT 'Umum',
    stok         INT          NOT NULL DEFAULT 0,
    status       ENUM('Tersedia','Habis') NOT NULL DEFAULT 'Tersedia'
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS peminjaman (
    id_peminjaman     VARCHAR(30) PRIMARY KEY,
    nim               VARCHAR(20) NOT NULL,
    id_buku           VARCHAR(20) NOT NULL,
    jumlah            INT NOT NULL DEFAULT 1,
    tanggal_pinjam    DATE NOT NULL,
    tanggal_kembali   DATE NULL,
    status_peminjaman ENUM('Dipinjam','Dikembalikan') NOT NULL DEFAULT 'Dipinjam',
    CONSTRAINT fk_pinjam_buku FOREIGN KEY (id_buku) REFERENCES buku(id_buku)
) ENGINE=InnoDB;

-- Akun default (password di-hash dengan bcrypt):
--   admin / admin123   (role admin)
--   user  / user123    (role user)
INSERT INTO users (nama, username, email, password, role) VALUES
('Administrator Perpustakaan', 'admin', 'admin@perpus.test', '$2y$10$cgEWyU24/yEmE1ncSDeS3etJPdxFcNlnO8yyVs1i1Arf8IoUwSnlK', 'admin'),
('Mahasiswa Contoh',           'user',  'user@perpus.test',  '$2y$10$sijO2bVHSwKf0u4/Ew78T.EeVTvaURjIHMvdeYNCyCaHPIJCFbQ6O', 'user');

INSERT INTO buku (id_buku, judul, penulis, penerbit, tahun_terbit, kategori, stok, status) VALUES
('BK001','Pemrograman Web dengan PHP','Budi Raharjo','Informatika',2022,'Pemrograman',5,'Tersedia'),
('BK002','Basis Data Relasional','Fathansyah','Informatika',2020,'Basis Data',3,'Tersedia'),
('BK003','Jaringan Komputer','Andrew S. Tanenbaum','Pearson',2019,'Jaringan',0,'Habis'),
('BK004','Rekayasa Perangkat Lunak','Roger S. Pressman','McGraw-Hill',2021,'Rekayasa Perangkat Lunak',2,'Tersedia');
