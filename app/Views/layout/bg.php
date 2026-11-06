<!-- Background video sakura + tema (palet: putih, pink sakura, aqua, charcoal) -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
<video class="bg-video" autoplay muted loop playsinline preload="auto" poster="assets/sakura-poster.jpg">
    <source src="assets/sakura.mp4" type="video/mp4">
</video>
<div class="bg-overlay"></div>
<canvas id="petals" aria-hidden="true"></canvas>
<style>
    :root {
        --sk-pink: #cf4a78;        /* pink sakura (tombol utama) */
        --sk-pink-dark: #b03562;
        --sk-pink-soft: #fdecf3;   /* pink sangat muda (header tabel, hover) */
        --sk-pink-line: rgba(207, 74, 120, .22);
        --sk-aqua: #2bb3ae;        /* aqua dari pita karakter */
        --sk-ink: #2b2730;         /* charcoal dari pakaian karakter */
        /* timpa variabel lama agar semua halaman ikut berganti */
        --primary-teal: var(--sk-pink);
        --primary-orange: var(--sk-pink);
        --primary-dark: var(--sk-pink-dark);
        --bs-link-color: var(--sk-pink);
        --bs-link-hover-color: var(--sk-pink-dark);
    }

    /* ---------- latar ---------- */
    html, body { background: #fff7fa url('assets/sakura-poster.jpg') center / cover fixed no-repeat; }
    body { background-color: transparent !important; font-family: 'Poppins', 'Segoe UI', sans-serif !important; color: var(--sk-ink); }
    .bg-video { position: fixed; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: -2; }
    .bg-overlay { position: fixed; inset: 0; background: rgba(255, 250, 252, 0.05); z-index: -1; }
    @media (prefers-reduced-motion: reduce) { .bg-video { display: none; } }

    /* ---------- navbar: kaca gelap seperti pakaian karakter ---------- */
    .navbar-teal {
        background: rgba(43, 39, 48, 0.38) !important;
        -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px);
        border-bottom: 2px solid var(--sk-pink);
    }
    .navbar-brand i { color: #ff9fbd; }
    .navbar-dark .navbar-nav .nav-link { color: rgba(255,255,255,.78); border-radius: 999px; padding: .4rem .9rem; margin-right: .15rem; }
    .navbar-dark .navbar-nav .nav-link:hover { color: #fff; background: rgba(255,255,255,.1); }
    .navbar-dark .navbar-nav .nav-link.active { color: #fff; background: var(--sk-pink); }

    /* ---------- kartu kaca ---------- */
    .card-custom, .stat-card, .card-login, .card-glass {
        background: rgba(255, 255, 255, 0.28) !important;
        -webkit-backdrop-filter: blur(18px) saturate(150%); backdrop-filter: blur(18px) saturate(150%);
        border: 1px solid rgba(255, 255, 255, .75) !important;
        border-radius: 18px !important;
        box-shadow: 0 8px 28px rgba(207, 74, 120, .12) !important;
    }
    .stat-card { border-left: 5px solid var(--sk-pink) !important; }
    .stat-card:nth-child(even) { border-left-color: var(--sk-aqua) !important; }
    .card-login, .card-glass { border-top: 5px solid var(--sk-pink) !important; }

    /* ---------- tombol ---------- */
    .btn-teal, .btn-orange {
        background: linear-gradient(135deg, #e2638c, var(--sk-pink)) !important;
        color: #fff !important; border: none !important;
        box-shadow: 0 4px 12px rgba(207, 74, 120, .28);
    }
    .btn-teal:hover, .btn-orange:hover { background: linear-gradient(135deg, var(--sk-pink), var(--sk-pink-dark)) !important; color: #fff !important; }
    .btn-outline-secondary { color: var(--sk-ink); border-color: rgba(43,39,48,.25); background: rgba(255,255,255,.6); }
    .btn-outline-secondary:hover { background: var(--sk-pink); border-color: var(--sk-pink); color: #fff; }
    .btn-info { background-color: var(--sk-aqua) !important; border-color: var(--sk-aqua) !important; color: #fff !important; }
    .btn-info:hover { filter: brightness(.92); }
    .text-teal { color: var(--sk-pink) !important; }

    /* ---------- tabel ---------- */
    .table { --bs-table-hover-bg: var(--sk-pink-soft); --bs-table-bg: transparent; color: var(--sk-ink); }
    .card-custom, .stat-card, .card-login, .card-glass { text-shadow: 0 0 8px rgba(255,255,255,.9), 0 0 2px rgba(255,255,255,.8); }
    .form-control, .form-select { background-color: rgba(255,255,255,.42); }
    .form-control:focus, .form-select:focus { background-color: rgba(255,255,255,.9); }
    .input-group-text { background-color: rgba(255,255,255,.42) !important; }
    .text-muted { color: #3f3a47 !important; }
    .table > thead.table-light > tr > th, .table-light { background-color: rgba(253,236,243,.40) !important; color: var(--sk-ink); border-bottom: 2px solid var(--sk-pink-line); }

    /* ---------- badge status ---------- */
    .badge.bg-success { background-color: var(--sk-aqua) !important; }
    .badge.bg-danger { background-color: #e5486a !important; }
    .badge.bg-secondary { background-color: #6f6a78 !important; }
    .badge-tersedia { background-color: #d5f3f1; color: #17756f; }
    .badge-dipinjam { background-color: var(--sk-pink-soft); color: var(--sk-pink-dark); }

    /* ---------- form ---------- */
    .form-control:focus, .form-select:focus { border-color: var(--sk-pink); box-shadow: 0 0 0 .2rem rgba(207, 74, 120, .18); }
    .form-control, .form-select { border-color: rgba(43,39,48,.18); }
    .modal-content { background: rgba(255,255,255,.62); -webkit-backdrop-filter: blur(20px); backdrop-filter: blur(20px); border-radius: 18px; }
    .alert-success { background: #e1f6f4; border-color: #b7e7e2; color: #17756f; }
</style>

<style>
    /* ================= ANIMASI ================= */
    #petals { position: fixed; inset: 0; width: 100%; height: 100%; z-index: -1; pointer-events: none; }

    @keyframes riseIn   { from { opacity: 0; transform: translateY(28px) scale(.97); } to { opacity: 1; transform: none; } }
    @keyframes slideDown{ from { opacity: 0; transform: translateY(-100%); } to { opacity: 1; transform: none; } }
    @keyframes rowIn    { from { opacity: 0; transform: translateX(-16px); } to { opacity: 1; transform: none; } }
    @keyframes floaty   { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-9px); } }
    @keyframes gradMove { to { background-position: 200% center; } }
    @keyframes pulseRing{ 0%, 100% { box-shadow: 0 0 0 0 rgba(229, 72, 106, .55); } 50% { box-shadow: 0 0 0 7px rgba(229, 72, 106, 0); } }
    @keyframes shineSweep { from { left: -80%; } to { left: 130%; } }
    @keyframes popIn    { from { opacity: 0; transform: translateY(-12px) scale(.96); } to { opacity: 1; transform: none; } }

    /* navbar meluncur dari atas */
    .navbar-teal { animation: slideDown .7s cubic-bezier(.2, .8, .2, 1) backwards; }
    .navbar-brand i { display: inline-block; animation: floaty 3s ease-in-out infinite; }

    /* kartu muncul bertahap (fill-mode "backwards" supaya efek hover tetap jalan) */
    .card-custom, .stat-card, .card-glass { animation: riseIn .8s cubic-bezier(.2, .8, .2, 1) backwards; }
    .card-custom { animation-delay: .1s; }
    .card-custom + .card-custom { animation-delay: .25s; }
    .row > div:nth-child(1) .stat-card { animation-delay: .05s; }
    .row > div:nth-child(2) .stat-card { animation-delay: .12s; }
    .row > div:nth-child(3) .stat-card { animation-delay: .19s; }
    .row > div:nth-child(4) .stat-card { animation-delay: .26s; }
    .row > div:nth-child(5) .stat-card { animation-delay: .33s; }
    .row > div:nth-child(6) .stat-card { animation-delay: .40s; }

    /* kartu login: masuk lalu melayang pelan */
    .card-login { animation: riseIn .9s cubic-bezier(.2, .8, .2, 1) backwards, floaty 7s ease-in-out 1.2s infinite; }
    .card-glass { animation: riseIn .9s cubic-bezier(.2, .8, .2, 1) backwards; }

    /* judul login/register: gradasi pink-aqua bergerak */
    .card-login h3, .card-glass h3 {
        background: linear-gradient(90deg, #cf4a78, #2bb3ae, #cf4a78);
        background-size: 200% auto;
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent !important;
        text-shadow: none !important;
        animation: gradMove 4s linear infinite;
    }

    /* hover: kartu statistik terangkat + menyala */
    .stat-card, .card-custom { transition: transform .3s ease, box-shadow .3s ease; }
    .stat-card:hover { transform: translateY(-6px); box-shadow: 0 16px 34px rgba(207, 74, 120, .28) !important; }
    .card-custom:hover { box-shadow: 0 14px 38px rgba(207, 74, 120, .22) !important; }

    /* tombol: kilau menyapu saat disorot */
    .btn { transition: transform .2s ease, box-shadow .25s ease, background .25s ease, color .25s ease; }
    .btn:hover { transform: translateY(-2px); }
    .btn:active { transform: translateY(0) scale(.96); }
    .btn-teal, .btn-orange { position: relative; overflow: hidden; }
    .btn-teal::after, .btn-orange::after {
        content: ''; position: absolute; top: 0; left: -80%; width: 50%; height: 100%;
        background: linear-gradient(120deg, transparent, rgba(255, 255, 255, .55), transparent);
        transform: skewX(-20deg); pointer-events: none;
    }
    .btn-teal:hover::after, .btn-orange:hover::after { animation: shineSweep .8s ease; }
    .btn-teal:hover, .btn-orange:hover { box-shadow: 0 8px 22px rgba(207, 74, 120, .45); }

    /* baris tabel masuk satu per satu (delay diisi lewat JS) */
    tbody tr { animation: rowIn .55s ease backwards; animation-delay: calc(var(--i, 0) * 55ms + .3s); }
    .table tbody tr { transition: background-color .2s ease; }

    /* badge "Habis" berdenyut, menu navbar halus */
    .badge.bg-danger { animation: pulseRing 2.2s ease-in-out infinite; }
    .navbar-dark .navbar-nav .nav-link { transition: background .25s ease, color .25s ease, transform .2s ease; }
    .navbar-dark .navbar-nav .nav-link:hover { transform: translateY(-2px); }

    /* notifikasi & modal */
    .alert { animation: popIn .5s cubic-bezier(.2, .8, .2, 1); }
    .modal.fade .modal-dialog { transform: translateY(40px) scale(.94); transition: transform .4s cubic-bezier(.2, .8, .2, 1), opacity .3s; }
    .modal.show .modal-dialog { transform: none; }

    /* ikon & input */
    .form-control, .form-select { transition: box-shadow .25s ease, background-color .25s ease, border-color .25s ease; }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { animation: none !important; transition: none !important; }
        #petals { display: none; }
    }
</style>
<script>
(function () {
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.addEventListener('DOMContentLoaded', function () {
        // urutan animasi baris tabel
        document.querySelectorAll('tbody tr').forEach(function (tr, i) {
            tr.style.setProperty('--i', Math.min(i, 18));
        });
        // angka statistik menghitung naik dari 0
        if (!reduce) {
            document.querySelectorAll('.stat-card .fs-2').forEach(function (el) {
                var t = el.textContent.trim();
                if (!/^\d+$/.test(t)) return;
                var end = parseInt(t, 10), start = null, dur = 1100;
                el.textContent = '0';
                function step(ts) {
                    if (start === null) start = ts;
                    var p = Math.min((ts - start) / dur, 1);
                    el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3)));
                    if (p < 1) requestAnimationFrame(step);
                }
                setTimeout(function () { requestAnimationFrame(step); }, 350);
            });
        }
    });

    if (reduce) return;

    // ===== kelopak sakura berjatuhan =====
    var c = document.getElementById('petals');
    if (!c || !c.getContext) return;
    var ctx = c.getContext('2d'), W = 0, H = 0, petals = [];
    var colors = ['255,176,203', '255,196,216', '248,150,185', '255,222,234'];

    function resize() {
        var dpr = window.devicePixelRatio || 1;
        W = window.innerWidth; H = window.innerHeight;
        c.width = W * dpr; c.height = H * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }
    function make(initial) {
        return {
            x: Math.random() * W, y: initial ? Math.random() * H : -24,
            s: 5 + Math.random() * 8, vy: .5 + Math.random() * 1.1, vx: -.2 + Math.random() * .8,
            r: Math.random() * 6.28, vr: (Math.random() - .5) * .05,
            sw: Math.random() * 6.28, svs: .012 + Math.random() * .02,
            a: .45 + Math.random() * .4, col: colors[(Math.random() * colors.length) | 0]
        };
    }
    function init() {
        resize();
        var n = Math.round(Math.min(42, Math.max(16, W / 42)));
        petals = [];
        for (var i = 0; i < n; i++) petals.push(make(true));
    }
    function drawPetal(p) {
        ctx.save();
        ctx.translate(p.x, p.y);
        ctx.rotate(p.r);
        ctx.scale(1, .78 + Math.sin(p.sw) * .22);   // efek berputar 3D
        var s = p.s;
        ctx.beginPath();
        ctx.moveTo(0, -s);
        ctx.bezierCurveTo(s * 1.0, -s * .55, s * .75, s * .8, 0, s);
        ctx.bezierCurveTo(-s * .75, s * .8, -s * 1.0, -s * .55, 0, -s);
        ctx.fillStyle = 'rgba(' + p.col + ',' + p.a + ')';
        ctx.fill();
        ctx.restore();
    }
    function frame() {
        ctx.clearRect(0, 0, W, H);
        for (var i = 0; i < petals.length; i++) {
            var p = petals[i];
            p.y += p.vy; p.sw += p.svs; p.r += p.vr;
            p.x += p.vx + Math.sin(p.sw) * .7;
            if (p.y > H + 24 || p.x > W + 40 || p.x < -40) petals[i] = make(false);
            else drawPetal(p);
        }
        requestAnimationFrame(frame);
    }
    window.addEventListener('resize', init);
    init();
    requestAnimationFrame(frame);
})();
</script>
