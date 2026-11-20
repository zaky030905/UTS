<?php
// Konfigurasi koneksi database untuk XAMPP
$host = "localhost";
$user = "root";
$password = "";
$database = "db_alat_elektromedis";

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
    ]);
    session_start();
}

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Koneksi database gagal: " . htmlspecialchars(mysqli_connect_error(), ENT_QUOTES, 'UTF-8'));
}
mysqli_set_charset($conn, "utf8mb4");

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function is_logged_in() {
    return isset($_SESSION['user_id']) && (int) $_SESSION['user_id'] > 0;
}

function require_login() {
    if (!is_logged_in()) {
        $next = basename($_SERVER['PHP_SELF'] ?? 'index.php');
        if ($next === 'delete.php') $next = 'index.php';
        header('Location: login.php?next=' . urlencode($next));
        exit;
    }
}

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf() {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(419);
        exit('Sesi formulir tidak valid atau kedaluwarsa. Silakan kembali dan coba lagi.');
    }
}
