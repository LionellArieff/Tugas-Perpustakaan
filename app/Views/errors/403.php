<?php
$page = '';
$title = '403 - Akses Ditolak';
ob_start();
?>
<div class="card card-custom p-5 text-center">
    <div class="display-1 text-danger"><i class="fa-solid fa-ban"></i></div>
    <h2 class="fw-bold mt-3">403 - Akses Ditolak</h2>
    <p class="text-muted">Anda tidak memiliki hak akses ke halaman ini. Halaman tersebut hanya untuk role yang berwenang.</p>
    <div><a href="<?= e(Auth::homeUrl()); ?>" class="btn btn-teal rounded-pill px-4"><i class="fa-solid fa-house me-1"></i> Kembali ke Dashboard</a></div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layout/main.php';
