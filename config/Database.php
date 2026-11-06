<?php
class Database {
    private $host = "sql301.infinityfree.com";
private $db_name = "if0_43117376_perpustakaan_db";
private $username = "if0_43117376";
private $password = "hB9glzuy6vF";
    public $conn;
    private static $shared = null;   // 1 koneksi dipakai bersama (transaksi jadi konsisten)

    public function connect() {
        if (self::$shared !== null) {
            return $this->conn = self::$shared;
        }
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            require_once __DIR__ . '/../app/Core/Logger.php';
            Logger::log("KONEKSI DB GAGAL: " . $e->getMessage(), 'ERROR');
            http_response_code(500);
            die("Koneksi database gagal. Periksa konfigurasi di config/Database.php.");
        }
        self::$shared = $this->conn;
        return $this->conn;
    }
}