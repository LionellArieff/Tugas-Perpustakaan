<?php
// app/Controllers/AuthController.php

class AuthController {
    private $authService;

    public function __construct($authService) {
        $this->authService = $authService;
    }

    public function loginForm() {
        view('auth/login');
    }

    public function loginProcess() {
        $identity = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $old = ['username' => trim($identity)];

        if (!is_string($identity) || !is_string($password) || trim($identity) === '' || $password === '') {
            $error = "Username dan password wajib diisi.";
            view('auth/login', compact('error', 'old'));
            return;
        }

        $user = $this->authService->login($identity, $password);
        if ($user) {
            redirect(url('dashboard'));
        }

        http_response_code(401);
        $error = "Username atau password salah!";
        view('auth/login', compact('error', 'old'));
    }

    public function logout() {
        $this->authService->logout();
        // session sudah dihancurkan -> mulai session baru hanya untuk pesan flash
        session_start();
        flash('success', 'Anda telah logout.');
        redirect(url('login'));
    }

    public function registerForm() {
        view('auth/register');
    }

    public function registerProcess() {
        $errors = $this->authService->register(
            $_POST['nama'] ?? '',
            $_POST['username'] ?? '',
            $_POST['email'] ?? '',
            $_POST['password'] ?? '',
            $_POST['konfirmasi'] ?? ''
        );

        if (empty($errors)) {
            flash('success', 'Registrasi berhasil! Silakan login.');
            redirect(url('login'));
        }

        $old = [
            'nama'     => trim($_POST['nama'] ?? ''),
            'username' => trim($_POST['username'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
        ];
        view('auth/register', compact('errors', 'old'));
    }
}
