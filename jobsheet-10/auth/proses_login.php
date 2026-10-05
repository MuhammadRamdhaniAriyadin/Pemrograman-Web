<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

// Cek apakah user sedang dalam masa pemblokiran
if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Login ditolak. Anda masih dalam masa tunggu akibat terlalu banyak kesalahan.'
    ];
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// Inisialisasi penghitung jika belum ada
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = [];
}

if (!isset($_SESSION['login_attempts'][$username])) {
    $_SESSION['login_attempts'][$username] = 0;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Login berhasil: Reset penghitung kegagalan
    unset($_SESSION['login_attempts'][$username]);
    unset($_SESSION['lockout_time']);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];

    // Proses Remember Me
    if ($remember) {
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);

        $updateStmt = $pdo->prepare("UPDATE users SET remember_token = :token WHERE id = :id");
        $updateStmt->execute(['token' => $tokenHash, 'id' => $user['id']]);

        setcookie('remember_me', $user['id'] . ':' . $token, [
            'expires'  => time() + (86400 * 30),
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    header('Location: ../index.php');
    exit;
}

// Jika gagal login: Tambah jumlah percobaan
$_SESSION['login_attempts'][$username]++;
$maxAttempts = 3;
$lockoutDuration = 300; // 5 menit

if ($_SESSION['login_attempts'][$username] >= $maxAttempts) {
    $_SESSION['lockout_time'] = time() + $lockoutDuration;
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Anda telah salah memasukkan password sebanyak ' . $maxAttempts . ' kali. Akses dikunci selama 5 menit!'
    ];
} else {
    $sisaPercobaan = $maxAttempts - $_SESSION['login_attempts'][$username];
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Username atau password salah. Sisa percobaan: ' . $sisaPercobaan
    ];
}

header('Location: login.php');
exit;
