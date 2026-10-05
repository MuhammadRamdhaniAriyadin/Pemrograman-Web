<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../includes/koneksi.php';

// Hapus token di database jika pengguna terautentikasi
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("UPDATE users SET remember_token = NULL WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
}

// Hapus cookie
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', [
        'expires' => time() - 3600,
        'path' => '/'
    ]);
}

session_destroy();
header('Location: login.php');
exit;
