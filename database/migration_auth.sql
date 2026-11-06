-- ============================================================
-- MIGRASI untuk database perpustakaan_db YANG SUDAH ADA
-- (data buku & peminjaman lama TIDAK dihapus)
-- ============================================================
USE perpustakaan_db;

-- 1. Tambah kolom kategori (dibutuhkan fitur pencarian berdasarkan kategori)
--    Jika kolom sudah ada, MySQL akan menampilkan error "Duplicate column" -> abaikan saja.
ALTER TABLE buku ADD COLUMN kategori VARCHAR(50) NOT NULL DEFAULT 'Umum' AFTER tahun_terbit;

-- 2. Tabel users
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100) NOT NULL,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Akun default: admin / admin123  dan  user / user123
INSERT IGNORE INTO users (nama, username, email, password, role) VALUES
('Administrator Perpustakaan', 'admin', 'admin@perpus.test', '$2y$10$cgEWyU24/yEmE1ncSDeS3etJPdxFcNlnO8yyVs1i1Arf8IoUwSnlK', 'admin'),
('Mahasiswa Contoh',           'user',  'user@perpus.test',  '$2y$10$sijO2bVHSwKf0u4/Ew78T.EeVTvaURjIHMvdeYNCyCaHPIJCFbQ6O', 'user');
