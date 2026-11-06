# E-Perpus Kampus - Authentication & Multi-Level User

Aplikasi PHP (PDO + MySQL + Bootstrap) dengan arsitektur Controller - Service - Repository - Entity.

## Instalasi (XAMPP/Laragon)
1. Letakkan folder `perpustakaan` di `htdocs` (XAMPP) / `www` (Laragon).
2. Buka phpMyAdmin lalu:
   - **Instalasi baru** : import `database/schema.sql`
   - **Database lama sudah ada** (tabel `buku` & `peminjaman` dari tugas sebelumnya): import `database/migration_auth.sql`
3. Cek kredensial DB di `config/Database.php` (default: root, tanpa password).
4. Buka `http://localhost/perpustakaan/` .

## Akun demo
| Role  | Username | Password |
|-------|----------|----------|
| Admin | admin    | admin123 |
| User  | user     | user123  |

Akun baru lewat halaman Register otomatis berole **user**.

## Struktur
- `public/index.php` : front controller, tabel route, proteksi session/role/CSRF
- `app/Core` : `Auth` (middleware), `helpers`, `Logger`
- `app/Controllers`, `app/Services`, `app/Repositories`, `app/Entities`, `app/Views`
- `database/` : `schema.sql`, `migration_auth.sql`
- `storage/logs/app.log` : log login, logout, akses ditolak, CRUD
