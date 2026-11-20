<?php
require_once 'config.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    http_response_code(400);
    die('ID alat tidak valid. <a href="index.php">Kembali</a>');
}
$stmt = mysqli_prepare($conn, "DELETE FROM alat_elektromedis WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
if (mysqli_stmt_execute($stmt)) {
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    header("Location: index.php?status=" . ($affected > 0 ? "deleted" : "notfound"));
    exit;
}
mysqli_stmt_close($stmt);
http_response_code(500);
die('Gagal menghapus data alat. <a href="index.php">Kembali</a>');
