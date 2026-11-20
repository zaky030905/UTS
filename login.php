<?php
require_once 'config.php';
if (is_logged_in()) { header('Location: index.php'); exit; }
$error = '';
$next = $_GET['next'] ?? 'index.php';
$allowedNext = ['index.php', 'add.php', 'edit.php'];
if (!in_array($next, $allowedNext, true)) $next = 'index.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $identity = trim($_POST['identity'] ?? '');
    $passwordInput = $_POST['password'] ?? '';
    $next = $_POST['next'] ?? 'index.php';
    if (!in_array($next, $allowedNext, true)) $next = 'index.php';
    if ($identity === '' || $passwordInput === '') {
        $error = 'Masukkan username/email dan password.';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, nama_lengkap, username, password_hash FROM users WHERE username = ? OR email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'ss', $identity, $identity);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        if ($user && password_verify($passwordInput, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['user_name'] = $user['nama_lengkap'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header('Location: ' . $next); exit;
        }
        $error = 'Username/email atau password tidak sesuai.';
    }
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Login | Medical Inventory UMPKU</title><link rel="stylesheet" href="style.css"></head><body class="auth-body">
<div class="auth-side"><div class="auth-brand"><img src="logo-umpku.png" alt="Logo UMPKU"><span>MEDICAL INVENTORY SYSTEM</span></div><div class="auth-illustration"><div class="auth-cross">✚</div><span class="eyebrow">SMART HEALTHCARE ASSET MANAGEMENT</span><h1>Kelola Inventaris Medis dalam Satu Sistem.</h1><p>Masuk untuk mengelola, memantau, dan memperbarui data peralatan elektromedis dengan mudah.</p></div><div class="auth-side-foot">Universitas Muhammadiyah PKU Surakarta · D4 Elektromedis</div></div>
<main class="auth-main"><section class="auth-card"><div class="auth-mobile-brand"><img src="logo-umpku.png" alt="Logo UMPKU"></div><span class="auth-kicker">SELAMAT DATANG KEMBALI</span><h2>Masuk ke akun</h2><p class="auth-description">Gunakan akun yang sudah terdaftar untuk melanjutkan.</p>
<?php if (isset($_GET['registered'])): ?><div class="alert alert-success">Pendaftaran berhasil! Silakan masuk menggunakan akun baru kamu.</div><?php endif; ?>
<?php if (isset($_GET['logout'])): ?><div class="alert alert-success">Kamu berhasil keluar dari sistem dengan aman.</div><?php endif; ?>
<?php if ($error !== ''): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
<form method="POST" action="login.php" class="auth-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="next" value="<?= e($next) ?>"><div class="field"><label for="identity">Username atau Email</label><input id="identity" name="identity" type="text" placeholder="Masukkan username atau email" value="<?= e($_POST['identity'] ?? '') ?>" autocomplete="username" required autofocus></div><div class="field"><label for="password">Password</label><div class="password-wrap"><input id="password" name="password" type="password" placeholder="Masukkan password" autocomplete="current-password" required><button class="password-toggle" type="button" data-target="password" aria-label="Tampilkan password">Lihat</button></div></div><button type="submit" class="btn btn-primary auth-submit">Masuk ke Dashboard →</button></form><p class="auth-switch">Belum memiliki akun? <a href="register.php">Daftar sekarang</a></p><a class="auth-back" href="../">← Kembali</a></section></main><script>document.querySelectorAll(".password-toggle").forEach(btn=>btn.addEventListener("click",()=>{const input=document.getElementById(btn.dataset.target);const show=input.type==="password";input.type=show?"text":"password";btn.textContent=show?"Sembunyi":"Lihat";btn.setAttribute("aria-label",show?"Sembunyikan password":"Tampilkan password");}));</script></body></html>
