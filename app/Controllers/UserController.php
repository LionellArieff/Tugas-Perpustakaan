<?php
require_once __DIR__ . '/../Repositories/UserRepository.php';

// Manajemen pengguna: hanya Admin (dijaga di router).
class UserController {
    public function index() {
        $repo = new UserRepository(new Database());
        $users = $repo->getAllUsers();
        view('auth/users', compact('users'));
    }
}
