<?php
// app/Services/AuthService.php
require_once __DIR__ . '/../Core/Logger.php';

class AuthService {
    private $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    /**
     * Validasi username/password. Mengembalikan User jika berhasil, false jika gagal.
     * Pesan error sengaja dibuat sama (tidak membocorkan apakah username ada atau tidak).
     */
    public function login($identity, $password) {
        $identity = trim($identity);
        $user = $this->userRepo->findByUsernameOrEmail($identity);

        // Verifikasi dummy bila user tidak ada -> waktu respons relatif sama
        $hash = $user ? $user->password : '$2y$10$wcXMAUEHxpg3ibpbY6F9tek51KyAP6/h9PwqHoEYJ7xRZUME38mVi';

        if (password_verify($password, $hash) && $user) {
            session_regenerate_id(true);            // cegah session fixation
            $_SESSION['user_id']  = $user->id;
            $_SESSION['nama']     = $user->nama;
            $_SESSION['username'] = $user->username;
            $_SESSION['role']     = $user->role;
            Logger::log("LOGIN BERHASIL: [{$user->username}] role [{$user->role}]", 'INFO');
            return $user;
        }

        Logger::log("LOGIN GAGAL: identitas [" . substr($identity, 0, 50) . "]", 'WARNING');
        return false;
    }

    /**
     * Registrasi user baru. Mengembalikan array pesan error (kosong = sukses).
     */
    public function register($nama, $username, $email, $password, $konfirmasi) {
        $errors = [];
        $nama = trim($nama);
        $username = trim($username);
        $email = trim($email);

        if ($nama === '' || mb_strlen($nama) < 3 || mb_strlen($nama) > 100) {
            $errors[] = "Nama lengkap wajib diisi (3-100 karakter).";
        }
        if (!preg_match('/^[A-Za-z0-9_]{4,30}$/', $username)) {
            $errors[] = "Username 4-30 karakter, hanya huruf, angka, dan underscore.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
            $errors[] = "Format email tidak valid.";
        }
        if (strlen($password) < 6 || strlen($password) > 72) {
            $errors[] = "Password minimal 6 karakter (maksimal 72).";
        }
        if ($password !== $konfirmasi) {
            $errors[] = "Konfirmasi password tidak sama.";
        }
        if ($errors) return $errors;

        if ($this->userRepo->findByUsername($username)) {
            $errors[] = "Username sudah digunakan.";
        }
        if ($this->userRepo->findByEmail($email)) {
            $errors[] = "Email sudah terdaftar.";
        }
        if ($errors) return $errors;

        try {
            $this->userRepo->create($nama, $username, $email, $password);
            Logger::log("REGISTRASI: user baru [{$username}]", 'INFO');
        } catch (PDOException $e) {
            $errors[] = "Gagal mendaftarkan akun. Silakan coba lagi.";
        }
        return $errors;
    }

    /** Logout: hapus data session, hapus cookie session, hancurkan session. */
    public function logout() {
        $username = $_SESSION['username'] ?? '?';
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        Logger::log("LOGOUT: [{$username}]", 'INFO');
    }
}
