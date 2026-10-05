<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek Cookie Remember Me terlebih dahulu jika session belum ada
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    require_once __DIR__ . '/koneksi.php';

    list($userId, $token) = explode(':', $_COOKIE['remember_me'], 2);
    if ($userId && $token) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['remember_token'] && hash_equals($user['remember_token'], hash('sha256', $token))) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];
        }
    }
}

// JIKA BELUM LOGIN: Langsung hentikan eksekusi SEBELUM include header.php dipanggil!
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit; // PERINTAH INI SANGAT PENTING agar file selanjutnya (seperti header.php) tidak diproses
}
