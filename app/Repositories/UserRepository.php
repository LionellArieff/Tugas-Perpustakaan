<?php
require_once __DIR__ . '/../Entities/User.php';

class UserRepository {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->connect();
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }

    /** Login boleh memakai username ATAU email. */
    public function findByUsernameOrEmail($identity) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$identity, $identity]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }

    /**
     * Mendaftarkan akun baru. Role SELALU 'user' (tidak bisa dipilih dari form),
     * sehingga pendaftar tidak dapat membuat dirinya menjadi admin.
     */
    public function create($nama, $username, $email, $password) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (nama, username, email, password, role) VALUES (?, ?, ?, ?, 'user')");
        return $stmt->execute([$nama, $username, $email, $hashed]);
    }

    public function getAllUsers() {
        $stmt = $this->db->prepare("SELECT id, nama, username, email, role, created_at FROM users ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll() {
        return (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }
}
