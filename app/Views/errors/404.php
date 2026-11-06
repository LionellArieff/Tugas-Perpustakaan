<?php
$page = '';
$title = '404 - Halaman Tidak Ditemukan';
ob_start();
?>
<div class="card card-custom p-5 text-center">
    <div class="display-1 text-secondary"><i class="fa-solid fa-circle-question"></i></div>
    <h2 class="fw-bold mt-3">404 - Halaman Tidak Ditemukan</h2>
    <p class="text-muted">Alamat yang Anda buka tidak tersedia.</p>
    <div><a href="<?= e(Auth::check() ? Auth::homeUrl() : url('login')); ?>" class="btn btn-teal rounded-pill px-4">Kembali</a></div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layout/main.php';
