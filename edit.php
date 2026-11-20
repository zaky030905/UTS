<?php
require_once 'config.php';
require_login();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400); die('ID alat tidak valid. <a href="index.php">Kembali</a>'); }
$stmt = mysqli_prepare($conn, "SELECT id,nama_alat,tahun,merek,lokasi FROM alat_elektromedis WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id); mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); mysqli_stmt_close($stmt);
if (!$data) { http_response_code(404); die('Data alat tidak ditemukan. <a href="index.php">Kembali</a>'); }
$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $nama_alat=trim($_POST['nama_alat']??''); $tahun=filter_input(INPUT_POST,'tahun',FILTER_VALIDATE_INT);
    $merek=trim($_POST['merek']??''); $lokasi=trim($_POST['lokasi']??'');
    if ($nama_alat==='' || !$tahun || $tahun<1901 || $tahun>2155 || $merek==='' || $lokasi==='') {
        $pesan='Mohon isi seluruh kolom dengan data yang valid.';
        $data=array_merge($data,['nama_alat'=>$nama_alat,'tahun'=>$_POST['tahun']??'','merek'=>$merek,'lokasi'=>$lokasi]);
    } else {
        $update=mysqli_prepare($conn,"UPDATE alat_elektromedis SET nama_alat=?,tahun=?,merek=?,lokasi=? WHERE id=?");
        mysqli_stmt_bind_param($update,"sissi",$nama_alat,$tahun,$merek,$lokasi,$id);
        if(mysqli_stmt_execute($update)){mysqli_stmt_close($update);header("Location: index.php?status=updated");exit;}
        $pesan='Gagal memperbarui data. Silakan coba kembali.'; mysqli_stmt_close($update);
    }
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Edit Alat | Medical Inventory UMPKU</title><link rel="stylesheet" href="style.css"></head><body>
<div class="top-strip"><div class="top-strip-inner"><span>✚ <strong>MEDICAL EQUIPMENT MANAGEMENT</strong></span><span>Universitas Muhammadiyah PKU Surakarta · D4 Elektromedis</span></div></div>
<header class="header"><div class="header-inner"><a class="brand" href="index.php"><img src="logo-umpku.png" alt="Logo UMPKU"><span class="brand-rule"></span><span><span class="brand-title">MEDICAL INVENTORY SYSTEM</span><span class="brand-sub">Equipment & Asset Management</span></span></a><nav class="nav"><a href="index.php">Dashboard</a><span class="nav-user">👤 <?= e($_SESSION['user_name'] ?? 'Pengguna') ?></span><form class="logout-form" action="logout.php" method="POST"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button type="submit" class="nav-logout">Keluar</button></form><a href="add.php">Tambah Alat</a></nav></div></header>
<main class="container form-layout"><a href="index.php" class="back">← Kembali ke Dashboard</a><section class="form-hero"><span class="eyebrow">INVENTORY MANAGEMENT / UPDATE</span><h1>Edit Data Peralatan</h1><p>Perbarui informasi peralatan elektromedis yang tersimpan di sistem.</p></section>
<section class="panel form-panel"><div class="form-title"><div class="form-symbol">✎</div><div><h2>Perbarui Informasi Alat</h2><p>ID inventaris #<?= (int)$id ?></p></div></div>
<?php if($pesan!==''): ?><div class="alert alert-error"><?= e($pesan) ?></div><?php endif; ?>
<form method="POST" action="edit.php?id=<?= (int)$id ?>"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="form-grid">
<div class="field full"><label for="nama_alat">Nama Alat Elektromedis <span>*</span></label><input id="nama_alat" name="nama_alat" maxlength="100" value="<?= e($data['nama_alat']) ?>" required></div>
<div class="field"><label for="tahun">Tahun Pengadaan <span>*</span></label><input id="tahun" type="number" name="tahun" min="1901" max="2155" value="<?= e($data['tahun']) ?>" required></div>
<div class="field"><label for="merek">Merek Alat <span>*</span></label><input id="merek" name="merek" maxlength="100" value="<?= e($data['merek']) ?>" required></div>
<div class="field full"><label for="lokasi">Lokasi Alat <span>*</span></label><input id="lokasi" name="lokasi" maxlength="100" value="<?= e($data['lokasi']) ?>" required></div>
</div><div class="form-actions"><button type="submit" class="btn btn-primary">✓ Simpan Perubahan</button><a href="index.php" class="btn btn-secondary">Batal</a></div></form></section></main>
<footer class="footer"><div class="footer-inner"><div><strong>UNIVERSITAS MUHAMMADIYAH PKU SURAKARTA</strong><p>Program Studi D4 Elektromedis · Medical Inventory System</p></div><div class="student"><strong>Muhammad Zaky Alfiansyah · 1202405029</strong><p>Pengembang / Mahasiswa</p></div></div></footer>
</body></html>
