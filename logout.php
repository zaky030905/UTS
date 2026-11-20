<?php
require_once 'config.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
    header('Location: login.php?logout=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Keluar | Medical Inventory UMPKU</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="auth-body logout-body">
    <aside class="auth-side">
        <a class="auth-brand" href="index.php"><img src="logo-umpku.png" alt="Logo UMPKU"><span>MEDICAL INVENTORY SYSTEM</span></a>
        <div class="auth-illustration">
            <div class="auth-cross">✓</div>
            <span class="eyebrow">SESSION MANAGEMENT</span>
            <h1>Terima kasih telah menggunakan sistem.</h1>
            <p>Pastikan kamu keluar dari akun setelah selesai mengelola data inventaris, terutama saat menggunakan perangkat bersama.</p>
        </div>
        <div class="auth-side-foot">Universitas Muhammadiyah PKU Surakarta · D4 Elektromedis</div>
    </aside>
    <main class="auth-main">
        <section class="auth-card logout-card">
            <div class="logout-icon">↗</div>
            <span class="auth-kicker">KONFIRMASI KELUAR</span>
            <h2>Keluar dari akun?</h2>
            <p class="auth-description">Halo, <strong><?= e($_SESSION['user_name'] ?? 'Pengguna') ?></strong>. Apakah kamu yakin ingin mengakhiri sesi Medical Inventory System?</p>
            <div class="logout-summary">
                <span class="logout-avatar">👤</span>
                <span><strong><?= e($_SESSION['user_name'] ?? 'Pengguna') ?></strong><small>@<?= e($_SESSION['username'] ?? '') ?></small></span>
                <span class="session-status"><i></i> Aktif</span>
            </div>
            <form method="POST" action="logout.php" class="logout-actions">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <button type="submit" class="btn btn-logout-confirm">Ya, Keluar</button>
                <a href="index.php" class="btn btn-secondary">Batalkan</a>
            </form>
            <p class="logout-note">Sesi akun akan diakhiri dengan aman pada perangkat ini.</p>
        </section>
    </main>
</body>
</html>
