<?php
// app/Entities/User.php
class User {
    public $id;
    public $nama;
    public $username;
    public $email;
    public $password;
    public $role;

    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->nama = $data['nama'] ?? '';
        $this->username = $data['username'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->role = $data['role'] ?? 'user';
    }
}
