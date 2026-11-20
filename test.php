<?php
require_once 'config.php';
require_login();
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM alat_elektromedis");
$row = mysqli_fetch_assoc($result);
?>
<!doctype html><html lang="id"><meta charset="utf-8"><title>Tes Koneksi</title>
<body style="font:16px Arial;padding:30px"><h2 style="color:green">KONEKSI DATABASE BERHASIL!</h2>
<p>Database: db_alat_elektromedis</p><p>Total data alat: <?= (int)$row['total'] ?></p><a href="index.php">Buka Dashboard</a></body></html>
