<?php
require_once 'config.php';
require_login();
$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $nama_alat = trim($_POST['nama_alat'] ?? '');
    $tahun = filter_input(INPUT_POST, 'tahun', FILTER_VALIDATE_INT);
    $merek = trim($_POST['merek'] ?? '');
    $lokasi = trim($_POST['lokasi'] ?? '');
    if ($nama_alat === '' || !$tahun || $tahun < 1901 || $tahun > 2155 || $merek === '' || $lokasi === '') {
        $pesan = 'Mohon isi seluruh kolom dengan data yang valid.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO alat_elektromedis (nama_alat, tahun, merek, lokasi) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "siss", $nama_alat, $tahun, $merek, $lokasi);
        if (mysqli_stmt_execute($stmt)) { mysqli_stmt_close($stmt); header("Location: index.php?status=added"); exit; }
        $pesan = 'Gagal menyimpan data. Silakan coba kembali.';
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Tambah Alat | Medical Inventory UMPKU</title><link rel="stylesheet" href="style.css"></head><body>
<div class="top-strip"><div class="top-strip-inner"><span>✚ <strong>MEDICAL EQUIPMENT MANAGEMENT</strong></span><span>Universitas Muhammadiyah PKU Surakarta · D4 Elektromedis</span></div></div>
<header class="header"><div class="header-inner"><a class="brand" href="index.php"><img src="logo-umpku.png" alt="Logo UMPKU"><span class="brand-rule"></span><span><span class="brand-title">MEDICAL INVENTORY SYSTEM</span><span class="brand-sub">Equipment & Asset Management</span></span></a><nav class="nav"><a href="index.php">Dashboard</a><span class="nav-user">👤 <?= e($_SESSION['user_name'] ?? 'Pengguna') ?></span><form class="logout-form" action="logout.php" method="POST"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button type="submit" class="nav-logout">Keluar</button></form><a class="active" href="add.php">Tambah Alat</a></nav></div></header>
<main class="container form-layout"><a href="index.php" class="back">← Kembali ke Dashboard</a><section class="form-hero"><span class="eyebrow">INVENTORY MANAGEMENT / CREATE</span><h1>Tambah Peralatan Medis</h1><p>Masukkan informasi peralatan elektromedis ke dalam database inventaris.</p></section>
<section class="panel form-panel"><div class="form-title"><div class="form-symbol">＋</div><div><h2>Informasi Alat Elektromedis</h2><p>Lengkapi seluruh kolom bertanda *.</p></div></div>
<?php if($pesan!==''): ?><div class="alert alert-error"><?= e($pesan) ?></div><?php endif; ?>
<form method="POST" action="add.php"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="form-grid">
<div class="field full"><label for="nama_alat">Nama Alat Elektromedis <span>*</span></label><input id="nama_alat" name="nama_alat" maxlength="100" placeholder="Contoh: Patient Monitor" value="<?= e($_POST['nama_alat']??'') ?>" required></div>
<div class="field"><label for="tahun">Tahun Pengadaan <span>*</span></label><input id="tahun" type="number" name="tahun" min="1901" max="2155" placeholder="Contoh: 2024" value="<?= e($_POST['tahun']??'') ?>" required></div>
<div class="field"><label for="merek">Merek Alat <span>*</span></label><input id="merek" name="merek" maxlength="100" placeholder="Contoh: Mindray" value="<?= e($_POST['merek']??'') ?>" required></div>
<div class="field full"><label for="lokasi">Lokasi Alat <span>*</span></label><input id="lokasi" name="lokasi" maxlength="100" placeholder="Contoh: Ruang ICU" value="<?= e($_POST['lokasi']??'') ?>" required></div>
</div><div class="form-actions"><button type="submit" class="btn btn-primary">✓ Simpan Data</button><a href="index.php" class="btn btn-secondary">Batal</a></div></form></section></main>
<footer class="footer"><div class="footer-inner"><div><strong>UNIVERSITAS MUHAMMADIYAH PKU SURAKARTA</strong><p>Program Studi D4 Elektromedis · Medical Inventory System</p></div><div class="student"><strong>Muhammad Zaky Alfiansyah · 1202405029</strong><p>Pengembang / Mahasiswa</p></div></div></footer>
</body></html>
