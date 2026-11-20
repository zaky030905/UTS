<?php
require_once 'config.php';
if (is_logged_in()) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $nama = trim($_POST['nama_lengkap'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $passwordInput = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if ($nama === '' || $username === '' || $email === '' || $passwordInput === '' || $confirm === '') {
        $error = 'Semua kolom wajib diisi.';
    } elseif (mb_strlen($nama) > 120 || !preg_match('/^[A-Za-z0-9_.-]{4,50}$/', $username)) {
        $error = 'Nama terlalu panjang atau format username tidak valid (4–50 karakter: huruf, angka, titik, garis bawah, atau tanda hubung).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($passwordInput) < 8) {
        $error = 'Password minimal 8 karakter.';
    } elseif ($passwordInput !== $confirm) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        mysqli_stmt_bind_param($check, 'ss', $username, $email);
        mysqli_stmt_execute($check);
        $exists = mysqli_fetch_assoc(mysqli_stmt_get_result($check));
        mysqli_stmt_close($check);
        if ($exists) {
            $error = 'Username atau email sudah digunakan. Silakan gunakan yang lain.';
        } else {
            $hash = password_hash($passwordInput, PASSWORD_DEFAULT);
            $insert = mysqli_prepare($conn, "INSERT INTO users (nama_lengkap, username, email, password_hash) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert, 'ssss', $nama, $username, $email, $hash);
            if (mysqli_stmt_execute($insert)) {
                mysqli_stmt_close($insert);
                header('Location: login.php?registered=1'); exit;
            }
            mysqli_stmt_close($insert);
            $error = 'Pendaftaran gagal. Silakan coba kembali.';
        }
    }
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Register | Medical Inventory UMPKU</title><link rel="stylesheet" href="style.css"></head><body class="auth-body">
<div class="auth-side"><div class="auth-brand"><img src="logo-umpku.png" alt="Logo UMPKU"><span>MEDICAL INVENTORY SYSTEM</span></div><div class="auth-illustration"><div class="auth-cross">＋</div><span class="eyebrow">CREATE YOUR ACCOUNT</span><h1>Mulai Kelola Inventaris Medis.</h1><p>Buat akun untuk mengakses sistem informasi inventaris alat elektromedis UMPKU.</p></div><div class="auth-side-foot">Universitas Muhammadiyah PKU Surakarta · D4 Elektromedis</div></div>
<main class="auth-main"><section class="auth-card"><div class="auth-mobile-brand"><img src="logo-umpku.png" alt="Logo UMPKU"></div><span class="auth-kicker">REGISTRASI PENGGUNA</span><h2>Buat akun baru</h2><p class="auth-description">Lengkapi informasi berikut untuk mendaftar.</p>
<?php if ($error !== ''): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
<form method="POST" action="register.php" class="auth-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="field"><label for="nama_lengkap">Nama Lengkap</label><input id="nama_lengkap" name="nama_lengkap" type="text" maxlength="120" placeholder="Nama lengkap" value="<?= e($_POST['nama_lengkap'] ?? '') ?>" autocomplete="name" required></div><div class="field"><label for="username">Username</label><input id="username" name="username" type="text" minlength="4" maxlength="50" placeholder="Contoh: zaky030905" value="<?= e($_POST['username'] ?? '') ?>" autocomplete="username" required></div><div class="field"><label for="email">Email</label><input id="email" name="email" type="email" maxlength="150" placeholder="nama@email.com" value="<?= e($_POST['email'] ?? '') ?>" autocomplete="email" required></div><div class="field"><label for="password">Password</label><div class="password-wrap"><input id="password" name="password" type="password" minlength="8" placeholder="Minimal 8 karakter" autocomplete="new-password" required><button class="password-toggle" type="button" data-target="password" aria-label="Tampilkan password">Lihat</button></div></div><div class="field"><label for="confirm_password">Konfirmasi Password</label><div class="password-wrap"><input id="confirm_password" name="confirm_password" type="password" minlength="8" placeholder="Ulangi password" autocomplete="new-password" required><button class="password-toggle" type="button" data-target="confirm_password" aria-label="Tampilkan konfirmasi password">Lihat</button></div></div><button type="submit" class="btn btn-primary auth-submit">Buat Akun →</button></form><p class="auth-switch">Sudah memiliki akun? <a href="login.php">Masuk di sini</a></p><a class="auth-back" href="login.php">← Kembali ke Login</a></section></main><script>document.querySelectorAll(".password-toggle").forEach(btn=>btn.addEventListener("click",()=>{const input=document.getElementById(btn.dataset.target);const show=input.type==="password";input.type=show?"text":"password";btn.textContent=show?"Sembunyi":"Lihat";btn.setAttribute("aria-label",show?"Sembunyikan password":"Tampilkan password");}));</script></body></html>
