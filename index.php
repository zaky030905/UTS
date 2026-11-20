<?php
require_once 'config.php';
require_login();
$sql = "SELECT id, nama_alat, tahun, merek, lokasi FROM alat_elektromedis ORDER BY id DESC";
$query = mysqli_query($conn, $sql);
if (!$query) { die("Gagal mengambil data inventaris."); }
$total = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM alat_elektromedis"))['total'];
$jumlahTahun = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT tahun) AS jumlah FROM alat_elektromedis"))['jumlah'];
$jumlahMerek = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT merek) AS jumlah FROM alat_elektromedis"))['jumlah'];
$status = $_GET['status'] ?? '';
$messages = ['added'=>'Data alat berhasil ditambahkan.','updated'=>'Data alat berhasil diperbarui.','deleted'=>'Data alat berhasil dihapus.'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Medical Equipment Inventory - Sistem inventaris alat elektromedis UMPKU.">
<title>Dashboard | Medical Inventory UMPKU</title><link rel="stylesheet" href="style.css">
</head>
<body>
<div class="top-strip"><div class="top-strip-inner"><span>✚ <strong>MEDICAL EQUIPMENT MANAGEMENT</strong> · Sistem Inventaris Elektromedis</span><span>Universitas Muhammadiyah PKU Surakarta · D4 Elektromedis</span></div></div>
<header class="header"><div class="header-inner">
<a class="brand" href="index.php"><img src="logo-umpku.png" alt="Logo UMPKU"><span class="brand-rule"></span><span><span class="brand-title">MEDICAL INVENTORY SYSTEM</span><span class="brand-sub">Equipment & Asset Management</span></span></a>
<button class="menu-toggle" id="menuToggle" aria-label="Buka navigasi">☰</button>
<nav class="nav" id="mainNav"><a class="active" href="index.php">Dashboard</a><a href="#inventory">Data Inventaris</a><a href="add.php" class="nav-cta">＋ Tambah Alat</a><span class="nav-user">👤 <?= e($_SESSION['user_name'] ?? 'Pengguna') ?></span><form class="logout-form" action="logout.php" method="POST"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button type="submit" class="nav-logout">Keluar</button></form></nav>
</div></header>
<main class="container">
<section class="hero"><div class="hero-copy"><span class="eyebrow">SMART HEALTHCARE ASSET MANAGEMENT</span><h1>Kelola Peralatan Medis dengan Lebih Mudah</h1><p>Platform pencatatan inventaris alat elektromedis untuk membantu pengelolaan data peralatan, informasi merek, tahun pengadaan, dan lokasi penggunaan secara terpusat.</p><div class="hero-actions"><a href="add.php" class="btn btn-primary">＋ Tambah Data Alat</a><a href="#inventory" class="btn btn-light">Lihat Inventaris →</a></div></div><div class="hero-art"><div class="medical-icon">⚕</div></div></section>
<?php if (isset($messages[$status])): ?><div class="alert alert-success"><?= e($messages[$status]) ?></div><?php endif; ?>

<section aria-label="Ringkasan inventaris">
<div class="section-head"><div><div class="section-kicker">OVERVIEW</div><h2>Ringkasan Inventaris</h2><p>Informasi singkat data peralatan yang tersimpan di sistem.</p></div></div>
<div class="stats">
<article class="stat"><div><div class="stat-label">Total Alat Terdaftar</div><div class="stat-value"><?= $total ?></div><div class="stat-note">Unit dalam database inventaris</div></div><div class="stat-icon">▦</div></article>
<article class="stat"><div><div class="stat-label">Tahun Pengadaan</div><div class="stat-value"><?= $jumlahTahun ?></div><div class="stat-note">Variasi tahun pengadaan</div></div><div class="stat-icon">◷</div></article>
<article class="stat"><div><div class="stat-label">Merek Peralatan</div><div class="stat-value"><?= $jumlahMerek ?></div><div class="stat-note">Merek berbeda tercatat</div></div><div class="stat-icon">◇</div></article>
</div></section>

<section id="inventory">
<div class="section-head"><div><div class="section-kicker">MEDICAL EQUIPMENT DATABASE</div><h2>Daftar Inventaris Alat</h2><p>Kelola data alat elektromedis yang telah dimasukkan ke sistem.</p></div><a href="add.php" class="btn btn-primary">＋ Tambah Inventaris</a></div>
<div class="panel">
<div class="panel-head"><div><h2>Data Peralatan</h2><p>Daftar terbaru · <?= $total ?> data tersimpan</p></div><label class="search"><span>⌕</span><input type="search" id="tableSearch" placeholder="Cari nama alat, merek, lokasi..."></label></div>
<div class="table-wrap"><table id="inventoryTable"><thead><tr><th class="num">No.</th><th>Nama Alat</th><th>Tahun</th><th>Merek</th><th>Lokasi</th><th>Aksi</th></tr></thead><tbody>
<?php if (mysqli_num_rows($query)>0): $no=1; while($data=mysqli_fetch_assoc($query)): ?>
<tr><td class="num"><?= $no++ ?></td><td><strong class="equipment"><?= e($data['nama_alat']) ?></strong><span class="equipment-sub">ID Inventaris #<?= (int)$data['id'] ?></span></td><td><span class="year"><?= e($data['tahun']) ?></span></td><td><?= e($data['merek']) ?></td><td class="location">⌖ <?= e($data['lokasi']) ?></td><td><div class="actions"><a class="btn btn-edit" href="edit.php?id=<?= (int)$data['id'] ?>">✎ Edit</a><form method="POST" action="delete.php" onsubmit="return confirm('Hapus data <?= e($data['nama_alat']) ?>?')"><input type="hidden" name="id" value="<?= (int)$data['id'] ?>"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button type="submit" class="btn btn-danger">Hapus</button></form></div></td></tr>
<?php endwhile; else: ?><tr><td colspan="6" class="empty">Belum ada data inventaris. Klik Tambah Data Alat untuk memulai.</td></tr><?php endif; ?>
</tbody></table><p id="noSearchResult" class="empty" hidden>Data yang dicari tidak ditemukan.</p></div>
<div class="table-foot"><span>Medical Inventory System · UMPKU</span><span>Menampilkan <?= $total ?> data inventaris</span></div>
</div></section>
</main>
<footer class="footer"><div class="footer-inner"><div><strong>UNIVERSITAS MUHAMMADIYAH PKU SURAKARTA</strong><p>Program Studi D4 Elektromedis · Medical Inventory System</p></div><div class="student"><strong>Muhammad Zaky Alfiansyah · 1202405029</strong><p>Pengembang / Mahasiswa</p></div></div></footer>
<script>
const toggle=document.getElementById('menuToggle'),nav=document.getElementById('mainNav');
toggle.addEventListener('click',()=>nav.classList.toggle('open'));
const search=document.getElementById('tableSearch'),rows=document.querySelectorAll('#inventoryTable tbody tr'),empty=document.getElementById('noSearchResult');
search.addEventListener('input',function(){const key=this.value.toLocaleLowerCase('id').trim();let count=0;rows.forEach(row=>{const match=row.innerText.toLocaleLowerCase('id').includes(key);row.hidden=!match;if(match)count++;});empty.hidden=count!==0||key==='';});
</script>
</body></html>
